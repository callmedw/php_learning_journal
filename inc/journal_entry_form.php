<?php
if (isset($_GET['id'])) {
  list($entry_id, $title, $date, $time_spent, $learned, $resources) = get_journal_entry(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
}
?>

<form method="post">
  <label for="title"> Title</label>
  <input id="title" type="text" name="title" placeholder="<?php if(isset($title)) echo $title; ?>"><br>

  <label for="date">Date</label>
  <input id="date" type="date" name="date" placeholder="<?php if(isset($date)) echo $date; ?>"><br>

  <label for="time-spent"> Time Spent</label>
  <input id="time-spent" type="text" name="time-spent" placeholder="<?php if(isset($time_spent)) echo $time_spent; ?>"><br>

  <label for="learned">What I Learned</label>
  <textarea id="learned" rows="5" name="learned" placeholder="<?php if(isset($learned)) echo $learned; ?>"></textarea>

  <label for="resources">Resources to Remember</label>
  <textarea id="resources" rows="5" name="resources" placeholder="<?php if(isset($resources)) echo $resources; ?>"></textarea>

  <?php
    if (!empty($entry_id)) {
      echo "<input type='hidden' name='id' value='$entry_id' />";
    }
  ?>
  <input type="submit" value="Publish Entry" class="button">
  <a href="javascript:history.back()" class="button button-secondary">Cancel</a>
</form>
