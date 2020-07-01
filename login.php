<?php 
	if(session_status()!=PHP_SESSION_ACTIVE) 
		session_start();

	if (!in_array($_REQUEST['next'], ['assg.php','message.php','news.php','search.php','profile.php']))
			$_REQUEST['next']='index.php';

	if (isset($_SESSION['user']) and $_SESSION['user']!=""){
		echo "<script>location.replace('".$_REQUEST['next']."')</script>";
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="style/login.css">

	<!-- JQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>


	<!-- IMPORT FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Lato&display=swap" rel="stylesheet">

	<!-- ICONS !-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css'>


</head>
<body>

	<script type="text/javascript">
		 $(function () {
		 	var next = "<?php echo $_REQUEST['next'] ?>"
		    $('#login-form').on('submit', function(e) {
		        e.preventDefault();
		        $("#login-err").html("Validating...");
		        $.ajax({
		            url : "scripts/enter.php",
		            type: "POST",
		            data: $(this).serialize(),
		            success: function (data) {
		            	console.log(data);
		                if (data=='ok'){
		                	$("#login-err").html("Signing in...");
 							window.history.replaceState( null, null, window.location.href );
 							location.replace(next);
		                }
		                else if (data == 'deny'){
		                	$("#login-err").html("Invalid username/password");
		                }
		                else{
		                	$("#login-err").html(data);
		                }
		            }
		            });
		    });
		});
	</script>

	<div class="login">
		<title>LOGIN</title>
		<form class="login-content animate" method="POST" id="login-form">
			<div class="box">
				<div class="h open-sans">Login</div>
				<div><label for="username"><div class="icon"><i class="fa fa-user"></i></div><input type="text" name="username" id="username" required="true" pattern="[a-zA-Z0-9]+" placeholder="Username"></label></div>
				<div><label for="password"><div class="icon"><i class="fa fa-key"></i></div><input type="password" name="password" id="password" required="true" placeholder="Password"></label></div>
				<font color="red"><i><span id="login-err"></span></i></font>
				<div><input type="submit" value="Login >"></div>
			</div>
			<font color="grey">(Use your DOB in YYYY-MM-DD format as your password for the first time login)</font>
		</form>
	</div>


</body>
</html>