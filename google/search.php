<!DOCTYPE html>
<html lang="ja">
<?php include_once('../html/title.php') ?>
	<body>
		<?php include("../html/header.php");?>
		<div class="searchImage">
			<form action="./getInfo.php" method="post" enctype="multipart/form-data">
				<div class="bookName">
					<div class="searchTitle">
						検索条件を指定してください．
					</div>
					<div class="formLeft">
						タイトル：
					</div>
					<div class="formRight">
						<textarea name="bookTitle" id="bookTitle" value maxlength="50" placeholder="タイトル"></textarea>
					</div>
					<div class="formLeft">
						著者名：
					</div>
					<div class="formRight">
						<textarea name="author" id="author" value maxlength="50" placeholder="著者名"></textarea>
					</div>
				</div>
				<button type="submit" name="submit" class="searchSubmit">送信</button>
			</form>
		</div>
	</body>
</html>