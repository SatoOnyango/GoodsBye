<?php
session_start();
//1.エラーの内容を保持する配列変数を定義
$errors=[];
//確認画面から戻ってきた場合
if(isset($_GET['action'])&& $_GET['action']=='rewrite'){
    $_POST['input_name']=$_SESSION['GoodsBye']['name'];
    $_POST['input_password']=$_SESSION['GoodsBye']['password'];
//check.phpに遷移しないように
    $errors['rewirte']=true;
}
//空で変数を定義
$name='';
$email='';

//2送信されたデータと比較
//post送信時のみ（get送信時は処理されない）
if (!empty($_POST)){
    $name=$_POST['input_name'];
    $password=$_POST['input_password']; 

    //3入力項目に不備があった場合、配列変数に格納
    if ($name==''){
        $errors['name']='blank';}
    $count =strlen($password);
    if($password==''){
        $errors['password']='blank';}
    //文字数チェック4〜文字
    //strlen(文字列)
    //文字列の文字を返す
    elseif($count < 4){
        $errors['password']='length';
    }

//<-画像を使用するときは$_POSTではなく、＄_FILESで受け取る。
//$FILESにはtype="file"で選択されたデータが入る
//ただしルールが２つある
//1.formダグにenctype="multipart/form-dataが指定されている
//2.formタグにmethod="POST"が指定されている
//$_FILES[キー]['name']         画像名
//$_FILES[キー]['tmp＿name']    画像データそのもの
//画像名を取得
    $file_name='';
    if(!isset($_GET['action'])){
        $file_name=$_FILES['input_img_name']['name'];
    }
    if (!empty($file_name)){
       //画像が選択されている時の処理

        //拡張子チェック
        // １、画像ファイル名の拡張子を取得
        // substr(文字列、何文字目から)
        // 指定されたレンジの文字列を取得
        $file_type = substr($file_name,-3);//PNG
        // ２、大文字は小文字化
        $file_type = strtolower($file_type);//png
        // $file_type = strtoupper($file_type);//PNG<-大文字化

        // ３、jpg,png,gifと比較し、当てはまらない場合$errors['img_name']に格納
        if($file_type!='jpg' && $file_type!='png' && $file_type!='gif'){
            $errors['img_name'] ='type';
        }

    // } else {
    //         $errors['img_name']='blank';
    //        }
    //バリデーション成功時の処理＝入力不備がなかった時
    if(empty($errors)){
        echo 'complete！<br>';

           // １プロフィール画像のアップロード
           // まず書き込み権限があるか、ない場合変更する
            $date_str = date('YmdHis');
            // <-datephp参照
            $submit_file_name = $date_str .$file_name;
            //アップロード
            //move_uploaded_file(画像ファイル、アップロード先)
            move_uploaded_file($_FILES['input_img_name']['tmp_name'],'../user_profile_img/'.$submit_file_name);
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

            // ３次のページに遷移するw
            // header('Location:遷移先')
             header('Location: check.php');
             exit();
// echo '<pre>';
// var_dump($_FILES);
// echo '</pre>';
         }
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
                            <p class ="text-danger">ユーザー名を入力してください/Can't be blank</p>
                        <?php endif ;?>
                    </div>
                    <div class="form-group">
                        <label for="password">Password*</label>
                        <input type="password" name="input_password" class="form-control" id="password" placeholder="at least 4 characters">
                        <?php if(isset($errors['password']) && $errors['password']== 'blank'):?>
                            <p class ="text-danger">passwordを入力してください/Can't be blank</p>
                        <?php endif ;?>
                        <?php if(isset($errors['password']) && $errors['password']== 'length'):?>
                            <p class ="text-danger">4文字以上で入力/Must have at least 4 characters</p>
                        <?php endif ;?>
                        <?php if (!empty($errors)):?>
                            <p class ="text-danger">
                                パスワードを再度入力してください</p>
                    <?php endif;?>
                    </div>
                    <div class="form-group">
                        <label for="img_name">Profile image*</label>
                        <input type="file" name="input_img_name" id="img_name" accept="image/*"><!-- accept="image/*"画像以外選択できない -->
                        <?php if(isset($errors['img_name']) && $errors['img_name']== 'type'):?>
                            <p class ="text-danger">拡張子が違います</p>
                        <?php endif ;?>
                    </div>
                    <input type="submit" class="btn btn-default" value="confirm">
                    <span style="float: right; padding-top: 6px;">Username/Password<br>
                        <a href="../signin.php">Signin</a>
                    </span>
                </form>
            </div>
        </div>
    </div>
</body>
<!-- <script src="../assets/js/jquery-3.1.1.js"></script>
<script src="../assets/js/jquery-migrate-1.4.1.js"></script>
<script src="../assets/js/bootstrap.js"></script> -->
</html>