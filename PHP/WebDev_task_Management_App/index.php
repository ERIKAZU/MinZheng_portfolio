<?php
// declare the current location, utilized in header.php
$current_page_id="index";

$db = new PDO('sqlite:todos.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function exe_sql($db, $sql, $params) {
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return NULL;
}
// display msg if anything happens
$output_msg = array();
$task_counts = updateTaskNum($db);
function updateTaskNum($db){
  $sql = "SELECT * FROM todos WHERE finish=1;";
  $params = array();
  $records = exe_sql($db, $sql, $params);
  $tasks= $records->fetchAll();
  $task_counts = sizeof($tasks);
  // $task_counts = $records->num_rows();
  return $task_counts;
}

// search by selected field
const SEARCH_FIELDS = [
  "task_name" => "By task name",
  "label" => "By label",
  "priority" => "By priority",
  "notes" => "By notes"

];
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
function print_table($records){
  ?>
  <form action="index.php" method="post">
  <table>
    <tr>
      <th></th>
        <th>Task</th>
        <th>Priority</th>
        <th>Due day</th>
        <th>Label</th>
        <th>Notes</th>
    </tr>
    <?php

    foreach($records as $record) {
      print_record_row($record);
    }
    ?>
  </table>
  <input name="submit_completed" id="submit_completed" type="submit" value="submit finished tasks">
  </form>
  <?php
}


function print_record_row($record) {
  ?>
  <tr>

    <td>
      <input name="<?php echo htmlspecialchars($record["task_id"]);?>" type="checkbox"
      >
    </td>

    <td><?php echo htmlspecialchars($record["task_name"]);?></td>
    <td>
      <?php
      $stars = intval( $record["priority"] );
      for ($i = 1; $i <= 3; $i++) {
        if ($i <= $stars) {
          echo "★";
        } else {
          echo "☆";
        }
      }
      ?>
    </td>
    <td><?php
    $date_string = $record["due_date"];
    if (strlen($date_string) == 8) {

      echo substr($date_string, 0, 4)."-".substr($date_string, 4, 2)."-".substr($date_string, 6,2);
    }
     //echo htmlspecialchars($record["due_date"]);?></td>
    <td><?php echo htmlspecialchars($record["label"]);?></td>
    <td><?php echo htmlspecialchars($record["notes"]);?></td>
  </tr>
  <?php
}


// enter task
// $labels = array("Work", "School", "Life");
$labels = exe_sql($db, "SELECT label_name FROM labels", NULL)->fetchAll(PDO::FETCH_COLUMN);
$invalid_input = FALSE;

// add new button listener
if (isset($_POST["submit_insert"])) {
  $task_name = filter_input(INPUT_POST, 'task_name', FILTER_SANITIZE_STRING);
  $due_date = filter_input(INPUT_POST, 'due_date', FILTER_SANITIZE_STRING);

  $due_date = str_replace("-", "", $due_date);
  echo $due_date;

  $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
  $priority = filter_input(INPUT_POST, 'priority', FILTER_VALIDATE_INT);
  $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);


  if ( $priority < 1 or $priority > 3 ) {
    $invalid_input = TRUE;
  }

  if ($invalid_input) {
    array_push($output_msg, "Invalid input.");
  } else {

    $sql = "INSERT INTO todos (task_name, due_date, label, priority, notes)
    VALUES(:task_name , :due_date , :label, :priority, :notes)";

    $params = array(":task_name"=> $task_name,
    ":due_date" => $due_date,
    ":label" => $label,
    ":priority" => $priority,
    ":notes" => $notes);

    $result = exe_sql($db, $sql, $params);
    if ($result) {
      array_push($output_msg, "New todo added.");
    } else {
      array_push($output_msg, "Failed to add todo.");
    }

  }
}

// complete task checkbox listener
if (isset($_POST["submit_completed"])) {
  $unfinished_todos_ids = exe_sql($db, "SELECT task_id FROM todos WHERE finish = 0", NULL)->fetchAll(PDO::FETCH_COLUMN);

  foreach ($unfinished_todos_ids as $unfinished_todos_id){

    if(isset($_POST[$unfinished_todos_id])){
      $checked_task_id = $unfinished_todos_id;
      $sql = "UPDATE todos SET finish=1 WHERE task_id=
      :task_id";
      $params = array(":task_id"=> $checked_task_id);
      $result = exe_sql($db, $sql, $params);
    }
  }
}


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet'>
  <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="style/all.css" media="all" />

  <title>Home</title>
</head>

<body>
  <?php include("includes/header.php");
  $task_counts = updateTaskNum($db);

  echo "<div id=\"task_count\"><h3 >You have completed " . htmlspecialchars($task_counts) . " tasks so far! </h3></div>\n";
  ?>

  <?php
    // print msg
    foreach ($output_msg as $message) {
      echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
    }
  ?>

