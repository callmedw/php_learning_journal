<?php
require_once "inc/functions.php";

if (isset($_GET['id'])) {
  $tag_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
}

include "inc/header.php";
?>


  <div class="entry-list">
    <h1> All entries tagged "<?php echo $tag_id; ?>": </h1>
    <br>

    <?php
      foreach (get_entries_for_tag($tag_id) as $entry) {
        echo "<h2><a href='detail.php?id=" .$entry['id']. "'>" .$entry['title']. "</a></h2></ br>";
        echo "<time datetime=".$entry['date'].">" .strftime("%B %d, %Y", strtotime($entry['date'])). "</time>";
      }
    ?>
  </div>

<?php include "inc/footer.php"; ?>
