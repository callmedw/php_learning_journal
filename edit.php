<?php
require_once 'inc/functions.php';

if (isset($_GET['id'])) {
  list($entry_id, $title, $date, $time_spent, $learned, $resources) = get_journal_entry(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));

  $page_title = "Editing ... " .$title;
}

if (isset($_POST['remove_tag_tag_id']) && isset($_POST['remove_tag_entry_id'])) {
  $tag_info = filter_input(INPUT_POST, 'remove_tag_tag_id', FILTER_SANITIZE_NUMBER_INT);
  $entry_info = filter_input(INPUT_POST, 'remove_tag_entry_id', FILTER_SANITIZE_NUMBER_INT);
  if (delete_tag($entry_info, $tag_info)) {
    $message = "Tag Removed!";
    header("Refresh:0");
    exit;
  } else {
    $message = "Tag could not be deleted!";
    header("Refresh:0");
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $entry_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
  $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
  $date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING));
  $time_spent = trim(filter_input(INPUT_POST, 'time-spent', FILTER_SANITIZE_STRING));
  $learned = trim(filter_input(INPUT_POST, 'learned', FILTER_SANITIZE_STRING));
  $resources = trim(filter_input(INPUT_POST, 'resources', FILTER_SANITIZE_STRING));
  $tags = trim(filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING));

  if (empty($title) || empty($date) || empty($time_spent) || empty($learned) || empty($resources)) {
    $message = 'Please fill in all fields.';
  } else {
    if (add_journal_entry($title, $date, $time_spent, $learned, $resources, $tags, $entry_id)) {
      $message = "Success! Entry Updated.";
      header("location:detail.php?id=$entry_id");
      exit;
    } else {
      $message = 'Whoops! Something went wrong and this entry did not update.';
    }
  }
}

$entry_tags = form_display_entry_tags($entry_id);
include "inc/header.php"
?>

  <div class="edit-entry">
    <h2>Edit Entry</h2>
    <?php
      if(isset($message)) {
        echo "<p class='message'>$message</p>";
      }
    ?>
    <form id="update-entry" method="post" action="edit.php?id=<?php echo $entry_id; ?>"></form>
    <form id="remove-tag" method='post' onsubmit="return confirm('Are you sure?')"></form>
    <form>
      <?php include "inc/journal_entry_form.php" ?>
      <input form="update-entry" type="submit" value="Publish Entry" class="button">
      <a href="javascript:history.back()" class="button button-secondary">Cancel</a>
    </form>
  </div>

<?php include "inc/footer.php" ?>
