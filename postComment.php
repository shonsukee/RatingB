<?php

include('./database/dbconnect.php');
$imageId = $_GET['image_id']; /**画像idを取ってくる */
$comment = $_POST['comment']; /**textareaのname=commentの文字を取ってくる, imageDetail.php */

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($comment)) {
	$insert = $dbh->query("INSERT INTO comments (image_id, comment) VALUES (" . $imageId . ",'" . $comment . "')");

	if($insert) {
		$uri = $_SERVER['HTTP_REFERER'];
		header('Location: ' . $uri, true, 303);
		exit();
	}
} else {
	$uri = $_SERVER['HTTP_REFERER'];
	header('Location: ' . $uri, true, 303);
	exit();
}

?>