<h2>SETUP</h2>
From the Owner:<br>
&nbspWelcome to the Portal! First of all, thanks for choosing this! Its time for some setup... You are just one step behind before you can start using the Portal! Hope you will have a great time ahead... <br>Goodbye :)
<hr>
<h3>DATABASE DETAILS:</h3>
<form action="<?php $_PHP_SELF?>" method="POST">
		DB Host: <input type="text" name="dbhost" value="<?php echo $_REQUEST['dbhost']?>" required="true" placeholder="Host"><br>
		DB Username: <input type="text" name="dbuser" value="<?php echo $_REQUEST['dbuser']?>" required="true" placeholder="Username"><br>
		DB Password<input type="password" name="dbpass" value="<?php echo $_REQUEST['dbpass']?>" required="true" placeholder="Password"><br>
		DB Name<input type="text" name="db" value="<?php echo $_REQUEST['db']?>" required="true" placeholder="Database"><br>
		<input type="submit" name="db_connect" value="Go">
</form>
<font color="green" size=3><b>
<?php
	if ($_REQUEST['db_connect']=='Go'){
	$f=fopen("db_connect.php", "w");
	fwrite($f,"<?php\nfunction Connect(){\n\t\$dbhost = \"".$_REQUEST['dbhost']."\";\n\t\$dbuser = \"".$_REQUEST['dbuser']."\";\n\t\$dbpass = \"".$_REQUEST['dbpass']."\";\n\t\$db=\"".$_REQUEST['db']."\";\n\t\$conn = new mysqli(\$dbhost, \$dbuser, \$dbpass,\$db) or die(\"Connect failed! Try setting up again\");\nreturn \$conn;\n}\n\nfunction Close(\$conn){\n\t\$conn -> close();\n}\n?>");
	fclose($f);
		$con = new mysqli($_REQUEST['dbhost'], $_REQUEST['dbuser'], $_REQUEST['dbpass']) or die ("Cannot establish a connection!");
		mysqli_query($con,"CREATE DATABASE ".$_REQUEST['db']);
	$_REQUEST['db_connect']="";
	echo "Successful";
}?>
</b></font>

<hr>
<h3>TIMEZONE:</h3>
Note: The timezone should match with your <b>(<i>MYSQL</i>)SERVER's time zone</b>. This will be used in setting up the timer during examinations.<br>
<form action="<?php $_PHP_SELF?>" method="POST">
		 <select name="timezone" >
    		<?php  $zones = timezone_identifiers_list();
					foreach ($zones as $zone){ ?>
   			   <option value="<?php print $zone ?>"><?php print $zone?></option>
   			<?php } ?>
 		</select>
		<input type="submit" name="tz-sub" value="Go">
</form>
<font color="green" size=3><b>
<?php
	if ($_REQUEST['tz-sub']=='Go'){
	$f=fopen("clock.php", "w");
	fwrite($f,"<?php\n\tdate_default_timezone_set(\"".$_REQUEST['timezone']."\");\n?>");
	fclose($f);
	$_REQUEST['tz-sub']="";
	echo "Successful";
}?>
</b></font>



<hr>
&nbsp&nbsp<u><i><font color="red" size=3>for first time use only</font></i></u><br>
<h3>ADMIN DETAILS:</h3>
<i>(Fill this up only after filling up the DATABASE DETAILS)</i>
<form action="<?php $_PHP_SELF?>" method="POST">
	Username: <input type="text" name="username" readonly="true" value="admin"><br>
	Password: <input type="password" name="password" required="true" placeholder="Password">&nbsp&nbsp<u><i><font color="red" size=2>required</font></i></u><br>
	Name: <input type="text" name="name" required="true" placeholder="Admin">&nbsp&nbsp<u><i><font color="red" size=2>required</font></i></u><br>
	Email ID: <input type="text" name="email" placeholder="Email ID"><br>
	Mobile No: <input type="text" name="mobile" placeholder="Mobile"><br>
	DOB: <input type="date" name="dob" placeholder="YYYY-MM-DD"><br>
	<input type="submit" name="admin" value="Go">
</form>
<font color="green" size=3><b>
<?php
	if ($_REQUEST['admin']=='Go'){
		include "db_connect.php";
		$con=Connect();
		mysqli_query($con,"CREATE TABLE assignments (aid int AUTO_INCREMENT, aname tinytext, role varchar(15), manager varchar(63), stime datetime, dur time, etime datetime, maxmarks float, password varchar(25), finalised int(1) DEFAULT '0', PRIMARY KEY (aid) ); ");
		mysqli_query($con,"CREATE TABLE info (username varchar(63),password longblob,adm_no varchar(31), role varchar(15),active datetime, PRIMARY KEY (username) ); ");
		mysqli_query($con,"CREATE TABLE list (adm_no varchar(31),name varchar(255),email varchar(255), mobile bigint, dob date, role varchar(15), PRIMARY KEY (adm_no) ); ");
		mysqli_query($con,"CREATE TABLE messages (id bigint AUTO_INCREMENT,user1 varchar(63), user2 varchar(63),msg longtext,time datetime, PRIMARY KEY (id) ); ");
		mysqli_query($con,"CREATE TABLE news (id bigint AUTO_INCREMENT,user1 varchar(63), user2 varchar(63),msg longtext,time datetime, PRIMARY KEY (id) ); ");
		mysqli_query($con,"CREATE TABLE questions (qid bigint AUTO_INCREMENT,q mediumtext,qtype varchar(3),opt1 text,opt2 text,opt3 text,opt4 text,aid int,ans varchar(31),relax float DEFAULT '0',marks float,pen float,PRIMARY KEY (qid) ); ");
		mysqli_query($con,"CREATE TABLE reqasg (manager varchar(63),aname tinytext,role varchar(15),stime datetime,dur time,etime datetime, PRIMARY KEY (manager) ); ");
		mysqli_query($con,"CREATE TABLE timer (ctr bigint AUTO_INCREMENT,username varchar(63),aid int,etime datetime,token mediumblob, PRIMARY KEY (ctr) ); ");
		mysqli_query($con,"CREATE TABLE rolelist (name varchar(15),count int,rem int, PRIMARY KEY (name) ); ");
		mysqli_query($con,"CREATE TABLE count (dum int AUTO_INCREMENT, student bigint DEFAULT '0', faculty bigint DEFAULT '0', PRIMARY KEY (dum) ); ");
		mysqli_query($con,"CREATE TABLE rolefac (id bigint AUTO_INCREMENT,role varchar(15),faculty varchar(63), PRIMARY KEY (id) ); ");


		mysqli_query($con,"INSERT INTO info VALUES ('admin','".md5($_POST['password'])."','admin','admin',SYSDATE())");
		mysqli_query($con,"INSERT INTO list VALUES ('admin','".$_POST['name']."','".$_POST['email']."','".$_POST['mobile']."','".$_POST['dob']."','admin')");
		mysqli_query($con,"INSERT INTO count (student,faculty) VALUES(0,0)");
	
	echo "Successful";
}
?>
</b></font>




<hr>
<i>*You can use this page to update any of the first TWO sections above, at any point of time.</i>

<br><br><hr>
&copy All Rights Reserved



