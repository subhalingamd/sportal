<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('../login.php');</script>";
	}



	if ($_SESSION['user']['role']!='admin')
		echo "<script>location.replace('../index.php');</script>";
	
	include "../db_connect.php";

	$con=Connect();
	$res=mysqli_query($con,"SELECT * from rolelist");
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

	<title>ADMIN PANEL | Batches Management</title>

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
			<div class="tab-item selected"><a href="role.php">Batches Management</a></div>
			<div class="tab-item"><a href="facrole.php">Faculty-in-Batches Management</a></div>
			<div class="tab-item"><a href="rem.php">Remove user</a></div>
			<div class="tab-item"><a href="chgpass.php">Reset password for user</a></div>
		</div>

		<div class="container">
			<div class="title" style="text-align: center; " >Batches Management</div>
			<div class="box" style="background-color: Yellow; padding: 6px 9px;">
				<b><i class="fas fa-exclamation-triangle"></i>&nbspWARNING:</b><br>
				Deleting a batch will remove all the students' login details involved in the batch, the assignments given for the batch and questions and responses of students corresponding to the assignments. This action is IRREVERSIBLE.
			</div>
			<div>
				<table>
					<thead>
						<tr>
							<th></th>
							<th>Name</th>
							<th>Faculty</th>
							<th>Strength</th>
							<th>Action</th>	
						</tr>
					</thead>
					<form method="POST">
					<tbody>	
						<?php while ($batch=mysqli_fetch_assoc($res)){?>
							<tr valign="top">
							<form method="POST">
								<input type="text" name="role" value="<?php echo $batch['name']?>" readonly="true" hidden="true">
								<td style="text-align: center;"><input type="checkbox" required="true"></td>
								<td><?php echo $batch['name']?></td>
								<td><?php $fac=mysqli_query($con,"SELECT faculty from rolefac where role='".$batch['name']."'");
								while ($t=mysqli_fetch_array($fac,MYSQLI_NUM)){
									echo $t[0];?><br><?php } ?> </td>
								<td><?php echo (int)$batch['count']-(int)$batch['rem']?></td>
								<td><input type="submit" name="del" value="Remove"></td>
							</form>
							</tr>
							<?php } ?>
							<tr>
							<form method="POST">
								<td style="text-align: center; font-family: 'Open Sans Condensed'; font-size: 1.25em;">ADD</td>
								<td colspan=3><input type="text" name="role"  required="true" placeholder="Name" pattern="[A-Za-z0-9]+"></td>
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
						if ($_POST['role']=='admin' or $_POST['role']=='faculty')
							die("Reserved batch name!");
						if (!preg_match("/^[A-Za-z0-9]+$/",$_POST['role']))
							die("Invalid Batch Name");
						if (mysqli_fetch_array(mysqli_query($con,"SELECT EXISTS (SELECT * from rolelist where name='".$_POST['role']."') "),MYSQLI_NUM)[0])
							die("There is already a batch with this name!");

						$stmt=mysqli_prepare($con,"INSERT into rolelist VALUES (?,0,0)");
						mysqli_stmt_bind_param($stmt,"s",$_POST['role']);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt);

						echo "<script>location.replace('role.php')</script>";
					}

					if ($_POST['del']=="Delete")
					{
						mysqli_query($con,"DELETE from rolelist where name='".$_POST['role']."'");
						$aid=mysqli_query($con,"SELECT aid  from assignments where role='".$_POST['role']."' ");
						while ($a=mysqli_fetch_array($aid,MYSQLI_NUM))
						{
							mysqli_query($con,"DELETE from questions where aid='".$a[0]."' ");
							mysqli_query($con,"DROP TABLE a".$a[0]);
						}	
						$stmt=mysqli_prepare($con,"DELETE from info where role=?");
						mysqli_stmt_bind_param($stmt,"s",$_POST['role']);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt);

						$stmt=mysqli_prepare($con,"DELETE from assignments where role=?");
						mysqli_stmt_bind_param($stmt,"s",$_POST['role']);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt);

						$stmt=mysqli_prepare($con,"DELETE from rolefac where role=? ");
						mysqli_stmt_bind_param($stmt,"s",$_POST['role']);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt);

						echo "<script>location.replace('role.php')</script>";
					}
					Close($con);
					?>
						
		</div>

		<footer>
			<div class="copy">&copy All Rights Reserved</div>
			<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
		</footer>
</body>
</html>

	