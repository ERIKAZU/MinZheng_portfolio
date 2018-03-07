<?php
include("includes/init.php");
// declare the current location, utilized in header.php
$current_page_id="toys";
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" type="text/css" href="style/all.css" media="all" />

  <title>Toys - <?php echo $title;?></title>
</head>
<body>


  <?php include("includes/header.php");?>

    <h3 class="intro">
      You can purchase toys and place them in the garden to attract cats.
    </h3>

    <div  class="citation cite_at_top">

      <address>
        (Picture by <a class="link" href="http://www.lauriealfaro.com/neko/"> Neko Atsume Fan Site</a>)
      </address>

    </div>

  <div class="gs">

  <div class="gallery">

      <img src="images/toys/1.png" class="img-circle" alt="toy">

    <div class="desc">Kotatsu</div>
  </div>


  <div class="gallery">

      <img src="images/toys/2.png" class="img-circle" alt="toy">

    <div class="desc">Cat Metropolis</div>
  </div>


  <div class="gallery">

      <img src="images/toys/3.png" class="img-circle" alt="toy">

    <div class="desc">Cardboard Cafe</div>
  </div>


    <div class="gallery">

        <img src="images/toys/5.png" class="img-circle" alt="toy">

      <div class="desc">Temari Ball</div>
    </div>


    <div class="gallery">

        <img src="images/toys/6.png" class="img-circle" alt="toy">

      <div class="desc">Zanzibar Cushion</div>
    </div>


    <div class="gallery">

        <img src="images/toys/7.png" class="img-circle" alt="toy">

      <div class="desc">Cow Tunnel</div>
    </div>

  </div>





  <?php include("includes/footer.php");?>
</body>
</html>
