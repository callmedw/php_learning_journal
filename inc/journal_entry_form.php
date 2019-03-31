<?php require_once "inc/functions.php"; ?>

<label for="title">Title</label>
<input form="update-entry" id="title" type="text" name="title" value="<?php if(!empty($title)) echo $title; ?>"><br>

<label for="tags">Tags (seperated by commas)</label>
<?php if(!empty($entry_tags)) echo $entry_tags; ?>
<input form="update-entry" id="tags" type="text" name="tags"><br>

<label for="date">Date</label>
<input form="update-entry" id="date" type="date" name="date" value="<?php if(!empty($date)) echo $date; ?>"><br>

<label for="time-spent">Time Spent</label>
<input form="update-entry" id="time-spent" type="text" name="time-spent" value="<?php if(!empty($time_spent)) echo $time_spent; ?>"><br>

<label for="learned">What I Learned</label>
<textarea form="update-entry" id="learned" rows="5" name="learned"><?php if(!empty($learned)) echo $learned; ?></textarea>

<label for="resources">Resources to Remember</label>
<textarea form="update-entry" id="resources" rows="5" name="resources"><?php if(!empty($resources)) echo $resources; ?></textarea>

<?php
  if (!empty($entry_id)) {
    echo "<input form='update-entry' type='hidden' name='id' value='$entry_id' />";
  }
?>
