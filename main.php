<?php
session_start();
require('dbconnect.php');

   $errors = [];

if (isset($_GET['action']) && $_GET ['action'] == 'rewrite'){
    $_POST['input_content'] = $_SESSION['GoodsBye']['content'];
    $_POST['input_item_img'] = $_SESSION['GoodsBye']['item_img'];

    $errors['rewrite'] = true;

}


$content = '';

$item_img = '';

if (!empty($_POST)) {
    
$content = $_POST['input_content'];

$item_img = $_POST['input_item_img'];

if ($content == '') {
    $errors['content'] = 'blank';
}

if ($item_img == '') {
    $errors['item_img'] = 'blank';
}

        $file_name = '';

       if (!isset($_GET['action'])) {

        $file_name = $_FILES['input_img_name']['name'];
    }
        if (!empty($file_name)) {

            $file_type = substr($file_name,-3);


            $file_type = strtolower($file_type);


           if ($file_type != 'png' && $file_type != 'jpg'&& $file_type !='gif'&& $file_type != 'jpeg') {$errors['img_name'] = 'type';

            }

        if (empty($errors)) {
        echo "入力不備なし！<br>";
         

}
}

        $date_str = date('YmdHis');
        $submit_file_name = $date_str . $file_name;


        move_uploaded_file($_FILES['input_img_name']['tmp_name'],'../user_profile_img/' . $submit_file_name);


        $_SESSION['GoodsBye']['content'] = $content;
        $_SESSION['GoodsBye']['item_img'] = $item_img;


        header('Location: main.php');
        exit();


        




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

            <!-- １行目×３グッズ row1 -->
            <div class="row">
                <!-- TH1 -->
                <div class="col-sm-4">
                    <div class="thumbnail">
                      <a href="detail.php?item_id=1" class="">
                        <div class="caption">

                            <p class=""></p>
                        </div>
                        <img src="user_profile_img/default.png" alt="..." class="thumbnail">
                       </a>
                    </div>

                </div>
            </a>

                <!-- TH2 -->

                <div class="col-sm-4">
                    <div class="thumbnail">
                      <a href="detail.php?item_id=2" class="">
                        <div class="caption">

                            <p class=""></p>
                        </div>
                        <img src="user_profile_img/petbotles.jpeg" alt="..." class="thumbnail">
                        </p>
                    </div>
                </div>


                <!-- TH3 -->
                <div class="col-sm-4">
                    <div class="thumbnail">
                        <a href="detail.php?item_id=3" class="">
                        <div class="caption">

                            <p class=""></p>
                        </div>
                        <img src="user_profile_img/test_signin_user_img.jpg" alt="..." class="thumbnail ">
                        </a>
                    </div>
                </div>
            </div>
            <!-- end/row1 -->

        <!-- ２行目×３グッズ row2 -->
        <div class="row">
            <!-- TH4 -->
            <div class="col-sm-4">
                <div class="thumbnail">
                        <a href="detail.php?item_id=4" class="">
                    <div class="caption">

                        <p class=""></p>
                    </div>
                    <img src="http://placehold.it/350x250" alt="..." class="">
                    </a>
                </div>
            </div>

            <!-- TH5 -->
            <div class="col-sm-4">
                <div class="thumbnail">
                        <a href="detail.php?item_id=5" class="">
                    <div class="caption">

                        <p class="">
                        </p>
                    </div>
                    <img src="http://placehold.it/350x250" alt="..." class="">
                </a>
                </div>
            </div>


            <!-- TH6 -->
            <div class="col-sm-4">
                <div class="thumbnail">
                        <a href="detail.php?item_id=6" class="">
                    <div class="caption">

                        <p class="">
                        </p>
                    </div>
                    <img src="http://placehold.it/350x250" alt="..." class="">
                </a>
                </div>
            </div>
        </div>
        <!-- end/row2 -->

        <!-- 投稿エリア -->
        <section id="post" name="post">
            <div class="container">
                <div class="row">
                        <div class="post">
                            <div class="feed_form thumbnail">
                                <form method="POST" action="">
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <textarea name="feed" class="form-control" rows="2" placeholder="Your Comment Here" style="font-size: 24px; text-align: center;"></textarea><br>
                                    <?php if(isset($errors['comment_id']) && $errors['comment_id'] == 'blank'): ?>
                                        <p class="text-danger">写真を選択してください</p>         <?php endif; ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="img_name">Your Googs Image</label>
                                        <input type="file" name="input_img_name" id="img_name" accept="image/*">



                                    <?php if (isset($errors['comment/id'])&& $errors
                                    ['comment_id'] == 'blank'):?>
                                        <p class="text-danger">文字を入力してください</p>
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

        </div>
    </div>
    
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</body>
</html>

