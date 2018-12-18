<?php
$dsn = 'mysql:dbname=GoodsBye;host=localhost';
$user = 'root';
$password='';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->query('SET NAMES utf8');


// <?php
// $dsn = 'mysql:dbname=goodsbye_test;host=mysql1010.db.sakura.ne.jp';
// $user = 'goodsbye';
// $password='batch-47';
// $dbh = new PDO($dsn, $user, $password);
// $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $dbh->query('SET NAMES utf8');