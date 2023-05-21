<?php
session_start();

require_once '../login/classes/UserLogic.php';
require_once '../login/functions.php';

?>

<!DOCTYPE html>
<html lang="ja">
	<?php include_once('./title.php') ?>
<body>
	<?php include_once('../database/dbconnect.php') ?>
	<?php include('../getDatas.php') ?> <!--$pdoが使える-->
	<?php include('./header.php') ?>
	<?php include('./average.php') ?>

	<div class="imageList">
		<?php foreach ($data as $image) { ?> <!--降順に画像を貼る-->
			<div class="comment">
				<a href="./imageDetail.php?id=<?php echo $image["id"]; ?>">
					<?php $img = $image["book_url"];
					$json = file_get_contents($img);	// 書籍情報を取得
					$book = json_decode($json);			// デコード（objectに変換）
					?>
					<img src="<?php echo $book->volumeInfo->imageLinks->thumbnail; ?>" alt="画像">
					<p>
						<b>『<?php echo $image["book_name"]; ?>』</b><br />
						著者：<?php 
						$authcount = 0;
						foreach($book->volumeInfo->authors as $author){
							if($authcount < 2){
								echo $author; 
							} else if ($authcount == 2){
								echo "...etc";
							}
							$authcount++;
						}
						?>
					</p>
					<div class="average-score mb3">
						<div class="star-rating ml-2">
							<div class="star-rating-front" style="width: <?php echo average::avgScore($image["id"], 20);?>%" >★★★★★</div>
							<div class="star-rating-back">★★★★★</div>
						</div>
						<div class="average-score-display">
							<?php echo "(" . average::avgScore($image["id"], 1) . "点)"; ?>
						</div>
						<div class="commentNum">
							<?php echo "コメント:" . average::avgScore($image["id"], 0) . "件"?>
						</div>
					</div>
				</a>
			</div>
		<?php };?>
	</div>
	<div class="ref">
		<a target="_blank" href="https://icons8.com/icon/T42h1yujoQQG/%E3%83%AA%E3%83%9D%E3%82%B8%E3%83%88%E3%83%AA%E3%83%BC">リポジトリー</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
	</div>
</body>
</html>