<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('index.php');</script>";
	}


	if (!isset($_POST['aid']) or $_POST['aid']=='')
		echo "<script>location.replace('assg.php');</script>";
	if ($_SESSION['user']['role']=='admin')
		echo "<script>location.replace('assg.php');</script>";

	include "db_connect.php";
	$con=Connect();
?>



<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style/main.css">
	<link rel="stylesheet" type="text/css" href="style/form.css">
	<link rel="stylesheet" type="text/css" href="style/popup.css">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- IMPORT FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Lato&display=swap" rel="stylesheet">

	<!-- ICONS !-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css'>

	<!-- JQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

	<!-- Menu -->
	<script type="text/javascript">
		function ToggleNav(){
			$('ul.nav').slideToggle("slow");
		}
	</script>

	<title><?php echo $_POST['aname']." > Analysis"?></title>

	<style type="text/css">
		input,select,textarea{
			min-width: 0;
			padding: 3px;
		}
		a.btn{
			display: inline-block;
			min-width: 0;
		}
	
	</style>

	<script type="text/javascript">
		// Close opup when user clicks outside the content area
		window.onclick = function(event) {
   			if (event.target == document.getElementById('batch-marks')) {
        		document.getElementById('batch-marks').style.display = "none";
    		}
   			if (event.target == document.getElementById('batch-marks-disable')) {
        		document.getElementById('batch-marks-disable').style.display = "none";
    		}
		}
	</script>
