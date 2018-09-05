<?php
include ("includes/init.php");
$current_page_id = "people";

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <script src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
  <title> Our Team </title>
</head>
<body>
  <?php include("includes/nav.php");?>
   <?php
   $sql = "SELECT * FROM team;";
   $team = exec_sql_query($db, $sql) -> fetchAll();

   // alternating pattern for display
   $left = 1;
   foreach($team as $person) {
     echo "<div class=\"details\">";
     if ($left == 1) {
       showName($person);
       showInfo($person);
       $left = 0;
     }
     else {
       showInfo($person);
       showName($person);
       $left = 1;
     }
     echo "</div>";
   }

   function showName($person){
     echo "<div class=\"name\"><p class=\"first_name\">" . $person["first_name"]."</p><p class=\"last_name\">" . $person["last_name"]."</p></div>";
   }

   function showInfo($person){
     echo "<div class=\"info\"><ul>".
     "<li>Title: " . htmlspecialchars($person["title"]) . "</li>" .
     "<li> Description: " . htmlspecialchars($person["description"]) . "</li></ul></div>";
   }

   ?>
   <script>
   $(document).ready(function() {
     $(window).scrollTop();

     $('.details').first().animate({'opacity':'1'},1500);
    $(window).scroll( function(){
        $('.details').each( function(){
            var obj_bottom = $(this).offset().top + $(this).outerHeight();
            var window_bottom = $(window).scrollTop() + $(window).height();
            var obj_top = $(this).offset().top;
            var window_top = $(window).scrollTop();

            if( window_bottom > obj_bottom - 230 ){
                $(this).animate({'opacity':'1'},1300);
            }
            // if (obj_top )

        });

    });

});
   </script>
</body>
</html>
