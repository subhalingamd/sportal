<?php if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		
		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
			if (!(isset($_POST['username']) and isset($_POST['aid']) and $_POST['username']!='')){
				session_unset();
				session_destroy();
				echo "<script>location.replace('../index.php');</script>";
			}
		}


if (!isset($_POST['aid']) or $_POST['aid']=='')
echo "<script>location.replace('../assg.php');</script>";

if ($_POST['token']=='' or !preg_match("/^[a-f0-9]+$/", $_POST['token']))
die ("<h1>Submission failed</h1>You don't have a valid token to proceed.");

if ($_SESSION['user']['username']!='' and $_SESSION['user']['username']!=$_POST['username'])
die ("<h1>Submission failed</h1>There was an issue while validating your identity.");

if (!preg_match("/^[A-Za-z0-9]+$/", $_POST['username']))
die ("<h1>Submission failed</h1>There was an issue while validating your identity.");

if (!preg_match("/^[0-9]+$/", $_POST['aid']))
die ("<h1>Submission failed</h1>Assignment ID was found to be altered");

		
include "../db_connect.php";
$con=Connect();

$token=mysqli_fetch_array(mysqli_query($con,"SELECT token from timer where aid='".$_POST['aid']."' and username='".$_POST['username']."'"),MYSQLI_NUM)[0];

if ($token!=$_POST['token'])
die ("<h1>Submission failed</h1>A valid token was not found for this assignment and user in the server.");


	if ($_SESSION['user']['role']!='admin' or $_SESSION['user']['role']!='faculty')
	{
	mysqli_query($con,"INSERT into a".$_POST['aid']." (username) VALUES ('".$_POST['username']."')");
	$tot_qs=mysqli_num_rows(mysqli_query($con,"SELECT qid from questions where aid ='".$_POST['aid']."'"));
	
	$marks=0.0;
	for ($j=0;$j<$tot_qs;$j++){
		$crt=mysqli_fetch_assoc(mysqli_query($con,"SELECT ans,relax,marks,pen from questions where qid ='".$_POST['qid'][$j]."'"));

		
		if ($_POST['qtype'][$j]=='scq'){
			$ans=$_POST[$_POST['qid'][$j]];
			if ($crt['ans']=='')
				$marks+=0;
			elseif ($ans==$crt['ans'])
				$marks+=floatval($crt['marks']);
			elseif ($ans=='')
				$marks+=0;
			else
				$marks+=floatval($crt['pen']);
		}
		elseif ($_POST['qtype'][$j]=='mcq'){
			$ans="";
			for ($k=0;$k<count($_POST[$_POST['qid'][$j]]);$k++)
			$ans.=$_POST[$_POST['qid'][$j]][$k];
			if ($crt['ans']=='')
				$marks+=0;
			elseif ($ans==$crt['ans'])
				$marks+=floatval($crt['marks']);
			elseif ($ans=='')
				$marks+=0;
			else
				$marks+=floatval($crt['pen']);
		}
		elseif ($_POST['qtype'][$j]=='mat'){
			$ans="";
			for ($k=0;$k<3;$k++){
			$ans.=$_POST[$_POST['qid'][$j]][$k].",";
			if ($ans[2*$k]==$crt['ans'][2*$k])
				$marks+=floatval($crt['marks']/4);
			elseif ($ans[2*$k]=='-')
				$marks+=0;
			else
				$marks+=floatval($crt['pen']/4);
			}
			$ans.=$_POST[$_POST['qid'][$j]][$k];
			if ($crt['ans']=='')
				$marks+=0;
			elseif ($ans[2*$k]==$crt['ans'][2*$k])
				$marks+=floatval($crt['marks']/4);
			elseif ($ans[2*$k]=='-')
				$marks+=0;
			else
				$marks+=floatval($crt['pen']/4);
		}
		elseif ($_POST['qtype'][$j]=='int'){
			$ans=$_POST[$_POST['qid'][$j]];
			if ($crt['ans']=='')
				$marks+=0;
			elseif ($ans==$crt['ans'])
				$marks+=floatval($crt['marks']);
			elseif ($ans=='')
				$marks+=0;
			else
				$marks+=floatval($crt['pen']);
		}
		elseif ($_POST['qtype'][$j]=='num'){
			$ans=$_POST[$_POST['qid'][$j]];
			if ($ans=='')
				$marks+=0;
			elseif (floatval($ans)>=(floatval($crt['ans'])-floatval($crt['relax'])) and floatval($ans)<=(floatval($crt['ans'])+floatval($crt['relax'])))
				$marks+=floatval($crt['marks']);
			else
				$marks+=floatval($crt['pen']);
		}

		$stmt=mysqli_query($con,"UPDATE a".$_POST['aid']." SET q".$_POST['qid'][$j]." = '".$ans."' where username='".$_POST['username']."'");
		//mysqli_stmt_bind_param($stmt,"s",$ans);
		//mysqli_stmt_execute($stmt);
		//mysqli_stmt_close($stmt);

	}
	mysqli_query($con,"UPDATE a".$_POST['aid']." SET marks='".$marks."' where username='".$_POST['username']."'");
	mysqli_query($con,"DELETE from timer where username='".$_POST['username']."' and aid='".$_POST['aid']."'");

Close($con);
}
echo "<h1>Submission successful</h1>Your submission was successful. You will be redirected now...";
echo "<script>location.replace('../assg.php');</script>";
?>

