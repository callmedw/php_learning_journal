<?php
/////////////////////////////
//      journal CRUD      //
////////////////////////////

// add/update journal entry
function add_journal_entry($title, $date, $time_spent, $learned, $resources, $entry_id = NULL){
  include 'connection.php';

  if ($entry_id) {
    $sql =  'UPDATE entries SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ? WHERE id = ?';
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

// get (read) all journal entries //
function get_journal_entry_list() {
  include 'connection.php';

  try {
    return $db->query('SELECT * FROM entries ORDER BY date DESC');
  } catch (Exception $e) {
    echo "Error!: " . $e->getMessage() . "<br />";
    return array();
  }
}

// get (read) journal entry //
function get_journal_entry($entry_id){
  include 'connection.php';

  $sql = 'SELECT * FROM entries WHERE id = ?';

  try {
    $results = $db->prepare($sql);
    $results->bindValue(1, $entry_id, PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e) {
    echo $e->getMessage();
    return false;
  }
  return $results->fetch();
}

// delete journal entry //
function delete_journal_entry($entry_id){
  include 'connection.php';

  $sql = 'DELETE FROM entries WHERE id = ?';

  try {
    $results = $db->prepare($sql);
    $results->bindValue(1, $entry_id, PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e) {
    echo $e->getMessage();
    return false;
  }
  if ($results->rowCount() > 0 ) {
    return true;
  } else {
    return false;
  }
}

/////////////////////////////
//      tag CRUD      //
////////////////////////////

// get (read) entry tags //
function get_tags_for_entry($entry_id) {
  include 'connection.php';

  $sql = 'SELECT tags.name, tags.id
          FROM tags
          JOIN entry_tags ON tags.id = entry_tags.tag_id
          WHERE entry_tags.entry_id = ?';

  try {
    $results = $db->prepare($sql);
    $results->bindValue(1, $entry_id, PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e) {
    echo $e->getMessage();
    return false;
  }
  return $results;
}

// get (read) all entries for tag //
function get_entries_for_tag($tag_id) {
  include 'connection.php';

  $sql = 'SELECT entries.title, entries.id, entries.date
          FROM entries
          JOIN entry_tags ON entries.id = entry_tags.entry_id
          WHERE entry_tags.tag_id = ?
          ORDER BY entries.date DESC';

  try {
    $results = $db->prepare($sql);
    $results->bindValue(1, $tag_id, PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e) {
    echo $e->getMessage();
    return false;
  }
  return $results;
}
