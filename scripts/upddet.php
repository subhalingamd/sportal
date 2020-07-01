<?php if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../login.php');</script>";
}?>

<title>Updating Details | <?php echo $_SESSION['user']['name']?></title>
Loading...
<a href="../index.php">< Go Back</a>

<?php
include "../db_connect.php";
$con=Connect();

$check=mysqli_fetch_array(mysqli_query($con,"SELECT password from info where username='".$_SESSION['user']['username']."'"),MYSQLI_NUM);
if (!preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/",$_POST['email'] ) or !preg_match("/^[0-9]{8,}$/",$_POST['mobile'] )){
	$_SESSION['msg']['upd-det_res']="E-mail ID/Mobile Number entered cannot be validated. Please try again!";
}
else{

if (md5($_POST['pass'])!=$check[0]){
	$_SESSION['msg']['upd-det_res']="Current password entered is INCORRECT! Please Try again.";
}
else{
$stmt=mysqli_prepare($con,"UPDATE list SET email= ? , mobile= ? where adm_no='".$_SESSION['user']['adm_no']."'");
mysqli_stmt_bind_param($stmt,"ss",$_POST['email'],$_POST['mobile']);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
$_SESSION['user']['email']=$_POST['email'];
$_SESSION['user']['mobile']=mysqli_fetch_array(mysqli_query($con,"SELECT mobile from list where adm_no='".$_POST['adm_no']."'"),MYSQLI_NUM)[0];
$_SESSION['msg']['upd-det_res']="Profile updated successfully!";
}
}
Close($con);
echo "<script>location.replace('../profile.php')</script>";
?>



