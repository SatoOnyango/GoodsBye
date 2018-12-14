<?php
session_start();
require('dbconnect.php');
//1.エラーの内容を保持する配列変数を定義
$errors=[];

//確認画面から戻ってきた場合
if(isset($_GET['action'])&& $_GET['action']=='rewrite'){
    $_POST['input_name']=$_SESSION['GoodsBye']['name'];
    $_POST['input_password']=$_SESSION['GoodsBye']['password'];
//check.phpに遷移しないように
    $errors['rewirte']=true;
}

$name='';
$password='';

if (!empty($_POST)){
    $name=$_POST['input_name'];
    $password=$_POST['input_password']; 
    //3入力項目に不備があった場合、配列変数に格納
    if ($name==''){
        $errors['name']='blank';
    }
    //既に登録されているデータかどうか参照
    $sql = 'SELECT * FROM `users` WHERE `name`= ? ';
    $data = [$name];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($name==$record['name']){
        $errors['name'] = 'conflict';
    }
    // echo'<pre>';
    // var_dump($name);
    // echo'</pre>';

    // echo'<pre>';
    // var_dump($record['name']);
    // echo'</pre>';

    $count =strlen($password);

    if($password==''){
        $errors['password']='blank';
    }elseif($count < 4){
        $errors['password']='length';
    }

    $file_name='';
    if(!isset($_GET['action'])){
        $file_name=$_FILES['input_img_name']['name'];
    }
    if (!empty($file_name)){

        $file_type = substr($file_name,-3);
        $file_type = strtolower($file_type);//png
    // $file_type = strtoupper($file_type);//PNG<-大文字化

    // ３、jpg,png,gifと比較し、当てはまらない場合$errors['img_name']に格納

        if($file_type!='jpg' && $file_type!='png' && $file_type!='gif'){
            $errors['img_name'] ='type';
        }
    }else{
            $img_name='default.png';
    }

    if(empty($errors) && $img_name=='default.png'){
    $_SESSION['GoodsBye']['name']=$name;
    $_SESSION['GoodsBye']['password']=$password;
    $_SESSION['GoodsBye']['img_name']=$img_name;
    // die();
    header('Location: check.php');
    exit();

    }elseif(empty($errors)){

    $date_str = date('YmdHis');
            // <-datephp参照
    $submit_file_name = $date_str .$file_name;
            //アップロード
            //move_uploaded_file(画像ファイル、アップロード先)
    move_uploaded_file($_FILES['input_img_name']['tmp_name'],'user_profile_img/'.$submit_file_name);
             //$_FILES[キー]['name']         画像名
             //$_FILES[キー]['tmp＿name']    画像データそのもの

             //２セッションへ送信データを保存する
             //サーバーに用意された一時的にデータを保持できる機能
             //同じサーバー内であれば出し入れ自由
             //$_SESSION 連想配列形式で値を保持
             //使用するためにはsession_start();をファイルの頭に記述する必要がある
    $_SESSION['GoodsBye']['name']=$name;
    $_SESSION['GoodsBye']['password']=$password;
    $_SESSION['GoodsBye']['img_name']=$submit_file_name;
    // die();
    header('Location: check.php');
    exit();
    }else{
        $errors['img_name'] = 'blank';
    }
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>GoodsBye</title>

    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">

</head>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">Sign up for GoodsBye for Free!</h2>
                <form method="POST" action="signup.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Username*</label>
                        <input type="text" name="input_name" class="form-control" id="name" placeholder="input text"
                            value="<?php echo htmlspecialchars($name);?>">
                        <!-- 4不備が配列変数に格納されている場合、画面に出力 -->
                        <!-- isset(変数)変数が定義されていればtrue -->
                        <?php if(isset($errors['name']) && $errors['name']== 'blank'):?>
                            <p class ="text-danger">ユーザー名を入力してください/ Can't be blank</p>
                        <?php endif ;?>
                        <?php if(isset($errors['name']) && $errors['name']== 'conflict'):?>
                            <p class ="text-danger">既に使用されています/ Already in use</p>
                        <?php endif ;?>
                    </div>
                    <div class="form-group">
                        <label for="password">Password*</label>
                        <input type="password" name="input_password" class="form-control" id="password" placeholder="at least 4 characters">
                        <?php if(isset($errors['password']) && $errors['password']== 'blank'):?>
                            <p class ="text-danger">passwordを入力してください/ Can't be blank</p>
                        <?php endif ;?>
                        <?php if(isset($errors['password']) && $errors['password']== 'length'):?>
                            <p class ="text-danger">4文字以上で入力/ Must have at least 4 characters</p>
                        <?php endif ;?>
                        <?php if (!empty($errors)):?>
                            <p class ="text-danger">
                                パスワードを再度入力してください/ Reenter password</p>
                    <?php endif;?>
                    </div>
                    <div class="form-group">
                        <label for="img_name">Profile image</label>
                        <input type="file" name="input_img_name" id="img_name" accept="image/*"><!-- accept="image/*"画像以外選択できない -->
                        <?php if(isset($errors['img_name']) && $errors['img_name']== 'type'):?>
                            <p class ="text-danger">拡張子が違います/ Wrong file extension</p>
                        <?php endif ;?>
                    </div>
                    <input type="submit" class="btn btn-default" value="confirm">
                    <span style="float: right; padding-top: 6px;"><br>
                        <a href="signin.php" class="btn btn-info">Signin</a> <a href="index.php" class="btn btn-default">Top</a>

                    </span>
                </form>
            </div>
        </div>
    </div>
</body>

</html>