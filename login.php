<?php 
require( 'config.php' );
session_start();

//LOGOUT logic
if( $_GET['logout'] == true ){
	//clear the key in the DB
	$user_id = $_SESSION['user_id'];
	$query = "UPDATE users
				SET login_key = ''
				WHERE user_id = $user_id
				LIMIT 1";
	$result = $db->query($query);

	session_destroy();

	setcookie( 'key', '', time() - 99999 );
	unset( $_SESSION['key'] );

	setcookie( 'user_id', '', time() - 99999 );
	unset( $_SESSION['user_id'] );



}elseif( $_COOKIE['key'] ){
	$_SESSION['key'] = $_COOKIE['key'];
	$_SESSION['user_id'] = $_COOKIE['user_id'];
}

//parse the form if it is submitted
if( $_POST['did_login'] ){
	//username/password policy: username must be between 5 and 30 chars
	//password must be at least 5 chars
	$input_username = strip_tags(trim($_POST['username']));
	$input_password = strip_tags(trim($_POST['password']));

	$username_length = strlen($input_username);
	$password_length = strlen($input_password);

	if( $username_length >= 6 
		AND $username_length <= 30 
		AND $password_length >= 7 ){

		//look up the username/password combo in the DB
		$query = "SELECT user_id
					FROM users
					WHERE username = '$input_username' 
					AND password = sha1('$input_password')
					LIMIT 1";
		$result = $db->query($query);
		//if one user is found, log them in!
		if( $result->num_rows == 1 ){
			//success! remember the user for 1 week
			//generate a secret key
			$key = sha1(microtime() . 'aseilfz456skl.ivjad,ku1137jhgqkjhwqk');
			setcookie( 'key', $key, time() + 60 * 60 * 24 * 7 );
			$_SESSION['key'] = $key;

			$row = $result->fetch_assoc();
			$user_id = $row['user_id'];
			setcookie('user_id', $user_id, time() + 60 * 60 * 24 * 7 );
			$_SESSION['user_id'] = $user_id;

			//store the key in the DB
			$query = "UPDATE users
					SET login_key = '$key'
					WHERE user_id = $user_id
					LIMIT 1";
			$result = $db->query($query);
			//redirect to the admin panel
			header('Location:admin/');
		}else{
			//error
			$message = 'Incorrect combo. Try again.';
		}	
	} //end length check
	else{
		//error
			$message = 'Incorrect combo. Try again.';
	}
}//end of parser
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login to your account</title>
	<link rel="stylesheet" type="text/css" href="admin/css/admin-style.css">
</head>
<body class="login">
	<h1>Log in</h1>

	<?php echo $message; ?>

	<form action="login.php" method="post">
		<label>Username:</label>
		<input type="text" name="username">

		<label>Password:</label>
		<input type="password" name="password">

		<input type="submit" value="Log In">
		<input type="hidden" name="did_login" value="true">
	</form>

</body>
</html>