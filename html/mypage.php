<?php
session_start();

require_once '../login/classes/UserLogic.php';
require_once '../login/functions.php';

//ログインしているか判定して，していなかったら新規登録画面へ返す
$result = UserLogic::checkLogin();

if(!$result){
	$err = [];
	$err['login_err'] = 'ユーザを登録してログインしてください．';
	header('Location: ../login/temp/signup_mail.php');
	return;
}

$login_user = $_SESSION['login_user'];
?>

<!DOCTYPE html>
<html lang="ja">
	<?php include_once('./title.php') ?>
<body>
	<?php include_once('../database/dbconnect.php') ?>
	<?php include_once('../login/database/dbconnect.php') ?>
	<?php include('./header.php') ?>
	<?php include('./average.php') ?>
	<?php include('./getBooks.php') ?>

	<div class="myField">
		<div class="my-line">
			<div class="my-inner">
				<h1>マイページ</h1>
				<p>ユーザ名：<?php echo h($login_user['name']) ?></p>
				<p>メールアドレス：<?php echo h($login_user['mail']) ?></p>
				<p>コメントした本：<?php echo count($data);?>冊</p>
			</div>
		</div>
	</div>

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
		<?php }?>
	</div>
	
</body>
</html>