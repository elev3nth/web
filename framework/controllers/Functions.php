<?php

declare(strict_types=1);

namespace Web\Controllers;

class Functions {

  public static function Options($_api, $class) {

    $appopts = (array) new $class([]);
    if (!empty($appopts)) {
      foreach($appopts as $optkey => $optitem) {
        if (!empty($optitem)) {
          $cnf = $optitem;
        }
      }
      if ($_api['payload']['body']['user']['host']) {
        $init_apps = new Pdo($_api);
      }else{
        $init_apps = new Pdo($_api['client']);
      }
      if ($cnf['db']['enabled']) {
        $query[] = ' `'.$cnf['db']['prefix'].'enabled'.'` = ? ';
        $binds[] = 1;
      }
      if ($cnf['db']['sorted']) {
        $sortable = ' ORDER BY `'.$cnf['db']['prefix'].'sort` ASC ';
      }
      $load_apps = $init_apps->Execute('
       SELECT * FROM
        `'.$_api['env']['db_prfx'].$cnf['db']['table'].'`
        ' . (!empty($query) ? ' WHERE ' . implode(' AND ', $query) : '') .
        ' ' . $sortable
       , $binds)
      ->Run();

      if (!empty($load_apps)) {
        return [
          'conf' => $cnf,
          'data' => $load_apps
        ];
      }

      return false;

    }

  }

}
