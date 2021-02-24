<?php

$db = new PDO("mysql:host=localhost;dbname=second;charset=utf8", "root", "");

session_start();
$user = $_SESSION["user"][0] ?? [];
