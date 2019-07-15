<?php
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

	if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../index.php');</script>";
	}

	$err="";

	if ($_FILES['prof-pic']['type']!='image/png' and $_FILES['prof-pic']['type']!='image/jpeg')
		$_SESSION['msg']['prof-pic_fail']="Upload an image file in PNG or JPEG format.";
	else{
		if ($_FILES['prof-pic']['size']>1048576)						// Change memory accordingly
			$_SESSION['msg']['prof-pic_fail']="Image size should not exceed 1MB.";
		else{
			$dir='../prof-pic/';
			$name=$_SESSION['user']['username'];
			if (move_uploaded_file($_FILES['prof-pic']['tmp_name'], $dir.$name.".png"))
				$_SESSION['msg']['prof-pic_fail']='';
			else 
   				$_SESSION['msg']['prof-pic_fail']=$_FILES['prof-pic']['error'];
		}
	}
	echo "<script>location.replace('../profile.php')</script>";
?>

<title>Updating Profile Picture | <?php echo $_SESSION['user']['name']?></title>

Your beautiful pic is taking some time to upload...<br>
<a href="../index.php">< Go Back if you are not redirected</a>

