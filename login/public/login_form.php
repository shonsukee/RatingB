<?php
session_start();

require_once '../classes/UserLogic.php';

$result = UserLogic::checkLogin();
if($result){
	header('Location: ../../html/index.php');
	return;
}
if(isset($_SESSION['login_err'])){
	$error = $_SESSION['login_err'];
}
$err = $_SESSION; 

//sessionを消す
$_SESSION = array(); 
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../temp/register.css">
	<link rel="icon" href="../../tmpImages/favicon.png">
	<title>Rating B</title>
</head>
<body>
	<div class="head">
		<img src="../../tmpImages/logo.png" alt="logo">
	</div>
	<div class="registerField">
		<div class="box-line">
			<div class="box-inner">
				<h1>Rating B</h1>
				<h2>Sign in</h2>
				<form action="login.php" method="POST">
					<label for="email">メールアドレス</label>
					<input type="email" class="inchar" placeholder="メールアドレス" name="email">
					<?php if (isset($err['email'])) : ?>
						<p><?php echo $err['email']; ?> </p>
					<?php endif; ?>
		
					<label for="password">パスワード</label>
					<input type="password" class="inchar" placeholder="パスワード" name="password">
					<?php if (isset($err['password'])) : ?>
						<p><?php echo $err['password']; ?> </p>
					<?php endif; ?>
				
					<input type="hidden" name="token" value="<?=$token?>">
					<input type="submit" class="submitchar" name="submit" value="サインイン">
				</form>
				<div class="divider-inner">
					新規登録は<a href="../temp/signup_mail.php">こちら</a>
				</div>
			</div>
		</div>
	</div>
	</body>
</html>