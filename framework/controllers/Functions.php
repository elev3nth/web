<?php

declare(strict_types=1);

namespace Web\Controllers;

class Functions {

  public static function Options($_api, $class) {

    $app = (array) new $class($_api);

    /*
    $init_apps = new Pdo($_api);
    $load_apps = $init_apps->Execute('
     SELECT * FROM
     `'.$env['db_prfx'].$cnf['db']['table'].'`
     ' . (!empty($query) ? ' WHERE ' . implode(' AND ', $query) : '') .
     ' ' . $sortable
     , $binds)
    ->Run();
    */

    return $_api;

  }

}
