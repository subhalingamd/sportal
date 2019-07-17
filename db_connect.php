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

function Check(){
	session_start();
	if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		header ("Location: index.php");
	}
}
?>