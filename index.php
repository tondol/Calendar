<?php

require_once 'config.php';
require_once 'schedules.php';

class Util {
  public static function getThisYear() {
    $dt = new DateTime();
    return intval($dt->format('Y'));
  }
  public static function getThisMonth() {
    $dt = new DateTime();
    return intval($dt->format('m'));
  }
  public static function getToday() {
    $dt = new DateTime();
    return intval($dt->format('d'));
  }
  public static function getWeekOfFirstDay($year, $month) {
    $dt = new DateTime();
    $dt->setDate($year, $month, 1);
    return intval($dt->format('w'));
  }
  public static function getLastDayOfMonth($year, $month) {
    $dt = new DateTime();
    $dt->setDate($year, $month, 1);
    return intval($dt->format('t'));
  }
}

class DayElement {
  public function __construct($year, $month, $day, $week) {
    $this->year = $year;
    $this->month = $month;
    $this->day = $day;
    $this->week = $week;
  }
  public static function createEmpty($week) {
    return new DayElement(-1, -1, -1, $week);
  }
  public function getYear() { return $this->year; }
  public function getMonth() { return $this->month; }
  public function getDay() { return $this->day; }
  public function getWeek() { return $this->week; }
  public function isEmpty() {
    return $this->year < 0;
  }
  public function isToday() {
    if ($this->isEmpty()) {
      return false;
    }

    $dt1 = new DateTime();
    $dt2 = new DateTime();
    $dt2->setDate($this->year, $this->month, $this->day);
    return $dt1->format('Y-m-d') === $dt2->format('Y-m-d');
  }
}

class Calendar {
  public function __construct($year, $month) {
    $this->year = $year;
    $this->month = $month;
  }
  public function getYear() { return $this->year; }
  public function getMonth() { return $this->month; }
  public function toArray() {
    $lastDay = Util::getLastDayOfMonth($this->year, $this->month);
    $weekOfFirstDay = Util::getWeekOfFirstDay($this->year, $this->month);
    $rows = array();
    $day = 0;
    while ($day < $lastDay) {
      $columns = array();
      $week = 0;
      while (count($columns) < 7) {
        if ($day == 0 && $week < $weekOfFirstDay) {
          $columns[] = DayElement::createEmpty($week);
        } else if ($day >= $lastDay) {
          $columns[] = DayElement::createEmpty($week);
        } else {
          $columns[] = new DayElement($this->year, $this->month, $day + 1, $week);
          $day++;
        }
        $week++;
      }
      $rows[] = $columns;
    }
    return $rows;
  }
}

