
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
