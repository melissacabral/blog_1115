<?xml version="1.0" encoding="utf-8"?>
<?php require('config.php'); 

function timestamp( $date ){
	$date = new DateTime( $date );
	return $date->format('D, d M Y H:i:s O');
}
?>
<rss version="2.0">
	<channel>
		<title>Melissa's Blog</title>
		<link>http://localhost/melissa-php-1115/blog_1115</link>
		<description>Just a feed of our blog posts</description>
		<?php //get up to 10 published posts, newest first
		$query = "SELECT p.title, p.body, p.post_id, p.date, 
					u.username, u.email, c.name
				FROM posts AS p, users AS u, categories AS c
				WHERE p.is_public = 1
				AND p.user_id = u.user_id
				AND p.category_id = c.category_id
				ORDER BY p.date DESC
				LIMIT 10";
		$result = $db->query($query);
		if( $result->num_rows >= 1 ){
			while( $row = $result->fetch_assoc() ){
		 ?>		
		<item>
			<title><?php echo $row['title']; ?></title>
			<link>http://localhost/melissa-php-1115/blog_1115/single.php?post_id=<?php echo $row['post_id']; ?></link>
			<guid>http://localhost/melissa-php-1115/blog_1115/single.php?post_id=<?php echo $row['post_id']; ?></guid>
			<description><![CDATA[<?php echo $row['body']; ?>]]></description>
			<author><?php echo $row['email']; ?> (<?php echo $row['username'] ?>)</author>
			<pubDate><?php echo timestamp($row['date']); ?></pubDate>
			<category><?php echo $row['name']; ?></category>
		</item>
		<?php 
			}//end while
		}//end if ?>
	</channel>
</rss>