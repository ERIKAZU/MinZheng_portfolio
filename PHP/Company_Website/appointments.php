<?php
include ("includes/init.php");
$current_page_id = "appointments";

date_default_timezone_set('EST');
$today = substr(date("Y-m-d H:i:s"),0,10);

function get_client_name($client_id){
  global $db;
  $sql = "SELECT client_name FROM clients WHERE client_id = :id";
  $params = array(":id" => $client_id);
  $client_records = exec_sql_query($db, $sql, $params)->fetchAll();
  return ($client_records[0])["client_name"];
}

function get_project_name($project_id){
  global $db;
  $sql = "SELECT project_name FROM projects WHERE proj_id = :id";
  $params = array(":id" => $project_id);
  $project_records = exec_sql_query($db, $sql, $params)->fetchAll();
  return ($project_records[0])["project_name"];
}

function get_appointments() {
  global $db;
  $sql = "SELECT * FROM schedule WHERE pending_approval = 1";
  $records = exec_sql_query($db, $sql, array())->fetchAll();
  if($records == NULL) echo "<p>No appointments to review.</p>";
  else foreach ($records as $record) print_appointment_records($record);
}

function print_appointment_records($record) {
  global $db;
  $id = $record["id"];
  $client_id = $record["client_id"];
  $project_id = $record["proj_id"];
  $date = $record["date"];
  $time = $record["time"];

  $client_name = get_client_name($client_id);
  $project_name = get_project_name($project_id);

  $sql = "SELECT * FROM schedule WHERE `date` = :d AND `time` = :t AND id <> :id";
  $params = array(":d" => $date, ":t" => $time, ":id" => $id);
  $records = exec_sql_query($db, $sql, $params)->fetchAll();

  $conflicts = array();
  foreach($records as $record){
    $client_name = get_client_name($record['client_id']);
    $conflicts[]=  $client_name;
  }

  ?>
  <div>
    <p><b>Client:</b> <?php echo $client_name; ?>   |  <b>Project:</b> <?php echo $project_name; ?></p>
    <p>On <?php echo $date; ?> at <?php echo $time; ?></p>
    <form action="appointments.php" method="post">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="hidden" name="client_name" value="<?php echo $client_name; ?>">
      <input type="hidden" name="date" value="<?php echo $date; ?>">
      <input type="hidden" name="time" value="<?php echo $time; ?>">
      <button name="accept" type="submit">Accept</button>
      <button name="decline" type="submit">Decline</button>
      <label>Reason for declining:</label>
      <input type="text" name="comment">
    </form>
    <?php
      if($conflicts != NULL){
        echo "<p><b>Warning - potential scheduling conflicts!</b></p>
              <p>In this time slot Pietrzak & Pfau is also scheduled to meet with the following clients:</p><p>".
              implode(', ', $conflicts);;
      }
      ?>
    </div>
    <?php
    echo "<hr>";
}

if(isset($_POST['decline'])){
  $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
  $name = filter_input(INPUT_POST, "client_name", FILTER_SANITIZE_STRING);
  $date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING);
  $time = filter_input(INPUT_POST, "time", FILTER_SANITIZE_STRING);

  $sql = "SELECT * FROM `schedule` WHERE id = :id";
  $params = array(":id" => $id);
  $records = exec_sql_query($db, $sql, $params)->fetchAll();
  $email = ($records[0])["email"];
  $proj_id = ($records[0])["proj_id"];
  $project_name = get_project_name($proj_id);

  $subject = $project_name . " meeting confrimation.";
  $message = "Dear " . $name . ", " . "\r\n" .
             "Regretably, we are unable to meet with you on " . $date . " at " . $time . ". Please give us a call and we will figure out a time to meet that is convinient for you in a less busy time slot." . "\r\n"  .
             "-Pietrzak & Pfau" . "\r\n" .
             "(845) 294-0606";
  $headers = 'From: baxter.demers@gmail.com' . "\r\n" . 'Reply-To: baxter.demers@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
  mail($email, $subject, $message, $headers);

  //delete the appointment record
  $sql = "DELETE FROM schedule WHERE id = :id";
  $params = array(":id" => $id);
  exec_sql_query($db, $sql, $params);

  $alert = "Appointment with " . $name . " on " . $date . " at " . $time . " declined.";
  push_alert($alert);
}

