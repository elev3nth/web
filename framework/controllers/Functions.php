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

  public static function Pagination($pageno, $paging, $totalcount) {

    if ($paging) {
      $pagination = $paging;
    }else{
      $pagination = 10;
    }
    if (($pageno == 1) || (!$pageno)) {
      $min = 0;
      $max = $pagination;
    }else{
      $min = (($pageno - 1) * $pagination);
      $max = $pagination;
    }
    if ($totalcount != 0) {
      if ($max != 0) {
        $max_paging = ceil($totalcount / $max);
      }else{
        $max_paging = 1;
      }
      if ($pageno) {
        $min_paging = $pageno;
      }else{
        $min_paging = 1;
      }
      if ($pageno > $max_paging) {
        $min_paging = $max_paging;
      }
      if ($pageno < 1) {
        $min_paging = 1;
      }
      $paging_range = 4;
      $paging_setup = [];
      for($pages = ($min_paging - $paging_range);
        $pages < (($min_paging + $paging_range) + 1); $pages++) {
        if (($pages > 0) && ($pages <= $max_paging)) {
          if ($pages == $min_paging) {
            $paging_setup[] = [
              'page'   => $pages,
              'active' => true
            ];
          }else{
            $paging_setup[] = [
                'page'   => $pages
            ];
          }
        }
      }
      if ($min_paging != 1) {
        $previous_page = ($min_paging - 1);
      }
      if ($min_paging != $max_paging) {
        $next_page = ($min_paging + 1);
      }
      return [
        'total' => $totalcount,
        'min'   => $min_paging > 1 ? $min_paging : 1,
        'max'   => $max_paging > 1 ? $max_paging : 1,
        'prev'  => isset($previous_page) ? $previous_page : false,
        'next'  => isset($next_page) ? $next_page : false,
        'pages' => $paging_setup ? $paging_setup : 1
      ];
    }else{
      return [
        'total' => 0,
        'min'   => 0,
        'max'   => $paging,
        'prev'  => 0,
        'next'  => 0,
        'pages' => []
      ];
    }

  }

}
