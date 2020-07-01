<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('login.php?next=assg.php');</script>";
	}
	
	if (!isset($_POST['aid']) or $_POST['aid']=='')
		echo "<script>location.replace('assg.php');</script>";
	if ($_SESSION['user']['role']=='admin')
		echo "<script>location.replace('assg.php');</script>";


	include "db_connect.php";
	$con=Connect();
	

	if ($_SESSION['user']['role']!='faculty'){

		if ($_POST['token']==''){
			Close($con);
			echo "<script>location.replace('assg.php')</script>";
		}
		if (!preg_match("/^[a-f0-9]+$/", $_POST['token']) or !preg_match("/^[0-9]+$/",$_POST['aid'])){
			Close($con);
			die("<h1>Unknown error</h1>An unknown error was encountered. That's all we know... You can go back and try again....");
		}
		if (!mysqli_fetch_array(mysqli_query($con,"SELECT EXISTS(SELECT ctr+1 from timer where token='".$_POST['token']."' and username='".$_SESSION['user']['username']."' and aid='".$_POST['aid']."')"),MYSQLI_NUM)[0]){
			Close($con);
			echo "<script>location.replace('assg.php')</script>";
		}
	}
	$res=mysqli_query($con,"SELECT qid,q,qtype,opt1,opt2,opt3,opt4,relax,marks,pen FROM questions WHERE aid='".$_POST['aid']."'") ;
	$tot_qs=mysqli_num_rows($res);
	$i=0;

?>


