<?php

declare(strict_types=1);

namespace Web\Controllers;

class Pdo {

  private $fetch;
  private $query;
  private $binds;
  private $statement;
  private $driver;

  public function __construct($_api) {
     try {
       $this->driver    = new \PDO(
        $_api['env']['db_type'].':host='.$_api['env']['db_host'] .
        ';dbname='.$_api['env']['db_base'] .
        ';charset=UTF8',
        $_api['env']['db_user'],
        $_api['env']['db_pass'],
        [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
            \PDO::MYSQL_ATTR_FOUND_ROWS   => false
        ]
      );
    } catch(\PDOException $e) {
      throw new \PDOException(
        $e->getMessage(),
        (int) $e->getCode()
      );
    }
    return $this;
  }

  public function Execute($query, $binds = [], $debug = false) {
    if (!empty($query)) {
      $this->fetch     = false;
      $this->query     = '';
      $this->binds     = [];
      $this->statement = '';
      if (strpos(strtolower($query), 'select') !== false ||
          strpos(strtolower($query), 'count') !== false) {
          $this->fetch = true;
      }
      if ($debug) {
        $this->query = $query;
        $this->binds = $binds;
      }
      try {
        $this->statement = $this->driver->prepare($query);
        $this->statement->execute($binds);
      } catch(\PDOException $e) {
        throw new \PDOException($e->getMessage());
      }
    }
    return $this;
  }

  public function Run() {
    if (isset($this->query) || isset($this->binds)) {
      if (!empty($this->query) || !empty($this->binds)) {
        echo $this->query . "\n\r";
        echo json_encode($this->binds);
      }
    }
    if ($this->fetch) {
      $data = $this->statement->fetchAll();
      if ($data) {
        if (count($data) > 1) {
          return $data;
        }else{
          return $data[0];
        }
      }
      return false;
    }else{
      return $this->statement->rowCount();
    }
    return false;
  }

}
