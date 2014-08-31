<?php

date_default_timezone_set('Asia/Tokyo');

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
  public static function createCalendarOfToday() {
    return new Calendar(intval(date('Y')), intval(date('m')));
  }
  private function getZeroFilledMonth() {
    return str_pad($this->month, 2, '0', STR_PAD_LEFT);
  }
  public function renderAsArray() {
    $dt = new DateTime();
    $dt->setDate($this->year, $this->month, 1);
    $dayOfLast = intval($dt->format('t'));
    $weekOfFirst = intval($dt->format('w'));

    $rows = array();
    $day = 0;
    while ($day < $dayOfLast) {
      $columns = array();
      $week = 0;
      while (count($columns) < 7) {
        if ($day == 0 && $week < $weekOfFirst) {
          $columns[] = DayElement::createEmpty($week);
        } else if ($day >= $dayOfLast) {
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
  public function getTitle() {
    return "{$this->year}年{$this->getZeroFilledMonth()}月";
  }
  private function getPrevUri() {
    $dt = new DateTime();
    $dt->setDate($this->year, $this->month, 1);
    $dt->modify('last month');
    $year = intval($dt->format('Y'));
    $month = intval($dt->format('m'));
    return "?year={$year}&month={$month}";
  }
  private function getNextUri() {
    $dt = new DateTime();
    $dt->setDate($this->year, $this->month, 1);
    $dt->modify('next month');
    $year = intval($dt->format('Y'));
    $month = intval($dt->format('m'));
    return "?year={$year}&month={$month}";
  }
  public function renderAsHtml() {
    $title = $this->getTitle();
    $weekKeys = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
    $weekValues = array('日', '月', '火', '水', '木', '金', '土');

    echo '<table class="calendar">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="prev"><a href="' . $this->getPrevUri() . '">prev</a></th>';
    echo '<th colspan="5" class="title">' . $title . '</th>';
    echo '<th class="next"><a href="' . $this->getNextUri() . '">next</a></th></tr>';
    echo '<tr>';
    for ($i=0;$i<7;$i++) {
      echo '<th class="week-' . $weekKeys[$i] . '">' . $weekValues[$i] . '</th>';
    }
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    $rows = $this->renderAsArray();
    foreach ($rows as $row) {
      echo '<tr>';
      foreach ($row as $column) {
        if ($column->isEmpty()) {
          echo '<td class="week-' . $weekKeys[$column->week] . ' empty">';
        } else if ($column->isToday()) {
          echo '<td class="week-' . $weekKeys[$column->week] . ' today">';
          echo $column->day;
        } else {
          echo '<td class="week-' . $weekKeys[$column->week] . '">';
          echo $column->day;
        }
        echo '</td>';
      }
      echo '</tr>';
    }
    echo '</tbody>';
  }
}

function main() {
  if (isset($_REQUEST['year']) && isset($_REQUEST['month'])) {
    $calendar = new Calendar(intval($_REQUEST['year']), intval($_REQUEST['month']));
  } else {
    $calendar = Calendar::createCalendarOfToday();
  }

  $title = $calendar->getTitle();

  include 'header.tpl';

  echo $calendar->renderAsHtml();

  include 'footer.tpl';
}

main();
