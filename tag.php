<?php
require_once "inc/functions.php";

if (isset($_GET['id'])) {
  $tag_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  $tag_name = get_tag($tag_id)['name'];
}



$page_title = $tag_name;
include "inc/header.php";
?>

  <div class="entry-list">
    <h1 class="tag-header"> All entries tagged "<?php echo $tag_name; ?>": </h1>

    <?php
      foreach (get_entries_for_tag($tag_id) as $entry) {
        echo "<h2><a href='detail.php?id=" .$entry['id']. "'>" .$entry['title']. "</a></h2></ br>";
        echo "<time datetime=".$entry['date'].">" .strftime("%B %d, %Y", strtotime($entry['date'])). "</time>";
      }
    ?>
  </div>

<?php include "inc/footer.php"; ?>
