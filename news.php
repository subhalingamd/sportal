<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('login.php?next=news.php');</script>";
	}

	include "db_connect.php";
	$con=Connect();

	$name=$_SESSION['user'];
	$xyz=mysqli_query($con,"SELECT * from news where user2='".$name['username']."' or user2='".$name['role']."' order by time desc");
	?>


<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style/main.css">
	<link rel="stylesheet" type="text/css" href="style/form.css">
	<link rel="stylesheet" type="text/css" href="style/popup.css">
	
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

	<title>ANNOUNCEMENTS | <?php echo $_SESSION['user']['name']?></title>

	<style type="text/css">
		a.btn{
			min-width: 0;
			display: inline-block;
			user-select: none;
			cursor: pointer;
		}
	</style>

	<script type="text/javascript">
		setInterval(function(){ 
        $('#news-box').load('news.php #news-box');
		}, 60000);
	</script>

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
				<li><a href="news.php" class="selected">Announcements</a></li>
				<li><a href="search.php">Find user</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</header>

		<div class="container">
			<span class="title">Announcements</span>
			<a class="btn" style="border-radius: 50%; position: fixed; right: 1ch; z-index: 1; box-shadow: -3px 3px 3px #666666; " onclick="$('#news-box').load('news.php #news-box');"><i class="fas fa-redo"></i></a>

			<?php if ($_SESSION['user']['role']=='admin' or $_SESSION['user']['role']=='faculty'){  ?>
				<br><a class="btn" onclick="document.getElementById('news').style.display='block'" style="width:auto;"><i class="fas fa-plus"></i>&nbspAdd</a><br><br>
			<?php } ?>

			<div id="news-box">

			<?php while ($msg=mysqli_fetch_assoc($xyz)) {	?>
			<div class="news">
				<span class="news-from"><a href="search.php?id=<?php echo $msg['user1']; ?>&by=username"><?php echo $msg['user1']; ?></a></span>
				<span class="news-time"><?php echo $msg['time']; ?></span>
				<div class="news-desc"><?php echo nl2br($msg['msg']); ?></div>
			</div>
			<?php } 
			Close($con);?>

			</div>


		</div>
		<div class="popup" id="news">
			<form class="popup-content animate" action="scripts/post-news.php" method="POST">
				<div class="box">
					<div class="h">Add Announcement<span onclick="document.getElementById('news').style.display='none'" class="close" style=" float: right;">&times</span></div>
					<div><input type="text" readonly="True" value="<?php echo $_SESSION['user']['username'] ?>" name="user1"></div>
					<div><input type="text" name="user2" value="<?php echo $_POST['user2']?>" required="True" placeholder="To" pattern="[a-zA-Z0-9]+"></div>
					
					<?php if (isset($_POST['post-fail']) and $_POST['post-fail']!=''){ ?>
					<div style="color: red; font-style: italic; font-size: 0.8em;"><?php echo $_POST['post-fail'];?></div>
					<?php }?>
		
					<div><textarea name="msg" rows="5"  maxlength="1024" placeholder="Enter the Announcement here..." required="True"><?php echo $_POST['msg']?></textarea></div>

					<div><input type="submit"></div>

				</div>
			</form>
		</div>

		<?php if (isset($_POST['post-fail']) and $_POST['post-fail']!='')
			echo "<script>document.getElementById('news').style.display='block'</script>"
		?>


	<footer>
		<div class="copy">&copy All Rights Reserved</div>
		<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
	</footer>
</body>
</html>
