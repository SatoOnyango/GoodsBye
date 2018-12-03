<?php
session_start();
require('dbconnect.php');

// エラーを格納する配列
$errors=[];

// POST送信時のみ
if(!empty($_POST)){
    $name = $_POST['input_name'];
    $password = $_POST['input_password'];

    if($name == ''){
        $errors['name'] = 'blank';
    }

    $count = strlen($password);

    if($password == ''){
        $errors['password'] = 'blank';
    }
    if(empty($errors)){
        // echo '入力完了！！！';
        //バリデーション通過時の処理
        //1.DBからレコードを取得
        //宿題
        //SELECT文を考えてくる
        //ただし、パスワードは使わない
        $sql = 'SELECT * FROM `users` WHERE `name`= ? ';
        //sql文のなかに？があるのでそこを$dataで指定する
        $data = [$name];
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        //DBから取得した値を$recordに入れる
        //$recordには連想配列が入ってくる
        //値がない場合はfalseが入る
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        // $record = $stmt->fetch(PDO::FETCH_ASSOC)があれば連想配列、なければfalseを勝手にやってくれる

        echo '<pre>';
        var_dump($password);
        echo '</pre>';
        echo '<pre>';
        var_dump($record['password']);
        echo '</pre>';

        //メールアドレスでの本人確認
        if($record == false){
            $errors['signin'] = 'failed';
        }

        //2.パスワードが一致するか確認
        if(password_verify($password, $record['password'])){
        //認証成功
        //3.パスワード一致した場合、サインイン処理
        //3-1. セッションにユーザーのID追加
            $_SESSION['GoodsBye']['id'] = $record['id'];

        //3-2. timeline.phpに遷移
            echo '認証成功';
            echo '<pre>';
            var_dump($_SESSION);
            echo '</pre>';


            header('Location: main.php');
            exit();
            die();

        }else{
            //認証失敗
            $errors['signin'] = 'failed';
        }

    }
}

// echo '<pre>';
// var_dump();
// echo '</pre>';


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
                        <input type="text" name="input_name" class="form-control" id="name" placeholder="input text"
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