<?php 
$page = 'dashboard';
require('admin-header.php'); 
?>

<main role="main">
	<section class="panel important">
		<h2>Welcome to Your Dashboard </h2>
		<ul>
			<li>Important panel that will always be really wide Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
			<li>Aliquam tincidunt mauris eu risus.</li>
			<li>Vestibulum auctor dapibus neque.</li>
		</ul>
	</section>
	<section class="panel">
		<h2>Your Content:</h2>
		<ul>
			<li>You have written XX published posts</li>
			<li>You have written XX Post Drafts</li>
			<li>Latest Draft: POST TITLE</li>
		</ul>

	</section>
	<section class="panel">
		<h2>Most Popular:</h2>
		<ul>
			<li>POST TITLE with XX comments</li>
			<li>POST TITLE with XX comments</li>
			<li>POST TITLE with XX comments</li>
		</ul>
	</section>

</main>
<?php include('admin-footer.php'); ?>