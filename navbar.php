    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Learn SNS</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse1">
                <ul class="nav navbar-nav">
                    <?php if(strpos($_SERVER['REQUEST_URI'], 'timeline.php')!==false): ?>
                    <li class="active"><a href="timeline.php">タイムライン</a></li>
                    <li><a href="users.php">ユーザー一覧</a></li>
                    <?php else: ?>
                    <li><a href="timeline.php">タイムライン</a></li>
                    <li class="active"><a href="users.php">ユーザー一覧</a></li>
                    <?php endif; ?>
                </ul>
                <form method="GET" action="timeline.php" class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                        <input type="text" name="search_word" class="form-control" placeholder="投稿を検索" value="">
                    </div>
                    <button type="submit" class="btn btn-default">検索</button>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <span hidden id="signin-user"></span>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="user_profile_img/<?php echo $signin_user['img_name']; ?>" width="18" class="img-circle">
                            <!-- <?php //echo $signin_user['name']; ?><span class="caret"></span> -->
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="profile.php?user_id=<?php echo $signin_user['id']; ?>">マイページ</a></li>
                            <li><a href="signout.php">サインアウト</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>