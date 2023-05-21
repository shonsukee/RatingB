<?php

$username = 'root';
$pass = '';

try {
	$dbh = new PDO('mysql:host=localhost;dbname=login;charset=utf8', $username, $pass, 
	array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
		PDO::ATTR_EMULATE_PREPARES => true,));
		
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "<br/>";
    exit();
}


