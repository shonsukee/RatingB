<!DOCTYPE html>
<html lang="ja">
<?php include_once('../html/title.php') ?>
<body>
	<?php include('../html/header.php') ?>
	<h1>
	すでに投稿されているので投稿ページへ遷移します．
	</h1>

	<script>

		if(window.conform("already exist.")){
			<?php
			header('Location: ../html/imageDetail.php?id=' . $_GET["id"]);
			exit();
			?>
		}
	</script>

</body>
</html>