<?php require('header.php'); ?>
<main role="main" class="search">
	<?php //what phrase did they search? clean it up
	$phrase = mysqli_real_escape_string( $db, $_GET['phrase'] );
	//settings for pagination
	$per_page = 2;
	//default page to start on
	$page_number = 1;
	//if page is set in the URI, override page number
	//?page=2
	if( $_GET['page'] ){
		//make sure page is a whole number
		$page_number = round($_GET['page']);
	}

	//get all the public posts that have a title or body that matches the phrase 
	$query = "SELECT post_id, title, body
			FROM posts
			WHERE is_public = 1
			AND ( title LIKE '%$phrase%' OR body LIKE '%$phrase%' )
			ORDER BY date DESC";
	$result = $db->query($query);
	$total = $result->num_rows;
	//check it
	if( $total >= 1 ){

		//math for pagination
		//how many pages do we need? round up so we don't cut off leftover posts
		$total_pages = ceil($total / $per_page);

		//make sure we are viewing a valid page
		if( $page_number <= $total_pages AND $page_number > 0 ){

			$prev_page = $page_number - 1;
			$next_page = $page_number + 1;

			$offset = ($page_number - 1) * $per_page;
			$query_modified = $query . " LIMIT $offset, $per_page";
		//run the new query
			$result = $db->query($query_modified);
			?>
			<section class="search-heading">
				<h2>Search Results:</h2>
				<h3><?php echo $total; ?> results found for <?php echo $phrase; ?></h3>
				<h4>Showing page <?php echo $page_number; ?> of <?php echo $total_pages; ?></h4>
			</section>
			<?php while( $row = $result->fetch_assoc() ){ ?>
			<article>
				<h2><a href="single.php?post_id=<?php echo $row['post_id']; ?>">
					<?php echo $row['title']; ?>
				</a></h2>
				<div class="excerpt"><?php echo shorten( strip_tags($row['body']), 100 ); ?></div>
				<footer><?php count_comments($row['post_id']); ?></footer> 
			</article>
			<?php } ?>

			<section class="pagination">

				<?php if( $page_number > 1 ){ ?>
				<a href="?phrase=<?php echo $phrase; ?>&amp;page=<?php echo $prev_page; ?>">&larr; Previous Page</a>
				<?php } ?>

				<?php if( $page_number < $total_pages ){ ?>
				<a href="?phrase=<?php echo $phrase; ?>&amp;page=<?php echo $next_page; ?>">Next Page &rarr;</a>
				<?php } ?>

			</section>

			<?php 
		}else{
			$error =  'Invalid Page';
		}
	}else{
		$error =  'Sorry, No posts found.';
	} //end if total is more than 1 

	if(isset($error)){
		echo '<section><h2>';
		echo $error;
		echo '</h2></section>';
	}
	?>
</main>
<?php include('sidebar.php'); ?>
<?php include('footer.php'); ?>