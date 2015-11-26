<aside role="complementary" class="sticky">
	<section class="search-box">
		<form action="search.php" method="get">
			<label for="search">Search:</label>
			<input type="search" name="phrase" id="search" value="<?php echo $_GET['phrase'] ?>">
			<input type="submit" value="Search">
		</form>
	</section>

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

<?php if(IS_ADMIN != ''){ ?>
<section>
	<h2>You are logged in</h2>
	<ul>
		<li><a href="login.php?logout=true">Log Out</a></li>
		<li><a href="admin/">View Admin</a></li>
	</ul>
</section>
<?php } ?>

</aside>