<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_SESSION['user']['username']." :: ".$_POST['aname']?></title>

	<link rel="stylesheet" type="text/css" href="style/run.css">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- IMPORT FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Lato&display=swap" rel="stylesheet">

	<!-- ICONS !-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css'>

	<!-- JQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>


	<script>
		function TimerStart(){
		<?php if ($_SESSION['user']['role']!='faculty') {?>
			<?php include"clock.php";

			if (!mysqli_fetch_array(mysqli_query($con,"SELECT etime from timer where username='".$_SESSION['user']['username']."' and aid='".$_POST['aid']."'"),MYSQLI_NUM)[0]){
			$de=mysqli_fetch_array(mysqli_query($con,"SELECT dur,etime from assignments where aid='".$_POST['aid']."' "),MYSQLI_NUM);
			mysqli_query($con,"UPDATE timer SET etime=LEAST(ADDTIME(SYSDATE(),CONVERT('".$de[0]."',TIME)),CONVERT('".$de[1]."',DATETIME)) where username='".$_SESSION['user']['username']."' and aid='".$_POST['aid']."' ");
			}	
			$et=mysqli_fetch_array(mysqli_query($con,"SELECT etime from timer where username='".$_SESSION['user']['username']."' and aid='".$_POST['aid']."'"),MYSQLI_NUM);?>

    		var etime = <?php echo strtotime($et[0])?>*1000 ;
    		var now = <?php echo strtotime('now') ?>*1000 ;
    		var delta= Date.now()-now;
   			var x=setInterval(function() {
       		now = Date.now()+ delta;
       		var intr = etime - now;
        	var hours = Math.floor(intr  / (1000 * 60 * 60));
        	var minutes = Math.floor((intr % (1000 * 60 * 60)) / (1000 * 60));
        	var seconds = Math.floor((intr % (1000 * 60)) / 1000);
        	document.getElementById("timer").innerHTML = hours + "h " +
            	minutes + "m " + seconds + "s";
        	if (intr < 0) {
            	clearInterval(x);
            	document.getElementById("timer").innerHTML = document.responses.submit();
        		}
   			}, 1000);
   		<?php } ?>
   		}




		window.onclick = function(event) {
   			if (event.target == document.getElementById('qp-show')) {
        		document.getElementById('qp-show').style.display = "none";
    		}
    		if (event.target == document.getElementById('confirm-sub')) {
        		document.getElementById('confirm-sub').style.display = "none";
    		}
    	}

		var id_prev=1,prev_val=0,flag_toggle=0,tot;
		

		function Next(){
			if (id_prev>=tot)
				var tar=1;
			else
				var tar=id_prev+1;
			ToggleQ(tar);
		}

		function Prev(){
			if (id_prev<=1)
				var tar=tot;
			else
				var tar=id_prev-1;
			ToggleQ(tar);
		}



		

		function ToggleFlag(id){
			if ($("[name='f-"+id+"']").val()==''){
				$('#q'+id+'-ind').css('border-radius','50%');
				$("[name='f-"+id+"']").val("1");
				$('#add-flag-q'+id).css("display","none");
				$('#rem-flag-q'+id).css("display","inline-block");
				if (flag_toggle==0)
					flag_toggle=1;
				if (flag_toggle==-1)
					flag_toggle=0;
			}
			else{
				$('#q'+id+'-ind').css('border-radius','0');
				$("input[name='f-"+id+"']").val("");
				$('#rem-flag-q'+id).css("display","none");
				$('#add-flag-q'+id).css("display","inline-block");
				if (flag_toggle==0)
					flag_toggle=-1;
				if (flag_toggle==1)
					flag_toggle=0;
			}

		}

		function CheckAttempted(id){
			id_map=$("[name='qid["+(parseInt(id)-1)+"]']").val();

			if ($("#q"+id+"-item .q-details .q-pattern").text()=="Single Correct"){
				return $("[name='"+id_map+"']:checked").length;
			}
			if ($("#q"+id+"-item .q-details .q-pattern").text()=="One or More than one Correct"){
				return $("[name='"+id_map+"[]']:checked").length;		
			}
			if ($("#q"+id+"-item .q-details .q-pattern").text()=="Matrix Match"){
				for (var i=0;i<4;i++) 
					if ($("[name='"+id_map+"["+i+"]']").val()!='-')
						return 1;
				return 0;
			}
			if ($("#q"+id+"-item .q-details .q-pattern").text()=="Single Integer"){
				return $("[name='"+id_map+"']").val();
			}
			if ($("#q"+id+"-item .q-details .q-pattern").text()=="Numerical"){
				return $("[name='"+id_map+"']").val().length;
			}
			return 0;
		}

		function ToggleQ(id){	
			$('#q'+id_prev+'-item').fadeOut(50,function(){
				$('#q'+id+'-item').fadeIn("fast");
			})
			if (flag_toggle==1){
				if (prev_val)
					$('#count-attempted-flag').text(parseInt($('#count-attempted-flag').text())+1);
				else
					$('#count-unattempted-flag').text(parseInt($('#count-unattempted-flag').text())+1);
			}

			if (flag_toggle==-1){
				if (prev_val)
					$('#count-attempted-flag').text(parseInt($('#count-attempted-flag').text())-1);
				else
					$('#count-unattempted-flag').text(parseInt($('#count-unattempted-flag').text())-1);
			}
			flag_toggle=0;

			if (CheckAttempted(id_prev)){
				$('#q'+id_prev+'-ind').css('background-color','#000').css('color','#fff');
				if (!prev_val){
					$('#count-unattempted').text(parseInt($('#count-unattempted').text())-1);
					$('#count-attempted').text(parseInt($('#count-attempted').text())+1);
				
					if ($("[name=f-"+id_prev+"]").val()){
						$('#count-unattempted-flag').text(parseInt($('#count-unattempted-flag').text())-1);
						$('#count-attempted-flag').text(parseInt($('#count-attempted-flag').text())+1);
					}
				}
			}
			else{
				$('#q'+id_prev+'-ind').css('background-color','#fff');
				if (prev_val){
					$('#count-attempted').text(parseInt($('#count-attempted').text())-1);
					$('#count-unattempted').text(parseInt($('#count-unattempted').text())+1);
				
					if ($("[name=f-"+id_prev+"]").val()){
						$('#count-attempted-flag').text(parseInt($('#count-attempted-flag').text())-1);
						$('#count-unattempted-flag').text(parseInt($('#count-unattempted-flag').text())+1);
					}
				}
			}

			$('#q'+id+'-ind').css('background-color','#f1f1f1').css('color','#000');
			prev_val=CheckAttempted(id);

			
			id_prev=id;
		}

		function ClearResp(id){
			id_map=$("[name='qid["+(id-1)+"]']").val();
			if ($("#q"+id+"-item .q-details .q-pattern").text()=="Single Correct"){
				$("[name='"+id_map+"']").prop("checked",false);
			}
			if ($("#q"+id+"-item .q-details .q-pattern").text()=="One or More than one Correct"){
				$("[name='"+id_map+"[]']").prop("checked",false);		
			}
			if ($("#q"+id+"-item .q-details .q-pattern").text()=="Matrix Match"){
				for (var i=0;i<4;i++) 
					$("[name='"+id_map+"["+i+"]']").val('-');
			}
			if ($("#q"+id+"-item .q-details .q-pattern").text()=="Single Integer"){
				$("[name='"+id_map+"']").val('');
			}
			if ($("#q"+id+"-item .q-details .q-pattern").text()=="Numerical"){
				$("[name='"+id_map+"']").val('');
			}
		}

		$( window ).on( "load", function() {
			tot=<?php echo $tot_qs;?>;
			ToggleQ(1);
			
			TimerStart();


		});
	</script>


