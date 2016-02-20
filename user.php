<?php
session_start();

$db = new PDO('mysql:host=localhost;dbname=blog_app;charset=utf8', 'blog_app_user', 'sekret');

if (isset($_POST['edit_user_id']) && isset($_POST['edit_email']) && isset($_POST['edit_username']) && isset($_POST['edit_description'])) {
  $id = $_POST['edit_user_id'];

  if (!isset($_SESSION['username'])) {
    header('Location: http://localhost/login.php?not_logged=1');
    exit;
  }

  if ($id !== $_SESSION['user_id']) {
    header('Location: http://localhost');
    exit;
  }

  $email = $_POST['edit_email'];
  $username = $_POST['edit_username'];
  $description = $_POST['edit_description'];
  $timestamp = (new DateTime())->format('Y-m-d H:i:s');

  $sql = "update users set email=?, username=?, description=?, modified_at=? where id=?";
  $params = [$email, $username, $description, $timestamp, $id];
  if (isset($_POST['edit_password']) && $_POST['edit_password'] !== "") {
    $password = $_POST['edit_password'];
    $sql = "update users set email=?, username=?, password=?, description=?, modified_at=? where id=?";
    $params = [$email, $username, $password, $description, $timestamp, $id];
  }

  $stmt = $db->prepare($sql)->execute($params);

  $_SESSION['username'] = $username;
  header('Location: http://localhost');

} else if (isset($_GET['edit_user_id'])) {
  $id = $_GET['edit_user_id'];

  if (!isset($_SESSION['username'])) {
    header('Location: http://localhost/login.php?not_logged=1');
    exit;
  }

  if ($id !== $_SESSION['user_id']) {
    header('Location: http://localhost');
    exit;
  }
   
  $sql = "select * from users where id=?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$id]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $email = htmlspecialchars($results[0]['email'], ENT_HTML5 | ENT_QUOTES);
  $username = htmlspecialchars($results[0]['username'], ENT_HTML5 | ENT_QUOTES);
  $description = htmlspecialchars($results[0]['description'], ENT_HTML5 | ENT_QUOTES);

  echo '<html><head>';
  echo '<link href="style.css" rel="stylesheet" type="text/css">';
  echo '</head><body>';

  echo "<h2>edit user</h2>";
  echo "<form method='POST' action='user.php'>";
  echo "  <p>email: <input type='text' name='edit_email' value='$email'><br>";
  echo "  username: <input type='text' name='edit_username' value='$username'><br>";
  echo "  description: <input type='text' name='edit_description' value='$description'><br>";
  echo "  password: <input type='password' name='edit_password' value=''><br>";
  echo "  <input type='hidden' name='edit_user_id' value='$id'>";
  echo "  <input type='submit' name='submit' value='submit'></p>";
  echo "</form>";
  echo "<p>Go <a href='index.php'>back</a></p>";
  echo "</body></html>";

} else if (isset($_GET['user_id'])) {
  $id = $_GET['user_id'];

  $sql = "select * from users where id=?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$id]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo '<html><head>';
  echo '<link href="style.css" rel="stylesheet" type="text/css">';
  echo '</head><body>';
  if (count($results) !== 1) {
    echo "<p>Looks like somebody's barking up the wrong bush!&lt;/simpsons_reference&gt;<br>";
    echo "(that user doesn't seem to exist.)</p>";
  } else {
    $email = htmlspecialchars($results[0]['email'], ENT_HTML5 | ENT_QUOTES);
    $username = htmlspecialchars($results[0]['username'], ENT_HTML5 | ENT_QUOTES);
    $description = htmlspecialchars($results[0]['description'], ENT_HTML5 | ENT_QUOTES);
    echo "<h2>$username</h2>";
    echo "<p>$email</p>";
    echo "<p>$description</p>";
  }
  echo "<p>Go <a href='index.php'>home</a>";
  echo "</body></html>";
} else {
  echo '<html><head>';
  echo '  <link href="style.css" rel="stylesheet" type="text/css">';
  echo '</head><body>';
  echo "<p>Damnit jim! That's not enough information for me to do any good!</p>";
  echo "<p>Go <a href='index.php'>home</a><br>";
  echo "<small>and don't disappoint bones again</small></p>";
  echo "</body></html>";
}
?>
