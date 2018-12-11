<?php
session_start();
require('dbconnect.php');

// ログインしてない状態でのアクセス禁止
if(!isset($_SESSION['GoodsBye']['id']) ){
    header('Location: signin.php');
    exit();
}

$sql = 'SELECT * FROM `users` WHERE `id` = ?';
$data = [$_SESSION['GoodsBye']['id']];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

//ログインしているユーザーの情報
$signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

// echo '<pre>';
// var_dump($signin_user);
// echo '</pre>';

// 選択されたitemの情報を取得し配列化
// 選択されたitemって→パラメーターに与えられたitem_idから導き出せる。

//アイテムの一覧取得
// ORDER BY で表示する画像を並べ替える
$sql = 'SELECT * FROM `items` WHERE `user_id` = ? ORDER BY `created` DESC';
$data = [$signin_user['id']];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

// $record = $stmt->fetch(PDO::FETCH_ASSOC);
// このfetchのせいで最初の一件が表示されてなかった。
// fetchに一件入ってるとポインターが次のレコードに移ってしまうから、次のレコードからしかとってこれなくなってしまう。


// echo '<pre>';
// var_dump($record);
// echo '</pre>';

// 投稿情報全てを入れる配列定義
$items = [];
while(true){
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    //fetchは一つの行を取り出すこと
    if($record == false){
        break;
    }

    $items[] = $record;
}

$item_sql = 'SELECT COUNT(*) AS `cnt` FROM `items` WHERE `user_id` = ? ';
// `items`テーブルに何個ユーザーidがあるか、その数を数える
$item_data = [$signin_user['id']];
$item_stmt = $dbh->prepare($item_sql);
$item_stmt->execute($item_data);
//itemの数が入っている
$item_cnt = $item_stmt->fetch(PDO::FETCH_ASSOC);

// echo '<pre>';
// var_dump($items);
// echo '</pre>';

?>

<?php include('layouts/header.php'); ?>
<body style="margin-top: 60px; margin-right: auto;left: auto; background: #E4E6EB;">
    <?php include("navbar.php"); ?>
    <div class="container">
        <div class="row text-center">
            <div class="col-xs-3 text-center" style ="width: 100%; height: 10%">
                <h2><?php echo $signin_user['name']; ?></h2>
                <img src="user_profile_img/<?php echo $signin_user['img_name']; ?>" class="img-thumbnail" style="text-align: center;max-width: 100%; max-height: 200px; height: auto; vertical-align: bottom;">
            </div>

            <div class="col-xs-12">
                <!--  style="margin-top: 230px;" -->
                <span class="comment_count">Numbers of your posts (現在の投稿数)：<?php echo $item_cnt['cnt']; ?></span>
                <hr>
            </div>

            <!-- row 1  -->
            <div class="row">
                <?php foreach($items as $item): ?>
                <!-- TH1 -->
                <div class="col-sm-4">
                    <div class="thumbnail">
                        <div class="caption">
                            created: <?php echo $item['created']; ?><br>
                            deadline: <?php echo $item['deadline']; ?><br>
                            <p class=""><br><?php echo $item['content']; ?></p>
                            <hr class="bold-line">
                        </div>
                        <a href="detail.php?item_id=<?php echo $item['id']; ?>" class="">
                        <img src="user_profile_img/<?php echo $item['item_img'] ?>" alt="..." class="thumbnail" style="max-width: 100%; max-height: 200px; height: auto; vertical-align: bottom; padding-bottom: 10px;">
                        </a>
                        <!-- ログインしているユーザーだけ編集できるようにしたい -->
                        <?php if($signin_user['id'] == $item['user_id']): ?>
                        <div>
                            <a href="edit.php?item_id=<?php echo $item['id']; ?>" class="btn btn-success btn-xs">EDIT<br><span style="font-size: 10px;">（編集）</span></a>

                            <a onclick="return confirm('Are you sure to delete?（本当に削除しますか？）');" href="delete.php?item_id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">DELETE<br><span style="font-size: 10px;">（削除）</span></a>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div><!-- end/row1 -->
        </div><!-- end/row text-comtainer -->
    </div><!-- end/container -->
</body>
<?php include('layouts/footer.php'); ?>
</html>


