<?php 
session_start(); 
require('../config.php'); 
include_once('../functions.php');

//if not logged in as an admin, send them somewhere else
if( ! check_login_key() ){
	header( 'Location:../login.php' );
	die('You cannot see this');
}elseif( ! IS_ADMIN ){
	header('Location:../');
	die('You are not an admin');
}
?>
<!doctype html>
<html>
<head>
	<title>Admin Panel</title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/admin-style.css">
</head>
<body class="<?php echo $page; ?>">
	<header role="banner">
    <h1>Admin Panel</h1>
    <ul class="utilities">
      <li class="view"><a href="../">View Blog</a></li>
      <li class="users"><a href="#">My Account</a></li>
      <li class="logout warn"><a href="../login.php?logout=true">Log Out</a></li>
    </ul>
  </header>

  <nav role='navigation'>
    <ul class="main">
      <li class="dashboard"><a href="index.php">Dashboard</a></li>
      <li class="write"><a href="write-post.php">Write Post</a></li>
      <li class="edit"><a href="manage-posts.php">Edit Posts</a></li>
      <li class="comments"><a href="#">Comments</a></li>
      <li class="users"><a href="edit-profile.php">Edit Profile</a></li>
    </ul>
  </nav>