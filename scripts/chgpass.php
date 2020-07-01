<?php if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../login.php');</script>";
}

include "../db_connect.php";
?>

<title>Changing Password | <?php echo $_SESSION['user']['name']?></title>

Loading...
<a href="../profile.php">< Go Back</a>

<hr>

<?php
$con=Connect();
$check=mysqli_fetch_array(mysqli_query($con,"SELECT password from info where username='".$_SESSION['user']['username']."'"),MYSQLI_NUM);
$_SESSION['msg']['chg-pass_res-id']=0;
if (md5($_POST['curpass'])!=$check[0]){
	$_SESSION['msg']['chg-pass_res']="Current password entered is INCORRECT! Please Try again.";
}
elseif ($_POST['newpass'][0]!=$_POST['newpass'][1])
{
	$_SESSION['msg']['chg-pass_res']="Both the New passwords entered DONOT match! Try again.";
}
else{
	$stmt=mysqli_prepare($con,"UPDATE info SET password= ? where username='".$_SESSION['user']['username']."'");
	$pwd=md5($_POST['newpass'][0]);
	mysqli_stmt_bind_param($stmt,"s",$pwd);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	$_SESSION['msg']['chg-pass_res']="Password changed successfully! It's time to logout.";
	$_SESSION['msg']['chg-pass_res-id']=1;
}
Close($con);
echo "<script>location.replace('../profile.php')</script>";
?>
