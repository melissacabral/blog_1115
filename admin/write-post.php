<?php 
$page = 'write';
require('admin-header.php'); 
//parse the form
if( $_POST['did_post'] ){
	//sanitize everything
	$title = clean_input($_POST['title'] );
	$body = clean_input($_POST['body'], '<p><a><b><br><i>' );
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
?>

<main role="main">
	<section class="panel important">
		<h2>Write New Post</h2>

		<?php //if there is feedback, show it
		if( isset($message) ){
			echo '<div class="feedback '. $class .'">';
			echo $message;
			array_list($errors);
			echo '</div>';
		} ?>

		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
			<div class="twothirds">
				<label>Title:</label>
				<input type="text" name="title" value="<?php echo $title ?>">

				<label>Body:</label>
				<textarea name="body"><?php echo htmlspecialchars($body) ?></textarea>
			</div>
			<div class="onethird">
				<h3>Publish Settings</h3>
				<label>
					<input type="checkbox" name="allow_comments" value="1">
					Allow users to comment
				</label>

				<label>
					<input type="checkbox" name="is_public" value="1">
					Make this post public
				</label>

				<?php //get all the categories in alphabetical order
				$query = "SELECT * FROM categories
							ORDER BY name ASC";
				$result = $db->query($query);
				if( $result->num_rows >= 1 ){ ?>
				<h3>Category:</h3>
				<select name="category_id">
					<option>Choose a category</option>
					
					<?php while( $row = $result->fetch_assoc() ){ ?>
					<option value="<?php echo $row['category_id']; ?>">
						<?php echo $row['name']; ?>
					</option>
					<?php } //end while ?>

				</select>
				<?php } //end if cats ?>

				<input type="submit" value="Save Post">
				<input type="hidden" name="did_post" value="1">
			</div>
		</form>		
	</section>
</main>
<?php include('admin-footer.php'); ?>