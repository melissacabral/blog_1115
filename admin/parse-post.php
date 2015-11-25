<?php //parse the form
if( $_POST['did_post'] ){
	//sanitize everything
	$title = filter_var( $_POST['title'], FILTER_SANITIZE_STRING );
	$body = filter_var( $_POST['body'], FILTER_SANITIZE_STRING );
	$category_id = filter_var( $_POST['category_id'], FILTER_SANITIZE_NUMBER_INT );
	//make sure boolean values are 1 or 0
	if( $_POST['is_public'] == 1 ){
		$is_public = 1;
	}else{
		$is_public = 0;
	}

	if( $_POST['allow_comments'] == 1 ){
		$allow_comments = 1;
	}else{
		$allow_comments = 0;
	}

	//validate
	$valid = true;
	
	//title or body left blank
	if( $title == '' ){
		$valid = false;
		$errors['title'] = 'Title is required.';
	}	
	if($body == '' ){
		$valid = false;
		$errors['body'] = 'Body is required.';
	}	
	//category blank
	if($category_id == ''){
		$valid = false;
		$errors['category'] = 'Please choose a category for this post';
	}
	
	//if valid, add to DB
	if($valid){
		$user_id = $_SESSION['user_id'];
		$query = "INSERT INTO posts
				(title, category_id, date, body, user_id, is_public, allow_comments)
				VALUES 
				('$title', $category_id, now(), '$body', $user_id, $is_public, 
					$allow_comments )";
		$result = $db->query($query);
		if( $db->affected_rows == 1 ){
			$message = 'Your post has been saved';
			$class = 'success';
		}else{
			$message = 'Sorry, your post could not be saved';
			$class = 'error';
		} 
	} //end if valid
	else{
		$message = 'There were problems with your post. Please correct these issues:';
		$class = 'error';
	}
	
}//end of parser