<div id = "span_col">
  <div id="left_col">

  <!-- <article id="content"> -->
    <div>
      <fieldset>
        <legend class="font_size_20"> Enter new task</legend>

        <div class="container">
          <form action="index.php" method="post">
            <div class="row">
              <div class="add_margin">
                <label for="task_name">Task (required)</label>
              </div>
              <div class="add_margin">
                <input id="task_name" type="text" name="task_name" placeholder="Task Name" required/>
              </div>
            </div>

            <div class="row">
              <div class="add_margin">
                <label for="due_date">Due Day (equal or later than today):</label>
              </div>
              <div class="add_margin">
                <?php
                date_default_timezone_set('America/New_York');
                $date_now = date("Y-m-d");
                echo "<input id=\"due_date\" type=\"date\" name=\"due_date\" min=\"".$date_now."\"/>"
                ?>

              </div>
            </div>

            <div class="row">
              <div class="add_margin">
                <label>Labels:</label>
              </div>
              <div class="add_margin">

                <select name="label">
                  <option value="" selected disabled>Choose Label</option>
                  <?php
                  foreach($labels as $label) {
                    echo "<option value=\"" . $label . "\">" . $label . "</option>";
                  }
                  ?>
                </select>

              </div>
            </div>

            <div class="row">
              <div class="add_margin">
                <label>Priority:</label>
              </div>
              <div class="add_margin">
                <input type="radio" name="priority" value="1" checked/>1
                <input type="radio" name="priority" value="2"/>2
                <input type="radio" name="priority" value="3"/>3

              </div>
            </div>

            <div class="row">
              <div class="add_margin">
                <label for="notes">Notes</label>
              </div>
              <div class="add_margin">
                <textarea id="notes" name="notes" placeholder="Notes.."
                style="height:40px"></textarea>
              </div>
            </div>
            <div class="row add_margin">
              <input name="submit_insert" id="submit_button" type="submit" value="Submit">
            </div>
          </form>
          </div>
          </fieldset>
    <!-- </article> -->
    </div>
      </div>

    <div id="right_col">
        <fieldset>
          <legend id="task_window_legend" class="font_size_20">
            <?php
                echo 'View your tasks';
            ?>
          </legend>

          <!-- search by field -->
          <form id="searchForm" action="index.php" method="get">
        <select name="field_name" id="select_field">
          <option value="" selected disabled>Search By</option>
          <?php
          foreach(SEARCH_FIELDS as $field_name => $label){
            ?>
            <option value="<?php echo $field_name;?>"><?php echo $label;?></option>
            <?php
          }
          ?>
        </select>
        <input id="search_textbox" type="text" name="search"/>
        <button id="search_button" type="submit">Search</button>
      </form>


    <?php

    if (isset($_POST["show_all"])) {
      $sql = "SELECT * FROM todos WHERE finish=0;";
      $params = array();
      $records = exe_sql($db, $sql, $params);
      ?>
      <script>
      document.getElementById("task_window_legend").innerHTML="All todos";
      </script>
      <?php
    }
    // filter by due day listener
    else if (isset($_POST["filter_due"])) {

      $sql = "SELECT * FROM todos WHERE due_date !=\"\" AND due_date <=
      :today_date AND finish=0;";
      $today_date = date("Ymd");
      //echo $today_date;
      //$today_date = "20180320";
      $params = array(":today_date" => $today_date);
      $records = exe_sql($db, $sql, $params);
      ?>
      <script>
      document.getElementById("task_window_legend").innerHTML="Todos due by or before today";
      </script>
      <?php
    }
    else if ($do_search) {

      $sql = "SELECT * FROM todos WHERE " . $search_field ." LIKE '%' || :search || '%'"." AND finish=0;";
      $params = array(':search' => $search);
      $records = exe_sql($db, $sql, $params);
      ?>
      <script>
      document.getElementById("task_window_legend").innerHTML="Search results";
      </script>
      <?php
      $do_search= FALSE;
      $search_field=NULL;
    }

    else{
      $sql = "SELECT * FROM todos WHERE finish=0; ";
      $params = array();
      $records = exe_sql($db, $sql, $params)->fetchAll();
    }
    ?>
          <div class="add_margin">

                <div class = "inline_buttons" id="filter_due">
                  <form action="index.php" method="post">
                  <input name="filter_due"  class="inline_buttons" type="submit" value="due by today">
                  </form>
                </div>
                <div class = "inline_buttons" id="show_all"><form action="index.php" method="post">
                <input name="show_all"
                class="inline_buttons" type="submit" value="show all">
              </form></div>
          <?php
          if (isset($records) and !empty($records)) {
            print_table($records);
          } else {
            echo "<p> No tasks for you!</p>";
          }
      ?>
    </div>

    </fieldset>
    </div>
</div>

  <!-- </div>
</div> -->

<div style="width:100%;">
    <?php include("includes/footer.php");?>
</div>

</body>
</html>
