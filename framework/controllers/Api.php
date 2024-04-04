<?php

declare(strict_types=1);

namespace Web\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

final class Api extends Methods {

  public static function Init() {
    $init = new self;
    return $init->Routing();
  }

}

abstract class Methods {

  public function Routing() {

    set_time_limit(0);
    error_reporting(0);

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, X-Requested-With, Authorization");
    header('Access-Control-Max-Age: 1000');

    $_api           = [];
    $_api['env']    = parse_ini_file(realpath('.env'), true);
    $_api['slim']   = AppFactory::create();

    $_api['slim']->post('/[{model}[/{endpoint}[/{routines:.*}]]]',
    function (
      Request $request,
      Response $response,
      Array $arguments
    ) use ($_api) {

      if ($request->GetHeaders() !== null) {

        foreach($request->GetHeaders() as $rhkey => $rhitem) {
          if (isset($rhitem[0])) {
            $hfix = explode('-', $rhkey);
            if (!empty($hfix)) {
              if (!empty($hfix[1]) && strlen($rhitem[0]) < 50) {
                  $_api['headers'][strtolower($hfix[1])] = $rhitem[0];
              }
            }
          }
        }

        if (!empty($_api['headers'])) {

          $client = [
              'addr' => $_api['headers']['addr'] ?: false,
              'host' => $_api['headers']['host'] ?: false,
              'tmzn' => $_api['headers']['tmzn'] ?: 'Pacific/Auckland'
          ];

          date_default_timezone_set($client['tmzn']);

          if ($request->getParsedBody() !== null) {

            $payload = $request->getParsedBody();
            if (isset($payload[0])) {
              $payload = $payload[0];
            }else{
              $payload = $request->getParsedBody()['payload'];
            }

            if ($client['addr'] && $client['host']) {

              $logged_ep = new Pdo($_api);
              $logged_ep->Execute('
               INSERT INTO `'.$_api['env']['db_prfx'].'logs`
               (`log_address`, `log_host`, `log_payload`, `log_date`) VALUES
               (?, ?, ?, ?)', [
                trim($client['addr']),
                trim($client['host']),
                $payload,
                date('Y-m-d H:i:s')
              ])
              ->Run();

              $verify_client = new Pdo($_api);
              $verified_client = $verify_client->Execute('
               SELECT `client_key`,
               `client_domain`,
               `client_credentials` FROM
               `'.$_api['env']['db_prfx'].'clients`
               WHERE client_ip_address = ? AND client_domain = ? AND
               client_enabled = ? LIMIT 1
              ', [
                trim($client['addr']),
                trim($client['host']),
                1
              ])
              ->Run();

              if ($verified_client && $payload) {

                $decrypted_payload = Helper::Decrypt(
                  trim($payload),
                  trim($verified_client['client_key'])
                );

                if ($decrypted_payload) {

                  $decrypted_payload = json_decode(
                    base64_decode($decrypted_payload), true);
                  if (!empty($verified_client['client_credentials'])) {
                    $decrypted_crdntls = json_decode(
                      base64_decode(Helper::Decrypt(
                        trim($verified_client['client_credentials']),
                        trim($verified_client['client_key'])
                      )),
                    true);
                    if (!empty($decrypted_crdntls)) {
                      $verified_client['env'] = $decrypted_crdntls;
                    }
                  }

                  if (!empty($decrypted_payload) &&
                    isset($decrypted_payload['pkey']) &&
                    isset($decrypted_payload['host']) &&
                    $decrypted_payload['pkey'] ==
                    $verified_client['client_key'] &&
                    $decrypted_payload['host'] ==
                    $verified_client['client_domain']) {

                    $_api['client']  = $verified_client;
                    $_api['payload'] = $decrypted_payload;
                    $_api['model']   = trim(str_replace(' ', '', ucwords(
                      str_replace('-', ' ', $arguments['model']))));
                    $_api['endpoint'] = trim(str_replace(' ', '', ucwords(
                      str_replace('-', ' ', $arguments['endpoint']))));
                    if (isset($arguments['routines'])) {
                      $_api['routines'] = array_filter(
                        explode('/', trim(str_replace(' ', '',
                        ucwords(str_replace('-', ' ', $arguments['routines'])))
                      )));
                    }
                    $modelfile = Helper::Nix(__DIR__).
                    '/../models/'.$_api['model'].'.php';

                    if (file_exists($modelfile)) {

                      include(__DIR__.'/../models/'.
                      $_api['model'].'.php');
                      $initmodel = '\\Web\\Models\\'.$_api['model'];
                      $initclass = new $initmodel($_api);
                      if (class_exists($initmodel) &&
                          method_exists($initclass, $_api['endpoint'])) {
                          $ep = trim($_api['endpoint']);
                          return $response
                          ->withJson([
                            'success' => true,
                            'payload' => $initclass->$ep()
                          ])->withStatus(200);
                      }

                    }

                  }

                }

              }

            }

          }

        }

      }

      return $response
      ->withJson([
        'error'   => true,
        'message' => 'Invalid Access',
      ])->withStatus(401);

    });

    $_api['slim']->get('/[{routes:.*}]',
    function (
      Request $request,
      Response $response,
      Array $arguments
    ) use ($_api) {
      return $response
      ->withJson([
        'error'   => true,
        'message' => 'Invalid Access',
      ])->withStatus(401);
    });

    $_api['slim']->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'], '/{routes:.+}',
    function(
      Request $request,
      Response $response,
      Array $arguments
    ) use ($_api) {
        $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
        return $handler($req, $res);
    });

    $_api['slim']->run();

  }

}
