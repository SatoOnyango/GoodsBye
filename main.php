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

$current_date = date('Y-m-d');

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
    // 写真が選択されてるか
    if (isset($_FILES['input_img_name'])) {
        $file_name = $_FILES['input_img_name']['name'];
    }
    // 日付指定があるか
    $deadline =$_POST['deadline'];
    if ($deadline == '') {
    $errors['deadline'] = 'blank';
    }

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

        $sql='INSERT INTO`items`(`content`,`item_img`,`user_id`, `deadline`, `created`)VALUES(?,?,?,?,NOW());';
        $data= [$content,$file_name,$signin_user['id'], $_POST['deadline']];
        $stmt=$dbh->prepare($sql);
        $stmt->execute($data);

        header('Location:main.php');
    }
}

$sql = 'SELECT `i`.*, `u`.`name` FROM `items` AS `i` LEFT JOIN `users` AS `u` ON `i`.`user_id` = `u`.`id` WHERE`deadline`>= CURRENT_DATE()';
$data = [];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);


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
// 最後のページ数を取得
// 最後のページ = 取得したページ数 ÷ 1ページあたりのページ数

$result = $stmt->fetch(PDO::FETCH_ASSOC);
$cnt = $result['cnt'];

$last_page = ceil($cnt / CONTENT_PER_PAGE);
// 最後のページより大きい値を渡された際の対策
$page = min($page, $last_page);
// スキップするレコード数 = (指定ページ - 1) * 表示件数
$start = ($page - 1) * CONTENT_PER_PAGE;

$items=[];
$times=[];

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

    $sql = 'SELECT `i`.*, `u`.`name` FROM `items` AS `i` LEFT JOIN `users` AS `u` ON `i`.`user_id` = `u`.`id` ORDER BY `i`.`deadline` LIMIT ' . CONTENT_PER_PAGE . ' OFFSET ' . $start;
    $times_stmt = $dbh->prepare($sql);
    $times_stmt->execute();


    while(true){
        $record=$times_stmt->fetch(PDO::FETCH_ASSOC);
        if($record==false){
        break;
        }
        $times[] = $record;
    }
} else{
    $page=1;
    $last_page=1;
}

$date_str = date('Ymd');

$sql = 'SELECT * FROM `items` WHERE `deadline` < ? AND `user_id` = ? ORDER BY `created` DESC';
$data = [$date_str,$signin_user['id']];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

$before_deadline_items = [];
while(true){
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if($record == false){
        break;
    }
    $before_deadline_items[] = $record;
}


?>

<?php include('layouts/header.php'); ?>

<!-- サイドバー参照 -->
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {box-sizing: border-box}
body {font-family: "Lato", sans-serif;}

/* Style the tab */
.tab {
  float: left;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
  width: 10%;
  height: 300px;
}

/* Style the buttons inside the tab */
.tab button {
  display: block;
  background-color: inherit;
  color: black;
  padding: 22px 16px;
  width: 100%;
  border: none;
  outline: none;
  text-align: left;
  cursor: pointer;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current "tab button" class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  float: left;
  padding: 0px 12px;
  border: 1px solid #ccc;
  width: 90%;
  border-left: none;
  height:auto;
}
</style>
</head>
<!-- 参照終わり -->



<body>
<?php include('navbar.php'); ?>

