<?php
/**
 * XSS対策:エスケープ処理 //Webにアクセスすることで不正なスクリプトでcookieが盗まれる
 * 
 * @param string $str　対称の文字列
 * @param string 処理された文字列
 * 
 */
function h($str){
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); //エスケープする型，エスケープしたいもの，文字コード
}


/**
 * CSRF対策 //偽造されたリクエストが実行されないようにする
 * @param void
 * @return string $csrf_token
 */

function setToken() {
	//トークンを生成
	//フォームからそのトークンを送信	
	//送信後の画面でそのトークンを照会
	//トークンを削除
	$csrf_token = bin2hex(random_bytes(32)); //32バイトの暗号でトークンの生成
	$_SESSION['csrf_token'] = $csrf_token;

	return $csrf_token;
}