<?php
require_once "inc/functions.php";

if (isset($_GET['msg'])) {
  $message = trim(filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING));
}

if (isset($_POST['delete'])) {
  if (delete_journal_entry(filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_NUMBER_INT))) {
    $message = "Entry Deleted!";
    header("Location: index.php?msg=Entry+Deleted");
    exit;
  } else {
    $message = "Entry could not be deleted!";
    header("Location: detail.php?id=$entry_id&msg=Entry+Deletion+Unsuccesful");
    exit;
  }
}

$page_title = "My Learning Journal";
include "inc/header.php";
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
        echo "<p class='tag-list'>";
          foreach (get_tags_for_entry($entry['id']) as $tag) {
            echo "<a href='tag.php?id=" .$tag['id']. "'>" .$tag['name']. "</a> ";
          }
        echo "</p>";
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
