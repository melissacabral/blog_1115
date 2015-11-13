<?php 
require('header.php'); 
//which post are we trying to show?
//link will look like /blog/single.php?post_id=X
$post_id = $_GET['post_id'];
?>

<main role="main">
<?php //get the post the user is viewing
$q = "SELECT posts.title, posts.body, posts.date, users.username, 
			categories.name, posts.post_id
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
?>
	<article>
		<h2><?php echo $row['title']; ?></h2>
		<?php echo $row['body']; ?>
		<footer>
			<?php count_comments( $row['post_id'] ); ?>
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
		<h3>Comments:</h3>
		<ul>
			<?php 
			//loop it
			while( $row = $result->fetch_assoc() ){ ?>
			<li>
				Comment from <strong><?php echo $row['username']; ?></strong> on <strong><?php echo nice_date($row['date']) ?></strong>
				<p><?php echo $row['body']; ?></p>
			</li>
			<?php } //end while ?>
		</ul>
		
	</section>
	<?php 
	}//end of comment check ?>

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