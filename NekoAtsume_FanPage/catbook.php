<?php
include("includes/init.php");
// declare the current location, utilized in header.php
$current_page_id="catbook";
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" type="text/css" href="style/all.css" media="all" />

  <title>Catbook - <?php echo $title;?></title>
</head>

<body>
  <?php include("includes/header.php");?>

  <h3 class="intro">
    You can place food and toys in the garden to attract cats.
  </h3>

  <div  class="citation cite_at_top">

    <address>
      (Picture by <a class="link" href="http://www.lauriealfaro.com/neko/"> Neko Atsume Fan Site</a>)
    </address>

  </div>


<div class="gs">

  <div class="gallery">

      <img src="images/cat1.gif" class="img-circle" alt="cat">

    <div class="desc">Gozer</div>
  </div><!--
    --><div class="gallery">

      <img src="images/cat2.gif" class="img-circle" alt="cat">

    <div class="desc">Pickles</div>
  </div><!--
    --><div class="gallery">

      <img src="images/cat3.gif" class="img-circle" alt="cat">

    <div class="desc">Shadow</div>
  </div><!--
    --><div class="gallery">

      <img src="images/cat4.gif" class="img-circle" alt="cat">


    <div class="desc">Callie</div>
    <!-- <span class="tooltiptext"> Meow </span> -->
  </div><!--
    --><div class="gallery">

      <img src="images/cat6.gif" class="img-circle" alt="cat">

    <div class="desc">Spooky</div>
  </div>
</div>
  <?php include("includes/footer.php");?>
</body>
</html>
