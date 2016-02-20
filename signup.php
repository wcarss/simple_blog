<?php
session_start();

if (isset($_SESSION['username'])) {
  header('Location: http://localhost');
  exit;
}

if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['description'])) {

  # this is the most basic alternative to having a captcha that I could think of
  if (!isset($_POST['access_code']) || $_POST['access_code'] !== 'sekret') {
    header('Location: http://localhost');
    exit;
  }

  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $description = $_POST['description'];

  $db = new PDO('mysql:host=localhost;dbname=blog_app;charset=utf8', 'blog_app_user', 'sekret');
  $sql = "insert into users (email, username, password, description) values (?, ?, ?, ?);";
  $db->prepare($sql)->execute([$email, $username, $password, $description]);

  header('Location: http://localhost/login.php');
} else {
  echo '<html><head>';
  echo '  <link href="style.css" rel="stylesheet" type="text/css">';
  echo '</head><body>';

  echo "<h2>new user</h2>";

  echo "<form method='POST' action='signup.php'>";
  echo "  <p>email: <input type='text' name='email' placeholder='email'><br>";
  echo "  username: <input type='text' name='username' placeholder='username'><br>";
  echo "  description: <input type='text' name='description' placeholder='description'><br>";
  echo "  password: <input type='password' name='password' placeholder='password'><br>";
  echo "  access code: <input type='text' name='access_code' placeholder='access code'>";
  echo "  <input type='submit' name='submit' value='submit'></p>";
  echo "</form>";

  echo "<p>Go <a href='index.php'>back</a></p>";
  echo "</body></html>";
}

?>