if(isset($_POST['accept'])){
  $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
  $name = filter_input(INPUT_POST, "client_name", FILTER_SANITIZE_STRING);
  $date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING);
  $time = filter_input(INPUT_POST, "time", FILTER_SANITIZE_STRING);

  $sql = "UPDATE schedule SET `pending_approval` = 0 WHERE id = :id";
  $params = array(":id" => $id);
  exec_sql_query($db, $sql, $params)->fetchAll();
  $alert = "Appointment with " . $name . " confirmed for " . $date . " at " . $time;
  push_alert($alert);

  $sql = "SELECT * FROM `schedule` WHERE id = :id";
  $params = array(":id" => $id);
  $records = exec_sql_query($db, $sql, $params)->fetchAll();
  $email = ($records[0])["email"];
  $proj_id = ($records[0])["proj_id"];
  $project_name = get_project_name($proj_id);

  $subject = $project_name . " meeting confrimation.";
  $message = "Dear " . $name . ", " . "\r\n" .
             "We are writing to confrim your appointment to discuss " . $project_name . " on " . $date . " at " . $time . "." . "\r\n"  .
             "-Pietrzak & Pfau";
  $headers = 'From: baxter.demers@gmail.com' . "\r\n" . 'Reply-To: baxter.demers@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
  mail($email, $subject, $message, $headers);
}

if(isset($_POST['clear'])){
  setcookie("client_name",NULL);
  setcookie("project_name",NULL);
  setcookie("existing_client",NULL);
  setcookie("existing_project", NULL);
  setcookie("mailed", NULL);
  header("Refresh:0");
}

if(isset($_POST['client_set'])) {
  global $db;
  $existing_client = filter_input(INPUT_POST, "existing_client_name", FILTER_SANITIZE_STRING);
  $new_client = filter_input(INPUT_POST, "new_client_name", FILTER_SANITIZE_STRING);

  if($existing_client == NULL && $new_client == NULL){
    push_alert("Please enter an organization name.");
  }
  else if($existing_client != NULL && $new_client != NULL){
    push_alert("Please only enter the organization name once.");
  }
  else if($new_client != NULL){
    setcookie("client_name", $new_client);
    header("Refresh:0");
  }
  else {
    setcookie("client_name", $existing_client);
    setcookie("existing_client", true);
    header("Refresh:0");
  }
}

if(isset($_POST['project_set'])) {
  global $db;
  $existing_project = filter_input(INPUT_POST, "existing_project_name", FILTER_SANITIZE_STRING);
  $new_project = filter_input(INPUT_POST, "new_project_name", FILTER_SANITIZE_STRING);

  if($existing_project == NULL && $new_project == NULL){
    push_alert("Please enter a valid project name.");
  }
  else if($existing_project != NULL && $new_project != NULL){
    push_alert("Please only enter the project name once.");
  }
  else if($new_project != NULL){
    setcookie("project_name", $new_project);
    setcookie("existing_project", false);
    header("Refresh:0");
  }
  else {
    setcookie("project_name", $existing_project);
    setcookie("existing_project", true);
    header("Refresh:0");
  }
}

