<form method="post" action="new.php">
  <label for="title"> Title</label>
  <input id="title" type="text" name="title"><br>
  <label for="date">Date</label>
  <input id="date" type="date" name="date"><br>
  <label for="time-spent"> Time Spent</label>
  <input id="time-spent" type="text" name="time-spent"><br>
  <label for="learned">What I Learned</label>
  <textarea id="learned" rows="5" name="learned"></textarea>
  <label for="resources">Resources to Remember</label>
  <textarea id="resources" rows="5" name="resources"></textarea>
  <?php
    if (!empty($entry_id)) {
      echo "<input type='hidden' name='id' value='$entry_id' />";
    }
  ?>
  <input type="submit" value="Publish Entry" class="button">
  <a href="javascript:history.back()" class="button button-secondary">Cancel</a>
</form>
