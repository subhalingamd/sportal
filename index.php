<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('login.php');</script>";
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>HOME | <?php echo $_SESSION['user']['name']?></title>

	<link rel="stylesheet" type="text/css" href="style/main.css">
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

	<style type="text/css">
		@media screen and (min-width: 750px){
			table{
				min-width: 50%;
			}
		}
			th,td>a{
						display: block;
						text-decoration: none;
						font-family: "Open Sans Condensed";
						font-size: 1.2em;
			}

	</style>


</head>
<body>


	<script type="text/javascript">
		$(function(){
			$('.banner').slideDown("slow");
		})
	</script>

		<header>
			<div id="toggle"><a onclick="$('ul.nav').slideToggle('slow');"><i class="material-icons" style="font-size:1.5em;color:#fff">menu</i></a></div>
			<div class="profile">
				<img src="prof-pic/<?php echo $_SESSION['user']['username'];?>.png" onerror="this.src='prof-pic/default.png';" class="prof-pic">
				<div class="prof-name"><?php echo $_SESSION['user']['name']; ?></div>
			</div>
			<ul class="nav">
				<li><a href="index.php" class="selected">Home</a></li>
				<li><a href="assg.php">Assignments</a></li>
				<li><a href="message.php">Messages</a></li>
				<li><a href="news.php">Announcements</a></li>
				<li><a href="search.php">Find user</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="logout.php">Logout</a></li>

			</ul>
		</header>

		<div class="banner">
			<i class="fas fa-exclamation-circle" style="color: #888"></i> An update to this page will be available in the next release
			<span class="banner-close" onclick="$('.banner').slideUp();">&times</span>
		</div>

		<div class="container">
			<?php  include "db_connect.php";
					$con=Connect();
			if ($_SESSION['user']['role']=='faculty'){ ?>
				<table style="max-width: 50%">
					<thead>
						<tr>
							<th>YOUR BATCHES</th>
						</tr>
					</thead>
					<tbody>
						<?php $rol=mysqli_query($con,"SELECT role from rolefac where faculty='".$_SESSION['user']['username']."'");
						while ($r=mysqli_fetch_array($rol,MYSQLI_NUM)){?>
						<tr>
							<td><a href="search.php?id=s<?php echo $r[0]?>&by=username">> <?php echo $r[0]?></a></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>



			<?php }

			if ($_SESSION['user']['role']=="admin") { ?>

				<style>
					tr{
						border-bottom: none;
					}
					
				</style>

				<table>
					<thead>
						<tr>
							<th>ADMIN PANEL</th>
						</tr>
					</thead>
					<tbody>
						<tr><td><a href="admin/add.php">Add user</a></div></td></tr>
						<tr><td><a href="admin/role.php">Batches Management</a></td></tr>
						<tr><td><a href="admin/facrole.php">Faculty-in-Batches Management</a></td></tr>
						<tr><td><a href="admin/rem.php">Remove user</a></td></tr>
						<tr><td><a href="admin/chgpass.php">Reset password for user</a></td></tr>
					</tbody>
				</table>
		
			<?php } ?>
		</div>
 		<footer>
			<div class="copy">&copy All Rights Reserved</div>
			<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
		</footer>

</body>
</html>