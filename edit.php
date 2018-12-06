 <?php
session_start();
require('dbconnect.php');

// echo '<pre>';
// var_dump($_SESSION);
// echo '</pre>';


$sql = 'SELECT * FROM `items` WHERE `id` = 3';
// $data = [$_SESSION['GoodsBye']['id']];
$stmt = $dbh->prepare($sql);
$stmt->execute();
$signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

// // echo '<pre>';
// // var_dump($_GET['feed_id']);
// // echo '</pre>';

// if(isset($_GET['feed_id'])){
//     // 1. GETパラメーターを定義
//     $feed_id = $_GET['feed_id'];
//     // 2. SQL文定義

    $sql = 'SELECT `i`.*, `u`.`name` 
    FROM `items` AS `i` LEFT JOIN `users` AS `u` 
    ON `i`.`user_id` = `u`.`id` WHERE `i`.`id`= 3';

    // $data = [$feed_id];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $feed = $stmt->fetch(PDO::FETCH_ASSOC);

//     // echo '<pre>';
//     // var_dump($feed);
//     // echo '</pre>';
// }

if(!empty($_POST)){
    //$sql = 'UPDATE SET `feeds`.`feed`= ? WHERE `feeds`. `id` = ?';
//     //              ↑ UPDATEの後には必ず`テーブル名`を書く
//     // ここでテーブル名を指定すれば、その後SETやWHEREで指定する必要がない
//     // "."ドットは基本的にSELECT文でしか使わない  （副問い合わせ？）
//     // INSERT,UPDATE,DELETE文は基本的に一つのテーブルにしか関わらない

// // 2. SQL文
    $sql = 'UPDATE `items` SET `feed`= ? WHERE `id` = 3';
    //POST送信されているので、
    $data = [$_POST['feed']];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    // die();
    header('Location: edit.php');
    exit();

}
 
 ?>
　<?php include('layouts/header.php'); ?>
 <body style="margin-top: 100px;">
     <?php include('navbar.php'); ?>
     <div class="container">
         <div class="row">
             <div class="col-xs-12 ">
                <form class="form-group" method="post" action="edit.php">
                    <div align="center">
                        Updated/<?php echo $feed['updated'];?><br>
                    <img src="user_profile_img/<?php echo $feed['item_img'];?>" width="500" style="padding-left: auto;padding-right: auto;"><br>

                        <div class="feed_form thumbnail" style="font-size: 24px;text-align: center border 100px;padding-left: auto;padding-right: auto;width: 500.988636px;height: 109.988636px;">
                            <textarea name="feed" class="form-control" placeholder="Edit your comment agout your item" style="height: 68.988636px;"><?php echo $feed['feed']?></textarea>
                            <input type="submit" value="Update(更新)" class="btn btn-warning ">
                        </div>
                    </div>
                </form>
             </div>
         </div>
    </div>
 </body>
<?php include('layouts/footer.php'); ?>
</html> 