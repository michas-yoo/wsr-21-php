<!DOCTYPE HTML>
<!--
	Future Imperfect by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php

include "db.php";

if ($_GET) {
  if (isset($_GET['like'])) {
    $id = $_GET['like'];
    $cookie = unserialize($_COOKIE['likes']) ?? [];

    if (!$cookie[$id]) {
      $db->query("UPDATE likes SET amount = amount + 1 WHERE post_id=$id");
      $cookie[$id] = true;
      setcookie('likes', serialize($cookie));
    }

    echo "<script>location.href = 'index.php'; </script>";
    die();
  }
}

if ($_POST) {
  $login = $_POST['name'] ?? "";
  $password = $_POST['password'] ?? "";

  if (isset($_POST['login'])) {
    if ($query = $db->query("SELECT * FROM users WHERE login='$login' and password='$password'")) {
      $user = $query->fetchAll(PDO::FETCH_ASSOC);

      if (count($user) > 0) {
        $_SESSION['user'] = $user;
        echo "<script>alert('Добро пожаловать!'); location.href = 'index.php';</script>";
      } else {
        echo "<script>alert('Неверный логин или пароль!')</script>";
      }
    } else {
      echo $db->errorInfo()[2];
    }
  } else if (isset($_POST['logout'])) {
    $user = [];
    $_SESSION['user'] = [];
  } else {
    if ($query = $db->query("INSERT INTO users SET login='$login', password='$password'")) {
      echo "<script>alert('Вы успешно зарегистрировались! Пожалуйста авторизуйтесь');</script>";
    }
  }
}

$settings = [];
if ($settings_query = $db->query("SELECT * FROM settings")) {
  $settings = $settings_query->fetchAll(PDO::FETCH_ASSOC)[0];
}

$current_page = $_GET['page'] ?? 1;
$offset = ($current_page - 1) * intval($settings['posts']);

$posts = [];
if ($posts_query = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT $settings[posts] OFFSET $offset")) {
  $posts = $posts_query->fetchAll(PDO::FETCH_ASSOC);
}
?>
<html>
<head>
  <title>Future Imperfect by HTML5 UP</title>
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
<body>

<!-- Wrapper -->
<div id="wrapper">

  <!-- Header -->
  <header id="header">
    <h1><a href="#">Blog</a></h1>

    <?php if (!empty($user)): ?>
      <a href="add.php">
        <button class="button big">Добавить пост!</button>
      </a>
    <?php endif; ?>

    <?php if (!empty($user) && $user['role'] == 1): ?>
      <a href="admin.php">
        <button class="button big">Панель администрирования</button>
      </a>
    <?php endif; ?>

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
        <?php if (empty($user)): ?>

          <li><h3>Login</h3></li>
          <li>
            <form action="" method="post">
              <input required type="text" name="name" placeholder="Username"><br>
              <input required type="password" name="password" placeholder="Password"><br>
              <input type="submit" name="login" class="button big fit" value="Log In">
            </form>
          </li>
          <li><h3>Registration</h3></li>
          <li>
            <form action="" method="post">
              <input required type="text" name="name" placeholder="Username"><br>
              <input required type="password" name="password" placeholder="Password"><br>
              <input type="file" name="file"><br><br>
              <input type="submit" name="register" class="button big fit" value="Sign up">
            </form>
          </li>

        <?php else: ?>

          <li>
            <form action="" method="post">
              <input type="submit" value="Выйти" class="button big fit" name="logout">
            </form>
          </li>

        <?php endif; ?>

      </ul>
    </section>

  </section>

  <!-- Main -->
  <div id="main">

    <!-- Post -->
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
      <li><a href="?page=<?= $current_page - 1 ?>"
             class="<?php if ($current_page <= 1) echo "disabled" ?> button big previous">Previous Page</a></li>
      <li><a href="?page=<?= $current_page + 1 ?>" class="button big next">Next Page</a></li>
    </ul>

  </div>

  <!-- Sidebar -->
  <section id="sidebar">

    <!-- Intro -->
    <section id="intro">
      <a href="#" class="logo"><img src="<?= $settings['logo']; ?>" alt=""/></a>
      <header>
        <h2>Blog</h2>
        <p>Be popular with us</p>
      </header>
    </section>

    <!-- Mini Posts -->
    <section>
      <h3>Popular posts</h3>
      <div class="mini-posts">

        <!-- Mini Post -->
        <article class="mini-post">
          <header>
            <h3><a href="#">Vitae sed condimentum</a></h3>
            <time class="published" datetime="2015-10-20">1 Ноября 2015</time>
            <a href="#" class="author"><img src="images/avatar.jpg" alt=""/></a>
          </header>
          <a href="#" class="image"><img src="images/pic04.jpg" alt=""/></a>
        </article>

      </div>
    </section>

    <!-- Posts List -->
    <section>

      <h3>Rating bloggers</h3>

      <ul class="posts">

        <?php
        $bloggers = $db->query("SELECT id, login FROM users");

        foreach ($bloggers as $blogger) {
          $blogger_posts = $db->query("SELECT * FROM posts WHERE author_id=$blogger[id]")->fetchAll(PDO::FETCH_ASSOC);

          $posts_count = count($blogger_posts);
          $likes = 0;

          foreach ($blogger_posts as $bp) {
            $likes += intval($db->query("SELECT amount FROM likes WHERE post_id=$bp[id]")->fetchAll()[0][0]);
          }

          ?>
          <li>
            <article>
              <header>
                <h3><a href="user.php?id=<?= $blogger['id'] ?>"><?= $blogger['login'] ?></a></h3>
                <span class="published"><?= $likes ?> likes in <?= $posts_count ?> posts</span>
              </header>
              <a href="#" class="image"><img src="images/pic08.jpg" alt=""/></a>
            </article>
          </li>
        <?php } ?>

      </ul>
    </section>

    <!-- Footer -->
    <section id="footer">
      <p class="copyright">&copy; Blog. Design: <a href="http://html5up.net">HTML5 UP</a>.</p>
    </section>

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
