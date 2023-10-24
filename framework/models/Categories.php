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
      'sorted'  => true
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
        'crud' => [ 'C','R','U','D' ],
        'class' => '\\Web\\Models\\Categories'
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
       WHERE `'.$this->cnf['db']['prefix'].'parent` = ? AND
       `'.$this->cnf['db']['prefix'].'enabled` = ?
       ORDER BY `'.$this->cnf['db']['prefix'].'sort` ASC
      ', [ '', 1 ])
      ->Run();
    }
    if (!empty($load_categories)) {
      $ctgs = [];
      $self = new self($this->api);
      foreach($load_categories as $ctgkey => $ctgitem) {
        $ctgs[] = [
          'ukey' => $ctgitem[$this->cnf['db']['prefix'].'key'],
          'name' => $ctgitem[$this->cnf['db']['prefix'].'name'],
          'slug' => $ctgitem[$this->cnf['db']['prefix'].'slug'],
          'sctg' => $self->SubCategories(
            $ctgitem[$this->cnf['db']['prefix'].'key']
          )
        ];
      }
      return $ctgs;
    }
    return false;

  }

  private function SubCategories($ctgkey) {

    if (Helper::UuidValidate($ctgkey)) {

      $init_subcategories = new Pdo($this->api);
      $load_subcategories = $init_subcategories->Execute('SELECT * FROM
       `'.$this->api['env']['db_prfx'].$this->cnf['db']['table'].'`
       WHERE `'.$this->cnf['db']['prefix'].'parent` = ? AND
       `'.$this->cnf['db']['prefix'].'enabled` = ?
       ORDER BY `'.$this->cnf['db']['prefix'].'sort` ASC
      ', [ $ctgkey, 1 ])
      ->Run();
      if (!empty($load_subcategories)) {
        $tmp  = [];
        $self = new self($this->api);
        if (!isset($load_subcategories[0])) {
          $tmp[] = [
            'ukey' => $load_subcategories[$this->cnf['db']['prefix'].'key'],
            'name' => $load_subcategories[$this->cnf['db']['prefix'].'name'],
            'slug' => $load_subcategories[$this->cnf['db']['prefix'].'slug'],
            'prnt' => $load_subcategories[$this->cnf['db']['prefix'].'parent'],
            'sctg' => $self->SubCategories(
              $load_subcategories[$this->cnf['db']['prefix'].'key']
            )
          ];
        }else{
          foreach($load_subcategories as $sctgkey => $sctgitem) {
            $tmp[] = [
              'ukey' => $sctgitem[$this->cnf['db']['prefix'].'key'],
              'name' => $sctgitem[$this->cnf['db']['prefix'].'name'],
              'slug' => $sctgitem[$this->cnf['db']['prefix'].'slug'],
              'prnt' => $sctgitem[$this->cnf['db']['prefix'].'parent'],
              'sctg' => $self->SubCategories(
                $sctgitem[$this->cnf['db']['prefix'].'key']
              )
            ];
          }
        }

        if (!empty($tmp)) {
          return $tmp;
        }
      }
    }

    return false;

  }

}
