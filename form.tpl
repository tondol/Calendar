<h2><?= htmlspecialchars($year, ENT_QUOTES) ?>年<?= htmlspecialchars($month, ENT_QUOTES) ?>月<?= htmlspecialchars($day, ENT_QUOTES) ?>日の予定を作成
<form method="post" action="insertInto.php">
  <input type="hidden" name="year" value="<?= htmlspecialchars($year, ENT_QUOTES) ?>" />
  <input type="hidden" name="month" value="<?= htmlspecialchars($month, ENT_QUOTES) ?>" />
  <input type="hidden" name="day" value="<?= htmlspecialchars($day, ENT_QUOTES) ?>" />
  <input type="text" name="body" />
  <input type="submit" value="作成" />
</form>
