<!DOCTYPE HTML>
<!--
	Future Imperfect by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php

include "db.php";

if (empty($user)) {
  echo "<script>
    alert(`У вас недостаточно прав для просмотра этой страницы!`);
    location.href = 'index.php';
  </script>";
}

if ($_POST) {
  $title = htmlspecialchars($_POST['title']);
  $subtitle = htmlspecialchars($_POST['subtitle']);
  $resume = htmlspecialchars($_POST['anons']);
  $text = htmlspecialchars($_POST['content']);

  $img = $_FILES['file'];
  $name = 'images/' . md5(time()) . '.' . explode("/", $img['type'])[1];

  move_uploaded_file($img['tmp_name'], $name);

  if ($query = $db->query("INSERT INTO posts SET title='$title', subtitle='$subtitle',
                          resume='$resume', text='$text', created_at=CURDATE(),
                          img='$name', author_id=$user[id]")) {
    $id = $db->lastInsertId();

    $db->query("INSERT INTO likes SET post_id=$id, amount=0");

    echo "<script>alert('Успешно добавлено!')</script>";
  }
  else {
    echo "<script>alert(`" . $db->errorInfo()[2] . "`)</script>";
  }
}

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

  <!-- Menu -->
  <section id="menu">

    <!-- Links -->
    <section>
      <ul class="links">
        <li>
          <a href="#">
            <h3>Add Post</h3>
          </a>
        </li>
        <li>
          <a href="#"><h3>Log Out</h3></a>
        </li>
      </ul>
    </section>

  </section>

  <!-- Main -->
  <div id="main">

    <!-- Post -->
    <article class="post">
      <h1>Add Post</h1>
      <form action="" method="post" enctype="multipart/form-data">
        <input required type="text" name="title" placeholder="Post name"><br>
        <input required type="text" name="subtitle" placeholder="Subtitle"><br>
        <input required type="text" name="anons" placeholder="Anons"><br>
        <textarea required name="content" placeholder="Post content"></textarea><br>
        <input required type="file" accept="image/*" name="file"><br><br>
        <input type="submit" class="button big fit" value="Add Post">
      </form>
    </article>

  </div>

  <!-- Footer -->
  <section id="footer">
    <p class="copyright">&copy; Blog. Design: <a href="http://html5up.net">HTML5 UP</a>.</p>
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
