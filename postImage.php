<?php

include('./database/dbconnect.php');

/*画像アップロード先を指定*/
$targetDirectory = 'tmpImages/';
$fileName = basename($_FILES["file"]["name"]); //basename->pathの最後のファイル名を返す，$_FILES['inputで指定したname']['name']->ファイル名
$targetFilePath = $targetDirectory . $fileName; //保存先パスをドットでつなぐ
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); //調べたいpath，どの要素を返すか．今回は最後の拡張子だけ返す

$evalution = $_POST["num"];
$comment = $_POST["comment"];
$bookTitle = $_POST["bookTitle"];

/* 投稿フォームから画像が送られているか確認 */
if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($fileName)) {
	$arrImageTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');
	if(in_array($fileType, $arrImageTypes)){
		$postImageForServer = move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath); //一時保存，保存先，DBへ保存前に画像をアップロードする

		/**画像をアップロード */
		if($postImageForServer){ //画像アップロードが成功したらDBに保存
			$insrt = $dbh->query("INSERT INTO images (file_name, book_title) VALUES ('" . $fileName . "', '" . $bookTitle . "')"); //imagesテーブルのfile_name属性(カラム)に$fileNameの値を保存	
			$ids = $dbh->prepare("SELECT id from images where file_name = :NAME");
			$ids->bindValue('NAME', $fileName);
			$ids->execute(); //int型で取得してる

			$rows = $ids->fetchAll();
			$cnt = count($rows);
			$id = $rows[$cnt - 1]['id'];
			$insrt = $dbh->query("INSERT INTO comments (image_id, comment, commentEva) VALUES (" . $id . ", '" . $comment . "'," . $evalution . ")");  //imagesテーブルのidをcommentsテーブルに保存できない
		}
	}
}

header('Location: ' . './html/index.php', true, 303); //指定ページに遷移
exit();