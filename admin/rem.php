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

	<title>ADMIN PANEL | Remove user</title>

	<style type="text/css">
		tr{
			border-bottom: none;
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
			<div class="tab-item"><a href="facrole.php">Faculty-in-Batches Management</a></div>
			<div class="tab-item selected"><a href="rem.php">Remove user</a></div>
			<div class="tab-item"><a href="chgpass.php">Reset password for user</a></div>
		</div>

		<div class="container">
			<div class="title" style="text-align: center; " >Remove user</div>
			<div class="box" style="background-color: Yellow; padding: 6px 9px;">
				<b><i class="fas fa-exclamation-triangle"></i>&nbspWARNING:</b><br>
				This action is IRREVERSIBLE.
			</div>
			<div class="box">
				<form method="POST" >
					<label><span class="open-sans">USERNAME:</span><input type="text" name="username" value="<?php echo $_POST['username']?>" pattern="[A-Za-z0-9]+" placeholder="Username" required="yes"></label>&nbsp&nbsp<input type="submit" name="find" value="Get" style="min-width: 0;">
				</form>
			</div>
			<br>
			<div>
				<?php if ($_POST['find']=="Get")
				{ 
					if ($_POST['username']=='admin')
						die("This user cannot be removed.");
					if (!preg_match("/^[A-Za-z0-9]+$/",$_POST['username']))
						die("Invalid username.");
					if (!mysqli_fetch_array(mysqli_query($con,"SELECT EXISTS (SELECT username from info where username='".$_POST['username']."') "),MYSQLI_NUM)[0])
						die("No such user!");
					$res=mysqli_fetch_assoc(mysqli_query($con,"SELECT info.username,list.name,info.role,list.adm_no from info,list where info.username='".$_POST['username']."' and info.adm_no=list.adm_no"));
				?>
			</div>

			<div>
				<form method="POST">
				<table>
					<thead>
						<tr>
							<th colspan="2">Search Result</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Username:</td>
							<td><input type="text"  name="username" readonly="true" value="<?php echo $res['username']?>"></td>
						</tr>
						<tr>
							<td>Admission No:</td>
							<td><input type="text"   readonly="true" value="<?php echo $res['adm_no']?>"></td>
						</tr>
						<tr>
							<td>Name:</td>
							<td><input type="text"  readonly="true" value="<?php echo $res['name']?>"></td>
						</tr>
						<tr>
							<td>Batch:</td>
							<td><input type="text" name="role" readonly="True" hidden="true" value="<?php echo $res['role']?>"><input type="text" readonly="true" value="<?php if ($res['role']=='faculty')
									echo "Faculty";
								else
									echo $res['role']." / Student";?>"></td>
						</tr>
						<tr>
							<td colspan="2"><label><input type="Checkbox" required="true">&nbsp&nbspConfirm deletion of this user. This process is IRREVERSIBLE</label></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" name="del" value="Remove"></td>
						</tr>
					</tbody>
				</table>
				</form>
			</div>
			<br>
			<div>
				<?php }
					if ($_POST['del']=="Remove"){
						if ($_POST['username']=='admin')
							die("This user cannot be removed.");
						if (!preg_match("/^[A-Za-z0-9]+$/",$_POST['username']))
							die("Invalid username.");
						mysqli_query($con,"DELETE from info where username='".$_POST['username']."'");
						if ($_POST['role']=='faculty')
							mysqli_query($con,"DELETE from rolefac where faculty='".$_POST['username']."'");
						else
							mysqli_query($con,"UPDATE rolelist SET rem=rem+1 where name='".$_POST['role']."'");
						$_POST['del']="";
						echo ("Successfully removed!");
					} 
					Close($con); ?>
				</div>
		</div>

		<footer>
			<div class="copy">&copy All Rights Reserved</div>
			<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
		</footer>
</body>
</html>



	




			