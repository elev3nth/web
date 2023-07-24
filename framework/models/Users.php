<?php

declare(strict_types=1);

namespace Web\Models;

use Web\Controllers\{
  Pdo, Helper
};

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

    $verify_user = new Pdo($this->api);
    $verified_user = $verify_user->Execute('
     SELECT * FROM
     `'.$this->api['env']['db_prfx'].$this->cnf['table'].'`
     WHERE `'.$this->cnf['cprfx'].'email` = ? AND
     `'.$this->cnf['cprfx'].'enabled` = ? LIMIT 1
    ', [
      $this->api['payload']['body']['user'],
      1
    ])
    ->Run();
    if (empty($verified_user)) {
      $verify_user = new Pdo($this->api['client']);
      $verified_user = $verify_user->Execute('
       SELECT * FROM
       `'.$this->api['client']['env']['db_prfx'].$this->cnf['table'].'`
       WHERE `'.$this->cnf['cprfx'].'email` = ? AND
       `'.$this->cnf['cprfx'].'enabled` = ? LIMIT 1
      ', [
        $this->api['payload']['body']['user'],
        1
      ])
      ->Run();
    }

    if (!empty($verified_user) &&
        (password_verify(
          $this->api['payload']['body']['pass'],
          $verified_user['user_password']
        ))) {
      return [
        'uidtkn' => $verified_user['user_key'],
        'fname'  => $verified_user['user_firstname'],
        'mname'  => $verified_user['user_middlename'],
        'lname'  => $verified_user['user_lastname'],
        'email'  => $verified_user['user_email'],
        'dob'    => $verified_user['user_dob']
      ];
    }

    return [
      'error'   => true,
      'message' => 'Incorrect E-Mail or Password'
    ];

  }

}
