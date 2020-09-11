<?php

	session_start();

	$message = "";

	if(array_key_exists("id", $_COOKIE) AND $_COOKIE['id']){

		$_SESSION['id'] = $_COOKIE['id'];
	}

	if(array_key_exists('id', $_SESSION) AND $_SESSION['id']){

		

		include("connection.php");

		$query = "SELECT `message` FROM `users` WHERE id = '".mysqli_real_escape_string($link,$_SESSION['id'])."' LIMIT 1";

		$result = mysqli_query($link,$query);
		$row = mysqli_fetch_array($result);
		$message = $row['message'];

	}else{

		header("Location: index.php");
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="style1.css">
	<style>
		
	</style>
</head>
<body>
	<nav> 
        <div class="menu">
			<a class="logo" href="#">Secret Diary</a>
			<button class="btn btn-outline-success my-2 my-sm-0 navbar-toggler-right" type="submit"><a href='index.php?logout=1'>Log out</a></button>
		</div>    
	</nav>
	<div  id="message">
		<div class="blur">
		<textarea class="container" id="text">
			<?php echo $message;  ?>
		</textarea>
		</div>
	</div>


	<script type="text/javascript" src="jquery.min.js"></script>	
	<script src="bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript">
		
		$("#text").on("change paste keyup", function() {

 			  $.ajax({
				  method: "POST",
				  url: "updateText.php",
				  data: { content: $("#text").val() }
				});

		});

	</script>
</body>
</html>