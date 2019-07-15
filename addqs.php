<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('index.php');</script>";
	}
	
	include "db_connect.php";

	if (!isset($_POST['aid']) or $_POST['aid']=='')
		echo "<script>location.replace('assg.php');</script>";
	if ($_SESSION['user']['role']!='faculty')
		echo "<script>location.replace('assg.php');</script>";

	$con=Connect();


?>


<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style/main.css">
	<link rel="stylesheet" type="text/css" href="style/form.css">
	<link rel="stylesheet" type="text/css" href="style/popup.css">
	<link rel="stylesheet" type="text/css" href="style/addqs.css">


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
			width: 100%;
			padding: 3px;
		}
		a.btn{
			display: inline-block;
			min-width: 0;
		}
	</style>


	<script>
		window.onclick = function(event) {
   			if (event.target == document.getElementById('add-q')) {
        		document.getElementById('add-q').style.display = "none";
    		}
    		if (event.target == document.getElementById('edit-q')) {
        		document.getElementById('edit-q').style.display = "none";
    		}
    		if (event.target == document.getElementById('rem-q')) {
        		document.getElementById('rem-q').style.display = "none";
    		}
    	}

    	function QPattern(qtype){
    		if (qtype=="scq"){
              			$("[name='opt1']").attr({"placeholder":"Option A","required":"true"});
              			$("[name='opt2']").attr({"placeholder":"Option B","required":"true"});
              			$("[name='opt3']").attr({"placeholder":"Option C","required":"true"});
              			$("[name='opt4']").attr({"placeholder":"Option D","required":"true"});
              			$("[name='ans']").attr("pattern","[A-D]");
              		}
              		else if (qtype=="mcq"){
              			$("[name='opt1']").attr({"placeholder":"Option A","required":"true"});
              			$("[name='opt2']").attr({"placeholder":"Option B","required":"true"});
              			$("[name='opt3']").attr({"placeholder":"Option C","required":"true"});
              			$("[name='opt4']").attr({"placeholder":"Option D","required":"true"});
              			$("[name='ans']").attr("pattern","[A-D]{1,4}");
              		}
              		else if (qtype=="mat"){
              			$("[name='opt1']").attr({"placeholder":"Row P","required":"true"});
              			$("[name='opt2']").attr({"placeholder":"Row Q","required":"true"});
              			$("[name='opt3']").attr({"placeholder":"Row R","required":"true"});
              			$("[name='opt4']").attr({"placeholder":"Row S","required":"true"});
              			$("[name='ans']").attr("pattern","[P-S](,[P-S]){3}");
              		}
              		else if (qtype=="int"){
              			$("[name='opt1']").removeAttr('required');
              			$("[name='opt2']").removeAttr('required');
              			$("[name='opt3']").removeAttr('required');
              			$("[name='opt4']").removeAttr('required');
              			$("[name='ans']").attr("pattern","[0-9]");
              		}
              		else if (qtype=="num"){
              			$("[name='opt1']").removeAttr('required');
              			$("[name='opt2']").removeAttr('required');
              			$("[name='opt3']").removeAttr('required');
              			$("[name='opt4']").removeAttr('required');
              			$("[name='ans']").attr("pattern","[-+]?[0-9]*[.,]?[0-9]+");
              		}
    	}
		$(document).ready(function(){
			$(".qtype").hide();
    		$("select").change(function(){
       			$(this).find("option:selected").each(function(){
            		var qtype = $(this).attr("value");
              		$(".qtype").not("."+qtype).hide();
              		QPattern(qtype);
            	  	$("."+qtype).show();
        		});
    		}).change();
		});

		function EditQ(id){
			$(".qtype").hide();
			var qtype_long=$("#qid-"+id+" .q-pattern").text();
			if (qtype_long=="Single Correct")
				var qtype_short='scq';
			else if (qtype_long=="One or More than one Correct")
				var qtype_short='mcq';
			else if (qtype_long=="Matrix Match")
				var qtype_short='mat';
			else if (qtype_long=="Single Integer")
				var qtype_short='int';
			else if (qtype_long=="Numerical")
				var qtype_short='num';

			QPattern(qtype_short);
			$("#edit-q div .box form [name='qid']").val(id);
			$("#edit-q div .box form [name='qtype']").val(qtype_short);
			$("#edit-q div .box form [name='q']").val($("#qid-"+id+" .q-ques").text());
			$("#edit-q div .box form [name='opt1']").val($("#qid-"+id+" .q-opts ol li:eq(0)").text());
			$("#edit-q div .box form [name='opt2']").val($("#qid-"+id+" .q-opts ol li:eq(1)").text());
			$("#edit-q div .box form [name='opt3']").val($("#qid-"+id+" .q-opts ol li:eq(2)").text());
			$("#edit-q div .box form [name='opt4']").val($("#qid-"+id+" .q-opts ol li:eq(3)").text());
			$("#edit-q div .box form [name='ans']").val($("#qid-"+id+" .q-ans label [name='qid-ans']").val());
			$("#edit-q div .box form [name='relax']").val($("#qid-"+id+" .q-relax label [name='qid-relax']").val());
			$("#edit-q div .box form [name='marks']").val($("#qid-"+id+" .q-correct").text());
			$("#edit-q div .box form [name='pen']").val($("#qid-"+id+" .q-wrong").text());
			$("."+qtype_short).show();
			$('#edit-q').show();	
		}

		function RemQ(id){
			$("#rem-q div .box form [name='qid']").val(id);
			$('#rem-q').show();
		}

		function AddQ(){
		$('.qtype').hide();
		QPattern($("#add-q div .box form [name='qtype']").val());
		$('.'+$("#add-q div .box form [name='qtype']").val()).show();
		$('#add-q').show();
		}

	</script>
