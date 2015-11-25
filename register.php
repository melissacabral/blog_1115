<?php
//connect to DB
require('config.php'); 
include_once('functions.php');

//form parser
if( $_POST['did_register'] ){
	//sanitize all fields
	$username = filter_var( $_POST['username'], FILTER_SANITIZE_STRING );
	$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL );
	$password = filter_var( $_POST['password'], FILTER_SANITIZE_STRING );
	$policy = filter_var( $_POST['policy'], FILTER_SANITIZE_NUMBER_INT);

	//hashed password for storage. this will become 40 chars long
	$hashed_password = sha1($password);
	
	//validate
	$valid = true;
	//username wrong length
	if( strlen($username) < 6 OR strlen($username) > 30 ){
		$valid = false;
		$errors['username'] = 'Choose a username between 6 and 30 characters long.';
	}else{
		//username already taken
		$query = "SELECT username FROM users
				WHERE username = '$username' 
				LIMIT 1";
		$result = $db->query($query);
		//if one row found, the username is already taken :(
		if( $result->num_rows == 1 ){
			$valid = false;
			$errors['username'] = 'Sorry, that username is already taken.';
		}
	}
	//email not valid
	if( ! filter_var( $email, FILTER_VALIDATE_EMAIL) ){
		$valid = false;
		$errors['email'] = 'Please provide a valid email address';
	}else{
		//email already taken
		$query = "SELECT email FROM users
					WHERE email = '$email'
					LIMIT 1";
		$result = $db->query( $query );
		if( $result->num_rows == 1 ){
			$valid = false;
			$errors['email'] = 'Your email address is already registered. Try logging in.';
		}
	}
	//password too short
	if( strlen( $password ) < 7 ){
		$valid = false;
		$errors['password'] = 'That password is too short';
	}
	//policy not checked
	if( $policy != 1 ){
		$valid = false;
		$errors['policy'] = 'You must agree to the terms of service in order to sign up';
	}
	
	//if valid, add the user to the DB, tell them to go log in
	if( $valid ){
		$query = "INSERT INTO users
				  ( email, username, password, is_admin, date_joined )
				  VALUES 
				  ( '$email', '$username', '$hashed_password', 0, now() )";
		$result = $db->query($query);
		if( $db->affected_rows == 1 ){
			$feedback = "Welcome, $username You are now registered. Please go login.";
		}else{
			$feedback = 'Sorry, your account could not be created. Please try again';
		}
	}
}//end parser	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Register to Comment</title>
	<link rel="stylesheet" type="text/css" href="admin/css/admin-style.css">
</head>
<body class="login">
<h1>Sign up as a Commenter</h1>

<?php //user feedback
echo $feedback;
array_list($errors);
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<label>Create your username:</label>
	<input type="text" name="username">
	<span class="hint">Between 6-30 characters</span>

	<label>Your Email Address:</label>
	<input type="email" name="email">

	<label>Create your password</label>
	<input type="password" name="password">
	<span class="hint">At least 7 characters</span>

	<label>
		<input type="checkbox" name="policy" value="1">
		I agree to the <a href="#">Terms of service</a>
	</label>

	<input type="submit" value="Sign Up">
	<input type="hidden" name="did_register" value="1">
</form>
</body>
</html>