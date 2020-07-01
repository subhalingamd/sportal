<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../login.php');</script>";
	}

	include "../db_connect.php";
	
	if ($_SESSION['user']['role']!='faculty')
		echo "<script>location.replace('../assg.php');</script>";

	if ($_POST['aname']!='')
	{
		$con=Connect();
		$aname=htmlspecialchars($_POST['aname']);
		$stmt=mysqli_prepare($con,"INSERT into reqasg (aname,role,manager,stime,dur,etime) values (?,?,?,?,?,?)");
		mysqli_stmt_bind_param($stmt,"ssssss",$aname,$_POST['role'],$_SESSION['user']['username'],$_POST['stime'],$_POST['dur'],$_POST['etime']);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		
		Close($con);
		$_POST['aname']='';
	}
	echo "<script>location.replace('../assg.php');</script>";

?>
	


<title>Requesting Assignments</title>
<a href="../assg.php">Go Back</a> if you are not redirected.
