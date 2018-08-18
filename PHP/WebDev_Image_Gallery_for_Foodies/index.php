<?php
include("includes/init.php");
// declare the current location, utilized in header.php
$current_page_id="index";

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
  <title>Home - <?php echo $title;?></title>
</head>

<body>
  <?php include("includes/header.php");?>
  <h2>Welcome</h2>

        <?php
        print_messages();
        if(!$current_user){
        ?>
          <fieldset class="side_margin_10">
            <legend class="font_size_20"> log in</legend>
        <!-- <div id="content-wrap"> -->
        <form id="login_form" class = "box_form" action="index.php" method="post">
          <ul>

          <li>
            <label>Username:</label>
            <input type="text" name="username" required/>
          </li>

          <li>
            <label>Password:</label>
            <input type="password" name="password" required/>
          </li>

          <li>
            <button name="login" type="submit">Log In</button>
          </li>

        </ul>
      </form>

      </fieldset>

        <?php
      }
      else{
        // print_messages();
        ?>
        <fieldset class="side_margin_10">
          <legend class="font_size_20"> log out</legend>

        <form id="logout_form" class = "box_form" action="index.php" method="post">

            <button name="logout" type="submit">Log Out</button>
      </form>

      </fieldset>

    <?php }include("includes/footer.php");?>
</body>
</html>
