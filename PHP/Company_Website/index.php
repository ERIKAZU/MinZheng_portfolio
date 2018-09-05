<?php
include ("includes/init.php");
$current_page_id = "home";

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title> Home </title>
</head>
<body>
    <?php include("includes/nav.php");?>

     <!-- <div class= "index_banner">
       <h1 class="index_design"> Plan. Research. Design.  </h1>
    <!-- <h1 id="about">About</h1> -->
    <div class = "about_container">
      <p id="pfau">An Engineering and Surveying Firm
    </p>
      <p class="larger">
        <span>Village of Goshen, Orange County, New York</span>
      <span>  Village of Monticello, Sullivan County, New York</span>

</p>
    <div>
      <img class="index_image1" alt="clare" src="project_images/blackwhite.png"/>
      <img class="index_image1" alt="clare" src="project_images/project1-9.jpg"/>
    </div>
      <!-- image from https://www.pexels.com/search/blueprint/ -->
<!-- <div class = "index_description"> -->


  <!-- <p>
  The two principals of the firm, Vincent A. Pietrzak,
  P.E., P.L.S., LEED®AP and Joseph J. Pfau, P.E. have been practicing in Orange County
  for the past thirty years. In addition to the two principals, the firm has a total of four (4) Professional Licensed Engineers, one (1) Engineer
  in Training, and two (2) Professional Licensed Land Surveyors.
  </p> -->
<div class= "larger">
  <p class="larger">The objective of Pietrzak & Pfau Engineering and Surveying is to always provide
  personalized service.</p>
</div>
<div id= "objective_div">
  <p id="objective">
  Our staff has the skills and expertise to take your project through the
  approval process and can assist in any aspect where additional services may be required.
  Using state-of-the art equipment (including GPS), provides services that are expedited
  expeditiously.
  </p>
</div>
</div>
  <h1 id="about2">What can we help you with?</h1>
  <div class="bordered">
  <p class="larger">
  The firm’s practice is general civil engineering, which includes:<ol>
    <li>Commercial and residential site plans</li>
    <li>Sanitary engineering (water, sewer, drainage)</li>
    <li>Subdivision engineering (including approvals and permits)</li>
    <li>Agency applications and approvals</li>
    <li>All aspects in the practice of surveying and wetland issues</li>
    <li>Feasability studies</li>
    <li>Design construction and startup</li>
  </ol>
  </p>
  <p class="larger">
  In addition, we can help with the following administrative aspects: <ol>
    <li>Inspections</li>
    <li>Certification of monthly payment requisitions</li>
    <li>Change orders</li>
    <li>Claims</li>
    <li>Expert testimony</li>
    <li>Wetlands</li>
  </ol>
  </p>
</div>

<!-- <h2>Our team</h2> -->

    <!--TODO: change classname for stylilng
    This division is reserved for projects

    ------------div homepage_showcase-----------------
    |         (for each record)                      |
    |-----------div img_description ------------------
    |                   |                            |
    |-------div img-----| -------div description-----|
  -->
    <div class="homepage_showcase">
      <a href="portfolio.php"><h1 class="about3"> Our Projects </a></h1>
      <?php
      // display selected project
      $sql = "SELECT * FROM projects";
      $proj_info_array = exec_sql_query($db, $sql) -> fetchAll();
      // Could use scrollable division to view all projects though.
      $selected_projects = array_slice($proj_info_array, 0, 4);

      // display each selected project
      foreach ($selected_projects as $selected_project) {
        echo "<div class=\"img_description\">";
        // fetch all the images for the project
        $sql = "SELECT file_name, file_ext FROM images WHERE images.proj_id = :proj_id";
        $params = array(":proj_id" => $selected_project["proj_id"]);
        $proj_imgs = exec_sql_query($db, $sql, $params) -> fetchAll();

        // display the image if there is one for this project
        if ($proj_imgs && count($proj_imgs) != 0) {
          // display one image for the project
          $selected_img = $proj_imgs[0]; // get the first image of the project
          $file_name = PATH_IMG. $selected_img["file_name"] . "." . $selected_img["file_ext"];
          // MAY NEED TO CHANGE CLASS NAME AND CROP THE IMAGE
          echo "<div class='img'><a href=\"project.php?proj_id=".$selected_project["proj_id"]."\"><img src=\"" .
          $file_name . "\" class='thumbnail' alt=\"project\" /></a></div>";
        }

        // print project information
        // TODO: change class name for styling
        echo "<div class='description'>";
        echo "<ul><li> Project Name: " . htmlspecialchars($selected_project["project_name"]) . "</li>" .
        "<li> Address: " . htmlspecialchars($selected_project["project_address"]) . "</li>" .
        "<li> Project Status: " . htmlspecialchars($selected_project["project_status"]) . "</li>";
        echo "</ul>";
        echo "</div></div>";
      }
       ?>
     </div>

     <!--TODO: change or style the division reserved for team information-->
     <!--background: http://www.doctem.com/civil.php-->
     <!-- section for team -->
     <!-- <div class="bordered"> -->
     <!-- <div class="homepage_showcase">
       <h1 class="about3"> Our Team </h1>
     </div> -->
     <div class="homepage_team">
       <div class="overlay">
         <a href="people.php">Meet Our Team &#8680;</a>
       </div>
    </div>
 <!-- </div> -->
<h1 class="about3"> Locations </h1>
<div class = "index_container">
<div class = "goshen">
<h2> Goshen </h2>

<p>
<span>
262 Greenwich Ave, Suite A</span>

<span>Goshen, New York 10924</span>

<span><span class = "bold">Phone:</span class = "bold"> (845) 294-0606</span>

<span><span class = "bold">Fax:</span class = "bold">(845) 294-0610</span>
</p>
</div>
<div class = "monticello">
<h2>Monticello</h2>
<p>
<span>2 Hamilton Avenue</span>

Monticello, New York 12701

<span><span class = "bold">Phone:</span class = "bold"> (845) 796-4646</span>

<span><span class = "bold">Fax:</span class = "bold"> (845) 796-4092</span>
</p>
</div>
</div>
</body>
</html>
