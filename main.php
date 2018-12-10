<?php
session_start();
require('dbconnect.php');
const CONTENT_PER_PAGE = 30;
if(!isset($_SESSION['GoodsBye']['id'])){
   header('Location:signin.php');
   exit();
}

$sql='SELECT *FROM`users`WHERE`id`=?';
$data=[$_SESSION['GoodsBye']['id']];
$stmt=$dbh->prepare($sql);
$stmt->execute($data);
$signin_user=$stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];
$content = '';

if (!empty($_POST)){
    $content =$_POST['content'];
    if ($content == '') {
        $errors['content'] = 'blank';
    }
    $file_name = '';
    if (isset($_FILES['input_img_name'])) {
        $file_name = $_FILES['input_img_name']['name'];
    }

    if (!empty($file_name)) {
        $file_type = substr($file_name, -3);
        $file_type = strtolower($file_type);
        if ($file_type != 'png' && $file_type != 'jpg' && $file_type != 'gif') {
            $errors['input_img_name'] = 'type';
        }
    } else {
        $errors['input_img_name'] = 'blank';
    }
    if (empty($errors)) {
        $date_str = date('YmdHis');
        $submit_file_name = $date_str . $file_name;
        move_uploaded_file($_FILES['input_img_name']['tmp_name'],'user_profile_img/' . $submit_file_name);
        $file_name=$submit_file_name;

        $sql='INSERT INTO`items`(`content`,`item_img`,`user_id`,`created`)VALUES(?,?,?,NOW());';
        $data= [$content,$file_name,$signin_user['id']];
        $stmt=$dbh->prepare($sql);
        $stmt->execute($data);
        header('Location:main.php');
    }
}


if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$page = max($page, 1);


$sql = 'SELECT COUNT(*) AS `cnt` FROM `items`';
$stmt = $dbh->prepare($sql);
$stmt->execute();


$result = $stmt->fetch(PDO::FETCH_ASSOC);
$cnt = $result['cnt'];


$last_page = ceil($cnt / CONTENT_PER_PAGE);
$page = min($page, $last_page);
$start = ($page - 1) * CONTENT_PER_PAGE;

$items=[];

if ($cnt!=0) {
    $sql = 'SELECT `i`.*, `u`.`name` FROM `items` AS `i` LEFT JOIN `users` AS `u` ON `i`.`user_id` = `u`.`id` ORDER BY `i`.`created` DESC LIMIT ' . CONTENT_PER_PAGE . ' OFFSET ' . $start;
    $items_stmt = $dbh->prepare($sql);
    $items_stmt->execute();


    while(true){
        $record=$items_stmt->fetch(PDO::FETCH_ASSOC);
        if($record==false){
        break;
        }
        $items[] = $record;
    }
} else{
    $page=1;
    $last_page=1;
}
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
                    <?php foreach($items as $item): ?>
                    <div class="col-sm-4">
                        <div class="thumbnail">
                          <a href="detail.php?item_id=<?php echo$item['id'];?>" class="">
                            <div class="caption">

                                <p class=""></p>
                            </div>
                            <img src="user_profile_img/<?php echo $item['item_img'];?>" alt="..." class="thumbnail">
                          </a>
                           <?php if($signin_user['id']==$item['user_id']):?>
                                    <a href="edit.php?item_id2=<?php echo$item['id'];?>" class="btn btn-success btn-xs">編集</a>
                                    <a onclick="return confirm('ほんとに消すの？');" href="delete.php" class="btn btn-danger btn-xs">削除</a>
                                    <!-- get送信時はURL?(キー＝値)=パラメーター -->
                           <?php endif;?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div aria-label="Page navigation">
                    <ul class="pager">
                        <?php if ($page == 1): ?>
                            <li class="previous disabled">
                                <a><span aria-hidden="true">&larr;</span> Newer
                                </a>
                            </li>
                        <?php else: ?>
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


        <section id="post" name="post">
            <div class="container">
                <div class="row">
                        <div class="post">
                            <div class="content_form thumbnail">

                                <form method="POST" action="main.php" enctype="multipart/form-data">
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <textarea name="content" class="form-control" rows="2" placeholder="Your Comment Here" style="font-size: 24px; text-align: center;"></textarea><br>
                                        <?php if (isset($errors['content'])&& $errors
                                        ['content'] == 'blank'):?>
                                            <p class="text-danger">文字を入力してください/ Can't be blank</p>
                                        <?php endif; ?>
                                    </div>
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
                </div>
            </div>
        </section>
        </div>
    </div>
</body>
<?php include('layouts/footer.php'); ?>
</html>