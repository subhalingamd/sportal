<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../login.php');</script>";
	}

	include "../db_connect.php";

	
	if (!isset($_POST['aid']) or $_POST['aid']=='')
		echo "<script>location.replace('../analysis.php');</script>";

	if (!preg_match("/^[0-9]+$/", $_POST['aid']))
		die ("<h1>Submission failed</h1>Assignment ID was found to be altered");	

	$con=Connect();
	$tot_qs=mysqli_num_rows(mysqli_query($con,"SELECT qid from questions where aid ='".$_POST['aid']."'"));

	if ($_SESSION['user']['role']='faculty')
	{

		$resp=mysqli_query($con,"SELECT * from a".$_POST['aid']);


		for ($j=0;$j<$tot_qs;$j++){
			$temp=mysqli_fetch_assoc(mysqli_query($con,"SELECT marks,pen from questions where qid ='".$_POST['qid'][$j]."'"));
			$answers[$_POST['qid'][$j]][]=$temp['pen'];			
			$answers[$_POST['qid'][$j]][]=$temp['marks'];
		}

		

		while ($resp1=mysqli_fetch_assoc($resp)){

		$marks=0.0;
		for ($j=0;$j<$tot_qs;$j++){

		$ans=$resp1['q'.$_POST['qid'][$j]];

		if ($_POST['qtype'][$j]=='scq'){	
			if ($_POST[$_POST['qid'][$j]]=='*')
				$marks+=floatval($answers[$_POST['qid'][$j]][1]);		
			elseif ($ans=='')
				$marks+=0;
			elseif (in_array($ans, explode('/', $_POST[$_POST['qid'][$j]])))
				$marks+=floatval($answers[$_POST['qid'][$j]][1]);
			else
				$marks+=floatval($answers[$_POST['qid'][$j]][0]);
			mysqli_query($con,"UPDATE questions set ans='".$_POST[$_POST['qid'][$j]]."' where qid ='".$_POST['qid'][$j]."'");

		}
		elseif ($_POST['qtype'][$j]=='mcq'){
			if ($_POST[$_POST['qid'][$j]]=='*')
				$marks+=floatval($answers[$_POST['qid'][$j]][1]);		
			elseif ($ans=='')
				$marks+=0;
			elseif (in_array($ans, explode('/', $_POST[$_POST['qid'][$j]])))
				$marks+=floatval($answers[$_POST['qid'][$j]][1]);
			else
				$marks+=floatval($answers[$_POST['qid'][$j]][0]);
			mysqli_query($con,"UPDATE questions set ans='".$_POST[$_POST['qid'][$j]]."' where qid ='".$_POST['qid'][$j]."'");
		}
		elseif ($_POST['qtype'][$j]=='mat'){


			for ($k=0;$k<4;$k++){
			if (explode(',',$_POST[$_POST['qid'][$j]])[$k]=='*')
				$marks+=floatval($answers[$_POST['qid'][$j]][1])/4;		
			elseif ($ans[2*$k]=='-')
				$marks+=0;
			elseif (in_array($ans[2*$k], explode('/', explode(',',$_POST[$_POST['qid'][$j]])[$k])))
				$marks+=floatval($answers[$_POST['qid'][$j]][1])/4;		
			else
				$marks+=floatval($answers[$_POST['qid'][$j]][0])/4;
			}
			mysqli_query($con,"UPDATE questions set ans='".$_POST[$_POST['qid'][$j]]."' where qid ='".$_POST['qid'][$j]."'");
		}
		elseif ($_POST['qtype'][$j]=='int'){
			if ($_POST[$_POST['qid'][$j]]=='*')
				$marks+=floatval($answers[$_POST['qid'][$j]][1]);		
			elseif ($ans=='')
				$marks+=0;
			elseif (in_array($ans, explode('/', $_POST[$_POST['qid'][$j]])))
				$marks+=floatval($answers[$_POST['qid'][$j]][1]);
			else
				$marks+=floatval($answers[$_POST['qid'][$j]][0]);
			mysqli_query($con,"UPDATE questions set ans='".$_POST[$_POST['qid'][$j]]."' where qid ='".$_POST['qid'][$j]."'");		
		}
		elseif ($_POST['qtype'][$j]=='num'){
			if ($_POST[$_POST['qid'][$j]][0]=='*')
				$marks+=floatval($answers[$_POST['qid'][$j]][1]);		
			elseif ($ans=='')
				$marks+=0;
			elseif (floatval($ans)>=(floatval($_POST[$_POST['qid'][$j]][0])-floatval($_POST[$_POST['qid'][$j]][1])) and floatval($ans)<=(floatval($_POST[$_POST['qid'][$j]][0])+floatval($_POST[$_POST['qid'][$j]][1])))
				$marks+=floatval($answers[$_POST['qid'][$j]][1]);		
			else
				$marks+=floatval($answers[$_POST['qid'][$j]][0]);
			mysqli_query($con,"UPDATE questions set ans='".$_POST[$_POST['qid'][$j]][0]."', relax='".$_POST[$_POST['qid'][$j]][1]."' where qid ='".$_POST['qid'][$j]."'");		
		}
		}
		mysqli_query($con,"UPDATE a".$_POST['aid']." SET marks='".$marks."' where username='".$resp1['username']."'");

	}
	if ($_POST['freeze']=='1')
	mysqli_query($con,"UPDATE assignments SET finalised=1 where aid='".$_POST['aid']."'");
	Close($con);
} 

echo "<script>location.replace('../analysis.php');</script>";

?>



<title>Updating key changes</title>
<a href="..analysis/.php">Go Back</a> if you are not redirected.
