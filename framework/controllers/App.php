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
    error_reporting(E_ALL);
    session_start();

    $_app                = [];
    $_app['env']         = parse_ini_file(realpath('.env'), true);
    $_app['env']['root'] = str_replace('/.env', '',
      Helper::Nix(realpath('.env'))
    );
    $_app['slim']        = AppFactory::create();

    date_default_timezone_set($_app['env']['tz']);

    $_app['slim']->get('/admin/[{category}[/{application}[/{page}[/{params:.*}]]]]',
    function (
      Request $request,
      Response $response,
      Array $arguments
    ) use ($_app) {

      if (!empty($arguments)) {
        // print_r($arguments);
      }

      return $response->write(
        Helper::TwigRender($_app)
      )->withStatus(200);

    });

    $_app['slim']->run();

  }

}
