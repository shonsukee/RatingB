<?php
session_start();
//CSRF対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
//DB情報
$user = 'root';
$pass = '';
//エラーメッセージの初期化
$errors = array();

$pdo = new PDO('mysql:host=localhost; dbname=login', $user, $pass);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//送信ボタンクリックした後の処理
if (isset($_POST['submit'])) {
   if (empty($_POST['mail'])) {
       $errors['mail'] = 'メールアドレスが未入力です。';
   }else{
       $mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;

       if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
			$errors['mail_check'] = "メールアドレスの形式が正しくありません。";
       }

       $sql = "SELECT id FROM registeruser WHERE mail=:mail";
       $stm = $pdo->prepare($sql);
       $stm->bindValue(':mail', $mail, PDO::PARAM_STR);
       
       $stm->execute();
       $result = $stm->fetch(PDO::FETCH_ASSOC);
       //user テーブルに同じメールアドレスがある場合、エラー表示
       if(isset($result["id"])){
			$errors['user_check'] = "このメールアドレスはすでに利用されております。";
       }
       
   }

   if (count($errors) === 0){
       $urltoken = hash('sha256',uniqid(rand(),1));
       $url = "http://localhost:8080/rating/login/temp/signup.php?urltoken=".$urltoken;

       try{
           $sql = "INSERT INTO pre_user (urltoken, mail, date, flag) VALUES (:urltoken, :mail, now(), '0')";
           $stm = $pdo->prepare($sql);
           $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
           $stm->bindValue(':mail', $mail, PDO::PARAM_STR);
           $stm->execute();
           $pdo = null;
           $message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";     
       }catch (PDOException $e){
           print('Error:'.$e->getMessage());
           die();
       }
       /*
       * メール送信処理
       * 登録されたメールアドレスへメールをお送りする。
       */
   	$mailTo = $mail;
       $body = <<< EOM
この度はご登録いただきありがとうございます。
24時間以内に下記のURLからご登録下さい。
{$url}
EOM;
       mb_language('ja');
       mb_internal_encoding('UTF-8');
	   $companymail = "rating.b.20@gmail.com";
	   $companyname = "Rating_B";
	   $registation_subject = "本登録用メール";

       //Fromヘッダーを作成
       $header = 'From: ' . mb_encode_mimeheader($companyname). ' <' . $companymail. '>';
   
       if(mb_send_mail($mailTo, $registation_subject, $body, $header, '-f'. $companymail)){      
           //セッション変数を全て解除
           $_SESSION = array();
           //クッキーの削除
           if (isset($_COOKIE["PHPSESSID"])) {
               setcookie("PHPSESSID", '', time() - 1800, '/');
           }
           //セッションを破棄する
           session_destroy();
           $message = "ただいまメールをお送りしました。<br>24時間以内にメールに記載されたURLからご登録下さい。";
       }
   }
}
?>