<?php

session_start();

include_once 'classes/GenyWebConfig.php';
include_once 'classes/GenyProfile.php';
$web_config = new GenyWebConfig();

if(isset($_POST['geny_username']) && isset($_POST['geny_password']) ){
	trim($_POST['geny_username']);
	trim($_POST['geny_password']);

	$username = md5($_POST["geny_username"]);
	$passwd = md5($_POST["geny_password"]);

	if(!preg_match("/^[-a-z0-9 ']{4,12}+$/i",$_POST['geny_username'])){
	    echo "Username error";
	    exit();
	}

	$handle = mysql_connect($web_config->db_host,$web_config->db_user,$web_config->db_password);
	mysql_select_db("GYMActivity");
	$query = "SELECT profile_id,profile_login FROM Profiles WHERE md5(profile_login)='$username' AND profile_password='$passwd'";

	$result = mysql_query($query, $handle);

	if (mysql_num_rows($result)!=0) {
		//mark as valid user
		session_regenerate_id();
		$sqldata = mysql_fetch_assoc($result);
		$_SESSION['USERID'] = $username;
		$_SESSION['LOGGEDIN'] = true;
		$tmp_profile = new GenyProfile( $sqldata['profile_id'] );
		if( $tmp_profile->needs_password_reset )
			header('Location: user_admin_password_change.php');
		else
			header("Location: home.php");
		exit;
	}

	//if the code reaches this part then the login failed
	//wrong username/password

	header("Location: index.php?reason=badcredentials");
}
else
	echo "Error: Form variables undefined.";

?>