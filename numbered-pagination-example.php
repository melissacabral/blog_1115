<?php 
/**
 * Numbered pagination demo
 * @see lines 60-68 contains the additions, otherwise this code follows the same pattern as our search pagination demo. 
 */

require('header.php'); ?>
<main role="main">
	<?php 
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
	$query = "SELECT *
	FROM posts
	WHERE is_public = 1
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
				<h2>Blog with numbered pagination:</h2>
				<h3><?php echo $total; ?> posts total</h3>
				<h4>Showing page <?php echo $page_number; ?> of <?php echo $total_pages; ?></h4>
			</section>

			<section class="numbered-pagination">
				<ul>
					<?php if( $page_number > 1 ){ ?>
					<li class="page arrow">
						<a href="?phrase=<?php echo $phrase; ?>&amp;page=<?php echo $prev_page; ?>">&larr;</a>
					</li>
					<?php }

					//generate number links for each page needed				
					for ($i=1; $i <= $total_pages; $i++) { 
						if($i == $page_number){
							$css_class = 'page current-page';
						}else{
							$css_class = 'page';
						}
						echo "<li class='$css_class'><a href='?page=$i'>$i</a></li>";
					} 

					if( $page_number < $total_pages ){ ?>
					<li class="page arrow">
						<a href="?phrase=<?php echo $phrase; ?>&amp;page=<?php echo $next_page; ?>">&rarr;</a>
					</li>
					<?php } ?>
				</ul>
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