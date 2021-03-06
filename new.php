<?php
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $entry_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
  $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
  $tags = trim(filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING));
  $date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING));
  $time_spent = trim(filter_input(INPUT_POST, 'time-spent', FILTER_SANITIZE_STRING));
  $learned = trim(filter_input(INPUT_POST, 'learned', FILTER_SANITIZE_STRING));
  $resources = trim(filter_input(INPUT_POST, 'resources', FILTER_SANITIZE_STRING));

  if (empty($title) || empty($date) || empty($time_spent) || empty($learned) || empty($resources) || empty($tags)) {
    $message = 'Please fill in all fields.';
  } else {
    if (add_journal_entry($title, $date, $time_spent, $learned, $resources, $tags, $entry_id)) {
      $message = "Success! Your entry has been added.";
      header('location:index.php');
      exit;
    } else {
      $message = 'This entry was not successfully added.';
      header('location:new.php');
    }
  }
}

$page_title = "Add a New Entry";
include "inc/header.php"
?>

  <div class="new-entry">
    <h2>New Entry</h2>
    <?php
      if(isset($message)) {
        echo "<p class='message'>$message</p>";
      }
    ?>
    <form id="entry-form" method="post">
      <?php include "inc/journal_entry_form.php" ?>
      <input form="entry-form" type="submit" value="Publish Entry" class="button">
      <a href="javascript:history.back()" class="button button-secondary">Cancel</a>
    </form>
  </div>

<?php include "inc/footer.php" ?>
