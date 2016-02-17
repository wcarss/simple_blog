<?php
session_start();

$logged_in = false;
if (isset($_SESSION['user'])) {
  $username = $_SESSION['user'];
  $logged_in = true;
}

if ($logged_in) {
  echo "<small>hello, {$username}! <a href='/logout.php'>logout</a></small>";
} else {
  echo "<small>hello, unregistered user! <a href='/login.php'>login</a></small>";
}

$db = new PDO('mysql:host=localhost;dbname=blog_app;charset=utf8', 'blog_app_user', 'sekret');

echo '<html><body>';
echo '<h1>bloggggg</h1>';

foreach($db->query('select * from posts') as $row) {
  echo "<h2>${row['title']}</h2><p>${row['body']}</p>";
  if ($logged_in) {
    echo "<a href='edit.php?edit_post_id=${row['id']}'>edit post</a>";
    echo "<form method='POST' action='/delete.php'>";
    echo "  <input type='hidden' name='delete_post_id' value='${row['id']}'>";
    echo "  <input type='submit' name='submit' value='delete'>";
    echo "</form>";
  }
}

if ($logged_in) {
  echo '<form method="POST" action="/new.php">';
  echo '<input type="text" name="title">';
  echo '<input type="text" name="body">';
  echo '<input type="submit" name="submit" value="submit">';
  echo '</form>';
}

echo '</body></html>';
?>