if(isset($_POST['new_appointment'])) {
  global $db;
  $client_name = filter_input(INPUT_COOKIE, "client_name", FILTER_SANITIZE_STRING);
  $project_name = filter_input(INPUT_COOKIE, "project_name", FILTER_SANITIZE_STRING);
  $date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING);
  $time = filter_input(INPUT_POST, "time", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

  if(date('N', strtotime($date)) >= 6){
    push_alert("We are closed on the weekends.");
  }
  else{
    if(!isset($_COOKIE["existing_client"])){
      $sql = "INSERT INTO clients (client_name) VALUES (:name)";
      $params = array(":name" => $client_name);
      $result = exec_sql_query($db, $sql, $params);
    }
    $sql = "SELECT client_id FROM clients WHERE client_name = :clientName";
    $params = array(":clientName" => $client_name);
    $record = exec_sql_query($db, $sql, $params)->fetchAll();
    $clientID = $record[0]['client_id'];

    if(!isset($_COOKIE["existing_project"])){
      $sql = "INSERT INTO projects (client_id, project_name, project_status) VALUES (:id, :name, :status)";
      $params = array(":id" => $clientID, ":name" => $project_name, ":status" => "Consultation");
      $result = exec_sql_query($db, $sql, $params);
    }

    $sql = "SELECT proj_id FROM projects WHERE project_name = :Name";
    $params = array(":Name" => $project_name);
    $record = exec_sql_query($db, $sql, $params)->fetchAll();
    $projectID = $record[0]['proj_id'];

    $sql = "INSERT INTO schedule (client_id, proj_id, `date`, `time`, pending_approval, email) VALUES (:id, :proj, :d, :t, :app, :e)";
    $params = array(":id" => $clientID, ":proj" => $projectID, ":d" => $date, ":t" => $time, ":app" => 1, ":e" => $email);
    $result= exec_sql_query($db, $sql, $params)->fetchAll();

    $to = $email;
    $subject = $project_name . " meeting";
    $message = "We have recieved your appointment request to discuss " . $project_name . " and we will get back to you soon to confirm.";
    $headers = 'From: baxter.demers@gmail.com' . "\r\n" . 'Reply-To: baxter.demers@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);

    setcookie("mailed", true);
    header("Refresh:0");
  }
}

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
    <div class = "gallery" >

    <?php

    print_alerts();

    if($current_user){ ?>
    <br />
    <h1>Appointments to review:</h1><hr>
    <?php get_appointments(); } else { ?>
    <h1> Schedule an appointment </h1>
      <?php
      if(!isset($_COOKIE["client_name"])) { ?>
        <p> <b>Organization name:</b></p>
        <p>If you have worked with our firm in the past, please use the drop down menu to select your organization. </p>
        <form action="appointments.php" method="post">
          <select name="existing_client_name">
            <option selected disabled>Organizations</option>
            <?php
              $sql = "SELECT * FROM clients";
              $clients = exec_sql_query($db, $sql, array())->fetchAll();
              $client_list = array();
              foreach($clients as $client) array_push($client_list, $client["client_name"]);
              foreach($client_list as $name){ ?>
              <option value="<?php echo $name;?>"><?php echo $name;?></option>
            <?php } ?>
              ?>
          </select>
          <p>Otherwise, please enter your organization name here:</p>
          <input type="text" name="new_client_name" maxlength="75">
          <p>When you are ready to proceed hit the <b> Next </b> button:</p>
          <button name="client_set" type="submit">Next</button>
        </form>
    <?php }
    else if (!isset($_COOKIE["project_name"])){
    ?>
        <h2> Welcome <?php echo $_COOKIE["client_name"]; ?></h2>
        <p><b>Project name:</b></p>
        <form action="appointments.php" method="post">
        <?php if (isset($_COOKIE["existing_client"])){?>
        <p>If this meeting is regarding a current project with our firm, please use the drop down menu to select your project. </p>
        <select name="existing_project_name">
          <option value="new_project" selected disabled>Projects</option>
            <?php
            $sql = "SELECT client_id FROM clients WHERE client_name = :clientName";
            $params = array(":clientName" => $_COOKIE["client_name"]);
            $record = exec_sql_query($db, $sql, $params)->fetchAll();
            $clientID= $record[0]['client_id'];
            $sql = "SELECT * FROM projects WHERE client_id = :clientID";
            $params = array(":clientID" => $clientID);
            $projects = exec_sql_query($db, $sql, $params)->fetchAll();
            $project_list = array();
            foreach($projects as $project) array_push($project_list, $project["project_name"]);
            foreach($project_list as $name){
            ?>
            <option value="<?php echo $name;?>"><?php echo $name;?></option>
            <?php
          }
          ?>
        </select>
    <?php } ?>
        <p>Please enter the new project's name:</p>
        <input type="text" name="new_project_name" maxlength="75">
        <p>When you are ready to proceed hit the <b> Next </b> button:</p>
        <button name="project_set" type="submit">Next</button>
      </form>
    <?php } else if (!isset($_COOKIE["mailed"])){?>
      <form action="appointments.php" method="post">
        <h3> Welcome <?php echo $_COOKIE["client_name"]; ?></h3>
        <h3> We look forward to speaking with you about <?php echo $_COOKIE["project_name"]; ?></h3>
        <p><b>Date:</b></p>
        <p>Please note we are not open on the weekends.</p>
        <input type="date" name="date" min="<?php echo $today; ?>" required value="2018-06-01">
        <p><b>Time:</b></p>
        <p>We are open between 9:00am and 5:00pm. The last appointment slot is at 4:30pm</p>
        <input type="time" name="time" step="1800" value="10:30" min="09:00:00" max="16:30:00" required>
        <p><b>Email:</b></p>
        <input type="email" name="email" required>
        <p>We will send you a confrimation email to the address you provide.</p>
        <button name="new_appointment" type="submit">Schedule</button>
      </form>

    <?php } ?>
    <form action="appointments.php" method="post">
      <button name="clear" type="submit">Start New Appointment</button>
    </form>
  <?php } ?>
    </div>
</body>
</html>
