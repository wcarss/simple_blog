<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: http://localhost/login.php?not_logged=1');
  exit;
}

$db = new PDO('mysql:host=localhost;dbname=blog_app;charset=utf8', 'blog_app_user', 'sekret');

$title = $_POST['title'];
$body = $_POST['body'];

if ($title !== "" and $body !== "") {
  $sql = "insert into posts (title, body, author_id) values (?, ?, 1);";
  $db->prepare($sql)->execute([$title, $body]);
}

header('Location: http://localhost');
?>
