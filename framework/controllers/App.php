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

    set_time_limit(0);
    error_reporting(0);
    session_start();

    $_app                = [];
    $_app['env']         = parse_ini_file(realpath('.env'), true);
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
    $_app['env']['root'] = str_replace('/.env', '',
      Helper::Nix(realpath('.env'))
    );
    $_app['slim'] = AppFactory::create();

    date_default_timezone_set($_app['env']['tz']);

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

    $_app['slim']->get('/'.$_app['env']['pages']['admin'].'/'.
    $_app['env']['pages']['logout'],
    function (Request $request, Response $response) use ($_app) {
      unset($_SESSION['logged']);
      $_SESSION['logged'] = $verify_login['payload'];
      $set_vars = Helper::Variables($_app);
      return $response
      ->withRedirect($set_vars['host'].'/'.$set_vars['admin']);
    });

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
