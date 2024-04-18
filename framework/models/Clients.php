<?php

declare(strict_types=1);

namespace Web\Models;

class Clients {

  private $api;
  private $cnf = [
    'table'  => 'clients',
    'prefix' => 'client_',
    'title'  => [
      'singular' => 'Client',
      'plural'   => 'Clients'
    ]
  ];

  public function __construct($_api)  {
     $this->api = $_api;
     return $this;
  }

  public function Login() {
  }

}