<div class="container">
  <!-- １行目 -->
  <div class="row">
    
  <!-- 上部スライド -->
    <div>
      <img id="mypic" class="center" style="margin-top: 30px"src="img/how1.png" width="auto" height="350">
        <script>
        var pics_src = new Array("img/how1.png","img/how2.png","img/how3.png");
        var num = -1;

        slideshow_timer();

        function slideshow_timer(){
            if (num == 2){
                num = 0;
            } 
            else {
                num ++;
            }
            document.getElementById("mypic").src=pics_src[num];
            setTimeout("slideshow_timer()",4000); 
        }
        </script>
    </div>
      <!-- スライド終了 -->
    
  </div>
  <!-- ２行目 -->
  <div class="row">
    <p class="text-center">Click on the buttons inside the tabbed menu:</p>
  <!-- 左横タブ -->
  <div class="tab">
    <button class="tablinks" onclick="openCity(event, 'New')" id="defaultOpen">New</button>
    <button class="tablinks" onclick="openCity(event, 'Dead')">Almost Expired</button>
    <button class="tablinks" onclick="openCity(event, 'Expired')">Expired</button>
    <button class="tablinks" onclick="openCity(event, 'guide')">Guide</button>
  </div>
  <!-- 左横タブ　終了 -->

  <!-- タブの中身 -->
  <div id="New" class="tabcontent" style="width: 80%; border-right-width: 0px;border-bottom-width: 0px;">
    <h2 style="color: #0099E8">New!</h2>
    <?php foreach($items as $item): ?>
      <div class="col-sm-4">
        <div class="thumbnail">
          <div class="caption">
            <?php if($item['done_flag']==1): ?>
                <p class="text-danger text-center">終了しました(End)<br></p>
            <?php endif; ?>

            <?php if($item['done_flag']==0): ?>
                created: <?php echo $item['created']; ?><br>
                deadline: <?php echo $item['deadline']; ?><br>
            <?php endif; ?>
            <a href="detail.php?item_id=<?php echo$item['id'];?>" class="">
                <p class=""></p>
                <img src="user_profile_img/<?php echo $item['item_img'];?>" alt="..." class="thumbnail">
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div id="Dead" class="tabcontent">
    <h2 style="color: #0099E8">Expire Soon!</h2>
    <?php foreach($times as $time): ?>
      <div class="col-sm-4">
        <div class="thumbnail">
          <div class="caption">
            <?php if($time['done_flag']==1): ?>
                <p class="text-danger text-center">終了しました(End)<br></p>
            <?php endif; ?>

            <?php if($time['done_flag']==0): ?>
                created: <?php echo $time['created']; ?><br>
                deadline: <?php echo $time['deadline']; ?><br>
            <?php endif; ?>
            <a href="detail.php?item_id=<?php echo $time['id'];?>" class="">
                <p class=""></p>
                <img src="user_profile_img/<?php echo $time['item_img'];?>" alt="..." class="thumbnail">
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div id="Expired" class="tabcontent">
    <h2 style="color: #0099E8">Before trade</h2>
    <p>These items are expired.</p>
    <?php foreach($before_deadline_items as $before_deadline_item): ?>
      <div class="col-sm-4">
        <div class="thumbnail">
          <div class="caption">
            <?php if($before_deadline_item['done_flag']==1): ?>
                <p class="text-danger text-center">終了しました(End)<br></p>
            <?php endif; ?>

            <?php if($before_deadline_item['done_flag']==0): ?>
                created: <?php echo $before_deadline_item['created']; ?><br>
                deadline: <?php echo $before_deadline_item['deadline']; ?><br>
            <?php endif; ?>
            <a href="detail.php?item_id=<?php echo $before_deadline_item['id'];?>" class="">
                <p class=""></p>
                <img src="user_profile_img/<?php echo $before_deadline_item['item_img'];?>" alt="..." class="thumbnail">
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>


  <div id="guide" class="tabcontent">
    <h2 style="color: #0099E8">Guidance</h2>
    <h3>How to use</h3>
    <p>This service can reduce your wastefull.</p>
  </div>
    
  </div>
  <!-- ３行目 -->
  <div class="row">
    <!-- 投稿エリア -->
  <section id="post" name="post">
    <div class="container">
      <div class="row">
          <div class="post">

            <div class="content_form thumbnail">

              <!-- ページネーション -->
              <div aria-label="Page navigation" class="clear">
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


              <form method="POST" action="main.php#post" enctype="multipart/form-data">
                <div class="form-group" style="margin-bottom: 0px;">
                  <div class="col-xs-6 col-xs-offset-3 border">
                    <textarea name="content" class="form-control" rows="2" placeholder=" アイテムの説明/ The details about item" style="font-size: 22px; text-align: center;"></textarea><br>
                    <?php if (isset($errors['content'])&& $errors['content'] == 'blank'):?>
                      <p class="text-danger">アイテムの説明を入力してください/<br>Please write the details about item</p>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="form-group">
                    
                    <input type="file" name="input_img_name" id="img_name" accept="image/*">
                    <label for="img_name">Your item image</label>
                    <?php if(isset($errors['input_img_name']) && $errors['input_img_name'] == 'blank'): ?>
                        <p class="text-danger">Imageを選択してください/ Please choose item image</p>
                    <?php endif; ?>
                    
                </div>

                <div class="form-group" style="margin-bottom: 0px; height: 30px;">
                    <?php if(isset($errors['input_img_name']) && $errors['input_img_name'] == 'type'): ?>
                        <p class="text-danger">拡張子が違います/ Wrong file extension</p>
                    <?php endif; ?>
                    <!-- <br> -->
                    <label for="img_name">掲載期限/ Publication period </label>
                </div>

                <input type="date" name="deadline" value="today" min="<?php echo $current_date; ?>"><br>
                <div class="form-group">
                    <?php if (isset($errors['deadline']) && $errors['deadline'] =='blank'): ?>
                        <p class="text-danger">日付を選択してください/Pleae choose date</p>
                    <?php endif; ?>
                    <input type="submit" value="POST (投稿する)" class="btn btn-primary">
                </div>
              </form>

            </div><!-- end / content_form thumbnail -->
          </div>
      </div>
    </div>
  </section>
  </div>
</div>
</body>

<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>

<?php include('layouts/footer.php'); ?>
</html>


