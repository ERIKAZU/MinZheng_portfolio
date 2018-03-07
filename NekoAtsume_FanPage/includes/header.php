<header>
  <img class="img-circle"  src="images/logo.jpg" alt="logo" />
  <h1 id="title">Neko Park</h1>

  <nav id="menu">
    <ul>
      <?php
      foreach($pages as $page_id => $page_name) {
        // // utilize the current location to style it differently
        if ($page_id == $current_page_id) {
          echo "<li><a id=\"current_page\"".  "href= $page_id>$page_name</a></li>";
        }
        //   $css_id = "id='current_page'";
        // } else {
        //   $css_id = "";
        // }
        //echo "<li><a " . $css_id . " href='" . $page_id. ".php'>$page_name</a></li>";
        else {
          echo "<li><a " . " href='" . $page_id. ".php'>$page_name</a></li>";
        }
      }
      ?>
    </ul>
  </nav>
</header>
