<?php
include ("includes/init.php");
$current_page_id = "single_project";
include("includes/nav.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title>Single Project</title>
</head>

<body>
  <div class = "wrapper">
    <div class="content">
      <?php
      // if there are images for this project
      $image_exist = 1;
      if (!isset($_GET["proj_id"])) {
        $image_exist = 0;
        // record_message("Please choose an image to view");
      }
      else {
        // get information for this project
        $proj_id = filter_var($_GET["proj_id"], FILTER_SANITIZE_NUMBER_INT);
        $sql = "SELECT *  FROM projects WHERE projects.proj_id = :proj_id";
        $params = array(":proj_id" => $proj_id);
        $proj_info_array = exec_sql_query($db, $sql, $params) -> fetchAll();
        $proj_info = $proj_info_array[0];
        // fetch all the images for the project
        $sql = "SELECT file_name, file_ext FROM images WHERE images.proj_id = :proj_id";
        $params = array(":proj_id" => $proj_id);
        $proj_imgs = exec_sql_query($db, $sql, $params) -> fetchAll();
        // invalid image id or there is no image
        if (!$proj_imgs or count($proj_imgs) == 0) {
          $image_exist = 0;
        }
      }
      // if theere is image for the project
      if ($image_exist == 1) {
        // display the images
        echo "<div class=\"proj_img\">";
        $index = 0;
        foreach ($proj_imgs as $proj_img){
          $file_name = PATH_IMG. $proj_img["file_name"] . "." . $proj_img["file_ext"];
          echo "<div class='single_img'><img src=\"" .
          $file_name . "\" class='img_single' id = \"" . $index .  "\" alt=\"gallery\" onclick=enlarge($index)></div>";
          $index = $index + 1;
        }
      }

      // print project information
      echo "</div>";
      echo "<div class='project_info'>";
      echo "<ul><li> Project Name: " . htmlspecialchars($proj_info["project_name"]) . "</li>" .
      "<li> Address: " . htmlspecialchars($proj_info["project_address"]) . "</li>" .
      "<li> Project Status: " . htmlspecialchars($proj_info["project_status"]) . "</li>".
      "<li> Description: " . htmlspecialchars($proj_info["project_description"]) . "</li>";
      echo "</ul>";
      echo "</div>";

      ?>
      <!--The following division is to display enlarged image when clicked-->
      <div id="overlay" class="inactive">
        <span class="close"> &times;</span>
        <img class="engineering_draw" id="largeImg">
        <span class="moveRight" onclick=moveToAnother(event)> &#9654; </span>
        <span class="moveLeft" onclick=moveToAnother(event)> &#9664; </span>

    </div>
    </div>

  <?php include ("includes/footer.php") ?>
  <!--The follow script allows enlargement of a single image-->
  <script>
      // obtain the overlay division
  var div = document.getElementById("overlay");
  var img_index;

  const img_array = <?php echo json_encode(($proj_imgs)) ?>;
  // console.log(img_array);
  function enlarge(index) {
    img_index = index;

    div.className = "active";
    // the img to be enlarged and displayed
    document.getElementById("largeImg").src = document.getElementById(img_index).src;
  }

  function moveToAnother(event) {

    var change = 1;
    var class_name = event.target.className;
    // console.log(class_name);
    if (class_name == "moveLeft") {
      change = -1;
    }

    img_index = img_index + change;

    if (img_index < 0) {
      img_index = img_array.length - 1;
    }

    if (img_index >= img_array.length) {
      img_index = 0;
    }

    // console.log(img_index);
    var img_src = "/project_images/" + img_array[img_index].file_name + "." + img_array[img_index].file_ext;
    document.getElementById("largeImg").src = img_src;

    // console.log(img_src);

  }
  // the close icon is clicked
  var closeSpan = document.getElementsByClassName("close")[0];
  closeSpan.onclick = function () {
    div.className = "inactive";
  }

  </script>
</body>
</html>
