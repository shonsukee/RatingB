<?php
if(empty($_GET)) {
	$_SESSION['login_err'] = 'ユーザを登録してログインしてください．';

	header("Location: ../login/public/login_form.php");
	exit();
}