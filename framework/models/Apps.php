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
        'auths' => [
          'required' => true,
          'unique'   => true
        ],
        'crud'  => [ 'C','R','U','D' ]
      ],
      [
        'name'  => 'slug',
        'type'  => 'text',
        'auths' => [
          'required' => true,
          'unique'   => true
        ],
        'crud'  => [ 'C','R','U','D' ]
      ],
      [
        'name'  => 'category',
        'type'  => 'select',
        'crud'  => [ 'C','R','U','D' ],
        'class' => '\\Web\\Models\\Categories'
      ],
      [
        'name'  => 'description',
        'type'  => 'textbox',
        'auths' => [
          'required' => true,
        ],
        'crud'  => [ 'C','U','D' ]
      ],
      [
        'name'   => 'default',
        'type'   => 'switch',
        'crud'   => [ 'C','U','D' ],
        'toggle' => true
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

  private function LoadModel() {

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
    else {
      $env = $this->api['client']['env'];
    }
    return [
      'cnf' => $cnf,
      'env' => $env
    ];

  }

  public function LoadApp() {

    $setup = self::LoadModel();
    $cnf   = $setup['cnf'];
    $env   = $setup['env'];
    if (!empty($cnf)) {
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
          'crd' => $cnf['db']['crud']
        ],
        'title'   => $cnf['title'],
        'tabs'    => $cnf['tabs'],
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

  public function Create() {

    $setup = self::LoadModel();
    $cnf   = $setup['cnf'];
    $env   = $setup['env'];
    if (!empty($cnf)) {
      $query  = [];
      $holder = [];
      $binds  = [];
      $record = [];
      $errors = [];
      foreach($cnf['columns'] as $ckey => $citem) {
        $hsh_name = md5($cnf['db']['prefix'].$citem['name']);
        if (isset($this->api['payload']['body']['data'][$hsh_name])) {
          if (in_array('C', $citem['crud'])) {
            $query[] = ' `'.$cnf['db']['prefix'].$citem['name'].'`';
            $holder[] = '?';
            $binds[] = !empty(
              $this->api['payload']['body']['data'][$hsh_name]
            ) ? $this->api['payload']['body']['data'][$hsh_name] : '';
          }
          if ($citem['title']) {
            $record[] = $this->api['payload']['body']['data'][$hsh_name];
          }
          if (isset($citem['auths'])) {
            if ($citem['auths']['required']) {
              $check_error = self::Validation([
                'setup'    => $setup,
                'required' => $citem['auths']['required'],
                'field'    => $cnf['db']['prefix'].$citem['name'],
                'hash'     => $hsh_name,
                'value'    =>
                $this->api['payload']['body']['data'][$hsh_name]
              ]);
              if (!empty($check_error)) {
                array_push($errors, $check_error);
              }
            }
            if ($citem['auths']['unique']) {
              $check_error = self::Validation([
                'setup'  => $setup,
                'unique' => $citem['auths']['unique'],
                'field'  => $cnf['db']['prefix'].$citem['name'],
                'hash'   => $hsh_name,
                'value'  =>
                $this->api['payload']['body']['data'][$hsh_name]
              ]);
              if (!empty($check_error)) {
                array_push($errors, $check_error);
              }
            }
          }
        }
      }
      if (empty($errors)) {
        $query[] = '`'.$cnf['db']['prefix'].$cnf['db']['uuidkey'].'`';
        $holder[] = '?';
        $binds[] = $newuid = Helper::UuidGenerate();
        $init_record  = new Pdo($this->api);
        $create_record = $init_record->Execute('
          INSERT INTO `'.$env['db_prfx'].$cnf['db']['table'].'` '.
          ' (' . (!empty($query) ? implode(', ', $query) : '') . ') VALUES '.
          ' ( ' . (!empty($holder) ? implode(', ', $holder) : '') . ' ) '
        , $binds)
        ->Run();
        if ($create_record) {
          $init_auditing = new Auditing($this->api);
          $init_auditing->AppAudit([
            'env'    => $env,
            'key'    => Helper::UuidGenerate(),
            'app'    => $newuid,
            'user'   => $this->api['payload']['body']['user']['uidtkn'],
            'time'   => time(),
            'status' => 'create'
          ]);
          return [
            'record' => $record,
            'title'  => $cnf['title'],
            'newuid' => $newuid,
            'status' => 'created'
          ];
        }else{
          return [
            'record' => $record,
            'title'  => $cnf['title'],
            'status' => 'error'
          ];
        }
      }else{
        return [
          'title'  => $cnf['title'],
          'errors' => $errors,
          'status' => 'validate'
        ];
      }
    }
    return false;

  }

  public function Edit() {

    $setup = self::LoadModel();
    $cnf   = $setup['cnf'];
    $env   = $setup['env'];
    if (!empty($cnf)) {
      if (isset($this->api['payload']['body']['args']['uuid'])) {
        if (Helper::UuidValidate(
          $this->api['payload']['body']['args']['uuid']
        )) {
          $init_record  = new Pdo($this->api);
          $check_record = $init_record->Execute('
           SELECT * FROM
           `'.$env['db_prfx'].$cnf['db']['table'].'`'.
           ' WHERE `'.$cnf['db']['prefix'].$cnf['db']['uuidkey'].'` = ? '.
           ' LIMIT 0, 1 '
           , [ $this->api['payload']['body']['args']['uuid'] ])
          ->Run();
          if (!empty($check_record)) {
            $query  = [];
            $binds  = [];
            $record = [];
            $errors = [];
            foreach($cnf['columns'] as $ckey => $citem) {
              $hsh_name = md5($cnf['db']['prefix'].$citem['name']);
              if (isset($this->api['payload']['body']['data'][$hsh_name])) {
                if (in_array('U', $citem['crud'])) {
                  $query[] = ' `'.$cnf['db']['prefix'].$citem['name'].'` = ? ';
                  $binds[] = !empty(
                    $this->api['payload']['body']['data'][$hsh_name]
                  ) ? $this->api['payload']['body']['data'][$hsh_name] : '';
                }
                if ($citem['title']) {
                  $record[] = $this->api['payload']['body']['data'][$hsh_name];
                }
                if (isset($citem['auths'])) {
                  if ($citem['auths']['required']) {
                    $check_error = self::Validation([
                      'setup'    => $setup,
                      'required' => $citem['auths']['required'],
                      'field'    => $cnf['db']['prefix'].$citem['name'],
                      'hash'     => $hsh_name,
                      'value'    =>
                      $this->api['payload']['body']['data'][$hsh_name]
                    ]);
                    if (!empty($check_error)) {
                      array_push($errors, $check_error);
                    }
                  }
                  if ($citem['auths']['unique']) {
                    $check_error = self::Validation([
                      'setup'  => $setup,
                      'unique' => $citem['auths']['unique'],
                      'field'  => $cnf['db']['prefix'].$citem['name'],
                      'hash'   => $hsh_name,
                      'value'  =>
                      $this->api['payload']['body']['data'][$hsh_name],
                      'key'    =>
                      $this->api['payload']['body']['args']['uuid']
                    ]);
                    if (!empty($check_error)) {
                      array_push($errors, $check_error);
                    }
                  }
                }
              }
            }
            if (empty($errors)) {
              if (!empty($query) && !empty($binds)) {
                $binds[] = $this->api['payload']['body']['args']['uuid'];
                $update_record = $init_record->Execute('
                  UPDATE `'.$env['db_prfx'].$cnf['db']['table'].'` SET '.
                  (!empty($query) ? implode(', ', $query) : '') .
                  ' WHERE `'.$cnf['db']['prefix'].$cnf['db']['uuidkey'].'` = ? '
                , $binds)
                ->Run();
                if ($update_record) {
                  $init_auditing = new Auditing($this->api);
                  $xxx = $init_auditing->AppAudit([
                    'env'    => $env,
                    'key'    => Helper::UuidGenerate(),
                    'app'    => $this->api['payload']['body']['args']['uuid'],
                    'user'   => $this->api['payload']['body']['user']['uidtkn'],
                    'time'   => time(),
                    'status' => 'edit'
                  ]);
                  return [
                    'record' => $record,
                    'title'  => $cnf['title'],
                    'status' => 'success'
                  ];
                }else{
                  return [
                    'record' => $record,
                    'title'  => $cnf['title'],
                    'status' => 'nochange'
                  ];
                }
              }
            }else{
              return [
                'record' => $record,
                'title'  => $cnf['title'],
                'errors' => $errors,
                'status' => 'validate'
              ];
            }
          }
        }
      }
    }
    return false;

  }

  public function Delete() {
    return $this->api['payload']['body'];
  }

  private function Validation($params = []) {
    if (!empty($params)) {
      $errors = [];
      $iempty  = false;
      if ($params['required']) {
        if (empty($params['value'])) {
          $iempty    = true;
          $errors = [
            'type'  => 'required',
            'field' => $params['hash']
          ];
        }
      }
      if (!$empty && $params['unique']) {
        $init_record  = new Pdo($this->api);
        if (!isset($this->api['payload']['body']['args']['uuid'])) {
          $check_record = $init_record->Execute('
           SELECT * FROM
           `'.$params['setup']['env']['db_prfx'].
           $params['setup']['cnf']['db']['table'].'`'.
           ' WHERE '.
           '`'.$params['field'].'` =  ? '.
           ' LIMIT 0, 1 '
           , [ $params['value'] ])
          ->Run();
          if ($check_record) {
            $errors = [
              'type' => 'unique',
              'field' => $params['hash']
            ];
          }
        }else{
          if (Helper::UuidValidate(
            $this->api['payload']['body']['args']['uuid']
          )) {
            $check_record = $init_record->Execute('
             SELECT * FROM
             `'.$params['setup']['env']['db_prfx'].
             $params['setup']['cnf']['db']['table'].'`'.
             ' WHERE '.
             '`'.$params['field'].'` =  ? AND '.
             '`'.$params['setup']['cnf']['db']['prefix'].
             $params['setup']['cnf']['db']['uuidkey'].'` != ? '.
             ' LIMIT 0, 1 '
             , [ $params['value'], $params['key'] ])
            ->Run();
            if ($check_record) {
              $errors = [
                'type' => 'unique',
                'field' => $params['hash']
              ];
            }
          }
        }
      }
      return $errors;
    }
    return false;
  }

}
