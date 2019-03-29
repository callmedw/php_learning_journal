<?php

//      journal CRUD      //

// add/update journal entry
function add_journal_entry($title, $date, $time_spent, $learned, $resources, $entry_id = null){
  include 'connection.php';

  if ($entry_id) {
    $sql =  'UPDATE entries SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ? WHERE entry_id = ?';
  } else {
    $sql = 'INSERT INTO entries(title, date, time_spent, learned, resources) VALUES(?, ?, ?, ?, ?)';
  }

  try {
    $results = $db->prepare($sql);
    $results->bindValue(1, $title, PDO::PARAM_STR);
    $results->bindValue(2, $date, PDO::PARAM_STR);
    $results->bindValue(3, $time_spent, PDO::PARAM_STR);
    $results->bindValue(4, $learned, PDO::PARAM_STR);
    $results->bindValue(5, $resources, PDO::PARAM_STR);
    if ($entry_id) {
      $results->bindValue(6, $entry_id, PDO::PARAM_INT);
    }
    $results->execute();
  } catch (Exception $e) {
    echo $e->getMessage();
    return false;
  }
  return true;
}
