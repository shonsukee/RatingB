<?php
session_start();

require_once '../classes/UserLogic.php';

    // エラーメッセージ
    $err = [];

    $email = filter_input(INPUT_POST, 'email');
    if(!preg_match("/^[a-zA-Z0-9_.+-]+[@][a-zA-Z0-9.-]+$/", $email)){
        $err['email'] = 'メールアドレスを入力してください';
    }

    if(!$password = filter_input(INPUT_POST, 'password'))
	{
		$err['password'] = 'パスワードを入力して下さい．';
	}

    if(count($err) > 0) {	//エラー時は戻す
		$_SESSION = $err;
		header('Location: login_form.php');
		return;
    }
	$result = UserLogic::login($email, $password);
	
	if(!$result) {	//ログイン失敗時，DBのemail若しくはパスワードが一致しないため
		if($_SESSION['msg'] == 'emailが存在しません．新規登録してください．'){
			$err["email"] = $_SESSION['msg'];
		} else if($_SESSION['msg'] == 'パスワードが一致しません'){
			$err["password"] = $_SESSION['msg'];
		} 
		$_SESSION = $err;
		header('Location: login_form.php');
		return;
	}
	
	header('Location: ../../html/index.php');

?>
