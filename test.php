<?php

$dbName = 'mysql:host=localhost;dbname=imagepost;charset=utf8';
$username = 'root';
$pass = '';

try {
	$dbh = new PDO($dbName, $username, $pass);
	echo 'ok';
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "<br/>";
    exit();
}

 $qry = $dbh->query('select * from images');
 
 foreach($qry->fetchAll() as $q){
	$p = $q['id'];
	echo $p;
	echo "<br>";
}
echo $p;