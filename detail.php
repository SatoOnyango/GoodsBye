<?php
session_start();
require('dbconnect.php');

if(!isset($_SESSION['GoodsBye']['id'])){
   header('Location:signin.php');
   exit();
}


$item_id =$_GET['item_id'];

// echo '<pre>';
// var_dump($_GET['item_id']);
// echo '</pre>';


$sql = 'SELECT * FROM `items` WHERE `id` = ?';
$data = [$item_id];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
$detail = $stmt->fetch(PDO::FETCH_ASSOC);


$sql='SELECT *FROM`users`WHERE`id`=?';
$data=[$_SESSION['GoodsBye']['id']];
$stmt=$dbh->prepare($sql);
$stmt->execute($data);
$signin_user=$stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];
$comment = '';

if (!empty($_POST)){
    // 商品説明があるか
    $comment =$_POST['comment'];

    if ($comment == '') {
        $errors['comment'] = 'blank';
    }else{
        $sql='INSERT INTO`comments`(`comment`,`user_id`,`item_id`,`created`)VALUES(?,?,?,NOW());';
        $data= [$comment,$signin_user['id'],$item_id];
        $stmt=$dbh->prepare($sql);
        $stmt->execute($data);

        header('Location:detail.php?item_id='.$item_id);
    }
}

$sql = 'SELECT `c`.*, `u`.`name`,`u`.`img_name` FROM `comments` AS `c` LEFT JOIN `users` AS `u` ON `c`.`user_id` = `u`.`id` WHERE `c`.`item_id`=? ORDER BY `c`.`created` DESC';
$data = [$item_id];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
// 投稿情報全てを入れる配列定義
$contents=[];
while(true){
    $record= $stmt->fetch(PDO::FETCH_ASSOC);
    if($record==false){
    break;
    }
    $contents[] = $record;
}

$sql='SELECT `done_flag` FROM `items` WHERE `id`=?';
$data = [$item_id];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
$sold=$stmt->fetch(PDO::FETCH_ASSOC);
?>
<head>
    <meta charset="utf-8">
    <title>GoodsBye</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <link rel="stylesheet" href="detail.css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>


<?php include('layouts/header.php'); ?>


<body style="margin-top:65px">
    <?php include('navbar.php'); ?>
    <div class="container col-lg-auto col-md-auto col-sm-auto col-xs-auto">
        <div class="sidebar__item sidebar__item--fixed">
            <div class="col-xs-6 col-xs-offset-auto thumbnail">
                <img class="center-block " src="user_profile_img/<?php echo $detail['item_img'];?>" style="float: center; margin: center; max-width: auto; max-height:330px;">
                <p style="margin-top:15px" class="thumbnail"><?php echo $detail['content']?></p>
            <?php if($signin_user['id']==$detail['user_id']): ?>
                <?php if($sold['done_flag'] == 0): ?>
                    <div class="form-group center-block">
                    <a href="done.php?item_id=<?php echo $item_id; ?>">
                    <button type="submit" class="btn btn-sm btn-success center-block" style="margin-top: 10px">取引完了</button></a>
                    </div>
                <?php else: ?>
                    <div class="form-group center-block">
                    <a href="done.php?item_id=<?php echo $item_id; ?>& unsold=true">
                    <button type="submit" class="btn btn-sm btn-success center-block" style="margin-top: 10px">完了取り消し</button></a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            </div>
        </div>


            <form action="" method="post" style="float: right; margin: right; width: 509.988636px;height: auto;" class="center-block ">

                <textarea name="comment" class="center-block" style="width:66.5%;height:60px" placeholder="コメントを入力してください(Please comment)" cols="80" rows="4"></textarea>
                    <?php if (isset($errors['comment'])&& $errors
                        ['comment'] == 'blank'):?>
                        <p class="text-danger text-center">文字を入力してください/ Can't be blank</p>
                    <?php endif; ?>
                <?php if($sold['done_flag'] == 0): ?>
                <div class="form-group center-block">
                    <button type="submit" class="btn btn-sm btn-primary center-block" style="margin-top: 10px">返信する</button>
                </div>
                <?php endif; ?>

                <?php foreach($contents as $content): ?>
                    <div class="col-xs-auto col-xs-offset-auto thumbnail " style="margin: right ; margin-top:10px" >
                        <p style="margin-top: 10px; margin-bottom: 10px">
                            <img src="user_profile_img/<?php echo $content['img_name']; ?>" width="40" class="img-circle">
                            <span style="line-height:300%; word-break: break-all; border-radius: 100px!important; -webkit-appearance:none;background-color:#eff1f3;padding:10px;margin-top:10px;">
                                <?php echo $content['name']; ?>:
                                    <?php echo $content['comment']; ?>
                            </span>
                        </p>
                    </div>
                <?php endforeach; ?>
            </form>
    </div>
</body>


<?php include('layouts/footer.php'); ?>

</html>
