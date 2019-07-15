<?php if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../index.php');</script>";
	}


		include "../db_connect.php";
		$con=Connect();

		if ($_POST['msg']!=''){
			if (mysqli_fetch_array(mysqli_query($con,"SELECT EXISTS (SELECT username from info where username='".$_POST['user2']."')"),MYSQLI_NUM)[0])
			{
			$msg=htmlspecialchars($_POST['msg']);
			$stmt=mysqli_prepare($con,"INSERT INTO messages (user1,user2,msg,time) VALUES (?,?,?,SYSDATE())");
			mysqli_stmt_bind_param($stmt,"sss",$_POST['user1'],$_POST['user2'],$msg);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);

			$_POST['msg']='';
			echo "<script>location.replace('../message.php');</script>";
			}
			
			else{?>
			<form action="../message.php" method="POST" id="resend">
				<input type="text" name="post-fail" value="No such user found" hidden="true" readonly="true">
				<input type="text" name="user2" value="<?php echo $_POST['user2']?>" hidden="true" readonly="true">
				<textarea name="msg" hidden="true" readonly="true"><?php echo $_POST['msg']?></textarea>
			</form>
			<?php echo "<script>document.getElementById('resend').submit();</script>";
			}
		}
		else
			echo "<script>location.replace('../message.php');</script>";

		
?>

<title>Sending Message</title>
<a href="../message.php">Go Back</a> if you are not redirected.
