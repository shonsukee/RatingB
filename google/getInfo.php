<?php

// 検索条件を配列にする
$params = array(
  'intitle'  => $_POST["bookTitle"],  //書籍タイトル
  'inauthor' => $_POST["author"],       //著者
);

$params["intitle"] = str_replace(array(" ", "　"), "", $params["intitle"]);
$params["inauthor"] = str_replace(array(" ", "　"), "", $params["inauthor"]);

// 1ページあたりの取得件数
$maxResults = 40;

// ページ番号（1ページ目の情報を取得）
$startIndex = 0;  //欲しいページ番号-1 で設定

// APIの基本になるURL
$base_url = 'https://www.googleapis.com/books/v1/volumes?q=';

if($params["intitle"] == "" && $params["inauthor"] == ""){
	$_SESSION['search_err'] = 'タイトル若しくは著者名を入力してください．';
	header('Location:./search.php');
	return;
}

if($params["intitle"] != ""){
	$base_url .= 'intitle:' . $params["intitle"] . '+';
}
if($params["inauthor"] != ""){
	$base_url .= 'inauthor:' . $params["inauthor"] . '+';
}

// 末尾につく「+」をいったん削除
$params_url = substr($base_url, 0, -1);
// 件数情報を設定
$url = $params_url.'&maxResults='.$maxResults.'&startIndex='.$startIndex;

// 書籍情報を取得
$json = file_get_contents($url);

// デコード（objectに変換）
$data = json_decode($json);

// 全体の件数を取得
if(isset($data->totalItems)){
	$total_count = $data->totalItems;
}

if(isset($data->items)){
	// 書籍情報を取得
	$books = $data->items;
	
	// 実際に取得した件数
	$get_count = count($books);
} else {
	$get_count = 0;
}

?>

<!DOCTYPE html>
<html lang="ja">
<?php include_once('../html/title.php') ?>
<body>
	<?php include("../html/header.php");?>
	
	<!-- 1件以上取得した書籍情報がある場合 -->
	<?php if($get_count > 0): ?>
		<div class="imageList">		
			<!-- 取得した書籍情報を順に表示 -->
			<?php foreach($books as $book):
				if(isset($book->volumeInfo->imageLinks) && isset($book->volumeInfo->authors)){
					// タイトル
					$title = $book->volumeInfo->title;
					// サムネ画像
					$thumbnail = $book->volumeInfo->imageLinks->thumbnail;
					// 著者（配列なのでカンマ区切りに変更）
					$authors = implode(',', $book->volumeInfo->authors);
					?>
					
					<a href="./oneImage.php?link=<?php echo $book->selfLink; ?>&author=<?php echo $authors; ?>">
						<div class="loop_books_item">
							<img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>"><br />
							<p>
								<b>『<?php echo $title; ?>』</b><br />
								著者：<?php 
								$authcount = 0;
								foreach($book->volumeInfo->authors as $author){
									if($authcount < 3){
										echo $author; 
									} else if ($authcount == 3){
										echo "...etc";
									}
									$authcount++;
								}
								?>
							</p>
						</div>
					</a>
			<?php } ?>
		<?php endforeach; ?>
	</div><!-- ./loop_books -->

  <!-- 書籍情報が取得されていない場合 -->
  <?php else: ?>
	<?php 
	$query = "『  ";
	$query .= $params['intitle'] . "  ";
	$query .= $params['inauthor'] . "  ";
	$query .= "』";
	?>
    <p class="result"><?php echo $query . "は見つかりませんでした．"?></p>
  <?php endif; ?>

</body>
</html>