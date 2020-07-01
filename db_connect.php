<?php
function Connect(){
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "mysql";
	$db="sportal";
	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed! Try setting up again");
return $conn;
}

function Close($conn){
	$conn -> close();
}
?>