<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style/main.css">
	<link rel="stylesheet" type="text/css" href="style/login.css">
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


</head>
<body>

<?php 
	if ($_SESSION['user']==""){
?>

	<script type="text/javascript">
		 $(function () {
		    $('#login-form').on('submit', function(e) {
		        e.preventDefault();
		        $("#login-err").html("Validating...");
		        $.ajax({
		            url : "scripts/enter.php",
		            type: "POST",
		            data: $(this).serialize(),
		            success: function (data) {
		                $("#login-err").html(data);
		            }
		            });
		    });
		});
	</script>

	<div class="login">
		<title>LOGIN</title>
		<form class="login-content animate" method="POST" id="login-form">
			<div class="box">
				<div class="h">Login</div>
				<div><label for="username"><div class="icon"><i class="fa fa-user"></i></div><input type="text" name="username" id="username" required="true" pattern="[a-zA-Z0-9]+" placeholder="Username"></label></div>
				<div><label for="password"><div class="icon"><i class="fa fa-key"></i></div><input type="password" name="password" id="password" required="true" placeholder="Password"></label></div>
				<font color="red"><i><span id="login-err"></span></i></font>
				<div><input type="submit" value="Login >"></div>
			</div>
			<font color="grey">(Use your DOB in YYYY-MM-DD format as your password for the first time login)</font>
		</form>
	</div>

<?php 
	}
	else {
?>
		<title>HOME | <?php echo $_SESSION['user']['name']?></title>
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

		<div class="container">
			<?php  include "db_connect.php";
					$con=Connect();
			if ($_SESSION['user']['role']=='faculty'){ ?>
				<table>
					<thead>
						<tr>
							<th>YOUR BATCHES ></th>
						</tr>
					</thead>
					<tbody>
						<?php $rol=mysqli_query($con,"SELECT role from rolefac where faculty='".$_SESSION['user']['username']."'");
						while ($r=mysqli_fetch_array($rol,MYSQLI_NUM)){?>
						<tr>
							<td><a href="search.php?id=s<?php echo $r[0]?>&by=username"><?php echo $r[0]?></a></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>



			<?php }

			if ($_SESSION['user']['role']=="admin") { ?>
				<table>
					<thead>
						<tr>
							<th>ADMIN PANEL ></th>
						</tr>
					</thead>
					<tbody>
						<tr><td><a href="admin/add.php">Add user</a></td></tr>
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

	<?php } ?>


</body>
</html>