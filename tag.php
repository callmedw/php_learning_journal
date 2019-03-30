<?php
require_once "inc/functions.php";

if (isset($_GET['id'])) {
  list($entry_id, $title, $date, $time_spent, $learned, $resources) = get_journal_entry(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
}

include "inc/header.php";
?>

<?php include "inc/footer.php"; ?>
