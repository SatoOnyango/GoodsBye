<?php
session_start();

//1. セッションを空にする
$_SESSION = [];
session_destroy();


//2. サインイン画面に遷移

header("Location: signin.php");
exit();
