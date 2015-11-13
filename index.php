<?php 
require('config.php'); 

//prevent functions from accidentally being loaded twice
include_once('functions.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Blog Demo</title>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,900|Raleway:400,300,800' rel='stylesheet' type='text/css'>	
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<header role="banner">
	<h1>Just a Blog</h1>
</header>
<main role="main">
<?php //get all the published blog posts
$q = 'SELECT posts.title, posts.body, posts.date, users.username, 
			categories.name, posts.post_id
		FROM posts, users, categories
		WHERE posts.is_public = 1
		AND posts.user_id = users.user_id
		AND posts.category_id = categories.category_id
		ORDER BY posts.date DESC
		LIMIT 2';
//run it
$result = $db->query($q);
//check it
if( $result->num_rows >= 1 ){ 
	//go through each row in the result and display it
	while( $row = $result->fetch_assoc() ){
?>
	<article>
		<h2><?php echo $row['title']; ?></h2>
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

<aside role="complementary" class="sticky">
	<section>
		<h2>Latest Posts:</h2>
		<?php $query = 'SELECT title, post_id
						FROM posts
						WHERE is_public = 1
						ORDER BY date DESC
						LIMIT 5';
		$result = $db->query($query);
		if( $result->num_rows >= 1 ){
		?>
		<ul>
			<?php while( $row = $result->fetch_assoc() ){ ?>

			<li>
				<?php echo $row['title']; ?> 
				<?php count_comments($row['post_id']) ?>
			</li>

			<?php }//end while

			$result->free();
			 ?>
		</ul>
		<?php 
		}else{
			echo 'No posts to show';
		} ?>
	</section>

	<section>
		<h2>Categories:</h2>
		<?php $query = 'SELECT name
						FROM categories
						LIMIT 5';
		$result = $db->query($query);
		if( $result->num_rows >= 1 ){
		?>
		<ul>
			<?php while( $row = $result->fetch_assoc() ){ ?>

			<li><?php echo $row['name']; ?></li>

			<?php }//end while

			$result->free();
			 ?>
		</ul>
		<?php 
		}else{
			echo 'No cats to show';
		} ?>
	</section>

	<section>
		<h2>Links:</h2>
		<?php $query = 'SELECT title, url
						FROM links
						LIMIT 5';
		$result = $db->query($query);
		if( $result->num_rows >= 1 ){
		?>
		<ul>
			<?php while( $row = $result->fetch_assoc() ){ ?>

			<li>
				<a href="<?php echo $row['url'] ?>">
					<?php echo $row['title']; ?>
				</a>
			</li>

			<?php }//end while

			$result->free();
			 ?>
		</ul>
		<?php 
		}else{
			echo 'No links to show';
		} ?>
	</section>

</aside>

<footer role="contentinfo">Made for WIP400 at Platt College</footer>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
</body>
</html>