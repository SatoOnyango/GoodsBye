<?php
session_start();
require('dbconnect.php');

// エラーを格納する配列
$errors=[];


if (!empty($_POST)){
    $name=$_POST['input_name'];
    $password=$_POST['input_password']; 
    if($name==''){
        $errors['name']='blank';}
    if($password==''){
        $errors['password']='blank';
    }

    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";


    if (empty($errors)){

        $sql='SELECT *FROM`users`WHERE`name`=?';
        $data=[$name];
        $stml=$dbh->prepare($sql);
        $stml->execute($data);
        $record=$stml->fetch(PDO::FETCH_ASSOC);

        // echo '<pre>';
        // var_dump($record);
        // echo'</pre>';
        // 名前での本人確認
        if($record==false){
            $errors['signin']='failed';
        }
        // 2パスワードが一致するか確認
        if(password_verify($password,$record['password'])){
        // 3パスワードが一致したらサインイン処理
        // セッションにユーザーIDのID追加
            $_SESSION['GoodsBye']['id']=$record['id'];
            header('Location: main.php');
            exit();
            echo'complete！';
        }else{
            //認証失敗
            $errors['signin']='failed';
        }
    }


}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>GoodsBye</title>

  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">

</head>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">Sign in</h2>
                <?php if(isset($errors['signin']) && $errors['signin']== 'failed'):?>
                            <p class ="text-danger"> A required field</p>
                        <?php endif ;?>
                <form method="POST" action="signin.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Username*</label>
                        <input type="name" name="input_name" class="form-control" id="name" placeholder="input text"
                        <?php if(isset($errors['name']) && $errors['name']== 'blank'):?>>
                            <p class ="text-danger"> Can't be blank/ ユーザー名を入力してください</p>
                        <?php endif ;?>
                    </div>
                    <div class="form-group">
                        <label for="password">Password*</label>
                        <input type="password" name="input_password" class="form-control" id="password" placeholder="at least 4 characters">
                        <?php if(isset($errors['password']) && $errors['password']== 'blank'):?>
                            <p class ="text-danger">Can't be blank/ Passwordを入力してください</p>
                        <?php endif ;?>
                    </div>
                    <input type="submit" class="btn btn-info" value="Sign in">
                    <span style="float: right; padding-top: 6px;">
                        <a href="index.php">Back</a>
                    </span>
                </form>
            </div>
        </div>
    </div>
</body>

</html>