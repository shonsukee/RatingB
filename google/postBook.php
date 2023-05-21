<?php
include('../database/dbconnect.php');

session_start();

$userId = $_SESSION['login_user']['id'];

/*画像アップロード先を指定*/
$url = $_GET["link"];
$evalution = $_POST["num"];
$comment = $_POST["comment"];

// 書籍情報を取得
$json = file_get_contents($url);
// デコード（objectに変換）
$book = json_decode($json);
// タイトル
$title = $book->volumeInfo->title;

if(strlen($title) > 33){
	if(ctype_alnum($title)){//ctype_alnumは英語か数字ならtrue
		$title = substr($title, 0, 29);//29文字 + ... を表示する ,  alfavet
	} else {
		$title = mb_substr($title, 0, 20);//29文字 + ... を表示する ,  マルチバイト文字
	}
	$etc = '...';
	$title = preg_filter('/$/', $etc, $title);
}

/* 投稿フォームから画像が送られているか確認 */
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($url) && isset($title)) {
		/**画像をアップロード */
			$insrt = $dbh->query("INSERT INTO bookimages (book_name, book_url) VALUES ('" . $title ."', '" . $url . "')"); //imagesテーブルのfile_name属性(カラム)に$fileNameの値を保存	
			$ids = $dbh->prepare("SELECT id from bookimages where book_url = :URL");
			$ids->bindValue("URL", $url);
			$ids->execute(); //int型で取得してる

			$rows = $ids->fetchAll();
			$cnt = count($rows);
			$id = $rows[$cnt - 1]['id'];
			$insrt = $dbh->query("INSERT INTO bookcomments (image_id, user_id, comment, commentEva) VALUES (" . $id . "," . $userId . ", '" . $comment . "'," . $evalution . ")");  
}

header('Location: ' . '../html/index.php', true, 303); //指定ページに遷移
exit();