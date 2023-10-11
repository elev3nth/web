<?php

declare(strict_types=1);

namespace Web\Models;

use Web\Controllers\{
  Pdo, Helper
};

class Categories {

  private $api;
  private $cnf = [
    'db' => [
      'table'   => 'categories',
      'prefix'  => 'ctg_',
      'uuidkey' => 'key',
      'enabled' => true,
      'sorted'  => true,
    ],
    'title' => [
      'singular' => 'Category',
      'plural'   => 'Categories'
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
        'name' => 'parent',
        'type' => 'select',
        'crud' => [ 'C','R','U','D' ]
      ],
      [
        'name' => 'description',
        'type' => 'textbox',
        'crud' => [ 'C','U','D' ]
      ]
    ]
  ];

  public function __construct($_api)  {
     $this->api = $_api;
     return $this;
  }

  public function InitCtgs() {

    if ($this->api['payload']['body']['user']['host']) {
      $init_categories = new Pdo($this->api);
      $load_categories = $init_categories->Execute('
       SELECT * FROM
       `'.$this->api['env']['db_prfx'].$this->cnf['db']['table'].'`
       WHERE `'.$this->cnf['db']['prefix'].'enabled` = ?
       ORDER BY `'.$this->cnf['db']['prefix'].'sort` ASC
      ', [ 1 ])
      ->Run();
    }
    if (!empty($load_categories)) {
      $ctgs = [];
      foreach($load_categories aS $ctgkey => $ctgitem) {
        $ctgs[] = [
          'ukey' => $ctgitem[$this->cnf['db']['prefix'].'key'],
          'name' => $ctgitem[$this->cnf['db']['prefix'].'name'],
          'slug' => $ctgitem[$this->cnf['db']['prefix'].'slug']
        ];
      }
      return $ctgs;
    }
    return false;

  }

}
