<?php

declare(strict_types=1);

namespace Web\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

final class App extends Base {

  public static function Init() {
    $init = new self;
    return $init->Routing();
  }

}

abstract class Base {

  public function Routing() {

    $_app                = [];
    $_app['env']         = parse_ini_file(realpath('.env'), true);


    if ($_app['env']['dev']) {
      error_reporting(E_ALL);
    }else{
      error_reporting(0);
    }
    set_time_limit(0);
    session_start();

    if (isset($_app['env']['pages']) &&
        !empty($_app['env']['pages'])
    ) {
      $pages = explode(',', $_app['env']['pages']);
      $_app['env']['pages'] = [];
      foreach($pages as $page) {
        list($pagekey, $pageitem) = explode('=', $page);
        $_app['env']['pages'][trim($pagekey)] = trim($pageitem);
      }
    }

    if (isset($_app['env']['buttons']) &&
        !empty($_app['env']['buttons'])
    ) {
      $buttons = explode(',', $_app['env']['buttons']);
      $_app['env']['buttons'] = [];
      foreach($buttons as $button) {
        list($buttonkey, $buttonitem) = explode('=', $button);
        $_app['env']['buttons'][trim($buttonkey)] = trim($buttonitem);
      }
    }

    $_app['env']['root'] = str_replace('/.env', '',
      Helper::Nix(realpath('.env'))
    );

    if ($_app['env']['locale']) {
      $locale_name  = '\\Web\\Locales\\'.ucwords($_app['env']['locale']);
      if (class_exists($locale_name)) {
        $locale_class   = new $locale_name;
        $_app['locale'] = $locale_class->__construct();
      }
    }

    $_app['slim'] = AppFactory::create();

    date_default_timezone_set($_app['env']['tz']);

    // Post Login Process
    //
    $_app['slim']->post('/'.$_app['env']['pages']['admin'].'/'.
    $_app['env']['pages']['login'],
    function (Request $request, Response $response) use ($_app) {
      $payload = $request->getParsedBody();
      if ($_SESSION['csrf'] == $payload['userCsrf']) {
        $verify_login = Helper::Connect($_app, 'users/login', [
          'pkey' => $_app['env']['api_key'],
          'host' => $_SERVER['HTTP_HOST'],
          'body' => [
            'user' => $payload['userName'],
            'pass' => $payload['userPass']
          ]
        ]);
        $set_vars = Helper::Variables($_app);
        if ($verify_login['success']) {
          if (isset($verify_login['payload']['error'])) {
            return $response
            ->withRedirect(
              $set_vars['host'].'/'.$set_vars['admin'].'/?msg='.
              urlencode($verify_login['payload']['message'])
            );
          }else{
            $_SESSION['logged'] = $verify_login['payload'];
            return $response
            ->withRedirect($set_vars['host'].'/'.$set_vars['admin']);
          }
        }
      }
      return $response
      ->withRedirect($set_vars['host']);
    });

    // Post CRUD Process
    //
    $_app['slim']->post('/'.$_app['env']['pages']['admin'].
    '/[{params:.*}]',
    function (
      Request $request,
      Response $response,
      Array $arguments
    ) use ($_app) {
      $payload = $request->getParsedBody();
      if (!empty($arguments)) {
        $_app['args'] = $arguments;
      }
      if ($request->getQueryParams()) {
        $_app['get'] = $request->getQueryParams();
      }
      if ($_SESSION['csrf'] == $payload['userCsrf']) {
        $set_vars = Helper::Variables($_app);
        $crud_exe = Helper::Connect($_app, 'apps/'.$set_vars['args']['page'], [
          'pkey' => $_app['env']['api_key'],
          'host' => $_SERVER['HTTP_HOST'],
          'body' => [
            'args' => $set_vars['args'],
            'data' => $payload,
            'user' => $_SESSION['logged']
          ]
        ]);
        if (isset($crud_exe['success'])) {
          $message = $_app['locale']['backend']['content']['crud']
          [$set_vars['args']['page']][$crud_exe['payload']['status']];
          $message = str_replace('[%RECORD%]',
          implode($crud_exe['payload']['record']), $message);
          $message = str_replace('[%APP%]',
          $crud_exe['payload']['title']['plural'], $message);
          if (isset($crud_exe['payload']['errors']) &&
          !empty($crud_exe['payload']['errors'])) {
            $_SESSION['crud_response'] = [
             'message' => $message,
             'errors'  => $crud_exe['payload']['errors'],
             'status'  => $crud_exe['payload']['status'],
             'post'    => $payload
            ];
          }else{
            $_SESSION['crud_response'] = [
             'message' => $message,
             'status'  => $crud_exe['payload']['status'],
             'post'    => $payload
            ];
          }
          return $response
          ->withRedirect($set_vars['host'].'/'.$set_vars['admin'].'/'.
          $set_vars['args']['params']);
        }
        return $response
        ->withRedirect($set_vars['host'].'/'.$set_vars['admin']);
      }else{
        return $response
        ->withRedirect($set_vars['host'].'/'.$set_vars['admin']);
      }
    });

    // Logout Route
    //
    $_app['slim']->get('/'.$_app['env']['pages']['admin'].'/'.
    $_app['env']['pages']['logout'],
    function (Request $request, Response $response) use ($_app) {
      unset($_SESSION['logged']);
      $_SESSION['logged'] = $verify_login['payload'];
      $set_vars = Helper::Variables($_app);
      return $response
      ->withRedirect($set_vars['host'].'/'.$set_vars['admin']);
    });

    // Default Route
    //
    $_app['slim']->get('/'.$_app['env']['pages']['admin'].
    '/[{params:.*}]',
    function (
      Request $request,
      Response $response,
      Array $arguments
    ) use ($_app) {
      if (!empty($arguments)) {
        $_app['args'] = $arguments;
      }
      if ($request->getQueryParams()) {
        $_app['get'] = $request->getQueryParams();
      }
      return $response->write(
        Helper::TwigRender($_app)
      )->withStatus(200);
    });

    $_app['slim']->run();

  }

}
