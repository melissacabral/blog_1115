<?php 
session_start();
require('config.php');
include_once('functions.php');

//is the viewer logged in? 
check_login_key();
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