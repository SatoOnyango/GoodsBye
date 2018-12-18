<?php
session_start();
require('dbconnect.php');

if (!isset($_SESSION['GoodsBye'])) {
   header('Location:signup.php');
   exit();
}

$name=$_SESSION['GoodsBye']['name'];
$password=$_SESSION['GoodsBye']['password'];
$img_name=$_SESSION['GoodsBye']['img_name'];

// echo '<pre>';
// var_dump($_SESSION['GoodsBye']['img_name']);
// echo '</pre>';
// die();



if (!empty($_POST)) {
    $sql='INSERT INTO `users`(`name`,`password`,`img_name`,`created`) VALUES(?,?,?,NOW());';
    $data=[$name,password_hash($password,PASSWORD_DEFAULT),$img_name];
    $stmt=$dbh->prepare($sql);
    $stmt->execute($data);
    unset($_SESSION['GoodsBye']);
    header('Location:thanks.php');
    exit();
}





// echo '<pre>';
// var_dump($_SESSION['GoodsBye']['img_name']);
// echo '</pre>';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>GoodsBye</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
</head>
<body style="margin-top: 60px;background-color:#F8F8F8">
    <h1 class="text-center content_header">Goodsbye</h1>
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">Checkout</h2>
                <div class="row">
                    <div class="col-xs-4">
                        <img src="user_profile_img/<?php echo htmlspecialchars($img_name);?>" class="img-responsive img-thumbnail">
                    </div>
                    <div class="col-xs-8">
                        <div>
                            <span>Username</span>
                            <p class="lead"><?php echo htmlspecialchars($name); ?></p>
                        </div>
                        <div>
                            <span>Password</span>
                            <p class="lead">●●●●●●●●</p>
                        </div>
                        <form method="POST" action="check.php">
                            <a href="signup.php?action=rewrite" class="btn btn-default">&laquo;&nbsp;back</a> | 
                            <input type="hidden" name="action" value="submit">
                            <input type="submit" class="btn btn-success" value="Register">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>