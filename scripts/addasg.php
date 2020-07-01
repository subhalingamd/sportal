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

	if (preg_match("/^[A-Za-z0-9]+$/",$_POST['manager'])){

	if ($_POST['aname']!='')
	{
		$aname=htmlspecialchars($_POST['aname']);

		$con=Connect();
		$stmt=mysqli_prepare($con,"INSERT into assignments (aname,role,manager,stime,dur,etime,maxmarks,password) values (?,?,?,?,?,?,0,?) ");
		mysqli_stmt_bind_param($stmt,"sssssss",$aname,$_POST['role'],$_POST['manager'],$_POST['stime'],$_POST['dur'],$_POST['etime'],$_POST['password']);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		$aid=mysqli_fetch_array(mysqli_query($con,"SELECT LAST_INSERT_ID () from assignments"),MYSQLI_NUM);
		mysqli_query($con,"CREATE TABLE a".$aid[0]." (username varchar(32),marks float, PRIMARY KEY(username))");
		$news=mysqli_prepare($con,"INSERT INTO news (user1,user2,msg,time) VALUES ('admin',?,CONCAT('A new assignment in the name of \'',?,'\' has been added by ',?,'. This assignment will be open from ',?,' until ',?,'. Check your \'Assignments\' page for more details. All the best!'),SYSDATE())");
		mysqli_stmt_bind_param($news,"sssss",$_POST['role'],$aname,$_POST['manager'],$_POST['stime'],$_POST['etime']);
		mysqli_stmt_execute($news);
		mysqli_stmt_close($news);
		if (mysqli_fetch_array(mysqli_query($con,"SELECT EXISTS(SELECT manager FROM reqasg where manager='".$_POST['manager']."') "),MYSQLI_NUM)[0]){
			$rem=mysqli_prepare($con,"DELETE FROM reqasg where manager=? ");
			mysqli_stmt_bind_param($rem,"s",$_POST['manager']);
			mysqli_stmt_execute($rem);
			mysqli_stmt_close($rem);
			$msg=mysqli_prepare($con,"INSERT INTO messages (user1,user2,msg,time) VALUES ('admin',?,CONCAT('Your request for adding a new assignment in the name of \'',?,'\' has been accepted. You can go to the \'Assignments\' page and start uploading the questions now.'),SYSDATE())");
			mysqli_stmt_bind_param($msg,"ss",$_POST['manager'],$aname);
			mysqli_stmt_execute($msg);
			mysqli_stmt_close($msg);
		}
		else{
			$msg=mysqli_prepare($con,"INSERT INTO messages (user1,user2,msg,time) VALUES ('admin',?,CONCAT('A new assignment in the name of \'',?,'\' has been scheduled. You can go to the \'Assignments\' page and start uploading the questions now.'),SYSDATE())");
			mysqli_stmt_bind_param($msg,"ss",$_POST['manager'],$aname);
			mysqli_stmt_execute($msg);
			mysqli_stmt_close($msg);
		}

		Close($con);
		$_POST['aname']='';
	}
}
	echo "<script>location.replace('../assg.php');</script>";
?>
	
<title>Adding Assignments</title>
<a href="../assg.php">Go Back</a> if you are not redirected.
