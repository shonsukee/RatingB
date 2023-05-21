<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="register.css">
	<link rel="icon" href="../../tmpImages/favicon.png">
	<title>Rating B</title>
</head>
<body>
	<?php include("../classes/UserLogic.php")?>
	<div class="head">
		<img src="../../tmpImages/logo.png" alt="logo">
	</div>
	<div class="registerField">
<?php
session_start();
//CSRF対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//成功・エラーメッセージの初期化
$errors = array();

//DB情報
$user = 'root';//データベースユーザ名
$password = '';//データベースパスワード
//DB接続
$dsn = "mysql:host=localhost;dbname=login;charser=utf8";
$pdo = new PDO($dsn, $user, $password);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(empty($_GET)) {
	header("Location: registration_mail");
	exit();
}else{
	//GETデータを変数に入れる
	$urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;
	//メール入力判定
	if ($urltoken == ''){
		$errors['urltoken'] = "トークンがありません。";
	}else{
		try{
			// DB接続	
			//flagが0の未登録者 and 仮登録日から24時間以内
			$sql = "SELECT mail FROM pre_user WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour";
            $stm = $pdo->prepare($sql);
			$stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$stm->execute();
			
			//レコード件数取得
			$row_count = $stm->rowCount();
			
			//24時間以内に仮登録され、本登録されていないトークンの場合
			if( $row_count ==1){
				$mail_array = $stm->fetch();
				$mail = $mail_array["mail"];
				$_SESSION['mail'] = $mail;
			}else{
				$errors['urltoken_timeover'] = "このURLはご利用できません。<br>有効期限が過ぎたかURLを間違えている可能性がございます。もう一度登録をやりなおして下さい。";
			}
			//データベース接続切断
			$stm = null;
		}catch (PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}
	}
}

/**
* 確認するボタンを押した後の処理
*/
if(isset($_POST['btn_confirm'])){
	if(empty($_POST)) {
		header("Location: registration_mail.php");
		exit();
	}else{
		//POSTされたデータを各変数に入れる
		$name = isset($_POST['name']) ? $_POST['name'] : NULL;
		$password = isset($_POST['password']) ? $_POST['password'] : NULL;
		
		//セッションに登録
		$_SESSION['name'] = $name;
		$_SESSION['password'] = $password;

		//アカウント入力判定
		//パスワード入力判定
		// バリデーション
		if(!$name = filter_input(INPUT_POST, 'name')){
			$errors['name'] = 'ユーザ名を入力してください';
		}
	
		$password = filter_input(INPUT_POST, 'password');
		//正規表現，英数字で8文字以上100文字以下
		if(!preg_match("/^[a-zA-Z0-9]{8,100}+$/", $password)){
			$errors['password'] = 'パスワードは英数字8文字以上100文字以下にしてください';
		} else {
			$password_hide = str_repeat('*', strlen($password));
		}
	
		$password_conf = filter_input(INPUT_POST, 'password_conf');
		if($password != $password_conf){
			$errors['password'] = '確認用パスワードと異なっています';
		}
	}
	
}

