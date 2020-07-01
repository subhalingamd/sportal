<?php if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../login.php');</script>";
	}

		if (!($_SESSION['user']['role']=='admin' or $_SESSION['user']['role']=='faculty'))
			echo "<script>location.replace('../news.php');</script>";

		include "../db_connect.php";
		$con=Connect();

		if ($_POST['msg']!=''){
			if (mysqli_fetch_array(mysqli_query($con,"SELECT EXISTS (SELECT username from info where username='".$_POST['user2']."' or role='".$_POST['user2']."' LIMIT 1)"),MYSQLI_NUM)[0])
			{
			$msg=htmlspecialchars($_POST['msg']);
			$stmt=mysqli_prepare($con,"INSERT INTO news (user1,user2,msg,time) VALUES (?,?,?,SYSDATE())");
			mysqli_stmt_bind_param($stmt,"sss",$_POST['user1'],$_POST['user2'],$msg);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);

			//$resp=mysqli_query($con,"INSERT INTO news (user1,user2,msg,time) VALUES ('".$_POST['user1']."','".$_POST['user2']."','".mysqli_real_escape_string($con,$_POST['msg'])."',SYSDATE())");
			$_POST['msg']='';
			echo "<script>location.replace('../news.php');</script>";
			}
			
			else{?>
			<form action="../news.php" method="POST" id="resend">
				<input type="text" name="post-fail" value="No such user/batch found" hidden="true" readonly="true">
				<input type="text" name="user2" value="<?php echo $_POST['user2']?>" hidden="true" readonly="true">
				<textarea name="msg" hidden="true" readonly="true"><?php echo $_POST['msg']?></textarea>
			</form>
			<?php echo "<script>document.getElementById('resend').submit();</script>";
			}
		}
		else
			echo "<script>location.replace('../news.php');</script>";

		
?>

<title>Adding Announcement</title>
<a href="../news.php">Go Back</a> if you are not redirected.
