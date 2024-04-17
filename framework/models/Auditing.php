<?php

declare(strict_types=1);

namespace Web\Models;

use Web\Controllers\{
  Pdo, Functions, Helper
};

class Auditing {

  private $api;
  private $cnf = [
    'db' => [
      'table'   => 'auditing',
      'cprfx'   => 'audit_',
      'uuidkey' => 'key',
      'crud'    => [ 'R' ]
    ],
    'title' => [
      'singular' => 'Audit',
      'plural'   => 'Audits'
    ],
    'columns' => [
      [
        'name'  => 'app',
        'type'  => 'select',
        'link'  => true,
        'title' => true ,
        'class' => '\\Web\\Models\\Apps'
      ],
      [
        'name'  => 'user',
        'type'  => 'select',
        'class' => '\\Web\\Models\\Users'
      ],
      [
        'name'  => 'timestamp',
        'type'  => 'text'
      ],
      [
        'name'  => 'status',
        'type'  => 'text'
      ],
    ],
  ];

  public function __construct($_api)  {
     $this->api = $_api;
     return $this;
  }

  public function AppAudit($params) {
    if (!empty($params)) {

      $query = [];
      $binds = [];

      foreach($this->cnf['columns'] as $colkey => $colitem) {
        $query[] = '`'.$this->cnf['db']['cprfx'].$colitem['name'].'`';
      }
      $query[] = '`'.$this->cnf['db']['cprfx'].$this->cnf['db']['uuidkey'].'`';
      $binds[] = $params['app'];
      $binds[] = $params['user'];
      $binds[] = $params['time'];
      $binds[] = $params['status'];
      $binds[] = $params['key'];

      $init_audit  = new Pdo($this->api);
      $create_audit = $init_audit->Execute('
        INSERT INTO `'.
        $params['env']['db_prfx'].$this->cnf['db']['table'].'` '.
        ' (' . (!empty($query) ? implode(', ', $query) : '') . ') VALUES '.
        ' (?,?,?,?,?) '
      , $binds)
      ->Run();
      if ($create_audit) {
        return true;
      }
      return false;

    }
  }

}
