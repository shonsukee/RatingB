<?php include_once('./title.php') ?>
<body>
	<?php include('./header.php') ?>
	<div class="submitImage">
		<?php if(isset($_GET['id'])){ ?>
			<form action="./updateImage.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
		<?php } else {?>
			<form action="../postImage.php" method="post" enctype="multipart/form-data">
		<?php } ?>
			<img id="preview"><!--画像表示-->
			<input type="file" name="file" onchange="previewFile(this);"><!--画像ファイル選択-->
			<div class="bookName">
				<textarea name="bookTitle" id="bookTitle" cols="20" rows="10" placeholder="書籍名を入力してください！"></textarea>
			</div>
			<div class="radio">
				<input type="radio" id="inq1" name="num" value="1"> <label for="inq1">★</label>
				<input type="radio" id="inq2" name="num" value="2"><label for="inq2">★★</label>
				<input type="radio" id="inq3" name="num" value="3" checked><label for="inq3">★★★</label>
				<input type="radio" id="inq4" name="num" value="4"><label for="inq4">★★★★</label>
				<input type="radio" id="inq5" name="num" value="5"><label for="inq5">★★★★★</label>
			</div>
			<textarea name="comment" id="comment" cols="40" rows="10" placeholder="コメントを入力してください！"></textarea>
			<button type="submit" name="submit">送信</button>
		</form>
		<button onclick="location.href='./index.php';" class="backButton">戻る</button>
	</div>
</body>


<script>
  function previewFile(event){
    var fileData = new FileReader();
    fileData.onload = (function() {
      document.getElementById('preview').src = fileData.result;
    });
    fileData.readAsDataURL(event.files[0]);
  }
  </script>