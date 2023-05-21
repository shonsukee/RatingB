<?php
	// ユーザがコメントした本の情報を取ってくる
	$sql = 	"SELECT * FROM bookimages where id IN (SELECT image_id FROM bookcomments where user_id = " . $login_user["id"] . ")";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$data = $sth->fetchAll(); //SQL文で取得したデータの取り出し

	return $data;
?>