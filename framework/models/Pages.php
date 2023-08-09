<?php

declare(strict_types=1);

namespace Web\Models;

class Pages {

  private $api;
  private $cnf = [
    'table' => 'pages',
    'cprfx' => 'page_',
    'title' => [
      'singular' => 'Page',
      'plural'   => 'Pages'
    ]
  ];

  public function __construct($_api)  {
     $this->api = $_api;
     return $this;
  }

}
