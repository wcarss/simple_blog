<?php
session_start();

if (!isset($_SESSION['username'])) {
  header('Location: http://localhost/login.php?not_logged=1');
  exit;
}

$db = new PDO('mysql:host=localhost;dbname=blog_app;charset=utf8', 'blog_app_user', 'sekret');

if (isset($_POST['title']) && isset($_POST['body']) && isset($_POST['edit_post_id'])) {
  $id = $_POST['edit_post_id'];
  $title = $_POST['title'];
  $body = $_POST['body'];
  $timestamp = (new DateTime())->format('Y-m-d H:i:s');

  $sql = "update posts set title=?, body=?, modified_at=? where id=?";
  $stmt = $db->prepare($sql)->execute([$title, $body, $timestamp, $id]);

  header('Location: http://localhost');
} else if (isset($_GET['edit_post_id'])) {
  $id = $_GET['edit_post_id'];
  $sql = "select * from posts where id=?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$id]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo "<html><body>";
  if (count($results) !== 1) {
    echo "<p>Whoops! We couldn't find a single post you're referring to, and we're definitely not going to let you edit zero or many. Consider a profession outdoors, perhaps.<br>";
    echo "<small>(just kidding, but the outdoors <em>is</em> pretty nice!)</small></p>";
  } else {
    $title = htmlspecialchars($results[0]['title'], ENT_HTML5 | ENT_QUOTES);
    $body = htmlspecialchars($results[0]['body'], ENT_HTML5 | ENT_QUOTES);

    echo "<h2>edit post</h2>";
    echo "<form method='POST' action='edit.php'>";
    echo "  <input type='text' name='title' value='$title'>";
    echo "  <input type='text' name='body' value='$body'>";
    echo "  <input type='hidden' name='edit_post_id' value='$id'>";
    echo "  <input type='submit' name='submit' value='submit'>";
    echo "</form>";
  }
  echo "<p>Go <a href='index.php'>back</a></p>";
  echo "</body></html>";
} else {
  echo "<h2>no edit_post_id specified! Damnit Jim, I can't edit <em>nothing</em>!</h2>";
  echo "<p>Go <a href='index.php'>home</a>";
}
?>
