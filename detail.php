<?php
require_once "inc/functions.php";

if (isset($_GET['msg'])) {
  $message = trim(filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING));
}

if (isset($_GET['id'])) {
  list($entry_id, $title, $date, $time_spent, $learned, $resources) = get_journal_entry(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
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
$page_title = $title;
include "inc/header.php";
?>

  <?php
    if(isset($message)) {
      echo "<p class='message'>$message</p>";
    }
  ?>
  <div class="entry-list single">
    <article>
      <h1><?php echo $title ?></h1>
      <div class="tag-list">
        <?php
          foreach (get_tags_for_entry($entry_id) as $tag) {
            echo "<a href='tag.php?id=" .$tag['id']. "'>" .$tag['name']. "</a>  ";
          }
        ?>
      </div>
      <br>
      <time datetime="<?php echo $date ?>"><?php echo strftime("%B %d, %Y", strtotime($date)); ?></time>
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
  <div class="edit">
    <p><a href="edit.php?id=<?php echo $entry_id?>">Edit Entry</a></p>
    <form method='post' onsubmit=\"return confirm('Are you sure?');\">
      <input type='hidden' value="<?php echo $entry_id; ?>" name='delete' />
      <input type='submit' class='button button-danger' value='Delete Entry' />
    </form>
  </div>

<?php include "inc/footer.php" ?>
