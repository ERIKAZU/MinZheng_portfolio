<?php
include("includes/init.php");
// declare the current location, utilized in header.php
$current_page_id="upload";

// declare the current location, utilized in header.php
$current_page_id="upload";

// TODO 2-3
$db = open_or_init_sqlite_db('website.sqlite', "init/init.sql");

const MAX_FILE_SIZE = 100000000;


if (isset($_POST["submit_upload"])) {


  $upload_info = $_FILES["box_file"];

  $dish = filter_input(INPUT_POST, 'dish', FILTER_SANITIZE_STRING);

  $restaurant = filter_input(INPUT_POST, 'restaurant', FILTER_SANITIZE_STRING);
  $rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_INT);
  $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT);
  $user = filter_input(INPUT_POST, 'upload_user', FILTER_SANITIZE_STRING);


  // echo $upload_info['error'];

  if ($upload_info['error'] == UPLOAD_ERR_OK) {
    // get user_id
    $sql = "SELECT id FROM accounts WHERE username = :username";
    $params = array(':username'=> $user);
    $user_id_records = exec_sql_query($db, $sql, $params)->fetchAll();
    $user_id = $user_id_records[0]["id"];

    $upload_name = basename($upload_info["name"]);
    $upload_ext = strtolower(pathinfo($upload_name, PATHINFO_EXTENSION) );
    $sql = "INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
    VALUES (:filename, :file_ext, :dish_name, :restaurant,:rating,:price, :user);";

    $params = array(
      ':filename' => $upload_name,
      ':file_ext' => $upload_ext,
      ':dish_name' => $dish,
      ':restaurant' => $restaurant,
      ':rating' => $rating,
      ':price' => $price,
      ':user' => $user_id
    );

    $result = exec_sql_query($db, $sql, $params);


    if ($result) {

      $file_id = $db->lastInsertId("id");
      if (move_uploaded_file($upload_info["tmp_name"], UPLOADS_PATH . "$file_id.$upload_ext")){
        array_push($messages, "Your file has been uploaded.");
      }
    } else {

      array_push($messages, "Failed to upload file.");
    }
  } else {

    array_push($messages, "Failed to upload file.");
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
    <title>Upload - <?php echo $title;?></title>
</head>

<body>
  <?php include("includes/header.php");?>

  <div id="content-wrap">
    <p class="margin_top_0 side_margin_10">Share your food with images and descrions</p>

    <?php
    print_messages();
    if ($current_user){
    ?>

      <fieldset class="side_margin_10">
        <legend class="font_size_20"> share your food!</legend>
          <div class="container">
      <!-- TODO 3-3 -->
      <form id="uploadFile" class="box_form" action="upload.php" method="post" enctype="multipart/form-data">
<div class="row">


            <input type="hidden" name="MAX_FILE_SIZE" value=MAX_FILE_SIZE />
            <label>Upload Image:</label>
            <input type="file" id="box_file" name="box_file" multiple required/>

  </div>
          <div class="row">
            <div class="add_margin">
              <label>Restaurant:</label>
            </div>
            <div class="add_margin">
              <input id="restaurant"  type="text" name="restaurant" placeholder="Restaurant Name" required/>
            </div>
          </div>

          <div class="row">
            <div class="add_margin">
              <label>Dish:</label>
            </div>
            <div class="add_margin">
              <input id="dish"  type="text" name="dish" placeholder="Dish Name" required/>
            </div>
          </div>


          <div class="row">
            <div class="add_margin">
              <label>Rating:</label>
            </div>
            <div class="add_margin">
              <input type="radio" name="rating" value="1"/>Must try
              <input type="radio" name="rating" value="2" checked/>Good
              <input type="radio" name="rating" value="3"/>Don't like it
            </div>
          </div>

          <div class="row">
            <div class="add_margin">
              <label>Price:</label>
            </div>
            <div class="add_margin">
              <input type="radio" name="price" value="1"/>$
              <input type="radio" name="price" value="2" checked/>$$
              <input type="radio" name="price" value="3"/>$$$
            </div>
          </div>

          <input type="hidden" name="upload_user" value="<?php echo $current_user?>">

          <div class="row add_margin">
            <input name="submit_upload" id="submit_upload" type="submit" value="Submit" class="span">
          </div>

      </form>
    </div>
    </fieldset>
    </div>




  <?php
}
else{
  ?>
    <p class="margin_top_0">Please log in to upload images.</p>
<?php
}
include("includes/footer.php");?>
</body>
</html>
