<?php include("check.php");?>
<?php session_destroy(); ?>
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
	<div class="head">
		<img src="../../tmpImages/logo.png" alt="logo">
	</div>
	<div class="registerField">
		<div class="box-line">
			<div class="box-inner">
				<div class="parent">
					<h1>Rating B</h1>
					<h2>新規登録</h2>
					<?php if (isset($err['email'])) : ?>
						<p><?php echo $err['email']; ?> </p>
					<?php endif; ?>
					<?php if (isset($_POST['submit']) && count($errors) === 0): ?>
						<!-- 登録完了画面 -->
						<p class="sendMassage"><?=$message?></p>
						<p>↓TEST用(後ほど削除)：このURLが記載されたメールが届きます。</p>
						<a href="<?=$url?>"><?=$url?></a>
					<?php else: ?>
						<!-- 登録画面 -->
						<div id="loginMail">	
							<?php if(count($errors) > 0): ?>
							<?php
								foreach($errors as $value){
									echo "<p class='error'>".$value."</p>";
									}
									?>
							<?php endif; ?>
							<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">
								<p>メールアドレス</p>
								<input type="text" class="inchar" placeholder="メールアドレス" name="mail" size="50" value="<?php if( !empty($_POST['mail']) ){ echo $_POST['mail']; } ?>">
								<input type="hidden" name="token" value="<?=$token?>">
								<input type="submit" class="submitchar" name="submit" value="送信">
							</form>
							<div class="divider-inner">
								すでにアカウントをお持ちですか？<a href="../public/login_form.php">サインイン</a>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
	