<?php 
//clean up ugly timestamps
function nice_date( $uglydate ){
	$date = new DateTime( $uglydate );
	return $date->format('F j, Y');
}

//count the comments on any post
//$post = the ID of the post we are looking up
function count_comments( $post ){
	global $db;
	$query = "SELECT COUNT(*) AS total
	FROM comments
	WHERE post_id = $post
	AND is_approved = 1";
	//run it
	$result = $db->query($query);
	//check it
	if( $result->num_rows >= 1 ){
		//loop it
		while( $row = $result->fetch_assoc() ){
			echo '<span class="comments-number">' . $row['total'] . '</span>';
		}
		//free it
		$result->free();
	}	
}

//shorten a long string, but preserve words
function shorten($str, $length, $minword = 3){
	$sub = '';
	$len = 0;   
	foreach (explode(' ', $str) as $word){
		$part = (($sub != '') ? ' ' : '') . $word;
		$sub .= $part;
		$len += strlen($part);       
		if (strlen($word) > $minword && strlen($sub) >= $length){
			break;
		}
	}   
	return $sub . (($len < strlen($str)) ? '<span class="ellipses">&hellip;</span>' : '');
}

function array_list($array){
	if(is_array($array)){
		$output = '<ul>';
		foreach ($array as  $value) {
			$output .= '<li>' . $value . '</li>'; 
		}
		$output .= '</ul>';
		echo $output;
	}
}

//check to see if the user is already logged in
function check_login_key(){
	global $db;

	$key = $_SESSION['key'];
	$user_id = $_SESSION['user_id'];
	//compare the local key to the DB key
	$query = "SELECT is_admin FROM users
				WHERE login_key = '$key'
				AND user_id = $user_id
				LIMIT 1";
	$result = $db->query($query);

	if($result->num_rows == 1){
		$row = $result->fetch_assoc();
		define( IS_ADMIN, $row['is_admin'] );

		return true;
	}else{
		define( IS_ADMIN, '' );
		return false;
	}
}

// use this to clean user input strings and prepare them for the DB. 
// accepts a string list of allowed tags, like '<p><em><strong><br>'
function clean_input( $data, $allowed_tags = '' ){
	global $db;
	return mysqli_real_escape_string($db, strip_tags(trim($data), $allowed_tags));
}

//show any user's userpic at any of the preset sizes
//size names are thumb_img and medium_img
function show_userpic($user_id, $size = 'thumb_img'){
	global $db;
	//get the usepic randomsha from the DB
	$query = "SELECT userpic 
			FROM users
			WHERE user_id = $user_id
			LIMIT 1";
	$result = $db->query($query);
	if( $result->num_rows >= 1 ){
		$row = $result->fetch_assoc();
		//check to see if the user has a userpic
		if( $row['userpic'] == '' ){
			//show a default head
			echo '<img src="' . ROOT_URL . '/images/default.png" alt="default userpic" class="avatar default">';
		}else{
			//show the userpic
			echo '<img src="' . ROOT_URL . '/uploads/' . $row['userpic'] . '_' . $size . 
			'.jpg" alt="User Profile Pic" class="avatar">';
		}
	}
}
//no close PHP