<?php 
//clean up ugly timestamps
function nice_date( $uglydate ){
	$date = new DateTime( $uglydate );
	return $date->format('l, F j, Y');
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

//no close PHP