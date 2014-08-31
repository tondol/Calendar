<?php

require_once 'config.php';
require_once 'schedules.php';

function main() {
  $schedulesTable = new SchedulesTable();

  if (isset($_REQUEST['id'])) {
    $id = intval($_REQUEST['id']);
    $schedule = $schedulesTable->selectWithId($id);
    if (!$schedule) {
      header('Location: index.php');
    }
    $year = $schedule['year'];
    $month = $schedule['month'];
    $day = $schedule['day'];

    $schedulesTable->deleteWithId($id);
    header("Location: index.php?year={$year}&month={$month}&day={$day}");
  } else {
    header('Location: index.php');
  }
}

main();
