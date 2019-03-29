<?php
include "inc/functions.php";

if (isset($_GET['id'])) {
  list($entry_id, $title, $date, $time_spent, $learned, $resources) = get_journal_entry(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
}

include "inc/header.php";
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
    <div class="entry-list single">
      <article>
        <h1><?php echo $title ?></h1>
        <time datetime="<?php echo $date ?>"><?php echo $date ?></time>
        <div class="entry">
          <h3>Time Spent: </h3>
          <p><?php echo $time_spent ?></p>
        </div>
        <div class="entry">
          <h3>What I Learned:</h3>
          <p><?php echo $learned ?></p>
        </div>
        <div class="entry">
          <h3>Resources to Remember:</h3>
          <p><?php echo $resources ?></p>
        </div>
      </article>
    </div>
  </div>
  <div class="edit">
    <p><a href="edit.php">Edit Entry</a></p>
  </div>
</section>
<footer>
  <div>
    &copy; MyJournal
  </div>
</footer>

<?php include "inc/footer.php" ?>
