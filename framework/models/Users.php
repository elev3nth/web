<?php

declare(strict_types=1);

namespace Web\Models;

class Users {

  private $api;
  private $cnf = [
    'table' => 'users',
    'cprfx' => 'user_'
  ];

  public function __construct($_api)  {
     $this->api = $_api;
     return $this;
  }

  public function Login() {    
    return $this->api['payload']['body'];
  }

}
