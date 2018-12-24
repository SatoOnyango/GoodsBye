<?php
session_start();
require('dbconnect.php');


//サインインユーザーの読み出し
$sql='SELECT *FROM`users`WHERE`id`=?';
$data=[$_SESSION['GoodsBye']['id']];
$stmt=$dbh->prepare($sql);
$stmt->execute($data);
$signin_user=$stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="">
<head>
  <meta charset="UTF-8">
  <title>Goodsbye</title>
</head>
<body>
  <?php include('layouts/header.php'); ?>
  <?php include('navbar.php'); ?>
    <div class="tabcontent1" style="text-align: center;">
      <div>
        <img id="mypic2" style="margin-top: 100px "src="img/sample2.png" width="auto" height="500">
        <input type="button" value="＞" onclick="slideshow_timer2()" style="width: 50px; height: 50px;">
            <script>
            var pics_src2 = new Array("img/sample2.png","img/sample5.png");
            var num2 = -1;

            slideshow_timer2();

            function slideshow_timer2(){
                if (num2 == 1){
                    num2 = 0;
                } 
                else {
                    num2 ++;
                }
                document.getElementById("mypic2").src=pics_src2[num2];
                setTimeout2("slideshow2_timer()",4000); 
            }
            </script>
      </div>
    </div>

</body>
<?php include('layouts/footer.php'); ?>
</html>