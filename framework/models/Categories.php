<?php

declare(strict_types=1);

namespace Web\Models;

use Web\Controllers\{
  Pdo, Helper
};

class Categories {

  private $api;
  private $cnf = [
    'table' => 'categories',
    'cprfx' => 'ctg_',
    'title' => [
      'singular' => 'Category',
      'plural'   => 'Categories'
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
       `'.$this->api['env']['db_prfx'].$this->cnf['table'].'`
       WHERE `'.$this->cnf['cprfx'].'enabled` = ?
       ORDER BY `'.$this->cnf['cprfx'].'sort` ASC
      ', [ 1 ])
      ->Run();
    }
    if (!empty($load_categories)) {
      $ctgs = [];
      foreach($load_categories aS $ctgkey => $ctgitem) {
        $ctgs[] = [
          'ukey' => $ctgitem[$this->cnf['cprfx'].'key'],
          'name' => $ctgitem[$this->cnf['cprfx'].'name'],
          'slug' => $ctgitem[$this->cnf['cprfx'].'slug']
        ];
      }
      return $ctgs;
    }
    return false;

  }

}
