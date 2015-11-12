<?php require('config.php'); 
//clean up ugly timestamps
function nice_date( $uglydate ){
	$date = new DateTime( $uglydate );
	return $date->format('l, F j, Y');
}
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
$q = 'SELECT title, body, date 
		FROM posts
		WHERE is_public = 1
		ORDER BY date DESC
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
		<footer>Posted on <?php echo nice_date($row['date']); ?></footer>
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
		<?php $query = 'SELECT title
						FROM posts
						WHERE is_public = 1
						ORDER BY date DESC
						LIMIT 5';
		$result = $db->query($query);
		if( $result->num_rows >= 1 ){
		?>
		<ul>
			<?php while( $row = $result->fetch_assoc() ){ ?>

			<li><?php echo $row['title']; ?></li>

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