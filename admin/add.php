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

	<title>ADMIN PANEL | Add user</title>

	<style type="text/css">
		tr{
			border-bottom: none;
		}
	</style>

	<script type="text/javascript">
		$(document).ready(function(){
    		$("[name='role-sel']").change(function(){
       			$(this).find("option:selected").each(function(){
            		var opt = $(this).attr("value");
            		if (opt=="student"){
            			$("select[name='role'] option[value='faculty']").remove();
            			$("select[name='role']").removeAttr("disabled");
            		}
            		else{
            			$("select[name='role']").append("<option value='faculty'>FACULTY</option>");
            			$("select[name='role']").val("faculty");
            			$("select[name='role']").attr("disabled","true");
            		}
        		});
    		}).change();
		});	

		function Enable(){
			$("select[name='role']").removeAttr("disabled");

		}
	</script>


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
			<div class="tab-item selected"><a href="add.php">Add user</a></div>
			<div class="tab-item"><a href="role.php">Batches Management</a></div>
			<div class="tab-item"><a href="facrole.php">Faculty-in-Batches Management</a></div>
			<div class="tab-item"><a href="rem.php">Remove user</a></div>
			<div class="tab-item"><a href="chgpass.php">Reset password for user</a></div>
		</div>

		<div class="container">
			<div class="title" style="text-align: center;">Add User</div>
			<div>
				<table>
					<thead>
						<tr>
							<th>Role</th>
							<th>
								<select name="role-sel">
									<option value="student">Student</option>
									<option value="faculty">Faculty</option>
								</select>
							</th>
						</tr>
					</thead>
					<form method="POST" onsubmit="Enable();">
					<tbody>	
						<tr>
							<td>Name:</td>
							<td><input type="text" name="name" required="True" placeholder="Name" pattern="[A-Za-z0-9\s]+"></td>
						</tr>
						<tr>
							<td>Batch:</td>
							<td><select name="role" required="true">
								<option value="">-SELECT BATCH-</option>
								<?php $rol=mysqli_query($con,"SELECT name from rolelist");
								while ($r=mysqli_fetch_array($rol,MYSQLI_NUM)){?>
									<option value="<?php echo $r[0]?>"><?php echo $r[0]?></option>
								<?php } ?>
								<option value="faculty">FACULTY</option>
							</select></td>
						</tr>
						<tr>
							<td>DOB:</td>
							<td><input type="text" name="dob" required="true" placeholder="YYYY-MM-DD" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"></td>
						</tr>
						<tr>
							<td>E-mail ID:</td>
							<td><input type="text" name="email" placeholder="Email ID" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"></td>
						</tr>
						<tr>
							<td>Mobile:</td>
							<td><input type="text" name="mobile" placeholder="Mobile Number" pattern="^[0-9]{8,}$"></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" name="submit" value="Add"></td>
						</tr>
					</tbody>
					</form>
				</table>

				<?php
					if ($_POST['submit']=="Add"){
						if ($_POST['role']=="faculty"){
						mysqli_query($con,"UPDATE count set faculty=faculty+1");
						$adm_no=mysqli_fetch_array(mysqli_query($con,"SELECT CONCAT('t',DATE_FORMAT(now(),'%y'),LPAD(faculty,4,0)) from count"),MYSQLI_NUM)[0];
						$id=$adm_no;
						}
						else{
							mysqli_query($con,"UPDATE count set student=student+1");
							$adm_no=mysqli_fetch_array(mysqli_query($con,"SELECT CONCAT('s',DATE_FORMAT(now(),'%y'),LPAD(student,5,0)) from count"),MYSQLI_NUM)[0];
							mysqli_query($con,"UPDATE rolelist set count=count+1 where name='".$_POST['role']."'");
							$id=mysqli_fetch_array(mysqli_query($con,"SELECT CONCAT('s','".$_POST['role']."',LPAD(count,3,0)) from rolelist where name='".$_POST['role']."'"),MYSQLI_NUM)[0];
						}
						$pass=md5($_POST['dob']);

						$stmt=mysqli_prepare($con,"INSERT INTO info VALUES(?,?,?,?,SYSDATE())");
						mysqli_stmt_bind_param($stmt,"ssss",$id,$pass,$adm_no,$_POST['role']);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt);

						$stmt=mysqli_prepare($con,"INSERT INTO list VALUES (?,?,?,?,?,?)");
						mysqli_stmt_bind_param($stmt,"ssssss",$adm_no,$_POST['name'],$_POST['email'],$_POST['mobile'],$_POST['dob'],$_POST['role']);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt);
						echo "<script>location.replace('add.php')</script>";
					} 
					Close($con);?>


			</div>
		</div>

		<footer>
			<div class="copy">&copy All Rights Reserved</div>
			<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
		</footer>
</body>
</html>



			