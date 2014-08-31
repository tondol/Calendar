<?php

require_once 'config.php';
require_once 'schedules.php';

function main() {
  $schedulesTable = new SchedulesTable();

  if (isset($_REQUEST['year']) && isset($_REQUEST['month']) && isset($_REQUEST['day']) && isset($_REQUEST['body'])) {
    $year = intval($_REQUEST['year']);
    $month = intval($_REQUEST['month']);
    $day = intval($_REQUEST['day']);
    $body = $_REQUEST['body'];

    $schedulesTable->insertInto($year, $month, $day, $body);
    header("Location: index.php?year={$year}&month={$month}&day={$day}");
  } else {
    header('Location: index.php');
  }
}

main();
