<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('login.php?next=assg.php');</script>";
	}

	include "db_connect.php";
	$con=Connect();
?>


<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style/main.css">
	<link rel="stylesheet" type="text/css" href="style/form.css">
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

	<title>ASSIGNMENTS | <?php echo $_SESSION['user']['name']?></title>

	<style type="text/css">
		input,select,textarea{
			padding: 3px;
		}
		input[type='submit']{
			padding: 6px;
			background-color: #fff;
			text-transform: capitalize;
		}
	</style>
</head>
<body>


		<header>
			<div id="toggle"><a onclick="$('ul.nav').slideToggle('slow');"><i class="material-icons" style="font-size:1.5em;color:#fff">menu</i></a></div>
			<div class="profile">
				<img src="prof-pic/<?php echo $_SESSION['user']['username'];?>.png" onerror="this.src='prof-pic/default.png';" class="prof-pic">
				<div class="prof-name"><?php echo $_SESSION['user']['name']; ?></div>
			</div>
			<ul class="nav">
				<li><a href="index.php">Home</a></li>
				<li><a href="assg.php" class="selected">Assignments</a></li>
				<li><a href="message.php">Messages</a></li>
				<li><a href="news.php">Announcements</a></li>
				<li><a href="search.php">Find user</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</header>
	<div class="container">
		<?php 
		if ($_SESSION['user']['role']=='admin')
		{
			$res=mysqli_query($con,"SELECT * FROM assignments order by aid desc")
		?>

	<div class="table">
	<table>
		<thead>
		<tr>
			<th>ID</th>
			<th class="sticky">Assignment name</th>
			<th>Batch</th>
			<th>Faculty</th>
			<th>Start Time</th>
			<th>Duration</th>
			<th>End Time</th>
			<th>Key</th>
			<th>Status</th>
		</tr>
		</thead>
		<tbody>
		<?php 
			$reqs1=mysqli_query($con,"SELECT * FROM reqasg order by stime ");
			while($reqs=mysqli_fetch_assoc($reqs1)){	?>
		<tr>
			<form action="scripts/rejreqasg.php" method="POST">
				<input type="text" name="manager" required="true" hidden="true" value="<?php echo $reqs['manager']?>"><input type="text" name="aname" required="true" hidden="true" value="<?php echo $reqs['aname']?>">
				<td><center><input type="submit" value="&#10007Reject"></center></td>
			</form>
			<form action="scripts/addasg.php" method="POST">
			<td class="sticky"><input type="text" name="aname" required="true" placeholder="Assignment Name" value="<?php echo $reqs['aname']?>"></td>
			<td><input type="text" readonly="true" size=12 name="role" required="true" placeholder="Batch" value="<?php echo $reqs['role']?>"></td>
			<td><a href="search.php?id=<?php echo $reqs['manager']?>&by=username"><input type="text" readonly="true" name="manager" required="true" placeholder="Faculty" value="<?php echo $reqs['manager']?>"></a></td>
			<td><input type="text" name="stime" required="true" placeholder="YYYY-MM-DD HH:MM:SS" value="<?php echo $reqs['stime']?>" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31)) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]"></td>
			<td><input type="text" size=15 name="dur" pattern="([0-9][0-9])(:[0-5][0-9]){2}" required="true" placeholder="HH:MM:SS" value="<?php echo $reqs['dur']?>"></td>
			<td><input type="text" name="etime" required="true" placeholder="YYYY-MM-DD HH:MM:SS" value="<?php echo $reqs['etime']?>" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31)) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]"></td>
			<td><input type="password" size=15 name="password" pattern="[a-zA-Z0-9]+" placeholder="Key"></td>
			<td><center><input type="submit" value="&#10003Accept"></center></td>
			</form>
		</tr>
			<?php } ?>
	

		<form action="scripts/addasg.php" method="POST">
			<tr>
				<td><input type="text" readonly="true" size="4" placeholder="ID"></td>
				<td class="sticky"><input type="text" name="aname" required="true" placeholder="Assignment Name"></td>
				<td>
					<select name="role" required="true">
					<option value="">-SELECT BATCH-</option>
					<?php $rol=mysqli_query($con,"SELECT name from rolelist");
					while ($r=mysqli_fetch_array($rol,MYSQLI_NUM)){?>
					<option value="<?php echo $r[0]?>"><?php echo $r[0]?></option>
				<?php } ?>
				</select></td>
				<td>
					<select name="manager" required="true">
					<option value="">-SELECT FACULTY-</option>
					<?php $rol=mysqli_query($con,"SELECT username from info where role='faculty'");
					while ($r=mysqli_fetch_array($rol,MYSQLI_NUM)){?>
					<option value="<?php echo $r[0]?>"><?php echo $r[0]?></option>
				<?php } ?>
				</select></td>
				<td><input type="text" name="stime" required="true" placeholder="YYYY-MM-DD HH:MM:SS" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31)) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]"></td>
				<td><input type="text" name="dur" pattern="([0-9][0-9])(:[0-5][0-9]){2}" required="true" placeholder="HH:MM:SS" size="15"></td>
				<td><input type="text" name="etime" required="true" placeholder="YYYY-MM-DD HH:MM:SS" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31)) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]"></td>
				<td><input type="password" size=15 name="password" pattern="[a-zA-Z0-9]+"  placeholder="Key"></td>
				<td><center><input type="submit" name="submit" value="Add"></center></td>
			</tr>
		</form>
