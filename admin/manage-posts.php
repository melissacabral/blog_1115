<?php 
$page = 'edit';
require('admin-header.php'); 

$user_id = $_SESSION['user_id'];

//DELETE PARSER
if( $_GET['action'] == 'delete' ){
	//what post are they trying to delete?
	$post_id = $_GET['post_id'];
	$query = "DELETE FROM posts
				WHERE post_id = $post_id
				AND user_id = $user_id
				LIMIT 1";
	$result = $db->query($query);
}
?>

<main role="main">
	<section class="panel important">
		<h2>Manage Your Posts</h2>
		<?php 
		//get all posts written by the logged in user	
		$query = "SELECT p.post_id, p.title, p.date, p.is_public, c.name
					FROM posts AS p, categories AS c
					WHERE p.user_id = $user_id
					AND p.category_id = c.category_id
					ORDER BY p.date DESC";
		$result = $db->query($query);
		if( $result->num_rows >= 1 ){			
		?>
		<table>
			<tr>
				<th>Title</th>
				<th>Date</th>
				<th>Status</th>
				<th>Category</th>
				<th>Delete</th>
			</tr>

			<?php while( $row = $result->fetch_assoc() ){ ?>
			<tr>
				<td>
					<a href="edit-post.php?post_id=<?php echo $row['post_id'] ?>">
						<?php echo $row['title']; ?>
					</a>
				</td>
				<td><?php echo nice_date($row['date']); ?></td>

				<td><?php echo ( $row['is_public'] == 1 ) ? 'public' : '<strong>draft</strong>'; ?></td>

				<td><?php echo $row['name'] ?></td>
				<td>
					<a href="?action=delete&amp;post_id=<?php echo $row['post_id'] ?>" class="warn" onclick="return confirmDelete()">
					<i class="fa fa-trash"></i>
					</a>
				</td>
			</tr>
			<?php } //end while ?>

		</table>
		<?php } //end if posts found ?>
	</section>	

</main>

<script>
//confirm that delete is permanent. use onclick
function confirmDelete(){
	if( confirm("This is permanent, are you sure?") ){
		return true;
	}else{
		return false;
	}
}	
</script>

<?php include('admin-footer.php'); ?>