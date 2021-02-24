<?php

include "db.php";

if (empty($user) || $user['role'] != 1) {
  echo "<script>
    alert('У вас недостаточно прав для просмотра этой страницы!');
    location.href = 'index.php';
  </script>";
}

if ($_POST) {
  if (isset($_POST['logo'])) {
    $img = $_FILES['file'];

    $name = md5(rand()) . '.' . explode('/', $img['type'])[1];
    move_uploaded_file($img['tmp_name'], 'images/' . $name);

    $res = "";
    if ($db->query("UPDATE settings SET logo='images/$name' Where id=1")) {
      $res = "Успешно обновлено!";
    } else {
      $res = $db->errorInfo()[2];
    }

    echo "<script>alert(`$res`)</script>";
  }
  else {
    $val = $_POST['amount'];
    $res = "";

    if ($db->query("UPDATE settings SET pages=$val where id=1")) {
      $res = "Успешно изменено!";
    } else {
      $res = $db->errorInfo()[2];
    }

    echo "<script>alert(`$res`)</script>";
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
      <h1>Обновить логотип</h1>
      <form action="" method="post" enctype="multipart/form-data">
        <input type="file" accept="image/*" name="file"><br><br>
        <button name="logo" class="button big">Обновить!</button>
      </form>
    </article>
    <!-- Post -->
    <article class="post">
      <h1>Изменить пагинацию</h1>
      <form action="" method="post">
        <input type="number" name="amount"><br><br>
        <button name="page" class="button big">Обновить!</button>
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
