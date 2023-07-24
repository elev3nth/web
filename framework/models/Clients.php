<?php

declare(strict_types=1);

namespace Web\Models;

class Clients {

  private $api;
  private $cnf = [
    'table' => 'clients',
    'cprfx' => 'client_'
  ];

  public function __construct($_api)  {
     $this->api = $_api;
     return $this;
  }

  public function Login() {
  }  

}
