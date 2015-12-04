<?php
/**
 * INSERT,  DISPLAY or DELETE CATEGORIES
 * This file gets loaded by an AJAX request
 * It is identical whether using jquery or pure ajax
 * note that it has no doctype and is not intended as a standalone file. 
 */
require('../config.php');

//what action are we performing?
$action = $_REQUEST['action'];

if($action == 'display'){

	//get all cats to update the display
	$query = "SELECT *
			 FROM categories
			 ORDER BY category_id DESC";
	//run it
	$result = $db->query($query);

	//check
	if($result->num_rows >= 1){
		echo '<ul>';
		while($row = $result->fetch_assoc()){  
		?> 
			<li><?php echo $row['name']  ?> <a href="javascript:;" data-action="delete" data-id="<?php echo $row['category_id'] ?>" class="del"><i class="fa fa-trash warn"></i></a></li>
		<?php
		}
		echo '</ul>';
	}else{
		echo 'Sorry, no categories';
	}
}elseif($action == 'insert'){
	//sanitize
	$name = mysqli_real_escape_string($db, trim($_REQUEST['name']));
	//validate
	if($name != ''){
		//add the new category
		echo $query = "INSERT INTO categories 
				(name)	VALUES	('$name')";
		$result = $db->query($query);
	}
}elseif($action == 'delete'){
	$category_id = $_REQUEST['category_id'];
	$query = "DELETE FROM categories WHERE category_id = $category_id";
	$db->query($query);
}