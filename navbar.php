<!DOCTYPE html>
<html>
<head>
    <title>navbar</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="assets/css/navbar.css">
</head>
<body>
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="main.php">GoodsBye</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse1">
                <ul class="nav navbar-nav">
                    <li class="active"><a class="smothscroll" href="main.php#post" title="post">POST(投稿)</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <span hidden id="signin-user"></span>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="user_profile_img/test_signin_user_img.jpg" width="18" class="img-circle">しゅんたろう
                            <!-- <?php //echo $signin_user['img_name']; ?> -->
                            <!-- <?php //echo $signin_user['name']; ?> -->
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="mypage.php?user_id=<?php echo $signin_user['id']; ?>">My page</a></li>
                            <li><a href="signout.php">Sign out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php include('layouts/footer.php'); ?>
</body>
</html>