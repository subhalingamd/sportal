<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../index.php');</script>";
	}

	include "../db_connect.php";
	if ($_SESSION['user']['role']!='admin')
		echo "<script>location.replace('../assg.php');</script>";


	if ($_POST['aname']!='')
	{
		$con=Connect();
		$stmt=mysqli_prepare($con,"DELETE FROM reqasg where manager=? ");
		mysqli_stmt_bind_param($stmt,"s",$_POST['manager']);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		$aname=htmlspecialchars($_POST['aname']);
		$stmt=mysqli_prepare($con,"INSERT INTO messages (user1,user2,msg,time) VALUES ('admin',?,CONCAT('Your request for adding a new assignment in the name of \'',?,'\' has been rejected. Kindly contact the Admin office for more details. The inconvinience caused is regretted.'),SYSDATE())");
		mysqli_stmt_bind_param($stmt,"ss",$_POST['manager'],$aname);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		Close($con);
		$_POST['aname']='';
}

	echo "<script>location.replace('../assg.php');</script>";

?>
	


<title>Rejecting requested Assignments</title>
<a href="../assg.php">Go Back</a>  if you are not redirected.
