<?php
session_start();

if (isset($_SESSION['username'])) {
  header('Location: http://localhost');
}

if (isset($_POST['username']) && isset($_POST['password'])) {
  $db = new PDO('mysql:host=localhost;dbname=blog_app;charset=utf8', 'blog_app_user', 'sekret');

  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $db->prepare("select * from users where username = ?");
  
  $stmt->execute([$username]);

  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (count($results) === 1 && $results[0]['password'] === $password) {
      $_SESSION['username'] = $results[0]['username'];
      $_SESSION['user_id'] = $results[0]['id'];
      header('Location: http://localhost');
  } else {
      header('Location: http://localhost/login.php?wrong=1');
  }
} else {
  if (isset($_GET['wrong'])) {
    echo "<h3>wrong username or password!</h3>";
  }
  if (isset($_GET['not_logged'])) {
    echo "<h3>you need to be logged in to post!</h3>";
  }

  echo '<form method="POST" action="/login.php">';
  echo '  <input type="text" name="username">';
  echo '  <input type="password" name="password">';
  echo '  <input type="submit" name="submit" value="submit">';
  echo '</form>';
  echo '<p>Back to <a href="/">home</a></p>';
}
?>
