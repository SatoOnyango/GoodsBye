<?php
require('dbconnect.php');
 if (!isset($_GET['item_id'])) {
    header('Location: detail.php');
    exit();
}
if (isset($_GET['unsold'])) {
  $sql = 'UPDATE `items` SET `done_flag` = 0 WHERE `id` = ?';
}else{
  $sql = 'UPDATE `items` SET `done_flag` = 1 WHERE `id` = ?';
}
$data = [$_GET['item_id']];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
 header('Location: detail.php?item_id=' . $_GET['item_id']);
exit();
 ?>