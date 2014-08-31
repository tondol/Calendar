<?php

class DB {
  private static $instance;
  function __construct() {
    $this->config = &$GLOBALS['config'];
    $this->db = $this->getDB();
  }
  private function getDB() {
    try {
      return new PDO(
        "mysql:dbname=" . $this->config['db']['database'] . ";host=" . $this->config['db']['host'],
        $this->config['db']['user'],
        $this->config['db']['password'],
        array(
          PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        ));
    } catch (PDOException $e) {
      exit("db connection error: " . $e->getMessage());
    }
  }
  public static function getInstance() {
    if (is_null(self::$instance)) {
      self::$instance = new DB();
    }
    return self::$instance->db;
  }
}
