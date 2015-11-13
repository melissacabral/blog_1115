<?php 
require('header.php'); 
?>

<main role="main">
<?php //get all the published blog posts
$q = 'SELECT posts.title, posts.body, posts.date, users.username, 
categories.name, posts.post_id
FROM posts, users, categories
WHERE posts.is_public = 1
AND posts.user_id = users.user_id
AND posts.category_id = categories.category_id
ORDER BY posts.date DESC';
//run it
$result = $db->query($q);
//check it
if( $result->num_rows >= 1 ){ 
	//go through each row in the result and display it
	while( $row = $result->fetch_assoc() ){
		?>
		<article>
			<h2>
				<a href="single.php?post_id=<?php echo $row['post_id']; ?>">
					<?php echo $row['title']; ?>
				</a>
			</h2>
			<p><?php echo $row['body']; ?></p>
			<footer>
				<?php count_comments( $row['post_id'] ); ?>
				Posted on <?php echo nice_date($row['date']); ?>
				By <b><?php echo $row['username'] ?></b>
				In the category <b><?php echo $row['name'] ?></b>
				
			</footer>
		</article>
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