class CalendarRenderer {
  function __construct($calendar) {
    $this->weekKeys = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
    $this->weekValues = array('日', '月', '火', '水', '木', '金', '土');
    $this->calendar = $calendar;
    $this->schedulesTable = new SchedulesTable();
  }
  private function getZeroFilledMonth() {
    return str_pad($this->calendar->getMonth(), 2, '0', STR_PAD_LEFT);
  }
  private function getPrevUri() {
    $dt = new DateTime();
    $dt->setDate($this->calendar->getYear(), $this->calendar->getMonth(), 1);
    $dt->modify('last month');
    $year = intval($dt->format('Y'));
    $month = intval($dt->format('m'));
    return "?year={$year}&month={$month}";
  }
  private function getNextUri() {
    $dt = new DateTime();
    $dt->setDate($this->calendar->getYear(), $this->calendar->getMonth(), 1);
    $dt->modify('next month');
    $year = intval($dt->format('Y'));
    $month = intval($dt->format('m'));
    return "?year={$year}&month={$month}";
  }
  private function getDayUri($day) {
    return "?year={$this->calendar->getYear()}&month={$this->calendar->getMonth()}&day={$day}";
  }
  private function getSizeClass($schedulesOfMonth, $day) {
    $schedulesOfDay = array_filter($schedulesOfMonth, function ($schedule) use ($day) {
      return $schedule['day'] == $day->getDay();
    });
    $count = count($schedulesOfDay);
    if ($count == 0) {
      return 'size-1';
    } else if ($count == 1) {
      return 'size-2';
    } else if ($count == 2) {
      return 'size-3';
    } else {
      return 'size-4';
    }
  }
  public function getTitle() {
    return "{$this->calendar->getYear()}年{$this->calendar->getMonth()}月";
  }
  public function render() {
    echo '<h2>' . $this->getTitle() . '</h2>';
    echo '<table class="calendar">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="prev"><a href="' . $this->getPrevUri() . '">prev</a></th>';
    echo '<th colspan="5" class="title">' . $this->getTitle() . '</th>';
    echo '<th class="next"><a href="' . $this->getNextUri() . '">next</a></th></tr>';
    echo '<tr>';
    for ($i=0;$i<7;$i++) {
      $weekClass = 'week-' . $this->weekKeys[$i];
      echo '<th class="' . $weekClass . '">' . $this->weekValues[$i] . '</th>';
    }
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    $rows = $this->calendar->toArray();
    $schedules = $this->schedulesTable->selectAllWithMonth($this->calendar->getYear(), $this->calendar->getMonth());
    foreach ($rows as $row) {
      echo '<tr>';
      foreach ($row as $column) {
        $weekClass = 'week-' . $this->weekKeys[$column->getWeek()];
        if ($column->isEmpty()) {
          echo '<td class="' . $weekClass . ' empty">';
        } else if ($column->isToday()) {
          $sizeClass = $this->getSizeClass($schedules, $column);
          echo '<td class="' . $weekClass . ' today ' . $sizeClass . '">';
          echo '<a href="' . $this->getDayUri($column->day) . '">' . $column->day . '</a>';
        } else {
          $sizeClass = $this->getSizeClass($schedules, $column);
          echo '<td class="' . $weekClass . ' ' . $sizeClass . '">';
          echo '<a href="' . $this->getDayUri($column->day) . '">' . $column->day . '</a>';
        }
        echo '</td>';
      }
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
  }
}

class SchedulesRenderer {
  function __construct($year, $month, $day) {
    $this->schedulesTable = new SchedulesTable();
    $this->year = $year;
    $this->month = $month;
    $this->day = $day;
  }
  public function getTitle() {
    return "{$this->year}年{$this->month}月{$this->day}日の予定";
  }
  private function getDeleteUri($schedule) {
    return "delete.php?id={$schedule['id']}";
  }
  public function render() {
    $schedules = $this->schedulesTable->selectAllWithDay($this->year, $this->month, $this->day);
    echo '<h2>' . $this->getTitle() . '</h2>';
    echo '<ul>';
    foreach ($schedules as $schedule) {
      echo '<li>' . htmlspecialchars($schedule['body'], ENT_QUOTES) . ' - ' .
        '<a href="' . $this->getDeleteUri($schedule) . '">削除</a></li>';
    }
    if (count($schedules) == 0) {
      echo '<li>予定がありません。</li>';
    }
    echo '</ul>';
  }
}

function main() {
  if (isset($_REQUEST['year'])) {
    $year = intval($_REQUEST['year']);
  } else {
    $year = Util::getThisYear();
  }
  if (isset($_REQUEST['month'])) {
    $month = intval($_REQUEST['month']);
  } else {
    $month = Util::getThisMonth();
  }
  if (isset($_REQUEST['day'])) {
    $day = intval($_REQUEST['day']);
  } else {
    $day = Util::getToday();
  }

  $calendar = new Calendar($year, $month);
  $calendarRenderer = new CalendarRenderer($calendar);
  $schedulesRenderer = new SchedulesRenderer($year, $month, $day);
  $title = $calendarRenderer->getTitle();

  include 'header.tpl';
  $calendarRenderer->render();
  $schedulesRenderer->render();
  include 'form.tpl';
  include 'footer.tpl';
}

main();
