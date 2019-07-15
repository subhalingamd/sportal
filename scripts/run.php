<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../index.php');</script>";
	}
	
	if (!isset($_POST['aid']) or $_POST['aid']=='')
		echo "<script>location.replace('../assg.php');</script>";
	if ($_SESSION['user']['role']=='admin')
		echo "<script>location.replace('../assg.php');</script>";
	if (!preg_match("/[0-9]+/",$_POST['aid']))
		echo "<script>location.replace('../assg.php');</script>";
	 if ($_POST['password']!='' and !preg_match("/[A-Za-z0-9]+/",$_POST['password']))
		echo "<script>location.replace('../assg.php');</script>";
	include "../db_connect.php";
	$con=Connect();
?>


<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_SESSION['user']['username']." :: ".$_POST['aname']?></title>

	<link rel="stylesheet" type="text/css" href="../style/main.css">
	<link rel="stylesheet" type="text/css" href="../style/popup.css">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- IMPORT FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Lato&display=swap" rel="stylesheet">

	<!-- ICONS !-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css'>

	<!-- JQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
</head>
<body>

	<div class="popup" style="display: block; background-color: #aaaaaa;">
		<div class="popup-content animate">
			<div class="box">
				<?php if ($_SESSION['user']['role']!='faculty'){
					if (!mysqli_fetch_array(mysqli_query($con,"SELECT info.username from info,assignments where assignments.aid='".$_POST['aid']."' and info.username='".$_SESSION['user']['username']."' and assignments.role='".$_SESSION['user']['role']."' and info.role=assignments.role and SYSDATE()>=assignments.stime and SYSDATE()<=assignments.etime"))[0]){?>
						<div class="h">Unknown Error</div>
						<div>Snap!! There was an error and that's all we know. You can try again...</div>
						<div><a class="btn" href="../assg.php">Go Back</a></div>
					<?php }
					elseif (mysqli_fetch_array(mysqli_query($con,"SELECT password from assignments where aid='".$_POST['aid']."'"))[0]!=$_POST['key']){	?>
				<div class="h">Incorrect Key</div>
				<div>The Key you submitted cannot be used to open this Assignment as it is INCORRECT. Try again and make sure you that you enter the correct key or contact the Admin if you have tried enough!</div>
				<div><a class="btn" href="../assg.php">Go Back</a></div>
				<?php } 



				elseif (mysqli_fetch_array(mysqli_query($con,"SELECT etime from timer where username='".$_SESSION['user']['username']."' and aid='".$_POST['aid']."'"),MYSQLI_NUM)[0]){
					$token=mysqli_fetch_array(mysqli_query($con,"SELECT token from timer where aid='".$_POST['aid']."' and username='".$_SESSION['user']['username']."'"),MYSQLI_NUM)[0];?>
					<div class="h">Resuming</div>
					<div>Please wait for a moment while your page is getting ready...</div>
					<form action="../run.php" method="POST" id="ready">
					<input type="text" hidden="true" readonly="true" name="aid" value="<?php echo $_POST['aid'];?>"><input type="text" hidden="true" readonly="true" name="aname" value="<?php echo $_POST['aname'];?>"><input type="text" hidden="true" readonly="true" name="token" value="<?php echo $token ?>">
					</form>
					<script>$('#ready').submit();</script>

				<?php }
					else {
						if (!mysqli_fetch_array(mysqli_query($con,"SELECT token from timer where username='".$_SESSION['user']['username']."' and aid='".$_POST['aid']."'"),MYSQLI_NUM)[0]){
						mysqli_query($con,"INSERT into timer (username,aid,token) values ('".$_SESSION['user']['username']."','".$_POST['aid']."',MD5(CONCAT(SYSDATE(),'".$_POST['aid']."','".$_POST['key']."')))");}
						$token=mysqli_fetch_array(mysqli_query($con,"SELECT token from timer where aid='".$_POST['aid']."' and username='".$_SESSION['user']['username']."'"),MYSQLI_NUM)[0]; ?>
				<div class="h">Get Ready</div>
				<div><b>You are one step behind from starting your assignment.</b> <br>By clicking Start, your session will start and the timer will be set in the server. Kindly note that your timer won't be stopped for any reasons that are within your control, even if this window is closed. If you face any technical issues, inform an invigilator immediately. Note the Admin can decide to change the timer in the server, if required-so don't panic!. All the best!</div>
				<form action="../run.php" method="POST" id="ready">
					<input type="text" hidden="true" readonly="true" name="aid" value="<?php echo $_POST['aid'];?>"><input type="text" hidden="true" readonly="true" name="aname" value="<?php echo $_POST['aname'];?>"><input type="text" hidden="true" readonly="true" name="token" value="<?php echo $token ?>">
					<div><a class="btn" onclick="$('#ready').submit()">Start</a>
					</div>
				</form>

				<?php }
			} 
			else{ ?>
						<div class="h">Unknown Error</div>
						<div>Snap!! There was an error and that's all we know. You can try again...</div>
						<div><a class="btn" href="../assg.php">Go Back</a></div> 
			<?php } ?>
			</div>
		</div>
	</div>
</body>
</html>