</head>
<body>

<form action="scripts/submit.php" method="POST" name="responses">


	<header class="title">
		<div class="aname"><?php echo $_POST['aname'];?></div>

		<div class="extra">
			<div class="stat">
				<span>
					<span class="count" id="count-attempted" style="background-color: #000; color: #fff;">0</span>
					<span class="count-desc">ATTEMPTED</span>
				</span>
				<span>
					<span class="count" id="count-attempted-flag" style="border-radius: 50%; background-color: #000; color: #fff;">0</span>
					<span class="count-desc" style="border-right: 1px solid #000;">+ Flagged</span>
				</span>
				<span>
					<span class="count" id="count-unattempted"><?php echo $tot_qs;?></span>
					<span class="count-desc">UNATTEMPTED</span>
				</span>
				<span>
					<span class="count" id="count-unattempted-flag" style="border-radius: 50%;">0</span>
					<span class="count-desc" style="border-right: 1px solid #000;">+ Flagged</span>
				</span>
				
			</div>
			<div id="timer" class="timer">
				<?php if ($_SESSION['user']['role']!='faculty') {?>
					00:00:00
				<?php } else {?>
					PREVIEW
				<?php }?>
			</div>
		</div>
	</header>
	

	<div class="container">
		
		<div class="left">
			<a class="btn qp" onclick="document.getElementById('qp-show').style.display='block'"><i class="far fa-file"></i>&nbsp&nbsp&nbspView Question Paper</a>
		
			<div class="q-panel">
				<?php $j=0;
				while ($j<$tot_qs) {
					$j++;	?>
					<div class="q-no" id="<?php echo 'q'.$j.'-ind';?>" onclick="ToggleQ(<?php echo $j?>);"><?php echo $j;?></div>
				<?php } ?>
			</div>
		</div>

		<div class="right">

			<?php
			while ($temp=mysqli_fetch_assoc($res)) {
			?>

			<input type="text" name="<?php echo 'qid['.$i.']';?>" value="<?php echo $temp['qid']?>" hidden="true" readonly="true"><input type="text" name="qtype[]" value="<?php echo $temp['qtype']?>" hidden="tre" readonly="true">
			
			<?php $i++; ?>

			<div class="q-item" id="<?php echo 'q'.$i.'-item';?>" >
				<a id="<?php echo 'add-flag-q'.$i;?>" class="flag" onclick="ToggleFlag(<?php echo $i?>)"><i class="far fa-flag"></i></a><a id="<?php echo 'rem-flag-q'.$i;?>" class="unflag" onclick="ToggleFlag(<?php echo $i?>)"><i class="fas fa-flag"></i></a>

				<a class="reset" onclick="ClearResp(<?php echo $i?>)"><i class="fas fa-redo"></i> CLEAR</a><br><br>

				<input type="text" name="<?php echo 'f-'.$i;?>" readonly="true" hidden="true">

				<div class="ques"><?php echo nl2br($temp['q']); ?></div>

				

				<?php if ($temp['qtype']=='scq'){ ?>
					<div class="ans">
						<label>
							<?php echo nl2br($temp['opt1'])?>
  							<input type="radio" name="<?php echo $temp['qid']?>" value="A">
  							<span class="checkmark"></span>
						</label>
						<label>
							<?php echo nl2br($temp['opt2'])?>
  							<input type="radio" name="<?php echo $temp['qid']?>" value="B">
  							<span class="checkmark"></span>
						</label>
						<label>
							<?php echo nl2br($temp['opt3'])?>
  							<input type="radio" name="<?php echo $temp['qid']?>" value="C">
  							<span class="checkmark"></span>
						</label>
						<label>
							<?php echo nl2br($temp['opt4'])?>
  							<input type="radio" name="<?php echo $temp['qid']?>" value="D">
  							<span class="checkmark"></span>
						</label>
					</div>

				
				<?php } elseif ($temp['qtype']=='mcq') {?> 
					<div class="ans">
						<label>
							<?php echo nl2br($temp['opt1'])?>
  							<input type="checkbox" name="<?php echo $temp['qid']?>[]" value="A">
  							<span class="checkmark"></span>
						</label>
						<label>
							<?php echo nl2br($temp['opt2'])?>
  							<input type="checkbox" name="<?php echo $temp['qid']?>[]" value="B">
  							<span class="checkmark"></span>
						</label>
						<label>
							<?php echo nl2br($temp['opt3'])?>
  							<input type="checkbox" name="<?php echo $temp['qid']?>[]" value="C">
  							<span class="checkmark"></span>
						</label>
						<label>
							<?php echo nl2br($temp['opt4'])?>
  							<input type="checkbox" name="<?php echo $temp['qid']?>[]" value="D">
  							<span class="checkmark"></span>
						</label>
					</div>
		
				<?php } elseif ($temp['qtype']=='mat') {?>
					<ol type="A">
					<div class="ans">
						<label>
							<li>
							<select name="<?php echo $temp['qid']?>[0]">
								<option  value="-">-SELECT FOR ROW 1-</option>
								<option value="P"><?php echo nl2br($temp['opt1'])?> </option>
								<option value="Q"><?php echo nl2br($temp['opt2'])?> </option>
								<option value="R"><?php echo nl2br($temp['opt3'])?> </option>
								<option value="S"><?php echo nl2br($temp['opt4'])?> </option>
							</select>
							</li>
						</label>
						<label>
							<li>
							<select name="<?php echo $temp['qid']?>[1]">
								<option  value="-">-SELECT FOR ROW 2-</option>
								<option value="P"><?php echo nl2br($temp['opt1'])?> </option>
								<option value="Q"><?php echo nl2br($temp['opt2'])?> </option>
								<option value="R"><?php echo nl2br($temp['opt3'])?> </option>
								<option value="S"><?php echo nl2br($temp['opt4'])?> </option>
							</select>
							</li>
						</label>
						<label>
							<li>
							<select name="<?php echo $temp['qid']?>[2]">
								<option  value="-">-SELECT FOR ROW 3-</option>
								<option value="P"><?php echo nl2br($temp['opt1'])?> </option>
								<option value="Q"><?php echo nl2br($temp['opt2'])?> </option>
								<option value="R"><?php echo nl2br($temp['opt3'])?> </option>
								<option value="S"><?php echo nl2br($temp['opt4'])?> </option>
							</select>
							</li>
						</label>
						<label>
							<li>
							<select name="<?php echo $temp['qid']?>[3]">
								<option  value="-">-SELECT FOR ROW 4-</option>
								<option value="P"><?php echo nl2br($temp['opt1'])?> </option>
								<option value="Q"><?php echo nl2br($temp['opt2'])?> </option>
								<option value="R"><?php echo nl2br($temp['opt3'])?> </option>
								<option value="S"><?php echo nl2br($temp['opt4'])?> </option>
							</select>
							</li>
						</label>
					</div>
					</ol>
					<div class="ans">
						<b>Note:</b> Each subpart carries equal weightage and will be graded independently. Partial marks will be given for attempting a part of the question.
					</div>
			
		
				<?php } elseif ($temp['qtype']=='int') {?> 
					<div class="ans">
							<select name="<?php echo $temp['qid']?>" style="min-width: 25%;">
								<option  value="">-SELECT AN INTEGER-</option>
								<option value="0">0 - ZERO</option>
								<option value="1">1 - ONE</option>
								<option value="2">2 - TWO</option>
								<option value="3">3 - THREE</option>
								<option value="4">4 - FOUR</option>
								<option value="5">5 - FIVE</option>
								<option value="6">6 - SIX</option>
								<option value="7">7 - SEVEN</option>
								<option value="8">8 - EIGHT</option>
								<option value="9">9 - NINE</option>	
							</select>
					</div>

		
				<?php } elseif ($temp['qtype']=='num') {?> 
					<div class="ans">
						<label><input type="text" pattern="[-+]?[0-9]*[.,]?[0-9]+" placeholder="Your Response" name="<?php echo $temp['qid']?>"></label>
					</div>
					<!--
					<div class="ans">
						<i>(* Answer should lie in [Correct Answer-Relaxation,Correct Answer+Relaxation] for earning credits; where Relaxation=<input type="text" readonly="true" value="<?php echo $temp['relax']?>">)</i>
					</div>
					!-->	
				<?php } ?>



				<div class="q-details">
					<div class="q-correct">
						<?php echo $temp['marks'] ?>
						<div class="div-desc">Award for correct response(s)</div>
					</div>
					<div class="q-pattern"><?php if ($temp['qtype']=='scq')
								echo "Single Correct";
							elseif ($temp['qtype']=='mcq')
								echo "One or More than one Correct";
							elseif ($temp['qtype']=='mat')
								echo "Matrix Match";
							elseif ($temp['qtype']=='int')
								echo "Single Integer";
							elseif ($temp['qtype']=='num')
								echo "Numerical"; 
					?></div>	
					<div class="q-wrong">
						<?php echo $temp['pen'] ?>
						<div class="div-desc">Penalty for incorrect response(s)</div>
					</div>
				</div>

			</div>
			<?php } ?>

			<div class="prev" onclick="Prev()"><a class="btn">< Prev</a></div>
			<div class="next" onclick="Next()"><a class="btn">Next ></a></div>
		</div>
	</div>

	<?php if ($_SESSION['user']['role']!='faculty') {?>

	<footer>
		<div><a class="submit-btn" onclick="document.getElementById('confirm-sub').style.display='block'">Submit</a></div>
	</footer>

	<?php } ?>

	<?php if ($_SESSION['user']['role']=='faculty'){?>
	<footer>
		<div><a class="submit-btn" href="assg.php">Back to Assignments</a></div>
	</footer>
	<?php } ?>



	<div class="popup" id="confirm-sub">
			<div class="popup-content animate">
				<div class="box">
					<div class="h">Confirm Submission<span onclick="document.getElementById('confirm-sub').style.display='none'" class="close" style=" float: right;">&times</span></div>
					<div>You still have time to complete this assignment. Are you sure you want to submit it now?</div>
					<div>
						<label>I'm sure I want to submit.
							<input type="checkbox" required="true">
							<span class="checkmark"></span>
						</label>
						<input type="submit" value="Submit"></div>
					
				</div>
			</div>
		</div>



	<input type="text" name="username" value="<?php echo $_SESSION['user']['username']?>" readonly="true" hidden="true"><input hidden ='true' type="text" name="aid" value="<?php echo $_POST['aid']?>" readonly="true"><input hidden='true' type="text" name="token" value="<?php echo $_POST['token']?>" readonly="true">
