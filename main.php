<?php
session_start();
require('dbconnect.php');

// 1ページあたりの表示件数
const CONTENT_PER_PAGE = 30;
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

if (isset($_GET['page'])) {
    // ページの指定がある場合
    $page = $_GET['page'];
} else {
    // ページの指定がない場合(初期値)
    $page = 1;
}
// -1などの不正な値を渡された際の対策
$page = max($page, 1);
// feedsテーブルのレコード数を取得する
// COUNT() 何レコードあるか集計するSQLの関数
$sql = 'SELECT COUNT(*) AS `cnt` FROM `items`';
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$cnt = $result['cnt'];
// 最後のページ数を取得
// 最後のページ = 取得したページ数 ÷ 1ページあたりのページ数
$last_page = ceil($cnt / CONTENT_PER_PAGE);
// 最後のページより大きい値を渡された際の対策
$page = min($page, $last_page);
// スキップするレコード数 = (指定ページ - 1) * 表示件数
$start = ($page - 1) * CONTENT_PER_PAGE

?>

<?php include('layouts/header.php'); ?>
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

                <div class="row">
                    <?php foreach($contents as $content): ?>
                    <!-- TH1 -->
                    <div class="col-sm-4">
                        <div class="thumbnail">
                          <a href="detail.php?item_id=<?php echo$content['id'];?>" class="">
                            <div class="caption">

                                <p class=""></p>
                            </div>
                            <img src="user_profile_img/<?php echo $content['item_img'];?>" alt="..." class="thumbnail">
                          </a>
                          <!-- <?php //if(empty($content)):?>
                            <img src="user_profile_img/petbotles.jpeg" alt="..." class="thumbnail">
                          <?php //endif;?> -->
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- ページ遷移部分 -->
        <div aria-label="Page navigation">
                    <ul class="pager">
                        <?php if ($page == 1): ?>
                            <!-- Newer押せない時 -->
                            <!-- 最初のページより前は禁止 -->
                            <li class="previous disabled">
                                <a><span aria-hidden="true">&larr;</span> Newer
                                </a>
                            </li>
                        <?php else: ?>
                            <!-- Newer押せる時 -->
                            <li class="previous">
                                <a href="main.php?page=<?php echo $page - 1; ?>"><span aria-hidden="true">&larr;</span> Newer
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page == $last_page): ?>
                            <li class="next disabled">
                                <a>Older <span aria-hidden="true">&rarr;</span>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="next">
                                <a href="main.php?page=<?php echo $page + 1; ?>">Older <span aria-hidden="true">&rarr;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
        </div>
        <!-- 投稿エリア -->
        <section id="post" name="post">
            <div class="container">
                <div class="row">
                        <div class="post">
                            <div class="content_form thumbnail">

                                <form method="POST" action="main.php" enctype="multipart/form-data">
                                    <!-- 商品説明が入力されているか -->
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <textarea name="content" class="form-control" rows="2" placeholder="Your Comment Here" style="font-size: 24px; text-align: center;"></textarea><br>
                                        <?php if (isset($errors['content'])&& $errors
                                        ['content'] == 'blank'):?>
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
</body>
<?php include('layouts/footer.php'); ?>
</html>
