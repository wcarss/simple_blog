<?php
session_start();

if (!isset($_SESSION['username'])) {
  header('Location: http://localhost/login.php?not_logged=1');
  exit;
}

if (!isset($_POST['title']) || !isset($_POST['body'])) {
  header('Location: http://localhost');
  exit;
}

$title = $_POST['title'];
$body = $_POST['body'];
$user_id = $_SESSION['user_id'];

if ($POST['title'] === "" || $body === "") {
  header('Location: http://localhost');
  exit;
}

$db = new PDO('mysql:host=localhost;dbname=blog_app;charset=utf8', 'blog_app_user', 'sekret');
$sql = "insert into posts (title, body, author_id) values (?, ?, ?)";
$db->prepare($sql)->execute([$title, $body, $user_id]);

header('Location: http://localhost');
?>
