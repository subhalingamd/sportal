<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../index.php');</script>";
	}


	if ($_SESSION['user']['role']!='admin')
		echo "<script>location.replace('../index.php');</script>";

	include "../db_connect.php";

	$con=Connect();
	$res=mysqli_query($con,"SELECT * from rolefac order by faculty");
?>


<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../style/main.css">
	<link rel="stylesheet" type="text/css" href="../style/form.css">
	
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

	<title>ADMIN PANEL | Faculty-in-Batches Management</title>

	<style type="text/css">
		input[type='submit']{
			padding: 6px;
		}
	</style>


</head>
<body>
		<header>
			<div id="toggle"><a onclick="$('ul.nav').slideToggle('slow');"><i class="material-icons" style="font-size:1.5em;color:#fff">menu</i></a></div>
			<div class="profile">
				<img src="prof-pic/<?php echo $_SESSION['user']['username'];?>.png" onerror="this.src='../prof-pic/default.png';" class="prof-pic">
				<div class="prof-name"><?php echo $_SESSION['user']['name']; ?></div>
			</div>
			<ul class="nav">
				<li><a href="../index.php">Home</a></li>
				<li><a href="../assg.php">Assignments</a></li>
				<li><a href="../message.php">Messages</a></li>
				<li><a href="../news.php">Announcements</a></li>
				<li><a href="../search.php">Find user</a></li>
				<li><a href="../profile.php">Profile</a></li>
				<li><a href="../logout.php">Logout</a></li>
			</ul>
		</header>
		<div class="tab">
			<div class="tab-item"><a href="add.php">Add user</a></div>
			<div class="tab-item"><a href="role.php">Batches Management</a></div>
			<div class="tab-item selected"><a href="facrole.php">Faculty-in-Batches Management</a></div>
			<div class="tab-item"><a href="rem.php">Remove user</a></div>
			<div class="tab-item"><a href="chgpass.php">Reset password for user</a></div>
		</div>

		<div class="container">
			<div class="title" style="text-align: center; " >Faculty-in-Batches Management</div>
			<div class="box" style="background-color: Yellow; padding: 6px 9px;">
				<b><i class="fas fa-exclamation-triangle"></i>&nbspWARNING:</b><br>
				Some actions may be IRREVERSIBLE.
			</div>
			<div>
				<table>
					<thead>
						<tr>		
							<th></th>
							<th>Faculty</th>
							<th>Batch</th>
							<th>Action</th>
						</tr>
					</thead>
					<form method="POST">
					<tbody>	
						<?php while ($batch=mysqli_fetch_assoc($res)){?>
							<tr>	
								<form method="POST">
								<input type="text" name="role" value="<?php echo $batch['role']?>" readonly="true" hidden="true">
								<input type="text" name="faculty" value="<?php echo $batch['faculty']?>" readonly="true" hidden="true">
								<td style="text-align: center;"><input type="checkbox" required="true"></td>
								<td><?php echo $batch['faculty']?></td>
								<td><?php echo $batch['role']?></td>
								<td><input type="submit" name="del" value="Delete"></td>
								</form>
							</tr>
							<?php } ?>
							<tr>
								<form method="POST">
								<td class="open-sans" style="text-align: center; font-family: 'Open Sans Condensed'; font-size: 1.25em;">ADD</td>
								<td><select name="faculty" required="true">
										<option value="">-SELECT FACULTY-</option>
										<?php $fac=mysqli_query($con,"SELECT username from info where role='faculty'");
										while ($f=mysqli_fetch_array($fac,MYSQLI_NUM)){?>
											<option value="<?php echo $f[0]?>"><?php echo $f[0]?></option>
										<?php } ?>
										</select></td>
								<td><select name="role" required="true">
										<option value="">-SELECT BATCH-</option>
										<?php $rol=mysqli_query($con,"SELECT name from rolelist");
										while ($r=mysqli_fetch_array($rol,MYSQLI_NUM)){?>
											<option value="<?php echo $r[0]?>"><?php echo $r[0]?></option>
										<?php } ?>
										</select></td>
								<td><input type="submit" name="add" value="Add"></td>
								</form>
							</tr>
							</tbody>
						</table>
					</div>
						<br>

						<?php 
							if ($_POST['add']=="Add")
							{
								if (preg_match("/^[A-Za-z0-9]+$/",$_POST['role']) and preg_match("/^[A-Za-z0-9]+$/",$_POST['faculty'])){
								if (mysqli_fetch_array(mysqli_query($con,"SELECT EXISTS (SELECT * from rolefac where role='".$_POST['role']."' and faculty='".$_POST['faculty']."') "),MYSQLI_NUM)[0])
									die("This faculty is already a part of this batch!");
								else
								mysqli_query($con,"INSERT into rolefac (role,faculty) VALUES ('".$_POST['role']."','".$_POST['faculty']."')");
								$_POST['add']="";
								echo "<script>location.replace('facrole.php')</script>";
								}
							}

							if ($_POST['del']=="Delete")
							{
								if (preg_match("/^[A-Za-z0-9]+$/",$_POST['role']) and preg_match("/^[A-Za-z0-9]+$/",$_POST['faculty'])){
								mysqli_query($con,"DELETE from rolefac where role='".$_POST['role']."' and faculty='".$_POST['faculty']."'");
								$_POST['del']="";
								}
								echo "<script>location.replace('facrole.php')</script>";
							} 
							Close($con); ?>			
					
		</div>

		<footer>
			<div class="copy">&copy All Rights Reserved</div>
			<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
		</footer>
</body>
</html>


	
	
	