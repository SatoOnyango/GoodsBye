<?php
require('dbconnect.php');
// 1. どの投稿を削除するか
$item_id = $_GET['item_id'];

// echo '<pre>';
// var_dump($item_id);
// echo '</pre>';
// die();

// 2. delete処理
$sql = 'DELETE FROM `items` WHERE `id`=?';
//WHERE文がないとitems文が全て消えてしまう。必ずWHERE文でカラムを指定する。
$data = [$item_id];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

// 3. timeline.phpに遷移

header("Location: mypage.php");
exit();