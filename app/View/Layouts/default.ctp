<!DOCTYPE html>
<html>
<head>
	<title>RobotMail</title>
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">
</head>
<body>

	<nav>
		<div class="nav-wrapper">
			<div class="container">
				<a class="brand-logo">RobotMail</a>
			</div>
		</div>
	</nav>
	<br><br>

	<?php echo $this->fetch('content'); ?>

	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js"></script>
	<?php echo $this->fetch('script'); ?>

</body>
</html>