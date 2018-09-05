<?php
include("includes/init.php");

// declare the current location, utilized in header.php
$current_page_id="login";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />

  <title>Log in- <?php echo $title;?></title>
</head>

<body>

  <?php include("includes/nav.php");?>
  <div>

    <?php
    if($current_user){
      ?>
      <h3>You are already logged in! </h3>
      <p>You could <a href="logout.php">logout</a> to change to a different user</p>

      <?php
    }else{
      ?>
      <h1>Log in</h1>

      <?php
      print_alerts();
      ?>
      <p class= "larger"> Please login to book an appointment with us. We look forward to working
        with you soon. </p>
        <img class="login_image1" alt="blueprint" src="images/login_blueprint.jpeg"/>
        <!-- image from https://www.pexels.com/search/blueprint/ -->

        <div id= "login_form_center">
          <form id="loginForm" action="login.php" method="post">
            <ul>
<div class= "username">
              <li>
                <label class = "username">Username:</label>
                <input class ="textbox" type="text" name="username" required/>
              </li>
</div>
              <li>
                <label class = "password">Password:</label>
                <input class = "textbox" type="password" name="password" required/>
              </li>
              <li>
                <button class = "button" name="login" type="submit">Log In</button>
              </li>

            </ul>
          </form>
        </div>
      <?php }
      ?>
    </div>

  </body>

  </html>
