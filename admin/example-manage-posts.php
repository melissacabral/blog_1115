<?php 
$page = 'edit';
require('admin-header.php');
?>
<main role="main">
	<section class="panel important">
		<h2>Manage Your Posts</h2>
			<?php 
			//get all the posts that were written by the logged in user
			$user_id = $_SESSION['user_id'];
			$query = "SELECT p.post_id, p.title, p.is_public, c.name
						FROM posts AS p, categories AS c
						WHERE user_id = $user_id
						AND p.category_id = c.category_id
						ORDER BY date DESC";
			$result = $db->query($query); 
			if( $result->num_rows >= 1 ){ ?>
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
						<a href="admin-edit.php?post_id=<?php echo $row['post_id'] ?>" ><?php echo $row['title']; ?></a>
					</td>
					<td>
						<?php echo nice_date( $row['date'] ); ?>
					</td>
					<td>
						<?php echo ($row['is_public'] == 1) ? 'public' : '<b>draft<b>' ; ?>
					</td>
					<td><?php echo $row['name']; ?></td>
					<td>
						<a href="?action=delete&amp;post_id=<?php echo $row['post_id'] ?>" class="warn button" onclick="return confirmAction()" >
							<i class="fa fa-trash"></i> delete
						</a>
			</td>
			</tr>
			<?php } ?>
			</table>

<script>
// Function to confirm permanent actions. use onclick.
function confirmAction(){
	var agree = confirm("This is permanent. Are you sure?");
	if(agree){
		return true;
	}else{
		return false;
	}
}
</script>
<?php }//end if there are posts
else{
	echo 'You have not written any posts yet.';
	} ?>
</section>
</main>
<?php include('admin-footer.php'); ?>