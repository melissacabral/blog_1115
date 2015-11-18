<?php 
require('config.php'); 

//prevent functions from accidentally being loaded twice
include_once('functions.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Blog Demo</title>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,900|Raleway:400,300,800' rel='stylesheet' type='text/css'>	
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="alternate" type="application/rss+xml" href="rss.php">
</head>
<body>
<header role="banner">
	<h1><a href="index.php">Just a Blog</a></h1>
</header>