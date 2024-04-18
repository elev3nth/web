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
      'prefix'  => 'audit_',
      'uuidkey' => 'key',
      'crud'    => [ 'R' ],
      'sortby'  => [
        'type'  => 'DESC',
        'field' => 'timestamp'
      ],
    ],
    'title' => [
      'singular' => 'Audit',
      'plural'   => 'Audits'
    ],
    'columns' => [
      [
        'name'  => 'record',
        'type'  => 'text',
        'link'  => true,
        'title' => true,
        'crud'  => [ 'R' ]
      ],
      [
        'name'  => 'app',
        'type'  => 'text',
        'crud'  => [ 'R' ]
      ],
      [
        'name'  => 'user',
        'type'  => 'text',
        'crud'  => [ 'R' ]
      ],
      [
        'name'  => 'timestamp',
        'type'  => 'text',
        'align' => 'center',
        'crud'  => [ 'R' ]
      ],
      [
        'name'  => 'status',
        'type'  => 'text',
        'align' => 'center',
        'crud'  => [ 'R' ]
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
        $query[] = '`'.$this->cnf['db']['prefix'].$colitem['name'].'`';
      }
      $query[] = '`'.$this->cnf['db']['prefix'].$this->cnf['db']['uuidkey'].'`';
      $binds[] = $params['rec'];
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
        ' (?,?,?,?,?,?) '
      , $binds)
      ->Run();
      if ($create_audit) {
        return true;
      }
      return false;

    }
  }

}
