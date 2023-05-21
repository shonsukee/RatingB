<?php

include("../database/dbconnect.php");

session_start();


print_r($_SESSION);
$imageId = $_GET['image_id']; //imageDetailから取ってきた値?imageId=*
$userId = $_SESSION['login_user']['id'];
$comment = htmlspecialchars($_POST['comment'], ENT_QUOTES);
$evalution = $_POST['num'];

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($comment)) {
	$insert = $dbh->query("INSERT INTO bookcomments (image_id, user_id, comment, commentEva) VALUES (" . $imageId . ",". $userId . ",'" . $comment . "', " . $evalution . ")");

	if($insert){
		$uri = $_SERVER['HTTP_REFERER'];//どのページから来たのかわかる
		header('Location:' . $uri, true, 303);//来たページ(imageDetail)に戻る
		exit();
	}
} else {
	$uri = $_SERVER['HTTP_REFERER'];//どのページから来たのかわかる
	header('Location:' . $uri, true, 303);//来たページ(imageDetail)に戻る
	exit();
}

?>