<?php
include("includes/init.php");
// declare the current location, utilized in header.php
$current_page_id="backyard";
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" type="text/css" href="style/all.css" media="all" />

  <title>Backyard - <?php echo $title;?></title>
</head>
<body>
  <?php include("includes/header.php");?>
  <div class="gardens">
    <h2>garden A</h2>
  <img class="garden_img" src="images/backyard1.jpg" alt="gardenA" />
  <h2>garden B</h2>
  <img class="garden_img" src="images/backyard2.jpg" alt="gardenB" />

  <?php include("includes/footer.php");?>
</div>

</body>
</html>
