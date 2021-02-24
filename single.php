<!DOCTYPE HTML>
<!--
	Future Imperfect by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php
include "db.php";

if (!isset($_GET["post"]) && !isset($_GET['delete']))
  echo "<script>alert('Ошибка открытия поста. Возвращаемся обратно'); location.href='index.php';</script>";

if (isset($_GET['delete'])) {
  if ($user['role'] == 1) {
    $id = $_GET['delete'];
    $img = $db->query("SELECT img FROM posts WHERE id=$id")->fetchAll()[0][0];
    unlink($img);

    $db->query("DELETE FROM posts WHERE id=$id");
    $db->query("DELETE FROM comments WHERE post_id=$id");
    $db->query("DELETE FROM likes WHERE post_id=$id");

    echo "<script>alert('Успешно удалено!');  </script>";
  }
}

$post = $db->query("SELECT * FROM posts WHERE id=$_GET[post]")->fetchAll()[0];

$id = $post['author_id'];
$post['author_name'] = $db->query("SELECT login FROM users WHERE id=$id")->fetchAll()[0][0];

if ($_POST) {
  $comment = htmlspecialchars($_POST['comment']);
  $res = "";

  if (empty($user)) {
    $res = "Вы не можете оставлять комментарии пока не авторизуетесь!";
  } else {
    if ($query = $db->query("INSERT INTO comments SET comment='$comment', post_id=$post[id], author_id=$user[id]")) {
      $res = "Комментарий успешно добавлен!";
    } else {
      $res = "Ошибка!\n" . $db->errorInfo()[2];
    }
  }

  echo "<script>alert(`$res`)</script>";
}

$comments = $db->query("SELECT * FROM comments WHERE post_id=$post[id]")->fetchAll();

?>
<html>
<head>
  <title>Single - Future Imperfect by HTML5 UP</title>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <!--[if lte IE 8]>
  <script src="assets/js/ie/html5shiv.js"></script><![endif]-->
  <link rel="stylesheet" href="assets/css/main.css"/>
  <!--[if lte IE 9]>
  <link rel="stylesheet" href="assets/css/ie9.css"/><![endif]-->
  <!--[if lte IE 8]>
  <link rel="stylesheet" href="assets/css/ie8.css"/><![endif]-->
</head>
<body class="single">

<!-- Wrapper -->
<div id="wrapper">

  <!-- Header -->
  <header id="header">
    <h1><a href="index.php">Blog</a></h1>
    <nav class="main">
      <ul>
        <li class="menu user">
          <a href="#menu"><img src="images/avatar.jpg"></a>
        </li>
      </ul>
    </nav>
  </header>

  <!-- Main -->
  <div id="main">

    <!-- Post -->
    <article class="post">
      <header>
        <div class="title">
          <h2><a href="#"><?= $post['title'] ?></a></h2>
          <p><?= $post['subtitle'] ?></p>
        </div>
        <div class="meta">
          <time class="published" datetime="<?= $post['created_at'] ?>"><?= $post['created_at'] ?></time>
          <a href="#" class="author"><span class="name"><?= $post['author_name'] ?></span><img src="images/avatar.jpg"
                                                                                               alt=""/></a>
        </div>
      </header>
      <span class="image featured"><img src="<?= $post['img'] ?>" alt="post"/></span>
      <p><?= $post['text'] ?></p>
      <footer>
        <ul class="stats">

          <li><a href="#">Edit</a></li>
          <?php if (!empty($user) && $user['role'] == 1): ?>
          <li><a href="?delete=<?= $post['id'] ?>" class="red">Delete</a></li>
          <?php endif; ?>
          <li><a href="#" class="red">Blocked</a></li>

          <li><a href="#" class="icon fa-heart">0</a></li>
          <li><a href="#" class="icon fa-comment">0</a></li>
        </ul>
      </footer>
    </article>

    <!-- Comments -->
    <div class="post">
      <section class="comments">
        <h3>Comments</h3>
        <form action="" method="post">
          <textarea required name="comment"></textarea><br>
          <input type="submit" class="button big fit" value="Add Comment">
        </form>
      </section>

      <? foreach ($comments as $com): ?>
        <article class="comment">
          <div class="comment-autor">
            <a href="#"><img src="images/avatar.jpg"></a>
            <a href="#"><?php
              $user = $db->query("SELECT login FROM users WHERE id=$com[author_id]");
              echo $user->fetchAll()[0][0];
              ?></a>
          </div>
          <p><?= $com['comment']; ?></p>
        </article>
      <?php endforeach; ?>

    </div>

  </div>

  <!-- Footer -->
  <section id="footer">
    <p class="copyright">&copy; Untitled. Design: <a href="http://html5up.net">HTML5 UP</a>.</p>
  </section>

</div>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/skel.min.js"></script>
<script src="assets/js/util.js"></script>
<!--[if lte IE 8]>
<script src="assets/js/ie/respond.min.js"></script><![endif]-->
<script src="assets/js/main.js"></script>

</body>
</html>
