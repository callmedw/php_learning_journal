<?php
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $entry_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
  $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
  $date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING));
  $time_spent = trim(filter_input(INPUT_POST, 'time-spent', FILTER_SANITIZE_STRING));
  $learned = trim(filter_input(INPUT_POST, 'learned', FILTER_SANITIZE_STRING));
  $resources = trim(filter_input(INPUT_POST, 'resources', FILTER_SANITIZE_STRING));

  if (empty($title) || empty($date) || empty($time_spent) || empty($learned) || empty($resources)) {
    $message = 'Please fill in all fields.';
  } else {
    if (add_journal_entry($title, $date, $time_spent, $learned, $resources, $entry_id)) {
      $message = "Success! Your entry has been added.";
      header('location:index.php');
      exit;
    } else {
      $message = 'This entry was not successfully added.';
      header('location:new.php');
    }
  }
}

include "inc/header.php"
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
    <div class="new-entry">
      <h2>New Entry</h2>
      <?php
        if(isset($message)) {
          echo "<p class='message'>$message</p>";
        }
      ?>
      <form method="post">
        <?php include "inc/journal_entry_form.php" ?>
        <input type="submit" value="Publish Entry" class="button">
        <a href="javascript:history.back()" class="button button-secondary">Cancel</a>
      </form>
    </div>
  </div>
</section>
<footer>
  <div>
    &copy; MyJournal
  </div>
</footer>

<?php include "inc/footer.php" ?>
