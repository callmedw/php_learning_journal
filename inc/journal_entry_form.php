<?php
require_once "inc/functions.php";
?>

  <label for="title"> Title</label>
  <input id="title" type="text" name="title" value="<?php if(!empty($title)) echo $title; ?>"><br>

  <label for="date">Date</label>
  <input id="date" type="date" name="date" value="<?php if(!empty($date)) echo $date; ?>"><br>

  <label for="time-spent"> Time Spent</label>
  <input id="time-spent" type="text" name="time-spent" value="<?php if(!empty($time_spent)) echo $time_spent; ?>"><br>

  <label for="learned">What I Learned</label>
  <textarea id="learned" rows="5" name="learned" value="<?php if(!empty($learned)) echo $learned; ?>"></textarea>

  <label for="resources">Resources to Remember</label>
  <textarea id="resources" rows="5" name="resources" value="<?php if(!empty($resources)) echo $resources; ?>"></textarea>

  <?php
    if (!empty($entry_id)) {
      echo "<input type='hidden' name='id' value='$entry_id' />";
    }
  ?>
