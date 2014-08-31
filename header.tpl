<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>カレンダー - <?= $title ?></title>
  <style>
    th {
      border: solid 1px #999999;
      width: 3em;
    }
    th.week-sun { color: #ff3333; }
    th.week-sat { color: #3333ff; }
    td {
      border: solid 1px #999999;
      width: 3em;
      text-align: center;
    }
    td.week-sun { background: #ffcccc; }
    td.week-sat { background: #ccccff; }
    td.today {
      background: #222222;
      color: #ffffff;
    }
  </style>
</head>
<body>
