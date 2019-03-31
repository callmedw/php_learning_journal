<?php
/////////////////////////////
//      journal CRUD      //
////////////////////////////

// add/update journal entry
function add_journal_entry($title, $date, $time_spent, $learned, $resources, $tags, $entry_id = NULL) {
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
  if ($db->lastInsertId()) {
    $entry_id = $db->lastInsertId();
  }
  if (!empty($tags)) {
    try {
      add_tags($tags);
      $tag_array = get_tag_ids($tags);
      populate_entry_tags_table($tag_array, $entry_id);
    } catch (Exception $e) {
      echo $e->getMessage();
      return false;
    }
    return true;
  }
}

// get (read) all journal entries //
function get_journal_entry_list() {
  include 'connection.php';

  try {
    return $db->query('SELECT * FROM entries ORDER BY date DESC');
  } catch (Exception $e) {
    echo $e->getMessage();
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

// add entry tags //
function add_tags($tags) {
  include 'connection.php';

  $tag_array =  explode(',', $tags);
  $tag_ids = [];
  $sql = "INSERT INTO tags (name) VALUES (?)";

  try {
    $db->beginTransaction();
    foreach ($tag_array as $tag) {
      $results = $db->prepare($sql);
      $results->bindValue(1, trim($tag), PDO::PARAM_STR);
      try {
        $results->execute();
        $tag_ids[] = $db->lastInsertId();
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
    $db->commit();
  } catch (Exception $e) {
    $db->rollback();
    echo $e->getMessage();
    return false;
  }
  return true;
}

// return an entry's tag ids //
function get_tag_ids($tags) {
  include 'connection.php';

  $tag_array =  explode(',', $tags);
  $tag_ids = [];
  $sql = "SELECT id FROM tags WHERE name LIKE LOWER(?)";

  try {
    $db->beginTransaction();
    $results = $db->prepare($sql);
    foreach ($tag_array as $tag) {
      $results->bindValue(1, trim($tag), PDO::PARAM_STR);
      if ($results->execute()) {
        $tag_ids[] = $results->fetch();
      }
    }
    $db->commit();
  } catch (Exception $e) {
    $db->rollback();
    echo $e->getMessage();
    return false;
  }
  return $tag_ids;
}

// associate the tags with the entry
// got the $sql from https://stackoverflow.com/questions/19337029/insert-if-not-exists-statement-in-sqlite
// combined with the CRUD class.
function populate_entry_tags_table($tag_array, $entry_id) {
  include 'connection.php';

  $sql = "INSERT INTO entry_tags (entry_id, tag_id)
          SELECT ?, ?
          WHERE NOT EXISTS (
            SELECT 1
            FROM entry_tags
            WHERE entry_id = ?
            AND tag_id = ?
          )";

  try {
    $db->beginTransaction();
    $results = $db->prepare($sql);
    foreach ($tag_array as $tag_id) {
      $results->bindValue(1, $entry_id, PDO::PARAM_INT);
      $results->bindValue(2, $tag_id['id'], PDO::PARAM_INT);
      $results->bindValue(3, $entry_id, PDO::PARAM_INT);
      $results->bindValue(4, $tag_id['id'], PDO::PARAM_INT);
      $results->execute();
    }
    $db->commit();
  } catch (Exception $e) {
    $db->rollback();
    echo $e->getMessage();
    return false;
  }
  return true;
}

// get (read) a single tag //
function get_tag($tag_id) {
  include 'connection.php';

  $sql = 'SELECT * FROM tags WHERE tags.id = ?';

  try {
    $results = $db->prepare($sql);
    $results->bindValue(1, $tag_id, PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e) {
    echo $e->getMessage();
    return false;
  }
  return $results->fetch();
}

// get (read) all entry tags //
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

// display tags as a string for the entry update form  //
// this includes delete buttons to delete individual tags
function form_display_entry_tags($entry_id) {
  $tags = get_tags_for_entry($entry_id);
  $entry_tags = " ";
  foreach ($tags as $tag) {
    $tag_id = $tag['id'];
    $tag_name = $tag['name'];
    $entry_tags.=  "<input form='remove-tag' type='submit' value='$tag_name:$tag_id X' />";
    $entry_tags.=  "<input form='remove-tag' type='hidden' value='$entry_id' name='remove_tag_entry_id' />";
    $entry_tags.=  "<input form='remove-tag' type='hidden' value='$tag_id' name='remove_tag_tag_id' />";
    $entry_tags.= " ";
  }
  return $entry_tags;
}

// remove tag from journal entry //
function delete_tag($entry_id, $tag_id) {
  error_log(print_r($entry_id, true));
  error_log(print_r($tag_id, true));
  include 'connection.php';

  $sql = 'DELETE FROM entry_tags WHERE ( tag_id = ? AND entry_id = ? )';
  try {
    $results = $db->prepare($sql);
    $results->bindValue(1, $tag_id, PDO::PARAM_INT);
    $results->bindValue(2, $entry_id, PDO::PARAM_INT);
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

// get all tags //
function get_tag_list() {
  include 'connection.php';

  try {
    return $db->query('SELECT * FROM tags');
  } catch (Exception $e) {
    echo $e->getMessage();
    return array();
  }
}
