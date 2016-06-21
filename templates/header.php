<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Mod</title>

		<!-- Bootstrap -->
		<link href="../css/bootstrap.min.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
<body>
<div class="container-fluid">
	<p>Keep in mind that this is a alpha version of the mod panel, click <a href="<?php echo MOE_URL; ?>/user/includes/api.php?do=logout">here</a> to logout or <a href="http://cayootie.pomf.se/user/panel" target="_BLANK">here</a> to go to the panel for your personal account.</p>
	<form class="form-inline" action="<?php echo MOE_URL; ?>/user/includes/api.php" method="get">
		<input type="hidden" name="do" value="mod">
		<input type="hidden" name="action" value="fetch">
		<div class="form-group">
			<label for="date">Date:</label>
			<input id="date" type="date" name="date" value="<?php echo date('Y-m-d');?>">
		</div>
		<div class="form-group">
			<label for="amount">Amount:</label>
			<input id="amount" type="number" name="count" value="30">
		</div>

		<div class="form-group">
			<label for="amount">Keyword:</label>
			<input type="text" name="keyword">
		</div>
		<input class="btn btn-default" type="submit" value="fetch">
	</form>
	<br>
	<table id="result" class="table">
		<tr>
			<th>ID</th>
			<th>Orginal Name</th>
			<th>Filename</th>
			<th>Size (bytes)</th>
			<th>Action</th>
		</tr>
