<?php 
$page = 'write';
require('admin-header.php'); 
include('parse-post.php');
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
				<textarea name="body"><?php echo $body ?></textarea>
			</div>
			<div class="onethird">
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
				<label>Category:</label>
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