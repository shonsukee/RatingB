<?php

$uri = $_SERVER["REQUEST_URI"];

if(strpos($uri, 'imageDetail.php')) { //$uriのURLの中にimageDetail.phpが含まれていれば実行
	include('../html/incorect.php'); 
	$imageId = $_GET["id"]; //クエリパラメータ(?id=〇)に設定したidの値〇をとることができる

	//書籍名とurlを取得して$data["oneImage"]に格納
	$sql = "SELECT * from bookimages where id = " . $imageId;
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$data['oneImage'] = $sth->fetch(); //1レコードのみ取得，dataのoneImageという要素にデータを格納

	//commentを取得して$data["comments"]に格納
	$sql2 = "SELECT * from bookcomments where image_Id = " . $imageId;	
	$sth = $dbh->prepare($sql2);
	$sth->execute();
	$data['comments'] = $sth->fetchAll();
	$countComment = count($data["comments"]);

} else {
	$sql = "SELECT * from bookimages order by create_date desc";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$data = $sth->fetchAll(); //SQL文で取得したデータの取り出し
}


return $data;