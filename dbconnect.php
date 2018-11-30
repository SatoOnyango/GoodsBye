<?php
$dsn = 'mysql:dbname=GoodsBye;host=localhost';
$user = 'root';
$password='';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->query('SET NAMES utf8');