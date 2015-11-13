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
				<a href="single.php?post_id=<?php echo $row['post_id'] ?>">
					<?php echo $row['title']; ?>
				</a>
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