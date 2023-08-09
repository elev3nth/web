<?php

declare(strict_types=1);

namespace Web\Models;

use Web\Controllers\{
  Pdo, Helper
};

class Apps {

  private $api;
  private $cnf = [
    'table'   => 'apps',
    'cprfx'   => 'app_',
    'title'   => [
      'singular' => 'Application',
      'plural'   => 'Applications'
    ],
    'columns' => [
      [
        'name' => 'id',
        'type' => 'key'
      ],
      [
        'name' => 'key',
        'type' => 'uuid'
      ],
      [
        'name' => 'category',
        'type' => 'select'
      ],
      [
        'name' => 'name',
        'type' => 'text'
      ],
      [
        'name' => 'slug',
        'type' => 'text'
      ],
      [
        'name' => 'description',
        'type' => 'textbox'
      ],
      [
        'name'      => 'default',
        'type'      => 'switch',
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
       `'.$this->api['env']['db_prfx'].$this->cnf['table'].'`
       WHERE `'.$this->cnf['cprfx'].'enabled` = ?
       ORDER BY `'.$this->cnf['cprfx'].'sort` ASC
      ', [ 1 ])
      ->Run();
    }
    if (!empty($load_apps)) {
      $apps = [];
      foreach($load_apps aS $appkey => $appitem) {
        $apps[] = [
          'ukey' => $appitem[$this->cnf['cprfx'].'key'],
          'ckey' => $appitem[$this->cnf['cprfx'].'category'],
          'name' => $appitem[$this->cnf['cprfx'].'name'],
          'slug' => $appitem[$this->cnf['cprfx'].'slug']
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
    if (isset($cnf) && !empty($cnf)) {
      foreach($cnf['columns'] as $ckey => $citem) {
        $cnf['columns'][$ckey]['varf'] = md5($cnf['cprfx'].$citem['name']);
      }
      return [
        'title'   => $cnf['title'],
        'columns' => $cnf['columns']
      ];
    }
  }

}
