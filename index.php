<?php
include "inc/header.php";
require_once "inc/functions.php";
?>

  <?php
    if(isset($message)) {
      echo "<p class='message'>$message</p>";
    }
  ?>
  <div class="entry-list">
    <?php
      foreach (get_journal_entry_list() as $entry) {
        echo "<article>";
        echo "<h2><a href='detail.php?id=" .$entry['id']. "'>".$entry['title']."</a></h2>";
        echo "<time datetime=" .$entry['date']. ">" .strftime("%B %d, %Y", strtotime($entry['date'])). "</time>";
        echo "<p>" .substr($entry['learned'], 0, 250). "... <a href='detail.php?id=" .$entry['id']. "'>more</a></p>";
        echo "<form method='post' onsubmit=\"return confirm('Are you sure?');\">";
        echo "<input type='hidden' value='" .$entry['id']. "' name='delete' />";
        echo "<input type='submit' class='button button-danger' value='Delete' />";
        echo "</form>";
        echo "</article>";
      }
    ?>
  </div>

<?php include "inc/footer.php" ?>
