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
    .size-2 { font-size: 120%; }
    .size-3 { font-size: 140%; }
    .size-4 { font-size: 160%; }
    a:link { color: #ff8050; }
    a:visited { color: #995080; }
    a:hover {
      color: #ff0090;
      text-decoration: none;
    }
  </style>
</head>
<body>
