<?php
session_start();
require('dbconnect.php');

// ログインしてない状態でのアクセス禁止
if(!isset($_SESSION['GoodsBye']['id']) ){
    header('Location: main.php');
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

$date_str = date('Ymd');

// $date_str = new DateTime(date('Ymd'));
// $day2 = new DateTime('2015-05-11');

// if ($date_str > $day2) {
//     echo 'ok';
// }

// echo '<pre>';
// var_dump($date_str);
// echo '</pre>';


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

$sql = 'SELECT * FROM `items` WHERE `deadline` >= ? AND `user_id` = ? ORDER BY `created` DESC';
$data = [$date_str,$signin_user['id']];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

$after_deadline_items = [];
while(true){
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if($record == false){
        break;
    }
    $after_deadline_items[] = $record;
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
  height: 135px;
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



    <?php include("navbar.php"); ?>
    <div class="container">
     <div class="row text-center">

        <div class="col-xs-3 text-center" style="margin-top: 60px">
            <!-- width:630px; -> 830px on CSS-->
            <img src="user_profile_img/<?php echo $signin_user['img_name']; ?>" class="img-thumbnail">
            <div style="float: right; margin-right: 100px;margin-top: 50px; text-align: left;">
                <h2><?php echo $signin_user['name']; ?></h2>
                <br>
                <p class="comment_count">Numbers of your posts (現在の投稿数)：<?php echo $item_cnt['cnt']; ?></p>
            </div>
        </div>
        <div class="col-xs-12" style="height: 25px;">
         <br>
        </div>
    </div>
      <!-- １行目 -->
     <div class="row">
      <p class="text-center">Click on the buttons inside the tabbed menu:</p>
  <!-- 左横タブ -->
      <div class="tab">
        <button class="tablinks" onclick="openCity(event, 'POST')" id="defaultOpen">Posting</button>
        <button class="tablinks" onclick="openCity(event, 'EXPIRED')">Expired</button>
      </div>
  <!-- 左横タブ　終了 -->

  <!-- タブの中身 -->
      <div id="POST" class="tabcontent" style="width: 80%; border-right-width: 0px;border-bottom-width: 0px;">
        <?php foreach($after_deadline_items as $after_deadline_item): ?><!-- after_deadline_item 期限前 -->
            <div class="col-sm-4" ><!-- TH1 -->
                <div class="thumbnail">
                    <div class="caption">
                        created: <?php echo $after_deadline_item['created']; ?><br>
                        deadline: <?php echo $after_deadline_item['deadline']; ?><br>
                        <div class="wrapper caption">
                            <p class="content" style="word-break: break-all;"><?php echo $after_deadline_item['content']; ?></p>
                        </div>
                    </div>
                    <a href="detail.php?item_id=<?php echo $after_deadline_item['id']; ?>" class="item_img">
                        <img src="user_profile_img/<?php echo $after_deadline_item['item_img'] ?>" alt="..." class="item_deteil" style="">
                        <!-- max-width: 100%;max-height: 200px; height: auto; vertical-align: bottom; margin-top: 20px; margin-bottom: 10px; border-top-width: 0px;border-right-width: 0px;border-left-width: 0px;border-bottom-width: 0px; padding: 0px -->
                    </a>
                    <!-- ログインしているユーザーだけ編集できるようにしたい -->
                    <?php if($signin_user['id'] == $after_deadline_item['user_id']): ?>
                    <div class="bottom">
                        <a href="edit.php?item_id=<?php echo $after_deadline_item['id']; ?>" class="btn btn-success btn-xs">EDIT<br><span style="">（編集）</span></a>

                        <a onclick="return confirm('Are you sure to delete?（本当に削除しますか？）');" href="delete.php?item_id=<?php echo $after_deadline_item['id']; ?>" class="btn btn-danger btn-xs">DELETE<br><span style="">（削除）</span></a>
                    </div>
                    <?php endif;?>
                </div><!-- end/thumbnail -->
            </div><!-- end/TH1 -->
        <?php endforeach; ?>
      </div>

      <div id="EXPIRED" class="tabcontent" style="width: 80%; border-right-width: 0px;border-bottom-width: 0px;">
        <?php foreach($before_deadline_items as $before_deadline_item): ?> <!-- before_deadline_item 期限まだ -->
            <div class="col-sm-4" ><!-- TH2 -->
                <div class="thumbnail">
                    <div class="caption">
                        created: <?php echo $before_deadline_item['created']; ?><br>
                        deadline: <?php echo $before_deadline_item['deadline']; ?><br>
                        <div class="wrapper caption" style="padding:0px ;">
                            <p class="content" style="word-break: break-all;"><?php echo $before_deadline_item['content']; ?></p>
                        </div>
                    </div>
                    <a href="detail.php?item_id=<?php echo $before_deadline_item['id']; ?>" class="item_img">
                        <img src="user_profile_img/<?php echo $before_deadline_item['item_img'] ?>" alt="..." class="item_deteil" style="">
                        <!-- max-width: 100%;max-height: 200px; height: auto; vertical-align: bottom; margin-top: 20px; margin-bottom: 10px; border-top-width: 0px;border-right-width: 0px;border-left-width: 0px;border-bottom-width: 0px; padding: 0px -->
                    </a>
                    <!-- ログインしているユーザーだけ編集できるようにしたい -->
                    <?php if($signin_user['id'] == $before_deadline_item['user_id']): ?>
                        <div class="bottom">
                            <a href="edit.php?item_id=<?php echo $before_deadline_item['id']; ?>" class="btn btn-success btn-xs">EDIT<br><span style="font-size: 10px;">（編集）</span></a>

                            <a onclick="return confirm('Are you sure to delete?（本当に削除しますか？）');" href="delete.php?item_id=<?php echo $before_deadline_item['id']; ?>" class="btn btn-danger btn-xs">DELETE<br><span style="font-size: 10px;">（削除）</span></a>
                        </div>
                    <?php endif;?>
                </div><!-- thumbnail -->
            </div><!-- end/TH2 -->
        <?php endforeach; ?>
      </div>






                </div> <!-- tab-content -->
            </div><!-- col-xs-9 -->
        </div><!-- end/row text-comtainer -->
    </div><!-- end/container -->
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


