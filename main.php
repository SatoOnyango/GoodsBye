<?php
session_start();
require('dbconnect.php');

//ログインしてない状態でアクセス禁止
if(!isset($_SESSION['GoodsBye']['id'])){
   header('Location:signin.php');
   exit();
}

//サインインユーザーの読み出し
$sql='SELECT *FROM`users`WHERE`id`=?';
$data=[$_SESSION['GoodsBye']['id']];
$stmt=$dbh->prepare($sql);
$stmt->execute($data);
$signin_user=$stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];
$content = '';

//アイテム投稿
if (!empty($_POST)){
    // 商品説明があるか
    $content =$_POST['content'];
    if ($content == '') {
        $errors['content'] = 'blank';
    }
    $file_name = '';
    // 写真が選択されてか
    if (isset($_FILES['input_img_name'])) {
        $file_name = $_FILES['input_img_name']['name'];
    }
    // echo'<pre>';
    // var_dump($content);
    // echo'</pre>';
    // echo'<pre>';
    // var_dump($file_name);
    // echo'</pre>';
    if (!empty($file_name)) {
        $file_type = substr($file_name, -3);
        $file_type = strtolower($file_type);
        // 3. jpg,png,gifと比較し、当てはまらない場合$errors['img_name']に格納
        if ($file_type != 'png' && $file_type != 'jpg' && $file_type != 'gif') {
            $errors['input_img_name'] = 'type';
        }
    } else {
        $errors['input_img_name'] = 'blank';
    }
    //アイテム投稿時エラーがなければデータベースに登録する
    if (empty($errors)) {
        $date_str = date('YmdHis');
        $submit_file_name = $date_str . $file_name;
        move_uploaded_file($_FILES['input_img_name']['tmp_name'],'user_profile_img/' . $submit_file_name);
        $file_name=$submit_file_name;

        $sql='INSERT INTO`items`(`content`,`item_img`,`user_id`,`created`)VALUES(?,?,?,NOW());';
        $data= [$content,$file_name,$signin_user['id']];
        $stmt=$dbh->prepare($sql);
        $stmt->execute($data);
// die();
        header('Location:main.php');
    }
}
// echo'<pre>';
// var_dump($content);
// echo'</pre>';
// echo'<pre>';
// var_dump($file_name);
// echo'</pre>';

