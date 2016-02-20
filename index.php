<?php
session_start();

$logged_in = false;
if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $user_id  = $_SESSION['user_id'];
  $logged_in = true;
}

if ($logged_in) {
  echo "<small>hello, <a href='user.php?edit_user_id=${user_id}'>${username}</a>! <a href='/logout.php'>logout</a></small>";
} else {
  echo "<small>hello, unregistered user! <a href='/login.php'>login</a></small>";
}

$db = new PDO('mysql:host=localhost;dbname=blog_app;charset=utf8', 'blog_app_user', 'sekret');

echo '<html><head>';
echo '<link href="style.css" rel="stylesheet" type="text/css">';
echo '</head><body>';
echo '<h1>Thoughts from the font</h1>';
echo '<h2 class="subtitle">a blog by some guy for some reason</h2>';

foreach($db->query('select p.id, p.title, p.body, p.created_at, u.username, u.id as user_id from posts p inner join users u on p.author_id = u.id') as $row) {

  $username = htmlspecialchars($username);
  $title = htmlspecialchars($row['title']);
  $body = htmlspecialchars($row['body']);

  echo "<h2>${title}</h2><p>${body}</p>";
  echo "<div><small>by <a href='user.php?user_id=${row['user_id']}'>${username}</a> at ${row['created_at']}</small>";

  if ($logged_in) {
    echo "<a href='edit.php?edit_post_id=${row['id']}'>edit</a>";
    echo "<form method='POST' action='/delete.php'>";
    echo "  <input type='hidden' name='delete_post_id' value='${row['id']}'>";
    echo "  <input type='submit' class='delete_post' name='submit' value=''>";
    echo "</form>";
  }
  echo "</div>";
}

if ($logged_in) {
  echo '<h2>new post</h2>';
  echo '<form method="POST" action="/new.php">';
  echo '  <input type="text" name="title" placeholder="title"><br>';
  echo '  <textarea name="body" placeholder="content" rows=20 cols=50></textarea><br>';
  echo '  <input type="submit" name="submit" value="submit">';
  echo '</form>';
}

echo '</body></html>';
?>
