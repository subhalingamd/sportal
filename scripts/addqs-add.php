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

						echo "<h1>Unknown Error</h1>Time for adding questions in this assignment might have got over. We cannot think of any other reason(s) at this point of time...";
		die(); } 

	if ($_POST['q']!='')
	{
		if (preg_match("/^[0-9]+$/",$_POST['aid']) and preg_match("/^[-+]?[0-9]*[.,]?[0-9]+$/",$_POST['marks'])){
		$stmt=mysqli_prepare($con,"INSERT into questions (q,qtype,opt1,opt2,opt3,opt4,aid,ans,relax,marks,pen) values (?,?,?,?,?,?,?,?,?,?,?) ");
		$q=htmlspecialchars($_POST['q']);
		$opt1=htmlspecialchars($_POST['opt1']);
		$opt2=htmlspecialchars($_POST['opt2']);
		$opt3=htmlspecialchars($_POST['opt3']);
		$opt4=htmlspecialchars($_POST['opt4']);

		mysqli_stmt_bind_param($stmt,"sssssssssss",$q,$_POST['qtype'],$opt1,$opt2,$opt3,$opt4,$_POST['aid'],$_POST['ans'],$_POST['relax'],$_POST['marks'],$_POST['pen']);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		$qid=mysqli_fetch_array(mysqli_query($con,"SELECT LAST_INSERT_ID () from questions"),MYSQLI_NUM);
		mysqli_query($con,"ALTER TABLE a".$_POST['aid']." ADD q".$qid[0]." TINYTEXT");
		mysqli_query($con,"UPDATE assignments set maxmarks=maxmarks+".$_POST['marks']." where aid='".$_POST['aid']."'");
		}
		$_POST['q']='';
	} Close($con); ?>

		<form id="addqs" action="../addqs.php" method="POST">
		<input type="text" name="aid" value="<?php echo $_POST['aid']?>" readonly="true" hidden="true"><input type="text" name="aname" value="<?php echo $_POST['aname']?>" readonly="true" hidden="true">
		</form>

		<script>
			document.getElementById("addqs").submit();
		</script>

	
<title>Adding Question</title>
<a href="../assg.php">Go Back</a> if you are not redirected.
