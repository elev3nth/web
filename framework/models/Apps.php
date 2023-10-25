<?php

declare(strict_types=1);

namespace Web\Models;

use Web\Controllers\{
  Pdo, Functions, Helper
};

class Apps {

  private $api;
  private $cnf = [
    'db' => [
      'table'   => 'apps',
      'prefix'  => 'app_',
      'uuidkey' => 'key',
      'enabled' => true,
      'sorted'  => true,
      'crud'    => [ 'C','R','U','D' ]
    ],
    'title'   => [
      'singular' => 'Application',
      'plural'   => 'Applications'
    ],
    'columns' => [
      [
        'name'  => 'name',
        'type'  => 'text',
        'link'  => true,
        'title' => true,
        'crud'  => [ 'C','R','U','D' ]
      ],
      [
        'name' => 'slug',
        'type' => 'text',
        'crud' => [ 'C','R','U','D' ]
      ],
      [
        'name'  => 'category',
        'type'  => 'select',
        'crud'  => [ 'C','R','U','D' ],
        'class' => '\\Web\\Models\\Categories'
      ],
      [
        'name' => 'description',
        'type' => 'textbox',
        'crud' => [ 'C','U','D' ]
      ],
      [
        'name'      => 'default',
        'type'      => 'switch',
        'crud'      => [ 'C','U','D' ],
        'exclusive' => true
      ]
    ]
  ];

  public function __construct($_api)  {
     $this->api = $_api;
     return $this;
  }

  public function InitApps() {

    if ($this->api['payload']['body']['user']['host']) {
      $init_apps = new Pdo($this->api);
      $load_apps = $init_apps->Execute('
       SELECT * FROM
       `'.$this->api['env']['db_prfx'].$this->cnf['db']['table'].'`
       WHERE `'.$this->cnf['db']['prefix'].'enabled` = ?
       ORDER BY `'.$this->cnf['db']['prefix'].'sort` ASC
      ', [ 1 ])
      ->Run();
    }
    if (!empty($load_apps)) {
      $apps = [];
      foreach($load_apps aS $appkey => $appitem) {
        $apps[] = [
          'ukey' => $appitem[$this->cnf['db']['prefix'].'key'],
          'ckey' => $appitem[$this->cnf['db']['prefix'].'category'],
          'name' => $appitem[$this->cnf['db']['prefix'].'name'],
          'slug' => $appitem[$this->cnf['db']['prefix'].'slug']
        ];
      }
      return $apps;
    }
    return false;

  }

  public function LoadApp() {
    if (
      $this->api['payload']['body']['args']['application'] != 'applications'
    ) {
      $model = str_replace(' ', '', ucwords(str_replace('-', ' ',
        $this->api['payload']['body']['args']['application']
      )));
      $class = '\\Web\\Models\\'.$model;
      $cstrk = (array) new $class([]);
      if (!empty($cstrk)) {
        foreach($cstrk as $ckey => $citem) {
          if (!empty($citem)) {
            $cnf = $citem;
          }
        }
      }
    }
    else {
      $cnf = $this->cnf;
    }
    if ($this->api['payload']['body']['user']['host']) {
      $env = $this->api['env'];
    }
    else{
      $env = $this->api['client']['env'];
    }
    if (isset($cnf) && !empty($cnf)) {
      $query = [];
      $binds = [];
      if (isset($this->api['payload']['body']['args']['uuid'])) {
        if (Helper::UuidValidate(
          $this->api['payload']['body']['args']['uuid']
        )) {
          $query[] = ' `'.$cnf['db']['prefix'].$cnf['db']['uuidkey'].'` = ? ';
          $binds[] = $this->api['payload']['body']['args']['uuid'];
        }
      }
      if ($cnf['db']['enabled']) {
        $query[] = ' `'.$cnf['db']['prefix'].'enabled'.'` = ? ';
        $binds[] = 1;
      }
      if ($cnf['db']['sorted']) {
        $sortable = ' ORDER BY `'.$cnf['db']['prefix'].'sort` ASC ';
      }

      $init_apps = new Pdo($this->api);

      if (!isset($this->api['payload']['body']['args']['uuid'])) {
        if ($this->api['payload']['body']['args']['pagenum'] == 1) {
          $limit = ' LIMIT 0, ' . $this->api['env']['db_pges'];
        }else{
          $limit = ' LIMIT ' . (
            $this->api['payload']['body']['args']['pagenum'] - 1 *
            $this->api['env']['db_pges']
          ) . ', ' . $this->api['env']['db_pges'];
        }
        $count_apps = $init_apps->Execute('
         SELECT COUNT(*) as total FROM
         `'.$env['db_prfx'].$cnf['db']['table'].'`
         ' . (!empty($query) ? ' WHERE ' . implode(' AND ', $query) : '') .
         ' ' . $sortable
         , $binds)
        ->Run();
        $load_apps = $init_apps->Execute('
         SELECT * FROM
         `'.$env['db_prfx'].$cnf['db']['table'].'`
         ' . (!empty($query) ? ' WHERE ' . implode(' AND ', $query) : '') .
         ' ' . $sortable . $limit
         , $binds)
        ->Run();
      }else{
        $load_apps = $init_apps->Execute('
         SELECT * FROM
         `'.$env['db_prfx'].$cnf['db']['table'].'`
         ' . (!empty($query) ? ' WHERE ' . implode(' AND ', $query) : '') .
         ' ' . $sortable . ' LIMIT 0, 1'
         , $binds)
        ->Run();
      }
      foreach($cnf['columns'] as $ckey => $citem) {
        $cnf['columns'][$ckey]['flds'] = $cnf['db']['prefix'].$citem['name'];
        $cnf['columns'][$ckey]['varf'] = md5(
          $cnf['db']['prefix'].$citem['name']
        );
        if (isset($citem['class'])) {
          $cnf['columns'][$ckey]['opts'] = Functions::Options(
            $this->api, $citem['class']
          );
        }
      }
      return [
        'table'   => [
          'pfx' => $cnf['db']['prefix'],
          'key' => $cnf['db']['uuidkey'],
          'hsh' => md5($cnf['db']['prefix'].$cnf['db']['uuidkey']),
          'enb' => $cnf['db']['enabled'] ? true : false,
          'srt' => $cnf['db']['sorted'] ? true : false,
          'crd' => $cnf['db']['crud'],
        ],
        'title'   => $cnf['title'],
        'columns' => $cnf['columns'],
        'paging'  => isset($count_apps) ? Functions::Pagination(
          $this->api['payload']['body']['args']['pagenum'],
          $this->api['env']['db_pges'],
          $count_apps['total']
        ) : 0,
        'data'    => $load_apps ? $load_apps : false
      ];
    }
  }

}
