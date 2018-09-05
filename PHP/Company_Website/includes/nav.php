<header >
  <h1 id="title"><?php echo $title; ?></h1>

  <nav id="menu">
    <ul>
      <?php
      foreach($pages as $page_id => $page_name) {
        // utilize the current location to style it differently
        if ($page_id == $current_page_id) {
          $css_id = "id='current_page'";
        } else {
          $css_id = "";
        }
        if($current_user || $page_id != "logout"){
          echo "<li><a " . $css_id . " href='" . $page_id. ".php'>$page_name</a></li>";
        }
      }
      ?>
    </ul>
  </nav>
  <?php
  if ($current_user) {
    echo "<p class='loggedin'>Logged in as " . $current_user . "</p>";
  }?>
</header>
