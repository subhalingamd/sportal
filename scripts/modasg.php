<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../login.php');</script>";
	}

	include "../db_connect.php";
	
	if ($_SESSION['user']['role']!='admin')
		echo "<script>location.replace('../assg.php');</script>";
	

	if ($_POST['aname']!='')
	{
		$con=Connect();
		$stmt=mysqli_prepare($con,"UPDATE assignments SET stime=?,dur=?,etime=?,password=? where aid=?");
		mysqli_stmt_bind_param($stmt,"sssss",$_POST['stime'],$_POST['dur'],$_POST['etime'],$_POST['password'],$_POST['aid']);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);	
		Close($con);
		$_POST['aname']='';
	}
	echo "<script>location.replace('../assg.php');</script>";


?>


<title>Modifying Assignments</title>
<a href="../assg.php">Go Back</a> if you are not redirected.

	

<a href="assg.php">Go Back</a>
