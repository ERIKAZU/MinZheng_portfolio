<?php
include("includes/init.php");
// declare the current location, utilized in header.php
$current_page_id="viewPhoto";

if (isset($_GET['tag'])) {
  $tag_name = filter_input(INPUT_GET, 'tag', FILTER_SANITIZE_STRING);
  $sql =  "SELECT * FROM photos
  INNER JOIN photo_x_tags
  ON photos.id = photo_x_tags.photoID
  WHERE photo_x_tags.tagName = :tag_name";
  $params = array(':tag_name' => $tag_name);
  $header = "All photos with tag: " . $tag_name;
} else {
  // No search provided, so set the product to query to NULL
  $sql =  "SELECT * FROM photos";
  $params = [];
  $header = "All photos";
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
  <title>View Photos - <?php echo $title;?></title>


  <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>

</head>

<body>
  <?php include("includes/header.php");
      echo '<h2 class="no_vert_padding">'. $header . '</h2>';
  ?>



<p class="side_margin_10">Filter by tag:</p>
<form id="select_tag" class = "box_form" action="#" method = "GET">
  <ul class="padding_20">

    <li><select name="tag">


    <?php

      $tags = exec_sql_query($db, "SELECT * FROM tags;")->fetchAll();
        foreach($tags as $tag) {

              echo "<option value=\"". $tag["tagName"] ."\">" . $tag["tagName"] . "</option>";
        }
        echo "</select></li>";
    ?>


    <li><button type="submit">Go</button></li>

</ul>
</form>


<div class="img_row side_margin_10" id="img_row">

<?php

    const COLUMNS = 4;
    // const UPLOADS_PATH = "uploads/documents/";
    $records = exec_sql_query($db, $sql,$params)->fetchAll();
    if($records){
      $totNum = count($records);
      $imgs_per_col = ceil($totNum/COLUMNS);
      $img_count= 0;

// for each column
      for ($i = 0; $i < COLUMNS; $i++){
          echo "<div class=\"img_column\">";
          // append images
          for ($j = 0; $j < $imgs_per_col and $img_count < $totNum; $j++){
            $record = $records[$img_count];
            $img_path = UPLOADS_PATH.$record["id"].".".$record["file_ext"];
            //echo $record["id"];
            echo "<div class=\"each_img\"><a href=\"showOne.php?id=".$record["id"]."\">
            <img src=".$img_path." alt=\"food_image\"></a></div>";
            $img_count++;
          }
          echo "</div>";
    }
  }
    ?>
    </div>

    <?php include("includes/footer.php");?>
</body>
</html>
