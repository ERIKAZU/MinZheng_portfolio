<?php
include('includes/init.php');
$sql = "SELECT * FROM projects";
$proj_info_array = exec_sql_query($db, $sql) -> fetchAll();
echo "<script>console.log( 'projinfoready' );</script>";
?>
<?php
$current_page_id = "portfolio";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://d3js.org/d3.v4.min.js"></script>
  <script src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title>Portfolio</title>
</head>
<body>
  <?php include("includes/nav.php");?>
  <h1>Our Project Locations</h1>
  <div class='add_margin'>
  <div id="map">
<svg>
  <defs>
    <filter id="solid">
      <!-- <feFlood flood-color="yellow"/> -->
      <feGaussianBlur in="SourceGraphic" stdDeviation="1"/>
      <feComposite in="SourceGraphic"/>
    </filter>
  </defs>
</svg>
  </div>
  <script>

  window.onload = showMap();
  // the array to store all the projects to be displayed on the map.
  var projects;

  function showMap() {
    console.log("map is loading");
    projects = <?php echo json_encode($proj_info_array) ?>;
    console.log(projects);
    // var counties;
    // the coordinates are obtained from MATLAB in the order of the list of the projects in the given pdf
    var coordinates = [[786, 964],[684,648],[1292, 572],[846, 694],[1036, 824],[1144, 544],
    [826, 598], [508, 212], [364, 220],[1056,898]];

    // original size of the map image
    var height = 1204;
    var width = 1864;

    var svg_width = 960;
    var svg_height = svg_width / width * height;

    var xScale = d3.scaleLinear().domain([0, width]).range([0, svg_width]);
    var yScale = d3.scaleLinear().domain([0, height]).range([0, svg_height]);

    var svg = d3.select("svg")
    .attr("width",svg_width)
    .attr("height", svg_height);

    var defs = svg.append("defs");
    var filter = defs.append("filter")
                     .attr("id","textbackground")
                     .attr("height","120%");

    filter.append("feFlood")
          .attr("flood-color","blue")
          .attr("opacity",0.2);
    filter.append("feComposite")
          .attr("in", "SourceGraphics");

    svg.append('image')
    .attr('xlink:href','test_map.png')
    .attr("width",svg_width)
    .attr("height", svg_height);

    // clickable rectangle
    var rect_width = svg_width/25;
    var rect_height = svg_height/15;


    var bar = svg.selectAll("g")
       .data(coordinates).enter()
       .append("g")
       .attr("transform", function(d, i) {
         return "translate(" + (xScale(d[0]) - rect_width /2) + "," + (yScale(d[1]) - rect_height+10) + ")";
       });

    bar.append("a")
       .attr("xlink:href", function (d, i) {
         return "project.php?proj_id=" + (i+1);
       })
      .append("rect")
      .attr("class", "rectangle")
      .attr("width", rect_width)
      .attr("height", rect_height)
      .attr("opacity",0);

    bar.append("text")
    .attr("dy", "-.35em")
    .attr("text-anchor", "middle")
    .attr("class","tooltip_inactive")
    // .attr("id", function (d, i) {return "text" + i;})
    .text(function(d, i) { return projects[i].project_name; })

     //   // .on("click", function () {console.log("click")};)
     //   .on("mouseenter", function(d,i) {
     //     console.log("mouseover");
     //   div.style("opacity", .9);
     //   div.html(projects[i].project_name)
     //     .style("left", (d3.event.pageX) + "px")
     //     .style("top", (d3.event.pageY - 28) + "px");
     //   })
     // .on("mouseleave", function(d) {
     //   console.log("mouseout");
     //   div.style("opacity", 0);
     //   });


     $('.rectangle').hover(
       function () {
         console.log("mouseover");
         $(this).parent().next().animate({"opacity":"1"},500);

       },
       function () {
         console.log("mouseout");
         $(this).parent().next().animate({"opacity":"0"},200);
       }
     );
  }


  </script>

  <h2>Search:</h2>
  <form id="searchForm" action="portfolio.php" method="get">
    <select name="field_name" id="select_field">
      <option class="search_by" value="" selected disabled>Search By</option>
      <?php
      const SEARCH_FIELDS = [
        "project_name" => "By project name",
        "project_address" => "By project address",
        "project_status" => "By project status"
      ];
      foreach(SEARCH_FIELDS as $field_name => $label){
        ?>
        <option value="<?php echo $field_name;?>"><?php echo $label;?></option>
        <?php
      }
      ?>
    </select>
    <input id="search_textbox" type="text" name="search"/>
    <button id="search_button" type="submit" name="submit">Search</button>
  </form>


  <?php

  $search_field = NULL;
  $do_search = FALSE;
  if (isset($_GET['search']) and isset($_GET['field_name'])) {
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
    $search = trim($search);
    $field_name = filter_input(INPUT_GET, 'field_name', FILTER_SANITIZE_STRING);
    if (in_array($field_name, array_keys(SEARCH_FIELDS))) {
      echo $search_field;
      $do_search = TRUE;
      $search_field = $field_name;
    }else {
      $do_search = FALSE;
      $search_field = NULL;
    }
  }

  echo "<div class='list_projects'>";

  $record_exist = FALSE;

  if ($do_search) {
    $sql = "SELECT * FROM projects WHERE " . $search_field ." LIKE '%' || :search || '%';";
    $params = array(':search' => $search);
    // $records = exe_sql($db, $sql, $params);
    $records = exec_sql_query($db, $sql, $params) -> fetchAll();
    // no records exist
    if (!$records) {
      echo "<p>No results</p>";
    }
    // records exist
    else {
      echo "Filtered " .SEARCH_FIELDS[$field_name] ." that contains: ".$search;
      $record_exist = TRUE;
      displayProjects($records);
    }
  }
  // If no search is provided OR the previous query does not return anything
  // then display everything
  if (!$do_search or !$record_exist) {
    displayProjects($proj_info_array);
  }

  function displayProjects($records) {
    global $db;
    foreach ($records as $project) {

      echo "<div class='each_project'>";
      // fetch all the images for the project
      $sql = "SELECT file_name, file_ext FROM images WHERE images.proj_id = :proj_id";
      $params = array(":proj_id" => $project["proj_id"]);
      $proj_imgs = exec_sql_query($db, $sql, $params) -> fetchAll();

      // display the image if there is one for this project
      if ($proj_imgs && count($proj_imgs) != 0) {
        // display one image for the project
        $selected_img = $proj_imgs[0]; // get the first image of the project
        $file_name = PATH_IMG. $selected_img["file_name"] . "." . $selected_img["file_ext"];
        // MAY NEED TO CHANGE CLASS NAME AND CROP THE IMAGE
        echo "<div class=\"photo portfolio_image\"><img src=\"" .
        $file_name . "\" class='thumbnail' alt=\"project\" /></div>";
      }

      // print project information
      // TODO: change class name for styling
      echo "<div class='project_info'>";
      echo "<ul><li class='project_list'> Project Name: " . htmlspecialchars($project["project_name"]) . "</li>" .
      "<li class='project_list'> Address: " . htmlspecialchars($project["project_address"]) . "</li>" .
      "<li class='project_list'> Project overview: " . htmlspecialchars($project["project_description"]) . "</li>";
      echo "<li><a class='project_view' href=\"project.php?proj_id=".$project["proj_id"]."\">View</a></li>";
      echo "</ul>";
      echo "</div></div>";
    }
  }
  // display each selected project

  echo "</div></div>";
  ?>
</body>
</html>