//アイテム投稿情報(ユーザー情報含む)をすべて取得
$sql = 'SELECT `i`.*, `u`.`name` FROM `items` AS `i` LEFT JOIN `users` AS `u` ON `i`.`user_id` = `u`.`id`';
$data = [];
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


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GoodsBye</title>
    <!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <div class="row">
            <div>
                <h1></h1><br>
            </div>
            <div class="gallery col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1 class="goodsbye-title">GoodsBye</h1>
            </div>

                <?php foreach($contents as $content): ?>
            <!-- １行目×３グッズ row1 -->
                <div class="row">
                    <!-- TH1 -->
                    <div class="col-sm-4">
                        <div class="thumbnail">
                          <a href="detail.php?item_id=<?php echo$content['id'];?>" class="">
                            <div class="caption">

                                <p class=""></p>
                            </div>
                            <img src="user_profile_img/<?php echo $content['item_img'];?>" alt="..." class="thumbnail">
                           </a>
                           <?php if($signin_user['id']==$content['user_id']):?>
                                    <a href="edit.php?item_id2=<?php echo$content['id'];?>" class="btn btn-success btn-xs">編集</a>
                                    <a onclick="return confirm('ほんとに消すの？');" href="delete.php" class="btn btn-danger btn-xs">削除</a>
                                    <!-- get送信時はURL?(キー＝値)=パラメーター -->
                           <?php endif;?>
    <!--                   <?php 
                           // echo'<pre>';
                           // var_dump($signin_user['id']);
                           // echo'</pre>';

                           // echo'<pre>';
                           // var_dump($content['user_id']);
                           // echo'</pre>';
                           ?> -->
                        </div>
                    </div>

                    <!-- TH2 -->
                    <div class="col-sm-4">
                        <div class="thumbnail">
                          <a href="detail.php?item_id=<?php echo$content['id'];?>" class="">
                            <div class="caption">
                                <p class=""></p>
                            </div>
                            <img src="user_profile_img/<?php echo $content['item_img'];?>" alt="..." class="thumbnail">
                           </a>
                           <?php if($signin_user['id']==$content['user_id']):?>
                                    <a href="edit.php?item_id2=<?php echo$content['id'];?>" class="btn btn-success btn-xs">編集</a>
                                    <a onclick="return confirm('ほんとに消すの？');" href="delete.php" class="btn btn-danger btn-xs">削除</a>
                           <?php endif;?>
                        </div>
                    </div>

                    <!-- TH3 -->
                    <div class="col-sm-4">
                        <div class="thumbnail">
                          <a href="detail.php?item_id=<?php echo $content['id'];?>" class="">
                            <div class="caption">
                                <p class=""></p>
                            </div>
                            <img src="user_profile_img/<?php echo $content['item_img'];?>" alt="..." class="thumbnail">
                           </a>
                           <?php if($signin_user['id']==$content['user_id']):?>
                                    <a href="edit.php?item_id2=<?php echo $content['id'];?>" class="btn btn-success btn-xs">編集</a>
                                    <a onclick="return confirm('ほんとに消すの？');" href="delete.php" class="btn btn-danger btn-xs">削除</a>
                           <?php endif;?>
                        </div>
                    </div>
                </div>
            <!-- end/row1 -->

        <!-- ２行目×３グッズ row2 -->
                <div class="row">
                    <!-- TH4 -->
                    <div class="col-sm-4">
                        <div class="thumbnail">
                          <a href="detail.php?item_id=<?php echo $content['id'];?>" class="">
                            <div class="caption">
                                <p class=""></p>
                            </div>
                            <img src="user_profile_img/petbotles.jpeg" alt="..." class="thumbnail">
                          </a>
                        </div>
                    </div>

                    <!-- TH5 -->
                    <div class="col-sm-4">
                        <div class="thumbnail">
                          <a href="detail.php?item_id=<?php echo$content['id'];?>" class="">
                            <div class="caption">
                                <p class=""></p>
                            </div>
                            <img src="user_profile_img/petbotles.jpeg" alt="..." class="thumbnail">
                          </a>
                        </div>
                    </div> 

                    <!-- TH6 -->
                    <div class="col-sm-4">
                        <div class="thumbnail">
                          <a href="detail.php?item_id=<?php echo$content['id'];?>" class="">
                            <div class="caption">
                                <p class=""></p>
                            </div>
                            <img src="user_profile_img/petbotles.jpeg" alt="..." class="thumbnail">
                          </a>
                        </div>
                    </div>
                </div>
            <!-- end/row2 -->
                <?php endforeach; ?>

        <!-- 投稿エリア -->
        <section id="post" name="post">
            <div class="container">
                <div class="row">
                        <div class="post">
                            <div class="content_form thumbnail">

                                <form method="POST" action="main.php" enctype="multipart/form-data">
                                    <!-- 商品説明が入力されているか -->
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <textarea name="feed" class="form-control" rows="2" placeholder="Your Comment Here" style="font-size: 24px; text-align: center;"></textarea><br>
                                        <?php if (isset($errors['feed'])&& $errors
                                        ['feed'] == 'blank'):?>
                                            <p class="text-danger">文字を入力してください/ Can't be blank</p>
                                        <?php endif; ?>
                                    </div>
                                    <!-- 写真が選択されているか -->
                                    <div class="form-group">
                                        <label for="img_name">Your Goods Image</label>
                                        <input type="file" name="input_img_name" id="img_name" accept="image/*">
                                        <?php if(isset($errors['input_img_name']) && $errors['input_img_name'] == 'blank'): ?>
                                            <p class="text-danger">写真を選択してください/ Please choose item image</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <?php if(isset($errors['input_img_name']) && $errors['input_img_name'] == 'type'): ?>
                                            <p class="text-danger">拡張子が違います/ Wrong file extension</p>
                                        <?php endif; ?>
                                    </div>
                                    <input type="submit" value="POST (投稿する)" class="btn btn-primary">
                                </form>

                            </div>
                    </div>
                </div> <!-- /row -->
            </div> <!-- /container -->
        </section>
        <!-- /投稿エリア -->
        </div><!--/row -->
    </div> <!-- end container -->
    
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</body>
</html>