</head>
<body>

	<?php if (!mysqli_fetch_array(mysqli_query($con,"SELECT info.username from info,assignments where assignments.aid='".$_POST['aid']."' and assignments.manager='".$_SESSION['user']['username']."' and assignments.manager=info.username and SYSDATE()<assignments.stime"))[0]){ ?>
		<div class="popup" style="background-color: #aaaaaa; display: block;">
			<div class="popup-content animate">
				<div class="box">
						<div class="h">Unknown Error</div>
						<div>Snap!! There was an error and that's all we know. You can try again after sometime...</div>
						<div><a class="btn" href="assg.php">Go Back</a></div>
				</div>
			</div>
		</div>
	<?php 	Close($con);
			 die(); } ?>


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
			<hr>

			<center><a class="btn" onclick="AddQ()"><i class="fas fa-plus"></i>&nbspAdd Questions</a></center>
			<br><br>

			<?php 
			$res=mysqli_query($con,"SELECT qid,q,qtype,opt1,opt2,opt3,opt4,ans,relax,marks,pen FROM questions WHERE aid='".$_POST['aid']."'");
			$i=0;
			while ($temp=mysqli_fetch_assoc($res)){
				?>

			<div class="q" id="qid-<?php echo $temp['qid']?>">
				<div class="q-correct"><?php echo $temp['marks']?></div>
				<div class="q-pattern"><?php if ($temp['qtype']=='scq')
													echo "Single Correct";
												elseif ($temp['qtype']=='mcq')
													echo "One or More than one Correct";
												elseif ($temp['qtype']=='mat')
													echo "Matrix Match";
												elseif ($temp['qtype']=='int')
													echo "Single Integer";
												elseif ($temp['qtype']=='num')
													echo "Numerical"; ?></div>

				<div class="q-wrong"><?php echo $temp['pen'] ?></div>
				<div class="q-ques"><?php echo nl2br($temp['q']); ?></div>
				<div class="q-opts"><?php if ($temp['qtype']=='scq' or $temp['qtype']=='mcq'){?> 
					<ol type="A">
						<li><?php echo nl2br($temp['opt1'])?></li>
						<li><?php echo nl2br($temp['opt2'])?></li>
						<li><?php echo nl2br($temp['opt3'])?></li>
						<li><?php echo nl2br($temp['opt4'])?></li>
					</ol>
					<?php } elseif ($temp['qtype']=='mat') {?> 	
					<ol type="A" start="16">
						<li><?php echo nl2br($temp['opt1'])?></li>
						<li><?php echo nl2br($temp['opt2'])?></li>
						<li><?php echo nl2br($temp['opt3'])?></li>
						<li><?php echo nl2br($temp['opt4'])?></li>
					</ol>
				<?php } ?></div>
				<div class="q-ans">
					<label><span style="font-size:0.8em; font-family: 'Open Sans Condensed';">CORRECT ANSWER: </span><input type="text" name="qid-ans" style="width: 25ch;" placeholder="N.A." readonly="true" value="<?php echo $temp['ans']?>"></label>
				</div>
				<div class="q-relax">
					<?php if ($temp['qtype']=='num'){?>
						<label><span style="font-size:0.8em; font-family: 'Open Sans Condensed';">RELAXATION: </span><input type="text" name="qid-relax" style="max-width: 25ch;" placeholder="N.A." readonly="true" value="<?php echo $temp['relax']?>"></label>
					<?php } ?>
				</div>
				<div class="q-edit"><a class="btn" onclick="EditQ(<?php echo $temp['qid']?>)">Edit</a></div>
				<div class="q-rem"><a class="btn" onclick="RemQ(<?php echo $temp['qid']?>)">Remove</a></div>
			</div>
		<?php } ?>

		</div>	

		<div class="popup" id="edit-q">
			<div class="popup-content animate">
				<div class="box">
					<div class="h">Edit Question<span onclick="document.getElementById('edit-q').style.display='none'" class="close" style=" float: right;">&times</span></div>

					<form action="scripts/addqs-edit.php" method="POST">
					<input type="text" value="<?php echo $_POST['aid']?>" readonly="true" hidden="true" name="aid"><input type="text" value="<?php echo $_POST['aname']?>" readonly="true" hidden="true" name="aname">

					<input type="text" name="qid" hidden="true" ><input type="text" value="<?php echo md5(2*$_POST['aid']-1)?>" readonly="true" hidden="true" name="token">
					<div>
						<select name="qtype" required="true">
							<option value="scq">Single correct</option>
							<option value="mcq">One or more than one correct</option>
							<option value="mat">Matrix Match</option>
							<option value="int">Single digit Integer</option>
							<option value="num">Numerical (Any number)</option>
						</select>
					</div>

					<div class="qtype scq mcq mat int num">
						<textarea name="q" rows=8 required="true" placeholder="Question"></textarea>
					</div>

					<div class="qtype scq mcq mat">
						<textarea name="opt1" rows=3 placeholder="Option A or Row P"></textarea>
						<textarea name="opt2" rows=3 placeholder="Option B or Row Q"></textarea>
						<textarea name="opt3" rows=3 placeholder="Option C or Row R"></textarea>
						<textarea name="opt4" rows=3 placeholder="Option D or Row S"></textarea>
					</div>

					<div class="qtype scq mcq mat int num">
					<label>
						<center>CORRECT ANSWER:</center>
						<input type="text" name="ans" placeholder="Correct Answer">
						<span class="notes">You can choose to leave this field blank and update the answer(s) after the exam</span><br><br>

					<span class="qtype scq mcq mat int num notes"><b>FORMAT for entering Correct Answer</b></span><br>
					<span class="qtype scq notes">	
					A single letter A/B/C/D in capital. <i>For ex, <b>B</b> if Option 2 is correct</i></span>
					<span class="qtype mcq notes">All the correct options without any spaces in between, in alphabetical order, all in capital. <i> For ex, <b>ACD</b> if all options other than Option 2 are correct</i></span>
					<span class="qtype mat notes">The corresponding column in Right row (P,Q,R,S) for each column in Left row (A,B,C,D) seperated by commas, without any spaces in between, all in capital. <i>For ex, <b>P,Q,S,R</b> if A,B,C,D matches with P,Q,S,R respectively </i></span>
					<span class="qtype int notes">The exact single digit <b>integer</b>. <i>For ex, <b>2</b> if 2 is the correct integer</i></span>
					<span class="qtype num notes">The numerical value rounded off/truncated to <b>two decimals</b>. <i>For ex, <b>22.66</b> or <b>22.67</b> if 22.666... is the expected answer</i>. Further to accept range of answers, see <i>Relaxtion</i> below</span><br><br>
					<span class="qtype scq mcq mat int num notes"><b>Note:</b> DON'T use ORs (<b>/</b>) and BONUS (<b>*</b>) features for Correct answer HERE</span>
					</label>
					</div>
					
						

					<div class="qtype num">
						<label><center>RELAXATION:</center>
						<input type="text" name="relax" pattern="[-+]?[0-9]*[.,]?[0-9]+" placeholder="Relaxation">
						<span class="notes">Relaxation is a process used to increase range of answers when the amount of calculations are heavy or approximations cannot be avoided. After this process, answers in [Correct Answer-Relaxation,Correct Answer+Relaxation] are considered correct. It is NOT mandatory to 'relax' every Numerical type question</span>
						</label>
					</div>

					<div class="qtype scq mcq mat int num">
						<input type="text" name="marks" pattern="[-+]?[0-9]*[.,]?[0-9]+" placeholder="Marks (for correct response)" required="true">
						<input type="text" name="pen" pattern="[-+]?[0-9]*[.,]?[0-9]+" placeholder="Penalty (for incorrect response)">
					</div>

					<div><input type="submit" value="Save"></div>
					</form>
				</div>
			</div>
		</div>




		<div class="popup" id="rem-q">
			<div class="popup-content animate">
				<div class="box">
					
					<div class="h">Remove Question<span onclick="document.getElementById('rem-q').style.display='none'" class="close" style=" float: right;">&times</span></div>

					<form action="scripts/addqs-rem.php" method="POST">
						<input type="text" name="qid" hidden="true" ><input type="text" value="<?php echo md5(2*$_POST['aid']-1)?>" readonly="true" hidden="true" name="token">

						<input type="text" value="<?php echo $_POST['aid']?>" readonly="true" hidden="true" name="aid"><input type="text" value="<?php echo $_POST['aname']?>" readonly="true" hidden="true" name="aname">


						<div>Are you sure you want to delete this question? This process cannot be reversed.</div>
					

						<div><input type="submit" value="Remove"></div>
					</form>
				</div>
			</div>
		</div>



		<div class="popup" id="add-q">
			<div class="popup-content animate">
				<div class="box">

					<div class="h">Add Question<span onclick="document.getElementById('add-q').style.display='none'" class="close" style=" float: right;">&times</span></div>

					<form action="scripts/addqs-add.php" method="POST">
					<input type="text" value="<?php echo $_POST['aid']?>" readonly="true" hidden="true" name="aid"><input type="text" value="<?php echo $_POST['aname']?>" readonly="true" hidden="true" name="aname">

					<div>
						<select name="qtype" required="true">
							<option value="scq">Single correct</option>
							<option value="mcq">One or more than one correct</option>
							<option value="mat">Matrix Match</option>
							<option value="int">Single digit Integer</option>
							<option value="num">Numerical (Any number)</option>
						</select>
					</div>

					<div class="qtype scq mcq mat int num">
						<textarea name="q" rows=8 required="true" placeholder="Question"></textarea>
					</div>

					<div class="qtype scq mcq mat">
						<textarea name="opt1" rows=3 placeholder="Option A or Row P"></textarea>
						<textarea name="opt2" rows=3 placeholder="Option B or Row Q"></textarea>
						<textarea name="opt3" rows=3 placeholder="Option C or Row R"></textarea>
						<textarea name="opt4" rows=3 placeholder="Option D or Row S"></textarea>
					</div>

					<div class="qtype scq mcq mat int num">
					<label>
						<input type="text" name="ans" placeholder="Correct Answer">
						<span class="notes">You can choose to leave this field blank and update the answer(s) after the exam</span><br><br>

					<span class="qtype scq mcq mat int num notes"><b>FORMAT for entering Correct Answer</b></span><br>
					<span class="qtype scq notes">	
					A single letter A/B/C/D in capital. <i>For ex, <b>B</b> if Option 2 is correct</i></span>
					<span class="qtype mcq notes">All the correct options without any spaces in between, in alphabetical order, all in capital. <i> For ex, <b>ACD</b> if all options other than Option 2 are correct</i></span>
					<span class="qtype mat notes">The corresponding column in Right row (P,Q,R,S) for each column in Left row (A,B,C,D) seperated by commas, without any spaces in between, all in capital. <i>For ex, <b>P,Q,S,R</b> if A,B,C,D matches with P,Q,S,R respectively </i></span>
					<span class="qtype int notes">The exact single digit <b>integer</b>. <i>For ex, <b>2</b> if 2 is the correct integer</i></span>
					<span class="qtype num notes">The numerical value rounded off/truncated to <b>two decimals</b>. <i>For ex, <b>22.66</b> or <b>22.67</b> if 22.666... is the expected answer</i>. Further to accept range of answers, see <i>Relaxtion</i> below</span><br><br>
					<span class="qtype scq mcq mat int num notes"><b>Note:</b> DON'T use ORs (<b>/</b>) and BONUS (<b>*</b>) features for Correct answer HERE</span>
					</label>
					</div>
					
						

					<div class="qtype num">
						<label>
						<input type="text" name="relax"pattern="[-+]?[0-9]*[.,]?[0-9]+" placeholder="Relaxation">
						<span class="notes">Relaxation is a process used to increase range of answers when the amount of calculations are heavy or approximations cannot be avoided. After this process, answers in [Correct Answer-Relaxation,Correct Answer+Relaxation] are considered correct. It is NOT mandatory to 'relax' every Numerical type question</span>
						</label>
					</div>

					<div class="qtype scq mcq mat int num">
						<input type="text" name="marks" pattern="[-+]?[0-9]*[.,]?[0-9]+" placeholder="Marks (for correct response)" required="true">
						<input type="text" name="pen" pattern="[-+]?[0-9]*[.,]?[0-9]+" placeholder="Penalty (for incorrect response)">
					</div>

					<div><input type="submit" value="Add"></div>
				</form>

				</div>
			</div>
		</div>

	<footer>
		<div class="copy">&copy All Rights Reserved</div>
		<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
	</footer>
