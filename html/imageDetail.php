<?php include_once('./title.php') ?>
<body>
	<link rel="stylesheet" href="../imageStyle.css">
	<?php include('../database/dbconnect.php'); ?>
	<?php include('../getDatas.php'); ?>
	<?php include('./header.php'); ?>
	<?php include('./average.php'); ?>
	<?php include('./incorect.php'); ?>

	<?php
	$uri = $_SERVER["REQUEST_URI"];

	if(strpos($uri, 'index.php')) { //getInfoから呼び出されたときに遷移するか問う
	?>
		<script>
			if (window.confirm("同じ本がすでに投稿されています．遷移しますか？")) {
				console.log("move!");
			} else {
				console.log("back!");
			}
		</script>
	<?php } ?>

	<div class="detailImageBox">
		<div class="detailImage">
			<?php
			$img = $data["oneImage"]["book_url"];		//DBから取得したURL
			$bookName = $data["oneImage"]["book_name"];	//DBから取得した書籍名

			// 書籍情報を取得
			$json = file_get_contents($img);
			$book = json_decode($json);					// デコード（objectに変換）
			?>
			<img src="<?php echo $book->volumeInfo->imageLinks->thumbnail; ?>" alt="画像">
			<p>
				<b>『<?php echo $bookName; ?>』</b><br />
				
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
					<div class="star-rating-front" style="width: <?php echo average::avgScore($data["oneImage"]["id"], 20); ?>%" >★★★★★</div>
					<div class="star-rating-back">★★★★★</div>
				</div>
				<div class="average-score-display">
					<?php echo "(" . average::avgScore($data["oneImage"]["id"], 1) . "点)"; ?>
				</div>
			</div>
			<div class="commentNum"><?php echo "コメント:" . count($data["comments"]) . "件"?></div>	
			<button onclick="location.href='./index.php';">戻る</button>
		</div>
		<div class="commentsub">
			<div class="commentAll">
				<p class="commentTitle">コメント</p>
				<?php
				// session_start();
				// echo $_SESSION['login_user']['id'];
				// print_r("<pre>");
				// print_r($_SESSION['login_user']);
				// print_r("</pre>");
				?>
				<ul class="commentScroll">
					<?php for($i=0; $i<$countComment; $i++){?>
						<!-- 要変更 -->
						<div class="star-rating-comment-front">
							<?php 
								for($j=0; $j<$data['comments'][$i]['commentEva']; $j++){
									echo "★";
								}
								?>
						</div>
						<div class="create-time">
							<?php echo $data['comments'][$i]['create_date'];?>
						</div>
						<li class="userComment"><?php echo $data['comments'][$i]['comment']; ?></li>
						
					<?php } ?>
				</ul>
			</div>
				<div class="submitComment">
					<form action="./postComment.php?image_id=<?php echo $_GET['id']; ?>" method="POST" enctype="multipart/form-data">
						<div class="radio">
							<input type="radio" id="inq1" name="num" value="1"> <label for="inq1">★</label>
							<input type="radio" id="inq2" name="num" value="2"><label for="inq2">★★</label>
							<input type="radio" id="inq3" name="num" value="3" checked><label for="inq3">★★★</label>
							<input type="radio" id="inq4" name="num" value="4"><label for="inq4">★★★★</label>
							<input type="radio" id="inq5" name="num" value="5"><label for="inq5">★★★★★</label>
						</div>
						<div class="commentLR">
							<textarea name="comment" id="comment" value maxlength="50" placeholder="コメントを入力してください！"></textarea>
						</div>
					<button type="submit" name="submit">送信</button>
				</form>
			</div>
		</div>
	</div>
</body>