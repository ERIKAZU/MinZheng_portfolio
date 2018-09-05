<?php
  // echo "<script>console.log( 'init PHP loading' );</script>";
$title = "PIETRZAK & PFAU";

CONST PATH_IMG = "/project_images/";
CONST NO_LOGIN = "Login was unsuccessful. Please try entering your credentials again.";

function exec_sql_query($db, $sql, $params = array()) {
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return NULL;
}

// associative array mapping page 'id' to page title
$pages = array(
  "index" => "Home",
  "portfolio" => "Portfolio",
  "people" => "Our Team",
  "appointments" => "Appointments",
  "login" => "Log in",
  "logout" => "Log out"
);

$alerts = array();

//push an alert to the alert array
function push_alert($alert) {
  global $alerts;
  array_push($alerts, $alert);
}

//prints all the alerts
function print_alerts() {
  global $alerts;
  foreach ($alerts as $alert) {
    echo "<p><strong>" . htmlspecialchars($alert) . "</strong></p>\n";
  }
}

// YOU MAY COPY & PASTE THIS FUNCTION WITHOUT ATTRIBUTION.
// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename) {
  if (!file_exists($db_filename)) {

    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_init_sql = file_get_contents($init_sql_filename);
    if ($db_init_sql) {
      try {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
      } catch (PDOException $exception) {
        // If we had an error, then the DB did not initialize properly,
        // so let's delete it!
        unlink($db_filename);
        throw $exception;
      }
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return NULL;
}

// open connection to database
$db = open_or_init_sqlite_db("website.sqlite", "init/init.sql");

//login functions with session_start
function log_in($username, $password) {
  global $db;

  if ($username && $password) {
    $sql = "SELECT * FROM users WHERE username = :username;";
    $user_records = exec_sql_query($db, $sql, array(':username' => $username))->fetchAll();

    if ($user_records != NULL) {
      $account = $user_records[0];
      if (password_verify ($password, $account['password'])) {
          push_alert("Logged in as $username");
          $_SESSION['current_user'] = $username;
          return $_SESSION['current_user'];
        }
      else push_alert(NO_LOGIN);
    }
    else push_alert(NO_LOGIN);
  }
  else push_alert("Something went wrong. Please try again.");
  return NULL;
}

//log out with session methods
function log_out() {
  global $current_user;
  $current_user = NULL;
  unset($_SESSION['current_user']);
  session_destroy();
}

function get_user() {
  if (isset($_SESSION['current_user'])) {
    return $_SESSION['current_user'];
  }
  return NULL;
}

session_start();
if (isset($_POST['login'])) {
  $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $current_user = log_in($username, $password);
} else {
  $current_user = get_user();
}
// echo "<script>console.log( ' init PHP loading finished' );</script>";
?>
