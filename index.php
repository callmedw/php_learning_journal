<?php
include "inc/header.php";
require_once "inc/functions.php";
?>

<header>
  <div class="container">
    <div class="site-header">
      <a class="logo" href="index.php"><i class="material-icons">library_books</i></a>
      <a class="button icon-right" href="new.php"><span>New Entry</span> <i class="material-icons">add</i></a>
    </div>
  </div>
</header>
<section>
  <div class="container">
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
  </div>
</section>
<footer>
  <div>
    &copy; MyJournal
  </div>
</footer>

<?php include "inc/footer.php" ?>

<form method='post' onsubmit=\"return confirm('Are you sure?');\">
  <input type='hidden' value="<?php echo $entry_id; ?>" name='delete' />
  <input type='submit' class='button button-danger' value='Delete Entry' />
</form>
