<?php
include("includes/init.php");
// declare the current location, utilized in header.php
$current_page_id="showOne";

if (isset($_POST['logout'])) {
  log_out();
  if (!$current_user) {
    record_message("You've been successfully logged out.");
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <title>showOne - <?php echo $title;?></title>
</head>

<body>
  <?php
  include("includes/header.php");
  $cur_id = $_GET['id'];

  if (isset($_POST['delete_photo'])){
    // fetch the cur_img path for disk deletion
    $sql = "SELECT * FROM photos WHERE id = :cur_id";
    $params = array(':cur_id' => $cur_id);
    $cur_imgs = exec_sql_query($db, $sql, $params)->fetchAll();
    $cur_img = $cur_imgs[0];// because code starts from 0

    // delete from photos
    $sql = "DELETE FROM photos
    WHERE id = :cur_id;";
    $params = array(':cur_id' => $cur_id);
    exec_sql_query($db, $sql, $params);

    //delete from photo_x_tags
    $sql = "DELETE FROM photo_x_tags
    WHERE photoID = :cur_id;";
    exec_sql_query($db, $sql, $params);

    //delete from disk
    $filename = UPLOADS_PATH.$cur_img["id"].".".$cur_img["file_ext"];
    unlink($filename);

    record_message("The photo is deleted.");
    print_messages();

  }
  else{
  if (isset($_POST['submit_enter_tag'])){
    $add_tag_name = filter_input(INPUT_POST, 'new_tag', FILTER_SANITIZE_STRING);
    $sql = "SELECT COUNT(*) as NUM FROM tags
    WHERE tagName = :tag_name;";
    $params = array(':tag_name' => $add_tag_name);
    $record = exec_sql_query($db, $sql, $params)->fetchAll();



    if ($record[0]["NUM"]!= 0){
      record_message("The tag already exists.");
      print_messages();
    }
    else{
      // add tag into tags table
      $sql = "INSERT INTO tags (tagName)
      VALUES (:tag_name);";
      $params = array(':tag_name' => $add_tag_name);
      exec_sql_query($db, $sql, $params);
      // add relation to photo_x_tags
      $sql = "INSERT INTO photo_x_Tags (photoID,tagName)
          VALUES (:cur_photo_id,:tag_name);";
      $params = array(':tag_name' => $add_tag_name,
      ':cur_photo_id' => $cur_id,
      );
      exec_sql_query($db, $sql, $params);

    }
    // $header = "All photos with tag: " . $tag_name;
  }



    $sql = "SELECT * FROM photos WHERE id = :cur_id";
    $params = array(':cur_id' => $cur_id);
    $cur_imgs = exec_sql_query($db, $sql, $params)->fetchAll();
    $cur_img = $cur_imgs[0];// because code starts from 0
    $img_path = UPLOADS_PATH.$cur_img["id"].".".$cur_img["file_ext"];
    echo "<div><img class=\"oneImg\" src=".$img_path." alt=\"food_image\"></div>";
    echo "<p class=\"center\">- photo by: Google Images</p>";
    echo "<fieldset class=\"side_margin\">
      <legend class=\"font_size_20\">photo information</legend>";
    echo "<p class=\"center\">".$cur_img["dish_name"]." at ".$cur_img["restaurant"]."</p>";
    //print price
    echo "<p class=\"center\">price: ";
    for ($i=1; $i <= $cur_img["price"];$i++){
      echo "$";
    }
    echo "  Rating: ";
    for ($i=1; $i <= 3;$i++){
      if ($i <= $cur_img["rating"]){
        echo "★";
      }
      else {
        echo  "☆";
      }
    }
    echo "</p>";

    // user control for delete photo
    $sql = "SELECT username FROM accounts
    INNER JOIN photos
    on accounts.id = photos.user_uploaded
    WHERE photos.id=:cur_photo;";
    $params = array(':cur_photo' => $cur_id);
    $upload_user_record = exec_sql_query($db, $sql,$params)->fetchAll();
    $upload_user_name = $upload_user_record[0]["username"];

    if ($upload_user_name == $current_user){
      echo "<form id=\"delete_photo\" class = \"box_form center\" action=\"#\" method = \"POST\">";
        echo "<button class=\"center\" type=\"submit\" name=\"delete_photo\" value=\"delete_photo\">delete photo</button>";
        echo "</form>";
    }
    echo"</fieldset>";


    if (isset($_POST['add_tag_1'])){
      $add_tag_name = filter_input(INPUT_POST, 'add_tag_1', FILTER_SANITIZE_STRING);

      $sql = "INSERT INTO photo_x_Tags (photoID,tagName)
          VALUES (:cur_photo_id,:tag_name);";
      $params = array(':tag_name' => $add_tag_name,
      ':cur_photo_id' => $cur_id,
      );

      exec_sql_query($db, $sql, $params);
      // $header = "All photos with tag: " . $tag_name;
    }


  if (isset($_POST['deleted_tag'])){
    $del_tag_name = filter_input(INPUT_POST, 'deleted_tag', FILTER_SANITIZE_STRING);
    $sql = "DELETE FROM photo_x_tags
    WHERE photo_x_tags.tagName = :del_tag_name
    and photo_x_tags.photoID = :del_photo_id";
    $params = array(':del_tag_name' => $del_tag_name,
    ':del_photo_id' => $cur_id,
    );
    exec_sql_query($db, $sql, $params);
    // $header = "All photos with tag: " . $tag_name;
  }
    $sql = "SELECT tags.tagName FROM tags
    INNER JOIN photo_x_tags
    ON tags.tagName = photo_x_tags.tagName
    WHERE photo_x_tags.photoID = :cur_id";
    // echo $cur_id;
    $params = array(':cur_id' => $cur_id);

    $cur_tags = exec_sql_query($db, $sql, $params)->fetchAll();

    if (sizeof($cur_tags)>0){
      echo "<fieldset class=\"side_margin\">
        <legend class=\"font_size_20\">Tags of this photo: </legend>";

      // echo "<p class=\"center\">All the tags of the current photo:</p>";
      //echo sizeof($cur_tags);
      echo "<form id=\"delete_tag\" class = \"box_form center\" action=\"#\" method = \"POST\">
     <ul class=\"no_bullet\">";

        foreach($cur_tags as $tag) {
          if ($current_user){
            echo "<li>".$tag["tagName"]."<button type=\"submit\" name=\"deleted_tag\" value=".$tag["tagName"].">delete</button></li>";
          }
          else {
            echo "<li>".$tag["tagName"]."</li>";
          }
        }
        echo "</ul></form></fieldset>";
    }



?>
<fieldset class="side_margin">
  <legend class="font_size_20">Add tags: </legend>
      <!-- <p>Add existing tag:</p> -->
      <form id="add_tag_1" class ="box_form center" action="#" method = "POST">
        <div class="row">
          <div class="add_margin">
            <label>Select existing tag:</label>
      <select name="add_tag_1">
        <option value="" selected disabled>Choose Tag</option>
        <?php
        $sql = "SELECT  tag_id,tags.tagName FROM tags
          except
          SELECT  tag_id,tags.tagName FROM tags
          LEFT JOIN photo_x_tags
          ON tags.tagName = photo_x_tags.tagName
          WHERE photo_x_tags.photoID = :cur_id;";

        $params = array(':cur_id' => $cur_id);

        $tags = exec_sql_query($db, $sql, $params)->fetchAll();

        foreach($tags as $tag) {
          echo "<option value=\"" . $tag["tagName"] . "\">" . $tag["tagName"] . "</option>";
        }
        ?>


      </select>

      </div>
    <button type="submit" name="submit_add_tag">Add</button></div>

  </form>


    <form id="add_tag_2" class ="box_form center" action="#" method = "POST">
      <div class="row">
        <div class="add_margin center">
          <label for="new_tag">Enter new tag:</label>
        </div>
        <div class="add_margin center">
          <input id="new_tag"  type="text" name="new_tag" placeholder="New Tag" />
          <button type="submit" name="submit_enter_tag">Add</button>
        </div>
      </div>



  </form></fieldset>


<?php }// for delete photo
include("includes/footer.php");?>
</body>
</html>
