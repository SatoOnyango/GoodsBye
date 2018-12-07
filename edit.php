<?php
session_start();
require('dbconnect.php');

// echo '<pre>';
// var_dump($_SESSION);
// echo '</pre>';


$sql = 'SELECT * FROM `items` WHERE `id` = ?';
$data = [$_SESSION['GoodsBye']['id']];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
$signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

// // echo '<pre>';
// // var_dump($_GET['feed_id']);
// // echo '</pre>';

if(isset($_GET['item_id'])){
//     // 1. GETパラメーターを定義
    $item_id = $_GET['item_id'];
//     // 2. SQL文定義
    $sql = 'SELECT `i`.*, `u`.`name` 
    FROM `items` AS `i` LEFT JOIN `users` AS `u` 
    ON `i`.`user_id` = `u`.`id` WHERE `i`.`id`= ?';

    $data = [$item_id];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

//     // echo '<pre>';
//     // var_dump($item);
//     // echo '</pre>';
}

if(!empty($_POST)){
// // 2. SQL文

    $sql = 'UPDATE `feeds` SET `feed`= ? WHERE `id` = ?';
    //POST送信されているので、
    $data = [$_POST['feed'],$_POST['feed_id']];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    // 3. timeline.phpへ遷移
    //もしmypageから来ていたら？mypageに返した方がよくない？
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
                        Updated/<?php echo $content['updated'];?><br>
                    <img src="user_profile_img/<?php echo $content['item_img'];?>" width="500" style="padding-left: auto;padding-right: auto;"><br>

                        <div class="content_form thumbnail" style="font-size: 24px;text-align: center border 100px;padding-left: auto;padding-right: auto;width: 500.988636px;height: 109.988636px;">
                            <textarea name="content" class="form-control" placeholder="Edit your comment agout your item" style="height: 68.988636px;"><?php echo $content['content']?></textarea>
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