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
                <ul class="nav navbar-nav navbar-left">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">How to
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <p style="width: 900px;height: 500px; word-break: break-all;">
                                もらう人(taker)<br>
                                <img src="img_us/01howto.png" style="width: 290px;height: 200px;">
                                <img src="img_us/02howto.png" style="width: 290px;height: 200px;">
                                <img src="img_us/03howto.png" style="width: 290px;height: 180px;"><br>
                                <br>
                                あげる人(giver)<br><br>
                                <img src="img_us/04howto.png" style="width: 290px;height: 180px;">
                                <img src="img_us/05howto.png" style="width: 290px;height: 180px;">
                                <img src="img_us/06howto.png" style="width: 200px;height: 180px;"><br>
                                期日までにものを渡そう<br>
                            </p>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <span hidden id="signin-user"></span>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="user_profile_img/<?php echo $signin_user['img_name']; ?>" width="18" class="img-circle"><?php echo $signin_user['name']; ?>
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
</body>
</html>