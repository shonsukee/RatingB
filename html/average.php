<?php

class average{
	
	public static function avgScore($imageId, $num) { // 評価の平均を返す，引数の$imageIdはindexのimageId
		//ここから重複
		$username = 'root';
		$pass = '';
		
		try {
			$dbh = new PDO('mysql:host=localhost;dbname=imagepost;charset=utf8', $username, $pass, 
			array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
				PDO::ATTR_EMULATE_PREPARES => true,));
				
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage() . "<br/>";
			exit();
		}
		//ここまで重複

		$sql = "SELECT commentEva from bookcomments where image_id=" . $imageId; 
		//indexのimageIdと，同じimageIdをもつevaluationをcommentsテーブルから取ってくる

		$sth = $dbh->prepare($sql);
		$sth->execute();
		$evaluation = $sth->fetchAll(); 
		// $imageId = $_GET["id"];
		$avg_score = 0; 
		$count = count($evaluation);

		if($num == 0){
			return $count;
		}

		if(isset($evaluation)){
			for($i=0; $i<$count; $i++){
				$avg_score += $evaluation[$i]['commentEva'];
			}
		}

		$avg_score = round($avg_score / $count, 1);
		$avg_score *= $num;

		return $avg_score;
	}
}