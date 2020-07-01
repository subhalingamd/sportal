<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('login.php');</script>";
	}
	
	include "../db_connect.php";
		$con=Connect();

	if (!isset($_POST['aid']) or $_POST['aid']=='')
		echo "<script>location.replace('../assg.php');</script>";
	if ($_SESSION['user']['role']!='faculty')
		echo "<script>location.replace('../assg.php');</script>";

	if (!mysqli_fetch_array(mysqli_query($con,"SELECT info.username from info,assignments where assignments.aid='".$_POST['aid']."' and assignments.manager='".$_SESSION['user']['username']."' and assignments.manager=info.username and SYSDATE()<assignments.stime"))[0]){

						echo "<h1>Unknown Error</h1>Time for modifying questions in this assignment might have got over. We cannot think of any other reason(s) at this point of time...";
		die(); } 

	if ($_POST['qid']!='')
	{
		if (preg_match("/^[0-9]+$/",$_POST['aid']) and preg_match("/^[0-9]+$/",$_POST['qid']) and preg_match("/^[a-f0-9]+$/",$_POST['token'])){
		$mark=mysqli_fetch_array(mysqli_query($con,"SELECT marks from questions where qid='".$_POST['qid']."' and aid='".$_POST['aid']."' and MD5(2*aid-1)='".$_POST['token']."'"),MYSQLI_NUM)[0];
		$q=htmlspecialchars($_POST['q']);
		$opt1=htmlspecialchars($_POST['opt1']);
		$opt2=htmlspecialchars($_POST['opt2']);
		$opt3=htmlspecialchars($_POST['opt3']);
		$opt4=htmlspecialchars($_POST['opt4']);
		
		$stmt=mysqli_prepare($con,"UPDATE questions SET q=?,qtype=?,opt1=?,opt2=?,opt3=?,opt4=?,ans=?,relax=?,marks=?,pen=? where qid='".$_POST['qid']."' and aid='".$_POST['aid']."' and MD5(2*aid-1)='".$_POST['token']."'");
		mysqli_stmt_bind_param($stmt,"ssssssssss",$q,$_POST['qtype'],$opt1,$opt2,$opt3,$opt4,$_POST['ans'],$_POST['relax'],$_POST['marks'],$_POST['pen']);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		$mark=mysqli_prepare($con,"UPDATE assignments set maxmarks=(maxmarks-".floatval($mark)."+?) where aid='".$_POST['aid']."'");
		mysqli_stmt_bind_param($mark,"d",$_POST['mark']);
		mysqli_stmt_execute($mark);
		mysqli_stmt_close($mark);
		}
		
		$_POST['qid']='';
	} Close($con) ;?>

		<form id="editqs" action="../addqs.php" method="POST">
		<input type="text" name="aid" value="<?php echo $_POST['aid']?>" readonly="true" hidden="true"><input type="text" name="aname" value="<?php echo $_POST['aname']?>" readonly="true" hidden="true">
		</form>

		<script>
			document.getElementById("editqs").submit();
		</script>

	
<title>Updating Question</title>
<a href="../assg.php">Go Back</a> if you are not redirected.
