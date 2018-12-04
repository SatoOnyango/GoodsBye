<?php
// session_start();
// require('dbconnect.php');

// $sql = 'SELECT * FROM `users` WHERE `id` = ?';
// // $id = $_SESSION['47_LernSNS']['id'];
// // $data = $id;
// $data = [$_SESSION['GoodsBye']['id']];

// $stmt = $dbh->prepare($sql);
// $stmt->execute($data);

// //ログインしているユーザーの情報
// $signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

// //どんなデータが何型で入ってる？
// //野原ひろしの情報が配列型（連想配列）で入っている
// //SELECTでとってきたものは必ずこうなる
// // echo'<pre>';
// //var_dump($signin_user);
// // echo'</pre>';


// //ユーザーの一覧取得
// $sql = 'SELECT `name`,`img_name`,`created`,`id` FROM `users`';


// //今までは$data = [$email];
// //sqlの中に？がないので変数で指定する必要がないいから$dataは使わない

// $stmt = $dbh->prepare($sql);
// $stmt->execute();

// // 投稿情報全てを入れる配列定義
// $users = [];
// while(true){
//     $record = $stmt->fetch(PDO::FETCH_ASSOC);
//     //fetchは一つの行を取り出すこと
//     if($record == false){
//         break;
//     }
//     // echo '<pre>';
//     // var_dump($record);
//     // echo '</pre>';

//     $content_sql = 'SELECT COUNT(*) AS `cnt` FROM `items` WHERE `user_id` = ? ';
//     // `feeds`テーブルに何個ユーザーidがあるか、その数を数える
//     $content_data = [$record['id']];
//     $content_stmt = $dbh->prepare($content_sql);
//     $content_stmt->execute($content_data);

//     $content = $content_stmt->fetch(PDO::FETCH_ASSOC);
//     //コメント(comment)の数が入っている
//     $record['content_cnt'] = $content['cnt'];
//     $items[] = $record;
// }

// echo'<pre>';
// var_dump($users);
// echo'</pre>';

// echo $user['name'];
// echo $user[0]['name'];

?>

<?php include('layouts/header.php'); ?>
<body style="margin-top: 60px; margin-right: auto;left: auto; background: #E4E6EB;">
    <?php include("navbar.php"); ?>
    <div class="container">
        <div class="row text-center">
            <div class="col-xs-3 text-center" style ="width: 100%; height: 10%">
                しゅんたろう(サインインユーザーネーム)<br>
                <img src="user_profile_img/test_signin_user_img.jpg" class="img-thumbnail" style="text-align: center;max-width: 100%; max-height: 200px; height: auto; vertical-align: bottom;">
                <!-- <img src="user_profile_img/<?php //echo $profile_user['img_name']; ?>" class="img-thumbnail" /> -->
                <!-- <h2><?php //echo $profile_user['name']; ?></h2> -->
            </div>

            <div class="col-xs-12">
                <span class="comment_count">Numbers of your (アイテム数)：10</span>
                <hr>
            </div>


            <div class="row">
                <?php foreach($items as $content): ?>
                <!-- TH1 -->
                <div class="col-sm-4">
                    <div class="thumbnail">
                      <a href="#" class="">
                        <div class="caption">
                            feeded: 2018-12-02 <br>
                            <p class="">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore</p>
                        </div>
                        <img src="user_profile_img/default.png" alt="..." class="" style="max-width: 100%; max-height: 200px; height: auto; vertical-align: bottom; padding-bottom: 10px;">
                            <div>
                                <a href="edit.php" class="btn btn-success btn-xs">編集</a>
                                <!-- ?item_id=<?php //echo $content['id']; ?>" -->
                                <a onclick="return confirm('ほんとに消すの？');" href="delete.php" class="btn btn-danger btn-xs">削除</a>
                                <!-- ?item_id=<?php //echo $content['id']; ?> -->
                            </div>
                       </a>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- TH2 -->
                <div class="col-sm-4">
                    <div class="thumbnail">
                      <a href="#" class="">
                        <div class="caption">
                            feeded: 2018-12-02 <br>
                            <h4 class="">COMMENT</h4>
                            <p class=""></p>
                        </div>
                        <img src="user_profile_img/petbotles.jpeg" alt="..." class="" style="max-width: 100%; max-height: 200px; height: auto; vertical-align: bottom; padding-bottom: 10px;">
                        </a>
                    </div>
                </div>

                <!-- TH3 -->
                <div class="col-sm-4">
                    <div class="thumbnail">
                        <div class="caption">
                            feeded: 2018-12-02 <br>
                            <h4 class="">COMMENT</h4>
                            <p class=""></p>
                        </div>
                        <img src="user_profile_img/petbotles.jpeg" alt="..." class="" style="max-width: 100%;max-height: 200px;height: auto;vertical-align: bottom; padding-bottom: 10px;">
                        <div>
                            <a href="edit.php" class="btn btn-success btn-xs">編集</a>
                            <!-- ?content_id=<?php //echo $feed['id']; ?>" -->
                            <a onclick="return confirm('ほんとに消すの？');" href="delete.php" class="btn btn-danger btn-xs">削除</a>
                            <!-- ?feed_id=<?php //echo $feed['id']; ?> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- end/row1 -->


        </div>
    </div>
</body>
<?php include('layouts/footer.php'); ?>
</html>