<?php
	while ($temp=mysqli_fetch_assoc($res)){
		
		
		if (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]<$temp['stime'])
			$status="scripts/modasg.php";	

		elseif (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]>$temp['etime'])
			$status="";	

		else
			$status="";
?>		<form action="<?php echo $status?>" method="POST">
	<input type="text" hidden="true" readonly="true" name="aid" value="<?php echo $temp['aid']; ?>"><input type="text" hidden="true" readonly="true" name="aname" value="<?php echo $temp['aname']; ?>">
		<tr>
			<td><?php echo $temp['aid']; ?></td>
			<td class="sticky"><?php echo $temp['aname']; ?></td>
			<td><?php echo $temp['role']; ?></td>
			<td><a href="search.php?id=<?php echo $temp['manager']?>&by=username"><?php echo $temp['manager']; ?></a></td>
			<?php
				if (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]<$temp['stime']){ ?>
			<td><input type="text" name="stime" required="true" placeholder="YYYY-MM-DD HH:MM:SS" value="<?php echo $temp['stime']; ?>" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31)) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]"></td>
				<td><input type="text" name="dur" required="true" placeholder="HH:MM:SS" value="<?php echo $temp['dur']; ?>" pattern="([0-9][0-9])(:[0-5][0-9]){2}" size="15"></td>
				<td><input type="text" name="etime" required="true" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31)) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]" placeholder="YYYY-MM-DD HH:MM:SS" value="<?php echo $temp['etime']; ?>"></td>
				<td><input type="password" name="password" pattern="[a-zA-Z0-9]+" value="<?php echo $temp['password']; ?>" size=15  placeholder="Key"></td>		
				<td><center><input type="submit" name="modify" value="Update changes >"></center></td> 
			<?php }	
		else {?>
			<td><?php echo $temp['stime']; ?></td>
			<td><?php echo $temp['dur']; ?></td>
			<td><?php echo $temp['etime']; ?></td>
			<td><input type="text" name="password" pattern="[a-zA-Z0-9]+" size=15 value="<?php echo $temp['password'];?>"  readonly="true"></td>
			<td><center>
				<?php if (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]>$temp['etime']){
					echo "Completed";}
					else {echo "In progress";}?></center></td>
		<?php }
			
		?>
		</tr>
	</form>
<?php }?>
</tbody>
</table>
</div>

<?php }

