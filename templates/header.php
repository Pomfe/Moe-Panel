<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>File Query</title>

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
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo MOE_URL;?>"><?php echo POMF_NAME; ?></a>
				</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="<?php echo MOE_URL; ?>/user/panel" target="_BLANK">Moe Panel</a></li>
						<li><a href="<?php echo MOE_URL; ?>/includes/api.php?do=logout">Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container-fluid">
			<form class="form-inline" action="<?php echo MOE_URL; ?>/includes/api.php" method="get">
				<input type="hidden" name="do" value="fetch">
				<div class="form-group">
					<label for="date">Date:</label>
					<input id="date" type="date" name="date" data-date-format="YYYY-MM-DD" value="<?php if (empty($date)) {echo date('Y-m-d');} else { echo $date;}?>">
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
