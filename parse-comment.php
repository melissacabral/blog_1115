<?php
//if the form was submitted, parse it
if( $_POST['did_comment'] ){
	//sanitize all fields
	$user_id = filter_var( $_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);
	$body = filter_var( $_POST['body'], FILTER_SANITIZE_STRING);
	//protect DB from dangerous chars in the string
	$body = mysqli_real_escape_string( $db, $body );

	//validate
	$valid = true;

	//check for missing fields
	if( $user_id == '' OR $body == '' ){
		
		$feedback = 'Please fill in the comment field';
		$valid = false; 
	}
	
	//if valid, add to DB
	if( $valid ){
		//set up query
		$query = "INSERT INTO comments
					( user_id, body, post_id, date, is_approved )
					VALUES
					( $user_id, '$body', $post_id, now(), 1 )";
		//run it
		$result = $db->query($query);
		//check it
		if( $db->affected_rows == 1 ){
			//show user feedback
			$feedback = 'Thank you for your comment.';
			//redirect so that refreshing the page doesn't double-post
			header('Location:' . $_SERVER['REQUEST_URI'] );
	
		}else{
			$feedback = 'Sorry, your comment could not be added at this time.';
		} //end of check it
		
	} //end if valid	
	
} //end parser

//no close php