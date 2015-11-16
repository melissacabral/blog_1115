<?php 
require('header.php'); 
//which post are we trying to show?
//link will look like /blog/single.php?post_id=X
$post_id = $_GET['post_id'];

//load the comment processor
include('parse-comment.php');
?>

<main role="main">
<?php //get the post the user is viewing
$q = "SELECT posts.title, posts.body, posts.date, users.username, 
categories.name, posts.post_id, posts.allow_comments
FROM posts, users, categories
WHERE posts.is_public = 1
AND posts.user_id = users.user_id
AND posts.category_id = categories.category_id
AND posts.post_id = $post_id
LIMIT 1";
//run it
$result = $db->query($q);
//check it
if( $result->num_rows >= 1 ){ 
	//go through each row in the result and display it
	while( $row = $result->fetch_assoc() ){
		$comments_allowed = $row['allow_comments'];
		?>
		<article>
			<h2><?php echo $row['title']; ?></h2>
			<?php echo $row['body']; ?>
			<footer>
				
				Posted on <?php echo nice_date($row['date']); ?>
				By <b><?php echo $row['username'] ?></b>
				In the category <b><?php echo $row['name'] ?></b>
			</footer>
		</article>

	<?php //get all approved comments written about this post
	$query = "SELECT users.username, comments.body, comments.date
	FROM users, comments
	WHERE users.user_id = comments.user_id
	AND comments.is_approved = 1
	AND comments.post_id = $post_id
	LIMIT 10";
	//run it
	$result = $db->query($query);
	//check it
	if($result->num_rows >= 1){
		?>
		<section class="comments-list">
			<h3><?php count_comments($post_id); ?>Comments:</h3>
			<ul>
				<?php 
			//loop it
				while( $row = $result->fetch_assoc() ){ ?>
				<li>
					<span class="comment-info">Comment from <strong><?php echo $row['username']; ?></strong> 
						<br>
						<strong><?php echo nice_date($row['date']) ?></strong></span>
						<p class="comment-body"><?php echo $row['body']; ?></p>
					</li>
					<?php } //end while ?>
				</ul>
				
			</section>
			<?php 
		}//end of comment check ?>

		<?php if( $comments_allowed ){ ?>
		<section class="comment-form" id="leave-comment">
			<h3>Leave a Comment</h3>
			<?php 
	//display feedback
			if( isset($feedback) ){
				echo '<div class="feedback">';
				echo $feedback;
				echo '</div>';
			} ?>
			<form action="#leave-comment" method="post">
				<label for="body">Your Comment:</label>
				<textarea name="body" id="body"></textarea>

				<?php // TODO:  change this so it works with the actual logged in user ?>
				<input type="hidden" name="user_id" value="1">

				<input type="hidden" name="did_comment" value="true">

				<input type="submit" value="Comment">
			</form>
		</section>
		<?php } //end if comments allowed ?>


		<?php 
	} //end while loop

	//we are done working with the posts, set the results free
	$result->free();
}else{
	echo 'No posts to show';
}
?>

</main>

<?php include('sidebar.php'); ?>
<?php include('footer.php'); ?>