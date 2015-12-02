<?php 
$page = 'edit';
//which post are we editing?
$post_id = $_GET['post_id'];

require('admin-header.php'); 

//who is logged in?
$user_id = $_SESSION['user_id'];

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
		
		$query = "UPDATE posts
					SET
					title 			= '$title',
					body 			= '$body',
					is_public		= $is_public,
					allow_comments 	= $allow_comments,
					category_id 	= $category_id
					WHERE post_id = $post_id
					AND user_id = $user_id";
		$result = $db->query($query);
		if( $db->affected_rows == 1 ){
			$message = 'Your post has been saved';
			$class = 'success';
		}else{
			$message = 'No changes were made to this post';
			$class = 'info';
		} 
	} //end if valid
	else{
		$message = 'There were problems with your post. Please correct these issues:';
		$class = 'error';
	}
	
}//end of parser

//get all the info about this post, make sure the logged-in user wrote it
echo $query_post = "SELECT title, body, is_public, allow_comments, category_id
				FROM posts
				WHERE post_id = $post_id
				AND user_id = $user_id
				LIMIT 1";
$result_post = $db->query($query_post);

?>

<main role="main">
	<section class="panel important">

	<?php if( $result_post->num_rows >= 1 ){
		//no need to loop a LIMIT 1 query
		$row_post = $result_post->fetch_assoc();
	 ?>

		<h2>Edit Your Post</h2>

		<?php //if there is feedback, show it
		if( isset($message) ){
			echo '<div class="feedback '. $class .'">';
			echo $message;
			array_list($errors);
			echo '</div>';
		} ?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>?post_id=<?php echo $post_id; ?>" method="post">
			<div class="twothirds">
				<label>Title:</label>
				<input type="text" name="title" value="<?php echo $row_post['title'] ?>">

				<label>Body:</label>
				<textarea name="body"><?php echo htmlspecialchars($row_post['body']) ?></textarea>
			</div>
			<div class="onethird">
				<h3>Publish Settings</h3>
				<label>
					<input type="checkbox" name="allow_comments" value="1" <?php 
						if( $row_post['allow_comments'] )
							echo 'checked';
					 ?>>
					Allow users to comment
				</label>

				<label>
					<input type="checkbox" name="is_public" value="1" <?php 
						if( $row_post['is_public'] )
							echo 'checked';
					 ?>>
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
					<option value="<?php echo $row['category_id']; ?>" <?php 
						if( $row['category_id'] == $row_post['category_id'] ){
							echo 'selected';
						}
					 ?>>
						<?php echo $row['name']; ?>
					</option>
					<?php } //end while ?>

				</select>
				<?php } //end if cats ?>

				<input type="submit" value="Save Post">
				<input type="hidden" name="did_post" value="1">
			</div>
		</form>	
		<?php 
		}else{
			echo 'You cannot edit this post';
		} ?>	
	</section>
</main>
<?php include('admin-footer.php'); ?>