##############################################################################################




	elseif ($_SESSION['user']['role']=='faculty')
	{
	$res=mysqli_query($con,"SELECT * FROM assignments WHERE manager='".$_SESSION['user']['username']."' order by aid desc")
	?>

	<div class="table">
	<table>
		<thead>
		<tr>
			<th>ID</th>
			<th class="sticky">Assignment name</th>
			<th>Batch</th>
			<th>Start Time</th>
			<th>Duration</th>
			<th>End Time</th>
			<th>Max Marks</th>
			<th>Key/Avg</th>
			<th>Status</th>
		</tr>
		</thead>
		<tbody>
		<tr >
			<td><input type="text" size=4 readonly="true" value="REQ"></td>
			<?php if(mysqli_fetch_array(mysqli_query($con,"SELECT EXISTS(SELECT manager FROM reqasg where manager='".$_SESSION['user']['username']."') "),MYSQLI_NUM)[0]){
			$reqs=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM reqasg where manager='".$_SESSION['user']['username']."' "));?>
			<td class="sticky"><input type="text" readonly="true" value="<?php echo $reqs['aname']?>"></td>
			<td><input type="text" size=10 readonly="true" value="<?php echo $reqs['role']?>"></td>
			<td><input type="text" readonly="true" value="<?php echo $reqs['stime']?>"></td>
			<td><input type="text" size=12 readonly="true" value="<?php echo $reqs['dur']?>"></td>
			<td><input type="text" readonly="true" value="<?php echo $reqs['etime']?>" ></td>
			<td><input type="text" size=3 readonly="true" placeholder="0"></td>
			<td><input type="text" readonly="true" value=""></td>
			<td><center>Pending...</center></td>
		<?php } else {?>
			<form action="scripts/reqasg.php" method="POST">
			<td class="sticky"><input type="text" name="aname" required="true" placeholder="Assignment Name"></td>
			<td><select name="role" required="true">
				<option value="">-SELECT BATCH-</option>
				<?php $rol=mysqli_query($con,"SELECT role from rolefac where faculty='".$_SESSION['user']['username']."'");
				while ($r=mysqli_fetch_array($rol,MYSQLI_NUM)){?>
					<option value="<?php echo $r[0]?>"><?php echo $r[0]?></option>
				<?php } ?>
				</select></td>
			<td><input type="text" name="stime" required="true" placeholder="YYYY-MM-DD HH:MM:SS" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31)) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]"></td>
			<td><input type="text" size=15 name="dur" required="true" placeholder="HH:MM:SS" pattern="([0-9][0-9])(:[0-5][0-9]){2}"></td>
			<td><input type="text" name="etime" required="true" placeholder="YYYY-MM-DD HH:MM:SS" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31)) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]"></td>
			<td><input type="text" readonly="true" size=3 placeholder="0"></td>
			<td><input type="text" readonly="true" size=15 value=""></td>
			<td><center><input type="submit" value="Request"></center></td>
		</form>
		</tr>
		<?php }?>
		

<?php
	while ($temp=mysqli_fetch_assoc($res)){
		
		
		if (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]<$temp['stime'])
			$status="addqs.php";	
		elseif (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]>$temp['etime'])
			$status="analysis.php";	
		else
			$status="run.php";
?>		<form action="<?php echo $status?>" method="POST">
	<input type="text" hidden="true" readonly="true" name="aid" value="<?php echo $temp['aid']; ?>"><input type="text" hidden="true" readonly="true" name="aname" value="<?php echo $temp['aname']; ?>">
		<tr>
			<td><?php echo $temp['aid']; ?></td>
			<td class="sticky"><?php echo $temp['aname']; ?></td>
			<td><?php echo $temp['role']; ?></td>
			<td><?php echo $temp['stime']; ?></td>
			<td><?php echo $temp['dur']; ?></td>
			<td><?php echo $temp['etime']; ?></td>
			<td><?php echo $temp['maxmarks']; ?></td>
			<td>
				<?php
				if (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]<$temp['stime']){$status="Add Questions >"; ?>
			<input type="text" size=15 readonly="true">
			<?php }	
		elseif (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]>$temp['etime']){
			if (!$temp['finalised']){
				$status='Finalise >';?>
			<i><font size=1.5 color="grey">	
				<?php echo "(FINALISE ANSWERS)";?>
			</font></i>
				<?php
				 } 
				else {
			echo floatval(mysqli_fetch_array(mysqli_query($con,"SELECT AVG(marks) from a".$temp['aid']))[0]);
				$status='Analyse >';	}
		}
		else{$status='Preview Exam >';?>
			<input type="text" size=15 value="<?php echo $temp['password']?>" readonly="true"><?php }?>

			</td>
			<td><center>
				<input type="submit" name="submit" value='<?php echo $status ?>'>
				</center>
			</td>
		</tr>
	</form>
