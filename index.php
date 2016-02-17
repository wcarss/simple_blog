<?php
session_start();

if (isset($_SESSION['user'])) {
  echo "<small>hello, {$_SESSION['user']}! <a href='/logout.php'>logout</a></small>";
} else {
  echo "<small>hello, unregistered user! <a href='/login.php'>login</a></small>";
}

$db = new PDO('mysql:host=localhost;dbname=blog_app;charset=utf8', 'blog_app_user', 'sekret');

echo '<html><body>';
echo '<h1>bloggggg</h1>';
foreach($db->query('select * from posts') as $row) {
  echo "<h2>${row['title']}</h2><p>${row['body']}</p>";
}
echo '<form method="POST" action="/new.php">';
echo '<input type="text" name="title">';
echo '<input type="text" name="body">';
echo '<input type="submit" name="submit" value="submit">';
echo '</form>';
echo '</body></html>';
?>
