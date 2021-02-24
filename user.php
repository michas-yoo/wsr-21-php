<!DOCTYPE HTML>
<!--
	Future Imperfect by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php

include "db.php";

$posts = $db->query("SELECT * FROM posts WHERE author_id=$_GET[id]")->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head>
  <title>Future Imperfect by HTML5 UP</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
  <link rel="stylesheet" href="assets/css/main.css" />
  <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
  <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
</head>
<body>

<!-- Wrapper -->
<div id="wrapper">

  <!-- Header -->
  <header id="header">
    <h1><a href="index.php">Blog</a></h1>
    <nav class="main">
      <ul>
        <li class="menu">
          <a class="fa-user" href="#menu">Menu</a>
        </li>
      </ul>
    </nav>
  </header>

  <!-- Menu -->
  <section id="menu">

    <!-- Actions -->
    <section>
      <ul class="actions vertical">
        <li><h3>Login</h3></li>
        <li>
          <form action="?" method="post">
            <input type="text" name="neme" placeholder="Username"><br>
            <input type="password" name="neme" placeholder="Password"><br>
            <input type="submit" class="button big fit" value="Log In">
          </form>
        </li>

        <li><h3>Registration</h3></li>
        <li>
          <form action="?" method="post">
            <input type="text" name="neme" placeholder="Username"><br>
            <input type="password" name="neme" placeholder="Password"><br>
            <input type="password" name="neme" placeholder="Confirm password"><br>
            <input type="file" name="file"><br><br>
            <input type="submit" class="button big fit" value="Sign up">
          </form>
        </li>
      </ul>
    </section>

  </section>

  <!-- Main -->
  <div id="main">

    <?php foreach ($posts as $post): ?>

      <?php
      $id = $post['id'];
      $a_id = $post['author_id'];

      $author_name = $db->query("SELECT login FROM users WHERE id=$a_id")->fetchAll()[0][0];

      $comments_count = $db->query("SELECT count(*) FROM comments WHERE post_id=$id")->fetchAll()[0][0];
      $likes_count = $db->query("SELECT amount FROM likes WHERE post_id=$id")->fetchAll()[0][0];
      ?>
      <article class="post">
        <header>
          <div class="title">
            <h2><a href="single.php?post=<?= $id; ?>"><?php echo $post['title'] ?></a></h2>
            <p><?= $post['subtitle'] ?></p>
          </div>
          <div class="meta">
            <time class="published" datetime="<?= $post['created_at'] ?>"><?= $post['created_at'] ?></time>
            <a href="user.php?id=<?= $post['author_id']; ?>" class="author"><span class="name"><?= $author_name ?></span><img src="images/avatar.jpg"
                                                                                                                              alt=""/></a>
          </div>
        </header>
        <a href="single.php?post=<?= $id; ?>" class="image featured"><img src="<?= $post['img'] ?>" alt=""/></a>
        <p><?= $post['resume'] ?></p>
        <footer>
          <ul class="actions">
            <li><a href="single.php?post=<?= $id; ?>" class="button big">Continue Reading</a></li>
          </ul>
          <ul class="stats">
            <li><a href="?like=<?= $id; ?>" class="icon fa-heart<?php
              if (isset(unserialize($_COOKIE['likes'])[$id]))
                echo " liked"; ?>"><?= $likes_count ?></a></li>

            <li><a href="#" class="icon fa-comment"><?= $comments_count ?></a></li>
          </ul>
        </footer>
      </article>
    <?php endforeach; ?>

    <!-- Pagination -->
    <ul class="actions pagination">
      <li><a href="" class="disabled button big previous">Previous Page</a></li>
      <li><a href="#" class="button big next">Next Page</a></li>
    </ul>

  </div>

</div>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/skel.min.js"></script>
<script src="assets/js/util.js"></script>
<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
<script src="assets/js/main.js"></script>

</body>
</html>