<?php }?>
</tbody>
</table>
</div>

<?php }

##############################################################################################


else	{
	$res=mysqli_query($con,"SELECT * FROM assignments WHERE role='".$_SESSION['user']['role']."' order by aid desc")
	?>

	<div class="table">
	<table>
		<thead>
		<tr>
			<th>ID</th>
			<th class="sticky">Assignment name</th>
			<th>Faculty</th>
			<th>Start Time</th>
			<th>Duration</th>
			<th>End Time</th>
			<th>Max Marks</th>
			<th>Your Marks</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
<?php
	while ($temp=mysqli_fetch_assoc($res)){
		$ctr=mysqli_fetch_array(mysqli_query($con,"SELECT EXISTS(SELECT username from a".$temp['aid']." where username ='".$_SESSION['user']['username']."')"));
		
		if (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]<$temp['stime'])
			$status="";	
		elseif ($ctr[0])
			$status="analysis.php";
		elseif (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]>$temp['etime'])
			$status="exp";	
		else
			$status="scripts/run.php";
?>		<form action="<?php echo $status?>" method="POST">
	<input type="text" hidden="true" readonly="true" name="aid" value="<?php echo $temp['aid']; ?>"><input type="text" hidden="true" readonly="true" name="aname" value="<?php echo $temp['aname']; ?>">
		<tr>
			<td><?php echo $temp['aid']; ?></td>
			<td class="sticky"><?php echo $temp['aname']; ?></td>
			<td><a href="search.php?id=<?php echo $temp['manager']?>&by=username"><?php echo $temp['manager']; ?></a></td>
			<td><?php echo $temp['stime']; ?></td>
			<td><?php echo $temp['dur']; ?></td>
			<td><?php echo $temp['etime']; ?></td>
			<td><?php echo $temp['maxmarks']; ?></td>
			<td>
				<?php
				if (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]<$temp['stime']) {?>
			<input type="text" size=15 readonly="true">
			<?php 	}
		elseif (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]>$temp['etime']){
			echo mysqli_fetch_array(mysqli_query($con,"SELECT marks from a".$temp['aid']."  where username='".$_SESSION['user']['username']."'"))[0];
				if (!$ctr[0]) 
					echo"---";?>
			<i><font size=1.5 color="grey">	
			<?php if (!$temp['finalised'])
				echo " (PROVISIONAL)";
				?></font></i>
				<?php
		}
		elseif ($status=="analysis.php"){
			?><input type="password" size=15 value="<?php echo $temp['password']?>" readonly="true">
			<?php
		}
		else{?>
			<input type="password" size=15 placeholder="Enter Key..." name="key" pattern="[a-zA-Z0-9]+"><?php }?>
			</td>
			<td><center>
				<?php 
				if ($status=='')
					echo "Unavailable";
				elseif ($status=='exp')
					echo "Expired";
				elseif (mysqli_fetch_array(mysqli_query($con," SELECT SYSDATE()"))[0]<$temp['etime'] and $status=='analysis.php')
					echo "Waiting...";
				else {

				if ($status=='analysis.php')

						$status='Analyse >';
					elseif ($status=='scripts/run.php')
						$status='Write Exam >';
					?>
				<input type="submit" name="submit" value='<?php echo $status ?>'>
				<?php }?></center></td>
		</tr>
	</form>
<?php }?>
</tbody>
</table>
</div>

<?php } 
Close($con);?>
</div>
	<footer>
		<div class="copy">&copy All Rights Reserved</div>
		<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
	</footer>
</body>
</html>







