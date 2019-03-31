
<label for="tags">Tags (seperated by commas)</label>
<input id="tags" type="text" name="tags" value="<?php if(!empty($entry_tags)) echo $entry_tags; ?>"><br>

<label for="title">Title</label>
<input id="title" type="text" name="title" value="<?php if(!empty($title)) echo $title; ?>"><br>

<label for="tags">Tags (seperated by commas)</label>
<input id="tags" type="text" name="tags" value="<?php if(!empty($entry_tags)) echo $entry_tags; ?>"><br>


<label for="date">Date</label>
<input id="date" type="date" name="date" value="<?php if(!empty($date)) echo $date; ?>"><br>

<label for="time-spent">Time Spent</label>
<input id="time-spent" type="text" name="time-spent" value="<?php if(!empty($time_spent)) echo $time_spent; ?>"><br>

<label for="learned">What I Learned</label>
<textarea id="learned" rows="5" name="learned"><?php if(!empty($learned)) echo $learned; ?></textarea>

<label for="resources">Resources to Remember</label>
<textarea id="resources" rows="5" name="resources"><?php if(!empty($resources)) echo $resources; ?></textarea>

<?php
  if (!empty($entry_id)) {
    echo "<input type='hidden' name='id' value='$entry_id' />";
  }
?>

// edit pages
$entry_tags = form_display_entry_tags($entry_id);
include "inc/header.php"
?>

  <div class="edit-entry">
    <h2>Edit Entry</h2>
    <?php
      if(isset($message)) {
        echo "<p class='message'>$message</p>";
      }
    ?>
    <form method="post" action="edit.php?id=<?php echo $entry_id; ?>">
      <?php include "inc/journal_entry_form.php" ?>
      <input type="submit" value="Publish Entry" class="button">
      <a href="javascript:history.back()" class="button button-secondary">Cancel</a>
    </form>
  </div>

<?php include "inc/footer.php" ?>


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
  manage_tags($entry_id, $tags);
  return true;
}

function manage_tags($entry_id, $tags) {
  $entry_tags = get_tags_for_entry($entry_id);

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

// insert the journal entry.
// on success of journal entry I want to grab the id.
// then i want to check distinct of tag...if new insert tag and grab id.

// if exists...don't insert and grab existing id.
// then take the entry id and the tag id and populate the join table with both.

// get last inserted id //
// $db->lastInsertId();

//open close transaction
// try {
//     $sql = "INSERT INTO users (name) VALUES (?)";
//     $db->beginTransaction();
//     $stmt = $db->prepare($sql);
//     foreach (['Joe','Ben'] as $name)
//     {
//         $stmt->execute([$name]);
//     }
//     $db->commit();
// }catch (Exception $e){
//     $db->rollback();
//     throw $e->getMessage();
// }

//multiple statements

// $stmt = $pdo->prepare("SELECT ?;SELECT ?");
// $stmt->execute([1,2]);
// do {
//     $data = $stmt->fetchAll();
//     var_dump($data);
// } while ($stmt->nextRowset());


// add/update tag
// function add_tag($name, $entry_id, $tag_id = NULL){
//   include 'connection.php';
//
//   if ($tag_id) {
//     $sql =  'UPDATE tags SET name = ? WHERE id = ?';
//   } else {
//     $sql = 'INSERT INTO tags(name) VALUES(?)';
//   }
//
//   try {
//     $results = $db->prepare($sql);
//     $results->bindValue(1, $name, PDO::PARAM_STR);
//     if ($tag_id) {
//       $results->bindValue(2, $tag_id, PDO::PARAM_INT);
//     }
//     $results->execute();
//   } catch (Exception $e) {
//     echo $e->getMessage();
//     return false;
//   }
//   var_dump($results);
//   return true;
// }

// get (read) all entry tags //
// function get_all_tags_list() {
//   include 'connection.php';
//
//   try {
//     return $db->query('SELECT * FROM tags');
//   } catch (Exception $e) {
//     echo "Error!: " . $e->getMessage() . "<br />";
//     return array();
//   }
// }

// // delete tag //
// function delete_tag($tag_id){
//   include 'connection.php';
//
//   $sql = 'DELETE FROM tags WHERE id = ?';
//
//   try {
//     $results = $db->prepare($sql);
//     $results->bindValue(1, $tag_id, PDO::PARAM_INT);
//     $results->execute();
//   } catch (Exception $e) {
//     echo $e->getMessage();
//     return false;
//   }
//   if ($results->rowCount() > 0 ) {
//     return true;
//   } else {
//     return false;
//   }
// }



error_log(print_r($tag_ids, TRUE));
if (count($tag_ids) < count($tag_array)) {
  $sql = "SELECT name, id FROM tags WHERE name LIKE ?";
  $results = $db->prepare($sql);
  foreach ($tag_array as $tag) {
    $results->bindValue(1, trim($tag), PDO::PARAM_STR);
    if ($results->execute()) {
      $tag_ids[] = $db->lastInsertId();
    }
  }
  error_log(print_r($tag_ids, TRUE));
  return $tag_ids;
}


if (!empty($tag_ids)) {
  foreach ($tag_ids as $tag_id) {
    $sql = "INSERT INTO entry_tags (tag_id, entry_id) VALUES (?, ?)";
    $results->bindValue(1, $tag_id, PDO::PARAM_INT);
    $results->bindValue(2, $entry_id, PDO::PARAM_INT);
  }
}

//open close transaction
// try {
//     $sql = "INSERT INTO users (name) VALUES (?)";
//     $db->beginTransaction();
//     $stmt = $db->prepare($sql);
//     foreach (['Joe','Ben'] as $name)
//     {
//         $stmt->execute([$name]);
//     }
//     $db->commit();
// }catch (Exception $e){
//     $db->rollback();
//     throw $e->getMessage();
// }


// include 'connection.php';
//
// if ($entry_id) {
//   $sql =  'UPDATE entries SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ? WHERE id = ?';
// } else {
//   $sql = 'INSERT INTO entries(title, date, time_spent, learned, resources) VALUES(?, ?, ?, ?, ?)';
// }
//
// try {
//   $results = $db->prepare($sql);
//   $results->bindValue(1, $title, PDO::PARAM_STR);
//   $results->bindValue(2, $date, PDO::PARAM_STR);
//   $results->bindValue(3, $time_spent, PDO::PARAM_STR);
//   $results->bindValue(4, $learned, PDO::PARAM_STR);
//   $results->bindValue(5, $resources, PDO::PARAM_STR);
//   if ($entry_id) {
//     $results->bindValue(6, $entry_id, PDO::PARAM_INT);
//   }
//   $results->execute();
// } catch (Exception $e) {
//   echo $e->getMessage();
//   return false;
// }
// add_tag($tags);
// return true;
// }
