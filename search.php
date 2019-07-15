<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('index.php');</script>";
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

	<title>FIND USER | <?php echo $_SESSION['user']['name']?></title>

	<style type="text/css">
		input,select{
			min-width: 40%;
			max-width: 90vw;
		}
		input[type=submit]{
			min-width: 5%;
		}
		button.msg{
			min-width: 5px;
			border-radius: 50%;
			border: none;
			font-size: 1.25em;
		}

		button.msg:hover, button.msg:focus{
			background-color: #000;
			color: #fff;
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
				<li><a href="assg.php">Assignments</a></li>
				<li><a href="message.php">Messages</a></li>
				<li><a href="news.php">Announcements</a></li>
				<li><a href="search.php" class="selected">Find user</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</header>
	<div class="container" align="center">
		<form method="GET">
			<span class="open-sans">FIND</span>&nbsp <input type="text" class="search" value= "<?php echo $_REQUEST['id']?>" name="id" pattern="[a-zA-Z0-9]+"> &nbsp<span class="op
			">BY</span>&nbsp
			<select name="by">
				<option value="username" <?php if ($_REQUEST['by']=='username'){?> selected ="true" <?php } ?>>Username</option>
				<option value="name" <?php if ($_REQUEST['by']=='name'){?> selected ="true" <?php } ?>>Name</option>
			</select>
			<input type="submit">
		</form>

		<?php if (isset($_REQUEST['id'])){?>

		<hr>
		<div class="title" style="font-size: 1.5em; color: #444444">Search Results</div>
		<table>
			<thead>
			<tr>
				<th>Username</th>
				<th>Name</th>
				<th></th>
			</tr>
			</thead>
			<tbody>

		<?php 
		if (!preg_match("/^[a-zA-Z0-9]*$/",$_REQUEST['id']) or !preg_match("/^(name|username)$/",$_REQUEST['by']))
			echo "Invalid search query! Try again";

		else{
		if ($_REQUEST['by']=='name')
		$res=mysqli_query($con,"SELECT info.username,list.name,info.role from info,list where list.name LIKE '%".mysqli_real_escape_string($con,$_REQUEST['id'])."%' and info.adm_no=list.adm_no ");
		else 
			$res=mysqli_query($con,"SELECT info.username,list.name,info.role from info,list where info.username LIKE '%".mysqli_real_escape_string($con,$_REQUEST['id'])."%' and info.adm_no=list.adm_no ");
		$i=0;
		while ($t=mysqli_fetch_assoc($res))
			{?>
			<tr>
				<form action="message.php" method="GET"><tr valign="top">
				<td><?php echo $t['username']?></td>
				<td><?php echo $t['name']?></td>
				<td><input type="text" name="user2" readonly="true" hidden="true" value="<?php echo $t['username']?>">
					<button class="msg"><i class="far fa-comment"></i></button></td> 
				</form>
			</tr>
			
		<?php $i++; }

			if ($i==0)
				{?>
			<tr>
				<td colspan="3"><center>No records found</center></td>
			</tr>
				<?php } ?>
			</tr>
	</table>
	<?php 
	}
	}

 	Close($con); ?>

	</div>
	<footer>
		<div class="copy">&copy All Rights Reserved</div>
		<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
	</footer>
</body>
</html>



