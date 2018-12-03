<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GoodsBye</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
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
                      <a href="#" class="">
                        <div class="caption">
                             <h4 class="">COMMENT</h4>
                            <p class="">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore</p>
                        </div>
                        <img src="user_profile_img/default.png" alt="..." class="">
                       </a>
                    </div>
                </div>

                <!-- TH2 -->
                <div class="col-sm-4">
                    <div class="thumbnail">
                      <a href="#" class="">
                        <div class="caption">
                             <h4 class="">COMMENT</h4>
                            <p class=""></p>
                        </div>
                        <img src="user_profile_img/petbotles.jpeg" alt="..." class="">
                        </a>
                    </div>
                </div>

                <!-- TH3 -->
                <div class="col-sm-4">
                    <div class="thumbnail">
                        <a href="#" class="">
                        <div class="caption">
                            <h4 class="">COMMENT</h4>
                            <p class=""></p>
                        </div>
                        <img src="user_profile_img/test_signin_user_img.jpg" alt="..." class="">
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
                    <div class="caption">
                        <h4 class="">COMMENT</h4>
                        <p class="">
                        <a href="#" class=""></a>
                        </p>
                    </div>
                    <img src="http://placehold.it/350x250" alt="..." class="">
                </div>
            </div>

            <!-- TH5 -->
            <div class="col-sm-4">
                <div class="thumbnail">
                    <div class="caption">
                        <h4 class="">COMMENT</h4>
                        <p class="">
                        <a href="#" class=""></a>
                        </p>
                    </div>
                    <img src="http://placehold.it/350x250" alt="..." class="">
                </div>
            </div>


            <!-- TH6 -->
            <div class="col-sm-4">
                <div class="thumbnail">
                    <div class="caption">
                        <h4 class="">COMMENT</h4>
                        <p class="">
                        <a href="#" class=""></a>
                        </p>
                    </div>
                    <img src="http://placehold.it/350x250" alt="..." class="">
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
                                    <?php if(isset($errors['feed']) && $errors['feed'] == 'blank'): ?>
                                        <p class="text-danger">投稿を入力してください</p>
                                    <?php endif; ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="img_name">Your Googs Image</label>
                                        <input type="file" name="input_img_name" id="img_name" style="margin-left: 250px" accept="image/*"><!-- accept="image/*"画像以外選択できない -->
                                        <?php if(isset($errors['img_name']) && $errors['img_name']== 'type'):?>
                                        <p class ="text-danger">拡張子が違います</p>
                                        <?php endif ;?>
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
</body>
</html>

