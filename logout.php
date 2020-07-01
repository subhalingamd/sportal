<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('login.php');</script>";
	}
?>


<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style/main.css">
	<link rel="stylesheet" type="text/css" href="style/popup.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- IMPORT FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Lato&display=swap" rel="stylesheet">

	<!-- ICONS !-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css'>

	<!-- JQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

	<!-- Menu -->
	<title>Logged Out | <?php echo $_SESSION['user']['name']?></title>

</head>
<body>

<?php 
 	include "db_connect.php";
	$con=Connect();
	$t=mysqli_query($con,"UPDATE info set active=SYSDATE() where username='".$_SESSION['user']['username']."'");
	Close($con);
 	session_unset();
 	session_destroy();
?>

			<div class="popup" id="logout">
			<div class="popup-content animate">
				<div class="box">
					<div class="h">Logged Out</div>
					<div>Logged out successfully! Kindly go back and login again.</div>
					<a class="btn" href="login.php">Home</a>	
				</div>
			</div>
			</div>
		<?php echo"<script>document.getElementById('logout').style.display='block';</script>";
		?>

</body>
</html>