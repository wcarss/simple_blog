<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: http://localhost/login.php?not_logged=1');
  exit;
}

$db = new PDO('mysql:host=localhost;dbname=blog_app;charset=utf8', 'blog_app_user', 'sekret');

$id = $_POST['delete_post_id'];
$sql = "delete from posts where id=?";
$db->prepare($sql)->execute([$id]);

header('Location: http://localhost');
?>
