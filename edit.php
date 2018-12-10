<?php
session_start();
require('dbconnect.php');

// echo '<pre>';
// var_dump($_SESSION);
// echo '</pre>';

$sql = 'SELECT * FROM `users` WHERE `id` = ?';
$data = [$_SESSION['GoodsBye']['id']];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
$signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];
$content = '';
//マイページから遷移した場合
if(isset($_GET['item_id'])){
    $item_id = $_GET['item_id'];

    //編集したい投稿アイテムの読み出し
    $sql = 'SELECT `i`.*, `u`.`name` 
    FROM `items` AS `i` LEFT JOIN `users` AS `u` 
    ON `i`.`user_id` = `u`.`id` WHERE `i`.`id`= ?';

    $data = [$item_id];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($_POST)){
        $content =$_POST['content'];
        if ($content == '') {
        $errors['content'] = 'blank';
        }
        if(empty($errors)){
            //更新ボタンを押すとupdate
            $sql = 'UPDATE `items` SET `content`= ? WHERE `id` = ?';
            $data = [$_POST['content'],$_POST['item_id']];
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);

            //マイページから遷移してるのでマイページへ返す
            header('Location: mypage.php');
            exit();
        }else{
            header('Location: edit.php');
            exit();
        }
    }
//メインから遷移した場合
}elseif(isset($_GET['item_id2'])){
    $item_id = $_GET['item_id2'];

    //編集したい投稿アイテムの読み出し
    $sql = 'SELECT `i`.*, `u`.`name` 
    FROM `items` AS `i` LEFT JOIN `users` AS `u` 
    ON `i`.`user_id` = `u`.`id` WHERE `i`.`id`= ?';

    $data = [$item_id];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($_POST)){
        $content =$_POST['content'];
        if ($content == '') {
        $errors['content'] = 'blank';
        }
        if(empty($errors)){
            //更新ボタンを押すとupdate
            $sql = 'UPDATE `items` SET `content`= ? WHERE `id` = ?';
            $data = [$_POST['content'],$_POST['item_id']];
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);

        //メインから遷移してるのでメインへ返す
        header('Location: main.php');
        exit();
        }else{
            header('Location: edit.php');
            exit();
        }
    }
}else{
    //直接editへ来たらmainへ返す
   header('Location: main.php');
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
                        Updated/<?php echo $item['updated'];?><br>
                    <img src="user_profile_img/<?php echo $item['item_img'];?>" width="500" style="padding-left: auto;padding-right: auto;"><br>

                        <div class="content_form thumbnail" style="font-size: 24px;text-align: center border 100px;padding-left: auto;padding-right: auto;width: 500.988636px;height: 109.988636px;">
                            <textarea name="content" class="form-control" placeholder="Edit your comment agout your item" style="height: 68.988636px;"><?php echo $item['content']?></textarea>
                            <?php if (isset($errors['content'])&& $errors
                                ['content'] == 'blank'):?>
                                <p class="text-danger">文字を入力してください/ Can't be blank</p>
                            <?php endif; ?>
                            <input type = "hidden" name = "item_id" value = "<?php echo $item['id']; ?>">
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