/**
* page_3
* 登録ボタンを押した後の処理
*/
if(isset($_POST['btn_submit'])){
	//パスワードのハッシュ化
	$password_hash =  password_hash($_SESSION['password'], PASSWORD_DEFAULT);

	//ここでデータベースに登録する
	try{
		$sql = "INSERT INTO registeruser (name,password,mail,status,created_at,updated_at) VALUES (:name,:password_hash,:mail,1,now(),now())";
        $stm = $pdo->prepare($sql);
		$stm->bindValue(':name', $_SESSION['name'], PDO::PARAM_STR);
		$stm->bindValue(':mail', $_SESSION['mail'], PDO::PARAM_STR);
		$stm->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
		$stm->execute();

		//pre_userのflagを1にする(トークンの無効化)
		$sql = "UPDATE pre_user SET flag=1 WHERE mail=:mail";
		$stm = $pdo->prepare($sql);
		//プレースホルダへ実際の値を設定する
		$stm->bindValue(':mail', $mail, PDO::PARAM_STR);
		$stm->execute();
						
		/*
		* 登録ユーザと管理者へ仮登録されたメール送信
       */
	 	$registation_mail_subject = "登録完了メール";
	 	$companymail = "Rating.B.20@gmail.com";
	 	$companyname = "井上";
	 	$mailTo = $mail.','.$companymail;
	 	$body = <<< EOM
この度はご登録いただきありがとうございます。
本登録致しました。
EOM;
	  mb_language("Japanese");
	  mb_internal_encoding('UTF-8');
	  
       //Fromヘッダーを作成
       $header = 'From: ' . mb_encode_mimeheader($companyname). ' <' . $companymail. '>';
   
       if(mb_send_mail($mailTo, $registation_mail_subject, $body, $header, '-f'. $companymail)){          
           $message['success'] = "会員登録しました";
       }else{
           $errors['mail_error'] = "メールの送信に失敗しました。";
		}	

		//データベース接続切断
		$stm = null;

		//セッション変数を全て解除
		$_SESSION = array();
		//セッションクッキーの削除
		if (isset($_COOKIE["PHPSESSID"])) {
				setcookie("PHPSESSID", '', time() - 1800, '/');
		}
		//セッションを破棄する
		session_destroy();

	}catch (PDOException $e){
		//トランザクション取り消し（ロールバック）
		// $pdo->rollBack();
		$errors['error'] = "もう一度やりなおして下さい。";
		print('Error:'.$e->getMessage());
	}
}
?>

	<div class="box-line">
		<div class="box-inner">
			<h1>アカウントを作成</h1>
			<!-- page_3 完了画面-->
			<?php if(isset($_POST['btn_submit']) && count($errors) === 0): ?>
				<?php $result = UserLogic::login($mail, $password); ?>
				<div class="finalMessage">
					本登録されました。
					<br>
					<a href="../../html/index.php">ホームページに移動</a>
				</div>
				
				<!-- page_2 確認画面-->
				<?php elseif (isset($_POST['btn_confirm']) && count($errors) === 0): ?>
					<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>?urltoken=<?php print $urltoken; ?>" method="post">
						<p>メールアドレス：<?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES)?></p>
						<p>パスワード：<?=$password_hide?></p>
						<p>氏名：<?=htmlspecialchars($name, ENT_QUOTES)?></p>
						<div class="checkButton">
							<input type="submit" name="btn_back" value="戻る">
							<input type="hidden" name="token" value="<?=$_POST['token']?>">
							<input type="submit" name="btn_submit" value="登録する">
						</div>
					</form>
			
			<?php else: ?>
		<!-- page_1 登録画面 -->
		<?php if(!isset($errors['urltoken_timeover'])): ?>
			<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>?urltoken=<?php print $urltoken; ?>" method="post">
				<p>メールアドレス</p>
				<?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?>

				<p>パスワード</p>
				<input type="password" class="inchar" name="password">
				<?php if (isset($errors['password'])) : ?>
					<p><?php echo $errors['password']; ?> </p>
				<?php endif; ?>
				<p>確認用パスワード</p>
				<input type="password" class="inchar" name="password_conf">
				<?php if (isset($errors['password_conf'])) : ?>
					<p><?php echo $errors['password_conf']; ?> </p>
				<?php endif; ?>

				<p>氏名</p>
				<input type="text" class="inchar" name="name" value="<?php if(isset($_SESSION['name'])){echo $_SESSION['name'];} ?>">
				<?php if (isset($errors['name'])) : ?>
					<p><?php echo $errors['name']; ?> </p>
				<?php endif; ?>
				
				<input type="hidden" name="token" value="<?=$token?>">
				<input type="submit" class="submitchar" name="btn_confirm" value="確認する">
			</form>
		</div>
	</div>
	<?php endif ?>
	<?php endif; ?>
</div>
</body>
</html>
