<?php
include("includes/init.php");

// declare the current location, utilized in header.php
$current_page_id="logout";

log_out();
if (!$current_user) {
  push_alert("You've been successfully logged out.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />

  <title>Log out- <?php echo $title;?></title>
</head>

<body>
  <?php include("includes/nav.php");?>

  <div class="gallery">
    <h1>Log Out</h1>

    <?php
    print_alerts();
    ?>
  </div>

</body>

</html>
