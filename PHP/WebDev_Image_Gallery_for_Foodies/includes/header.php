<header>
  <img class="img-circle"  src="images/logo.png" alt="logo" />
  <h1 id="title">Yummy!</h1>


  <nav id="menu">
      <ul>
        <?php
        foreach($pages as $page_id => $page_name) {
          // utilize the current location to style it differently
          if ($page_id == $current_page_id) {
            echo "<li><a id=\"current_page\"".  " href= $page_id>$page_name</a></li>";

          } else {
            echo "<li><a " . " href='" . $page_id. ".php'>$page_name</a></li>";
          }

        }
        ?>
      </ul>

    </nav>
    <p>
      <?php
      if ($current_user) {
        echo "<a class=\"side_margin_10 margin_top_0 no_vert_padding\">Logged in as $current_user </a>";
      }
      ?>
    </p>


</header>