</form>




	<div class="popup" id="qp-show">
			<div class="qp-content animate">
				<div class="box">
					<div class="h">Question Paper<span onclick="document.getElementById('qp-show').style.display='none'" class="close" style=" float: right;">&times</span></div>
					<table>
						<thead>
							<tr>
								<th>Q#</th>
								<th>Questions</th>
								<th>Extra info</th>
							</tr>
						</thead>
						<tbody>

						<?php
						$i=0;
						$res=mysqli_query($con,"SELECT qid,q,qtype,opt1,opt2,opt3,opt4,relax,marks,pen FROM questions WHERE aid='".$_POST['aid']."'") ;
						while ($temp=mysqli_fetch_assoc($res)){
						$i++;
						?>
							<tr>
								<td valign="top"><?php echo $i?></td>
								<td>
									<?php echo nl2br($temp['q']); ?><br>

									<?php if ($temp['qtype']=='scq' or $temp['qtype']=='mcq'){?> 	<ol type="A">
											<li><?php echo nl2br($temp['opt1'])?></li>
											<li><?php echo nl2br($temp['opt2'])?></li>
			 								<li><?php echo nl2br($temp['opt3'])?></li>
											<li><?php echo nl2br($temp['opt4'])?></li>
										</ol>
									<?php }  elseif ($temp['qtype']=='mat') {?> 	
										<ol type="A" start="16">
											<li><?php echo nl2br($temp['opt1'])?></li>
											<li><?php echo nl2br($temp['opt2'])?></li>
											<li><?php echo nl2br($temp['opt3'])?></li>
											<li><?php echo nl2br($temp['opt4'])?></li>
										</ol>
										<br><br>
			
											<i>(*Each subpart carries equal weightage and will be graded independently)</i>
			
		
								<?php } ?> 
								<br><br><br>		
								</td>
								<td valign="top">
									<b> 
											<?php if ($temp['qtype']=='scq')
													echo "Single Correct";
												elseif ($temp['qtype']=='mcq')
													echo "One or More than one Correct";
												elseif ($temp['qtype']=='mat')
													echo "Matrix Match";
												elseif ($temp['qtype']=='int')
													echo "Single Integer";
												elseif ($temp['qtype']=='num')
													echo "Numerical"; ?>

									</b><br>
									Correct: <b><?php echo $temp['marks'] ?></b><br>
									Wrong: <b><?php echo $temp['pen'] ?></b><br>
									Unattempted: <b>0</b>
								</td>
							</tr>
						<?php } ?>
						</tbody>
						</table>
					</div>
				</div>
		</div>
</body>
</html>