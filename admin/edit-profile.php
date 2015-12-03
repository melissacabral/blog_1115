<?php 
$page = 'users';
require('admin-header.php'); 

//begin image parser
if( $_POST['did_upload'] ){
	
	//file uploading stuff begins
	$target_path = "../uploads/";
	
	//list of image sizes to generate. make sure a column name in your DB matches up with a key for each size
	$sizes = array(
		'thumb_img' => 150,
		'medium_img' => 300 
	);	
		
	// This is the temporary file created by PHP
	$uploadedfile = $_FILES['uploadedfile']['tmp_name'];
	// Capture the original size of the uploaded image
	list($width,$height) = getimagesize($uploadedfile);
	
	//make sure the width and height exist, otherwise, this is not a valid image
	if($width > 0 AND $height > 0){
	
	//what kind of image is it
	$filetype = $_FILES['uploadedfile']['type'];
	
	switch($filetype){
		case 'image/gif':
			// Create an Image from it so we can do the resize
			$src = imagecreatefromgif($uploadedfile);
		break;
		
		case 'image/pjpeg':
		case 'image/jpg':
		case 'image/jpeg': 
			// Create an Image from it so we can do the resize
			$src = imagecreatefromjpeg($uploadedfile);
		break;
	
		case 'image/png':
			// Create an Image from it so we can do the resize
			$required_memory = Round($width * $height * $size['bits']);
			$new_limit=memory_get_usage() + $required_memory;
			ini_set("memory_limit", $new_limit);
			$src = imagecreatefrompng($uploadedfile);
			ini_restore ("memory_limit");
		break;		
			
	}
	//for filename
	$randomsha = sha1(microtime());
	
	//do it!  resize images
	foreach($sizes as $size_name => $size_width){
		if($width >=  $size_width){
			$newwidth = $size_width;
			$newheight=($height/$width) * $newwidth;
		}else{
			$newwidth=$width;
			$newheight=$height;
		}
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		
		$filename = $target_path.$randomsha.'_'.$size_name.'.jpg';
		$didcreate = imagejpeg($tmp,$filename,70);
		imagedestroy($tmp);
				
	}
	
	imagedestroy($src);
	
		
	}else{//width and height not greater than 0
		$didcreate = false;
		$statusmsg = 'You did not upload an image.';
	}
	
	
	if($didcreate) {
		//the file upload worked, so save the randomsha in the DB
		$user_id = $_SESSION['user_id'];
		$query = "UPDATE users
					SET userpic = '$randomsha'
					WHERE user_id = $user_id
					LIMIT 1";
		$result = $db->query($query);

		$statusmsg .=  "The file ".  basename( $_FILES['uploadedfile']['name']). 
		" has been uploaded <br />";
	} else{
		$statusmsg .= "There was an error uploading the file, please try again!<br />";
	}		


}//end parser
?>

<main role="main">
	<section class="panel important">
		<h2>Edit Your Profile Picture</h2>

		<div class="onehalf box">
		<?php 
		if( isset($statusmsg) ){
			echo $statusmsg;
		} ?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" 
				enctype="multipart/form-data">
			
			<input type="file" name="uploadedfile">

			<input type="submit" value="Update Image">
			<input type="hidden" name="did_upload" value="1">
			
		</form>
		</div>
		<div class="onehalf box">
		<!-- Current profile pic -->
		<?php show_userpic( $_SESSION['user_id'], 'medium_img' ); ?>
		</div>
	</section>
	

</main>
<?php include('admin-footer.php'); ?>