<?php
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

	if (!isset($_POST['username']) or $_POST['username']==''){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../index.php');</script>";
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Logging in...</title>
</head>
<body>

<?php
	include "../db_connect.php";
	$con=Connect();

	if (!preg_match('/^[a-zA-Z0-9]+$/',$_POST['username'])){
		echo "Invalid username/password";
	}
	
	else{

	$res=mysqli_query($con,"SELECT * from info where username='".$_POST['username']."' and password='".md5($_POST['password'])."'");

	$name=mysqli_fetch_assoc($res);
	if (!isset($name['adm_no']))
		{
			echo "Invalid username/password";

		}		
	else {	
	$temp=mysqli_fetch_assoc(mysqli_query($con,"SELECT list.name,list.email,list.dob,list.mobile from list,info where info.adm_no='".$name['adm_no']."' and list.adm_no=info.adm_no"));
	$name['name']=$temp['name'];
	$name['email']=$temp['email'];
	$name['mobile']=$temp['mobile'];
	$name['dob']=$temp['dob'];
	$_SESSION['user']=$name;
	$t=mysqli_query($con,"UPDATE info set active=SYSDATE() where username='".$_SESSION['user']['username']."'");
	echo "Signing in...<script>location.replace('index.php');</script>";
	}
	}
	Close($con);
	
?>

</body>
</html>