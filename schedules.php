<?php

require_once 'db.php';

class SchedulesTable {
  function __construct() {
    $this->config = &$GLOBALS['config'];
    $this->db = DB::getInstance();
  }
  public function selectWithId($id) {
    $sql = "select * from `schedules`" .
      " where `id` = ?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array(
      $id,
    ));
    return $statement->fetch(PDO::FETCH_ASSOC);
  }
  public function selectAllWithDay($year, $month, $day) {
    $sql = "select * from `schedules`" .
      " where `year` = ? and `month` = ? and `day` = ?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array(
      $year, $month, $day,
    ));
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }
  public function selectAllWithMonth($year, $month) {
    $sql = "select * from `schedules`" .
      " where `year` = ? and `month` = ?";
    $statement = $this->db->prepare($sql);
    $statement->execute(array(
      $year, $month,
    ));
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }
  public function insertInto($year, $month, $day, $body) {
    $sql = "insert into `schedules`" .
      " (`year`, `month`, `day`, `body`, `createdAt`)" .
      " VALUES (?, ?, ?, ?, ?)";
    $statement = $this->db->prepare($sql);
    return $statement->execute(array(
      $year, $month, $day, $body, date('Y-m-d h:i:s'),
    ));
  }
  public function deleteWithId($id) {
    $sql = "delete from `schedules`" .
      " where `id` = ?";
    $statement = $this->db->prepare($sql);
    return $statement->execute(array(
      $id,
    ));
  }
}
