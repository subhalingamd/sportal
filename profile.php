<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

		if (!isset($_SESSION['user']) or $_SESSION['user']==""){
		session_unset();
		session_destroy();
		echo "<script>location.replace('login.php?next=profile.php');</script>";
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style/main.css">
	<link rel="stylesheet" type="text/css" href="style/profile.css">
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
	<title>PROFILE | <?php echo $_SESSION['user']['name']?></title>
	<script type="text/javascript">
		// Close opup when user clicks outside the content area
		window.onclick = function(event) {
   			if (event.target == document.getElementById('up-prof-pic')) {
        		document.getElementById('up-prof-pic').style.display = "none";
    		}
   			if (event.target == document.getElementById('chg-pass')) {
        		document.getElementById('chg-pass').style.display = "none";
    		}
    		if (event.target == document.getElementById('upd-det')) {
        		document.getElementById('upd-det').style.display = "none";
    		}
		}

		

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
				<li><a href="news.php">Announcements</a></li>
				<li><a href="search.php">Find user</a></li>
				<li><a href="profile.php" class="selected">Profile</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</header>

<?php
	$name=$_SESSION['user'];
	include "db_connect.php";
	$con=Connect();
?>

		<div class="container">
			<div class="left">
				
				<img src="prof-pic/<?php echo $_SESSION['user']['username'];?>.png" onerror="this.src='prof-pic/default.png';" alt="Profile Picture" onclick="document.getElementById('up-prof-pic').style.display='block'" style="width:auto;" class="prof-pic-1">
				<br><br><br>
				<a class="btn" onclick="document.getElementById('upd-det').style.display='block'" style="width:auto;">Update Profile</a><br>
				<a class="btn" onclick="document.getElementById('chg-pass').style.display='block'" style="width:auto;">Change Password</a>
				
			</div>
			<div class="right">
				<div class="box animate" style="width: 100%;">
					<div class="title">Profile</div>
					<div><label for="username"><div class="icon"><i class="fa fa-user"></i></div><input type="text" value="<?php echo $_SESSION['user']['username'];?>" id="username" required="true" readonly="true"></label><div class="div-desc">Username</div></div>
					<div><label for="adm_no"><div class="icon"><i class="fa fa-hashtag"></i></div><input type="text" value="<?php echo $_SESSION['user']['adm_no'];?>" id="adm_no" readonly="true"></label><div class="div-desc">Admission Number</div></div>
					<div><label for="role"><div class="icon"><i class="fas fa-users"></i></div><input type="text" name="role" id="role" readonly="true" 
						value="<?php if ($_SESSION['user']['role']=='admin')
										echo "ADMIN";
									elseif ($_SESSION['user']['role']=='faculty')
										echo "Faculty";
									else
										echo $_SESSION['user']['role']." / Student";?>">
					</label><div class="div-desc">Batch/Role</div></div>
					<div><label for="name"><div class="icon"><i class="fa fa-user-tie"></i></div><input type="text" value="<?php echo $_SESSION['user']['name'];?>" id="name" readonly="true"></label><div class="div-desc">Name</div></div>
					<div><label for="email"><div class="icon"><i class="fa fa-at"></i></div><input type="email" value="<?php echo $_SESSION['user']['email'];?>" id="email"  placeholder="E-mail ID" readonly="true"></label><div class="div-desc">E-mail ID</div></div>
					<div><label for="mobile"><div class="icon"><i class="fa fa-phone"></i></div><input type="tel" value="<?php echo $_SESSION['user']['mobile'];?>" id="mobile"  placeholder="Mobile No." readonly="true"></label><div class="div-desc">Mobile No.</div></div>
					<div><label for="dob"><div class="icon"><i class="far fa-calendar-alt"></i></div><input type="text" value="<?php echo $_SESSION['user']['dob'];?>" id="dob" readonly="true"></label><div class="div-desc">DOB</div></div>
				</div>
			</div>
		</div>

		<div class="popup" id="up-prof-pic">
			<form class="popup-content animate" action="scripts/upprofpic.php" enctype="multipart/form-data" method="POST">
				<div class="box">
					<div class="h">Update Profile Picture<span onclick="document.getElementById('up-prof-pic').style.display='none'" class="close" style=" float: right;">&times</span></div>
					<div><input type="file" name="prof-pic" id="prof-pic-select" accept="image/*" required="true"></div>
					<img src="" id="prof-pic-preview" style="width: 75%; height: auto;" />
					<div style="color: #444444">Upload an <b>image</b> file in <b>PNG</b> or <b>JPEG</b> format. File size <b>shouln't exceed 1MB</b>. You are advised to upload a <b>SQUARE</b> image, i.e., image with same height and width.</div>
					<div><input type="submit" value="Update"></div>
				</div>
			</form>
		</div>

		<script type="text/javascript">
		// SHOW PREVIEW OF UPLOADED IMAGE. CODE TAKEN FROM---
		// https://itsolutionstuff.com/post/display-preview-selected-image-in-input-type-file-using-jqueryexample.html
    	function readURL(input) {
        	if (input.files && input.files[0]) {
            	var reader = new FileReader();
            
            	reader.onload = function (e) {
                	$('#prof-pic-preview').attr('src', e.target.result);
            	}
            	reader.readAsDataURL(input.files[0]);
        	}
    	}
    	$("#prof-pic-select").change(function(){
     	   readURL(this);
    	});
		</script>



		<?php if ($_SESSION['msg']['prof-pic_fail']!=''){?>
			<div class="popup" id="upd-prof-pic-err">
			<div class="popup-content animate">
				<div class="box">
					<div class="h">Error Uploading</div>
					<div>
						<?php echo $_SESSION['msg']['prof-pic_fail']?>
					</div>
					<a class="btn"  onclick="document.getElementById('upd-prof-pic-err').style.display='none'" style="min-width: 40%;">Ok</a>
				</div>
			</div>
			</div>
		<?php echo"<script>document.getElementById('upd-prof-pic-err').style.display='block';</script>";
		unset($_SESSION['msg']['prof-pic_fail']);
		} ?>



		<div class="popup" id="chg-pass">
			<form class="popup-content animate" action="scripts/chgpass.php" method="POST">
				<div class="box">
					<div class="h">Change Password<span onclick="document.getElementById('chg-pass').style.display='none'" class="close" style=" float: right;">&times</span></div>
					<div><label for="curpass"><div class="icon"><i class="fa fa-key"></i></div><input type="password" name="curpass" id="curpass" required="true" placeholder="Current Password"></label></div>
					<div><label for="newpass1"><div class="icon"><i class="fa fa-key"></i></div><input type="password" name="newpass[]" id="newpass1" required="true" placeholder="New Password"></label></div>
					<div><label for="newpass2"><div class="icon"><i class="fa fa-key"></i></div><input type="password" name="newpass[]" id="newpass2" required="true" placeholder="Confirm New Password"></label></div>
					<font color="grey"><b>NOTE:</b> You will be logged out after a successful password change</font>
					<div><input type="submit" name="chg-pass" value="Change"></div>
					
				</div>
			</form>
		</div>

		<?php if (isset($_SESSION['msg']['chg-pass_res']) and $_SESSION['msg']['chg-pass_res']!=''){?>
			<div class="popup" id="chg-pass-res">
			<div class="popup-content animate">
				<div class="box">
					<div class="h">Change Password</div>
					<div>
						<?php echo $_SESSION['msg']['chg-pass_res']?>
					</div>
					<?php if ($_SESSION['msg']['chg-pass_res-id']==1){
							session_unset();
							session_destroy();?>
						<a class="btn" href="index.php" style="min-width: 40%">Logout</a>
					<?php } else {?>
						<a class="btn" onclick="document.getElementById('chg-pass-res').style.display='none'" style="min-width: 40%;">Ok</a>
					<?php }?>
				</div>
			</div>
			</div>
		<?php echo"<script>document.getElementById('chg-pass-res').style.display='block';</script>";
		unset($_SESSION['msg']['chg-pass_res']);
		unset($_SESSION['msg']['chg-pass_res-id']);
	} ?>

		<div class="popup" id="upd-det">
			<form class="popup-content animate" action="scripts/upddet.php" method="POST">
				<div class="box">
					<div class="h">Update Details<span onclick="document.getElementById('upd-det').style.display='none'" class="close" style=" float: right;">&times</span></div>
					<div><label for="upd-username"><div class="icon"><i class="fa fa-user"></i></div><input type="text" value="<?php echo $_SESSION['user']['username'];?>" name="username" id="upd-username" required="true" readonly="true"></label></div>
					<div><label for="upd-adm_no"><div class="icon"><i class="fa fa-hashtag"></i></div><input type="text" value="<?php echo $_SESSION['user']['adm_no'];?>" name="adm_no" id="upd-adm_no" required="true" readonly="true"></label></div>
					<div><label for="upd-name"><div class="icon"><i class="fa fa-user-tie"></i></div><input type="text" value="<?php echo $_SESSION['user']['name'];?>" id="upd-name" required="true" readonly="true"></label></div>

					<div><label for="upd-email"><div class="icon"><i class="fa fa-at"></i></div><input type="email" value="<?php echo $_SESSION['user']['email'];?>" name="email" id="upd-email" required="true" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Enter a valid E-mail ID" placeholder="E-mail ID"></label></div>
					<div><label for="upd-mobile"><div class="icon"><i class="fa fa-phone"></i></div><input type="tel" value="<?php echo $_SESSION['user']['mobile'];?>" name="mobile" id="upd-mobile" required="true" pattern="^[0-9]{8,}$" title="Enter a valid Number (without +)" placeholder="Mobile No."></label></div>

					<div><label for="upd-dob"><div class="icon"><i class="far fa-calendar-alt"></i></div><input type="text" value="<?php echo $_SESSION['user']['dob'];?>" id="upd-dob" required="true" readonly="true"></label></div><br>


					Confirm your identity by entering the current password
					<div><label for="upd-pass"><div class="icon"><i class="fa fa-key"></i></div><input type="password" name="pass" id="upd-pass" required="true" placeholder="Password"></label></div>
					<div><input type="submit" name="upd-det" value="Update"></div>
				</div>
			</form>
		</div>

		<?php if (isset($_SESSION['msg']['upd-det_res']) and $_SESSION['msg']['upd-det_res']!=''){?>
			<div class="popup" id="upd-det-res">
			<div class="popup-content animate">
				<div class="box">
					<div class="h">Update Profile</div>
					<div>
						<?php echo $_SESSION['msg']['upd-det_res']?>
					</div>
						<a class="btn" onclick="document.getElementById('upd-det-res').style.display='none'" style="min-width: 40%;">Ok</a>
				</div>
			</div>
			</div>
		<?php echo"<script>document.getElementById('upd-det-res').style.display='block';</script>";
		unset($_SESSION['msg']['upd-det_res']);;
		} ?>

	<footer>
		<div class="copy">&copy All Rights Reserved</div>
		<div class="last-login"><?php echo "Last Logout: ".$_SESSION['user']['active'];?></div>
	</footer>
</body>
</html>