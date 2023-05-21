<!DOCTYPE html>
<html lang="ja">
	<head>
		<?php include_once('../html/title.php') ?>
	</head>
	<body>
		<?php include('../html/header.php') ?>
		<?php include('../database/dbconnect.php') ?>
		<?php 
		$url = $_GET["link"];
		$authors = $_GET["author"];

		//SQLにすでに保存されていないか確認する
		$check = "SELECT id FROM bookimages WHERE book_url = :book_url";
		$stmt = $dbh->prepare($check);
		$stmt->bindValue(":book_url", $url, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if(isset($result["id"])){
			header('Location: ./exist.php?id=' . $result["id"]);
			exit();
		}

		// 書籍情報を取得
		$json = file_get_contents($url);

		// デコード（objectに変換）
		$book = json_decode($json);
		// タイトル
		$title = $book->volumeInfo->title;
		// サムネ画像
		$thumbnail = $book->volumeInfo->imageLinks->thumbnail;
		
		?>

		<div class="one_book_item">
		<img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>"><br />
		<p>
			<b>『<?php echo $title; ?>』</b><br />
			著者：<?php echo $authors; ?>
		</p>
		</div>

		<div class="submitImage">
			<form action="./postBook.php?link=<?php echo $book->selfLink;?>" method="post" enctype="multipart/form-data">
				<div class="radio">
					<input type="radio" id="inq1" name="num" value="1"> <label for="inq1">★</label>
					<input type="radio" id="inq2" name="num" value="2"><label for="inq2">★★</label>
					<input type="radio" id="inq3" name="num" value="3" checked><label for="inq3">★★★</label>
					<input type="radio" id="inq4" name="num" value="4"><label for="inq4">★★★★</label>
					<input type="radio" id="inq5" name="num" value="5"><label for="inq5">★★★★★</label>
				</div>
				<div class="oneComment">
					<textarea name="comment" id="comment" cols="40" rows="10" placeholder="コメントを入力してください！"></textarea>
				</div>
				<button type="submit" name="submit" class="bottomButton">送信</button>
			</form>
				<button onclick="location.href='./getinfo.php';" class="bottomButton">戻る</button>
		</div>
	</body>
</html>