</head>
<body>


	<?php if (!preg_match("/^[0-9]+$/", $_POST['aid'])){?>
		<div class="popup" style="background-color: #aaaaaa; display: block;">
			<div class="popup-content animate">
				<div class="box">
						<div class="h">Unknown Error</div>
						<div>Snap!! There was an error and that's all we know. You can try again...</div>
						<div><a class="btn" href="../assg.php">Go Back</a></div>
				</div>
			</div>
		</div>
	<?php die(); }

		 if (!mysqli_fetch_array(mysqli_query($con,"SELECT aid from assignments where aid='".$_POST['aid']."' and SYSDATE()>assignments.etime"))[0]){?>
		<div class="popup" style="background-color: #aaaaaa; display: block;">
			<div class="popup-content animate">
				<div class="box">
						<div class="h">Unknown Error</div>
						<div>Snap!! There was an error and that's all we know. You can try again after sometime...</div>
						<div><a class="btn" href="assg.php">Go Back</a></div>
				</div>
			</div>
		</div>
	<?php die(); } ?>


		<header>
			<div id="toggle"><a onclick="$('ul.nav').slideToggle('slow');"><i class="material-icons" style="font-size:1.5em;color:#fff">menu</i></a></div>
			<div class="profile">
				<div class="prof-name"><a href="assg.php" style="color: #fff; display: inline-block; font-size: 0.8em; padding-right: 12px;"><i class="fas fa-chevron-left"></i></a><?php echo $_SESSION['user']['name']; ?></div>
			</div>
			<ul class="nav">
				<li><a href="index.php">Home</a></li>
				<li><a href="assg.php">Assignments</a></li>
				<li><a href="message.php">Messages</a></li>
				<li><a href="news.php">Announcements</a></li>
				<li><a href="search.php">Find user</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</header>
		<div class="container">

		<div class="title" align="center"><?php echo $_POST['aid']."-".$_POST['aname']?></div>

	<?php
	if ($_SESSION['user']['role']=='faculty') {
	$i=0;
	$res=mysqli_query($con,"SELECT qid,q,qtype,opt1,opt2,opt3,opt4,ans,relax,marks,pen FROM questions WHERE aid='".$_POST['aid']."'");
	
	$maxmark=mysqli_fetch_array(mysqli_query($con,"SELECT maxmarks,finalised from assignments where aid='".$_POST['aid']."'"),MYSQLI_NUM);
	$stat=mysqli_fetch_array(mysqli_query($con,"SELECT AVG(marks),MAX(marks) from a".$_POST['aid']));
	
?>
		<a class="btn" style="background-color: #000; color: #fff;">Question-wise Analysis</a> &nbsp&nbsp 
		<a class="btn" onclick=
					<?php if ($maxmark[1])
								 echo "document.getElementById('batch-marks').style.display='block'";
						else 
							echo "document.getElementById('batch-marks-disable').style.display='block'"; ?> 
				style="width:auto;">Batch marks</a><br><br>


	<div class="table">

	<form action="scripts/keychg.php" method="POST">

	<input type="text" value="<?php echo $_POST['aid']?>" readonly="true" hidden="true" name="aid">
	<table>
		<thead>
		<tr>
			<th>Q#</th>
			<th>Description</th>
			<th>Marking Scheme</th>
		</tr>
		</thead>
		<tbody>

<?php
	while ($temp=mysqli_fetch_assoc($res)){
		$i++;
?>		
		
		<tr>
			<input type="text" value="<?php echo $temp['qid']?>" readonly="true" hidden="true" name="qid[]"><input type="text" value="<?php echo $temp['qtype']?>" readonly="true" hidden="true" name="qtype[]">

			<td valign="top"><?php echo $i?></td>

			<td><?php echo nl2br($temp['q']); ?><br>

			<?php if ($temp['qtype']=='scq'){?> 
			<ol type="A">
				<li><?php echo nl2br($temp['opt1'])?> </li>
				<li><?php echo nl2br($temp['opt2'])?> </li>
				<li><?php echo nl2br($temp['opt3'])?> </li>
				<li><?php echo nl2br($temp['opt4'])?> </li>
			</ol>

			
			<?php if ($maxmark[1]) {?>
				<b>Correct Answer: </b><input type="text" name="<?php echo $temp['qid']?>" value="<?php echo $temp['ans']?>" readonly="true">
			<?php } else { ?>

			<b>Given Answer: </b><input type="text" readonly="true" value="<?php echo $temp['ans']?>"><br>
			<label><b>Correct Answer: </b><input type="text" pattern="\*|([A-D](/[A-D]){0,3})" name="<?php echo $temp['qid']?>" value="<?php echo $temp['ans']?>" required="true" placeholder="New Answer"></label>
		<?php }?>
			<br><br>

		<?php } elseif ($temp['qtype']=='mcq'){?> 
			<ol type="A">
				<li><?php echo nl2br($temp['opt1'])?> </li>
				<li><?php echo nl2br($temp['opt2'])?> </li>
				<li><?php echo nl2br($temp['opt3'])?> </li>
				<li><?php echo nl2br($temp['opt4'])?> </li>
			</ol>

			
			<?php if ($maxmark[1]) {?>
				<b>Correct Answer: </b><input type="text" name="<?php echo $temp['qid']?>" value="<?php echo $temp['ans']?>" readonly="true">
			<?php } else { ?>

			<b>Given Answer: </b><input type="text" readonly="true" value="<?php echo $temp['ans']?>"><br>
			<label><b>Correct Answer: </b><input type="text" pattern="\*|([A-D]{1,4}(/[A-D]{1,4}){0,14})" name="<?php echo $temp['qid']?>" value="<?php echo $temp['ans']?>" required="true" placeholder="New Answer"></label>
		<?php }?>
			<br><br>
		
		<?php } elseif ($temp['qtype']=='mat') {?> 	
			<ol type="A" start="16">
				<li><?php echo nl2br($temp['opt1'])?></li>
				<li><?php echo nl2br($temp['opt2'])?></li>
				<li><?php echo nl2br($temp['opt3'])?></li>
				<li><?php echo nl2br($temp['opt4'])?></li>
			</ol>
			
			<?php if ($maxmark[1]) {?>
				<b>Correct Answer: </b><input type="text" name="<?php echo $temp['qid']?>" value="<?php echo $temp['ans']?>" readonly="true">
			<?php } else { ?>
			

			<b>Given Answer: </b><input type="text" readonly="true" value="<?php echo $temp['ans']?>"><br>
			<label><b>Correct Answer: </b><input type="text" name="<?php echo $temp['qid']?>" value="<?php echo $temp['ans']?>" pattern="(\*|([P-S](/[P-S]){0,3})),(\*|([P-S](/[P-S]){0,3})),(\*|([P-S](/[P-S]){0,3})),(\*|([P-S](/[P-S]){0,3}))" required="true" placeholder="New Answer"></label>
		<?php  }?>
			<br><br>
			
		
		<?php } elseif ($temp['qtype']=='int') {?> 
			<br>
			<?php if ($maxmark[1]) {?>
			<b>Correct Answer: </b><input type="text" name="<?php echo $temp['qid']?>" value="<?php echo $temp['ans']?>" readonly="true">
			
			<?php } else { ?>
			<b>Given Answer: </b><input type="text" readonly="true" value="<?php echo $temp['ans']?>"><br>
			<label><b>Correct Answer: </b><input type="text" name="<?php echo $temp['qid']?>" value="<?php echo $temp['ans']?>" pattern="\*|([0-9](/[0-9]){0,9})" required="true" placeholder="New Answer"></label>
			 <?php } ?>
			 <br><br>
			
		
		<?php } elseif ($temp['qtype']=='num') {?> 
			<br>
			<?php if ($maxmark[1]) {?>
				<b>Correct Answer: </b><input type="text" name="<?php echo $temp['qid']?>[]" value="<?php echo $temp['ans']?>" readonly="true" >&nbsp&nbsp&nbsp<b>Relaxation: </b><input type="text" name="<?php echo $temp['qid']?>[]" value="<?php echo $temp['relax']?>" required="true" placeholder="New Relaxation"> <br>

			<?php if ($temp['ans']!='*'){?>
			<i>(* Range:  </i><b>[<?php echo (floatval($temp['ans'])-floatval($temp['relax'])) ?>,<?php echo (floatval($temp['ans'])+floatval($temp['relax']))?>]</b><i> )</i><br><br>
		<?php } 
		 } else { ?>

			<b>Given Answer: </b><input type="text"  readonly="true" value="<?php echo $temp['ans']?>">&nbsp&nbsp&nbsp<b>Relaxation: </b><input type="text" readonly="true" value="<?php echo $temp['relax']?>"><br>
			<?php if ($temp['ans']!='*'){?>
			<i>(* Range:  </i><b>[<?php echo (floatval($temp['ans'])-floatval($temp['relax'])) ?>,<?php echo (floatval($temp['ans'])+floatval($temp['relax']))?>]</b><i> )</i><br><br>
		<?php } ?>
			<label><b>Correct Answer: </b><input type="text" name="<?php echo $temp['qid']?>[]" value="<?php echo $temp['ans']?>" pattern="\*|([-+]?[0-9]*[.,]?[0-9]+)" required="true" placeholder="New Answer">&nbsp&nbsp&nbsp <input type="text" name="<?php echo $temp['qid']?>[]" value="<?php echo $temp['relax']?>" pattern="\*|([-+]?[0-9]*[.,]?[0-9]+)" required="true" placeholder="New Relaxation"></label> <?php } ?>
			<br><br>

		<?php } ?>

		</td>
		<td valign="top">
			Correct: <b><?php echo $temp['marks']?></b><br>
			Wrong: <b><?php echo $temp['pen'] ?></b><br>
			Unattempted: <b>0</b>
		</td>
	</tr>

	
<?php } ?>

		</tbody>
		</table>
		<br>


<?php if (!$maxmark[1]) { ?>

<hr><br>* Use <b>*</b> to denote questions that do not have a matching option or is ambiguous or for any other reasons, to ignore the question while evaluating. Note that such questions will be treated as 'BONUS' and marks will be awarded for every student for such questions.<br>
* To give more than one choice as answer, use <b>/</b> WITHOUT any blank spaces.<i>For ex, <b>A/B/C</b>, <b>A/AC/ACD</b>, <b>P/Q/R,Q/R,S,P/Q/R/S</b>, <b>3/4</b> for Single correct, One or more than one correct, Matrix Match and Single Integer type questions respectively.</i> Note that Numerical Type answers CANNOT have <b>/</b> due to Relaxation process <i>(they can only be given as 'BONUS')</i>.<br>
<i>* Matrix match type can have both <b>/</b> and <b>*</b>,i.e, P/Q/R,*,*,Q denotes P/Q/R as the answer to the first subquestion, Q for fourth and the second and third part are given as 'BONUS'</i>.<br><br><br>

<label for="freeze-checkbox"><input type="checkbox" name="freeze" id="freeze-checkbox" value="1">&nbsp Finalise Answer Key (no further changes can be made)</label>
<input type="submit" style="float: right; right: 12px; min-width: 0;">Submit Answer Key changes</a>
</form>


	<div class="popup" id="batch-marks-disable">
		<div class="popup-content animate">
				<div class="box">
					<div class="h">Batch Marks<span onclick="document.getElementById('batch-marks-disable').style.display='none'" class="close" style=" float: right;">&times</span></div>
					<div>Freeze/Finalise the answer key to view the Batch Marks.</div>
				</div>
		</div>
	</div>



<?php } else { ?>


	<div class="popup" id="batch-marks">
		<div class="popup-content animate">
				<div class="box">
					<div class="h">Batch Marks<span onclick="document.getElementById('batch-marks').style.display='none'" class="close" style=" float: right;">&times</span></div>
					<div>
						<?php	$res=mysqli_query($con,"SELECT * FROM a".$_POST['aid']." order by username");
	
						?>
						<table>
						<thead>
						<tr>
							<td class="sticky">Username</td>
							<td>Marks</td>
							<?php for ($j=0;$j<$i;$j++){?>
								<td><?php echo "Q".($j+1);?></td>
							<?php } ?>
						</tr>

						<tr>
							<th class="sticky"><center>CORRECT ANSWERS</center></th>
							<th><center><?php echo mysqli_fetch_array(mysqli_query($con,"SELECT maxmarks from assignments where aid='".$_POST['aid']."'"))[0];?></center></th>
							<?php $q1=mysqli_query($con,"SELECT qtype,ans,relax from questions where aid='".$_POST['aid']."'");
				while ($q=mysqli_fetch_array($q1)){
				if ($q[0]=='num'){
				?>
				<?php

				if ($q[1]=='*')
					echo '*';
					else  echo "[".(floatval($q[1])-floatval($q[2])).",".(floatval($q[1])+floatval($q[2]))."]";?></b></th>
					<?php }
					else{
				?>
				<th><center><?php echo $q[1]?></center></th>
			<?php }}?>
		</tr>
	</thead>
	<tbody>

		<?php
			while ($res1=mysqli_fetch_array($res,MYSQLI_NUM)){
				?>
				<tr>
					<?php
						$x=0;
						 foreach($res1 as $r)
					 {if ($x==0){
						?>
						<td class="sticky"><a href="search.php?id=<?php echo $r?>&by=username"><?php echo $r;?></a></td><?php $x++;}
						else {?><td><?php echo $r; }?></td>
					<?php }
					?>
				</tr>
			<?php }?>
		</tbody>
		</table>


					</div>
						
				</div>
		</div>
	</div>



<table>	
	<tr>
		<td>Maximum Mark:</td>
		<td><b><?php echo $maxmark[0]?></b></td>
	</tr>
	<tr>
		<td>Average Mark:</td>
		<td><b><?php echo $stat[0]?></b></td>
	</tr>
	<tr>
		<td>Highest Mark:</td>
		<td><b><?php echo $stat[1]?></b></td>
	</tr>
</table>
	<?php } ?>
</div>

<!--------------------------------------------------------------------->
<?php 
	}









	else {
	$i=0;
	$res=mysqli_query($con,"SELECT qid,q,qtype,opt1,opt2,opt3,opt4,ans,relax,marks,pen FROM questions WHERE aid='".$_POST['aid']."'");
	$ans=mysqli_fetch_array(mysqli_query($con,"SELECT * from a".$_POST['aid']." where username='".$_SESSION['user']['username']."'"));
	$maxmark=mysqli_fetch_array(mysqli_query($con,"SELECT maxmarks from assignments where aid='".$_POST['aid']."'"));
	$stat=mysqli_fetch_array(mysqli_query($con,"SELECT AVG(marks),MAX(marks) from a".$_POST['aid']));
	?>


	<div class="table">
	<table>
		<thead>
		<tr>
			<th>Q#</th>
			<th>Description</th>
			<th>Marking Scheme</th>			
		</tr> 
	</thead>
	<tbody>

<?php
	while ($temp=mysqli_fetch_assoc($res)){
		$i++;
?>
		<tr>
			<td valign="top"><?php echo $i?>
			</td>
			<td><?php echo nl2br($temp['q']); ?><br>
			<?php if ($temp['qtype']=='scq' or $temp['qtype']=='mcq'){

				?> 
			<ol type="A">
				<li><?php echo nl2br($temp['opt1'])?> </li>
				<li><?php echo nl2br($temp['opt2'])?> </li>
				<li><?php echo nl2br($temp['opt3'])?> </li>
				<li><?php echo nl2br($temp['opt4'])?> </li>
			</ol><br>
			<b>Your Answer: </b><input type="text" placeholder="NOT ATTEMPTED" readonly="true" value="<?php echo $ans[$i+1]?>"><br>
			<b>Correct Answer: </b><input type="text" readonly="true" value="<?php echo $temp['ans']?>">
			<br><br><br>
		
		<?php } elseif ($temp['qtype']=='mat') {?> 	
			<ol type="A" start="16">
				<li><?php echo nl2br($temp['opt1'])?> </li>
				<li><?php echo nl2br($temp['opt2'])?> </li>
				<li><?php echo nl2br($temp['opt3'])?> </li>
				<li><?php echo nl2br($temp['opt4'])?> </li>
			</ol><br>
			<b>Your Answer: </b><input type="text" placeholder="NOT ATTEMPTED" readonly="true" value="<?php echo $ans[$i+1]?>"><br>
			<b>Correct Answer: </b><input type="text" readonly="true" value="<?php echo $temp['ans']?>">
			<br><br><br>
			
		
		<?php } elseif ($temp['qtype']=='int') {?> 
			<br>
			<b>Your Answer: </b><input type="text" placeholder="NOT ATTEMPTED" readonly="true" value="<?php echo $ans[$i+1]?>"><br>
			<b>Correct Answer: </b><input type="text" readonly="true" value="<?php echo $temp['ans']?>">
			 <br><br><br>
			
		
		<?php } elseif ($temp['qtype']=='num') {?> 
			<br>
			<b>Your Answer: </b><input type="text" readonly="true" placeholder="NOT ATTEMPTED" value="<?php echo $ans[$i+1]?>"><br>
			<b>Correct Answer: </b><input type="text" readonly="true" value="<?php echo $temp['ans']?>">&nbsp&nbsp&nbsp<b>Relaxation: </b><input type="text" readonly="true" value="<?php echo $temp['relax']?>"><br>
			
			<?php if ($temp['ans']!='*'){?>
			<i>(* Answer should lie in </i><b>[<?php echo (floatval($temp['ans'])-floatval($temp['relax'])) ?>,<?php echo (floatval($temp['ans'])+floatval($temp['relax']))?>]</b><i> for earning credits)</i>
				<?php } ?>




			<br><br><br>
			
		
		<?php } ?>





		</td>
		<td valign="top">
			Correct: <b><?php echo $temp['marks']?></b><br>
			Wrong: <b><?php echo $temp['pen'] ?></b><br>
			Unattempted: <b>0</b>
		</td>
	</tr>


	
<?php } ?>

	</tbody>	
</table>
<table>
	<tr>
		<td>Your Mark:</td>
		<td><b><?php echo $ans[1]?></b></td>
	</tr>
	<tr>
		<td>Maximum Mark:</td>
		<td><b><?php echo $maxmark[0]?></b></td>
	</tr>
	<tr>
		<td>Average Mark:</td>
		<td><b><?php echo $stat[0]?></b></td>
	</tr>
	<tr>
		<td>Highest Mark:</td>
		<td><b><?php echo $stat[1]?></b></td>
	</tr>
</table>
<br><br>
<b>*</b> denotes 'BONUS' question; you will be awarded full marks for such questions.
</div>

<?php 
}

Close($con); ?>

</div>
	<footer>
		<div class="copy">&copy All Rights Reserved</div>
		<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
	</footer>
</body>
</html>





