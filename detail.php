<?php
session_start();
require('dbconnect.php');

if(!isset($_SESSION['GoodsBye']['id'])){
   header('Location:signin.php');
   exit();
}

$item_id = $_GET['item_id'];

$sql = 'SELECT * FROM `items` WHERE `id` = ?';
//選択されたユーザーって⇒パラメーターに与えられたuser_idから導き出せるぜ！
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
    }
}

if (empty($errors)) {

    $sql='INSERT INTO`comments`(`comment`,`user_id`,`item_id`,`created`)VALUES(?,?,?,NOW());';
    $data= [$comment,$signin_user['id'],$item_id];
    $stmt=$dbh->prepare($sql);
    $stmt->execute($data);
}

$sql = 'SELECT `c`.*, `u`.`name`,`u`.`img_name` FROM `comments` AS `c` LEFT JOIN `users` AS `u` ON `c`.`user_id` = `u`.`id`';
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


?>
<head>
    <meta charset="utf-8">
    <title>GoodsBye</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>


<?php include('navbar.php'); ?>

<body style="margin-top:150px">
    <h1 class="text-center content_header">GoodsBye</h1>
        <div class="col-xs-8 col-xs-offset-2 thumbnail">
            <img class="center-block " src="user_profile_img/<?php echo $detail['item_img'];?>">
        </div>
        <div class="col-xs-8 col-xs-offset-2 thumbnail">
            <p><?php echo $detail['content']?></p>
        </div>
    <div class="container">
    </div>
</body>

<form action="" method="post"　class="center-block thumbnail">
    <textarea name="comment" class="center-block" style="width:66.5%;height:60px" placeholder="コメントを入力してください(Please comment)" cols="80" rows="4"></textarea>
    <div class="form-group center-block">
        <button type="submit" class="btn btn-sm btn-primary center-block" style="margin-top: 10px">返信する</button>
    </div>
</form>


<?php foreach($contents as $content): ?>
    <div class="col-xs-6 col-xs-offset-3 thumbnail" style="margin-top:10px" >
        <p style="margin-top: 10px; margin-bottom: 10px">
            <img src="user_profile_img/<?php echo $content['img_name']; ?>" width="40" class="img-circle">
            <span style="border-radius: 100px!important; -webkit-appearance:none;background-color:#eff1f3;padding:10px;margin-top:10px;">
                <a href="profile.php"><?php echo $content['name']; ?></a>
                    <?php echo $content['comment']; ?>
            </span>
        </p>
    </div>
<?php endforeach; ?>


<?php include('layouts/footer.php'); ?>
</html>
