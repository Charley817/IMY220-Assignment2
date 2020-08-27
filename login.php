<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Charlotte Jacobs">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					$userId = $row['user_id'];
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";

					echo 	"<form method='POST' action='login.php' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='hidden' name='loginEmail' value=" .  $row['email'] . " />
									<input type='hidden' name='loginPass' value=" .  $row['password'] . " />
									<input type='file' class='form-control' name='picToUpload[]' id='picToUpload' multiple='multiple'/><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>";
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}



				$target_dir = "gallery/";
				if(isset($_FILES["picToUpload"]["name"])){
					$images = $_FILES['picToUpload'];
					$numFiles = count($images["name"]);
					for ($i = 0; $i < $numFiles; $i++){
						if (($images["type"][$i] == "image/jpg"  || $images["type"][$i]=="image/jpeg")
							&& $images["size"][$i] < 1024000)
							{
								if($images["error"][$i] > 0){
									echo "Error: " . $images["error"][$i] . "<br/>";
								}
								else {
									move_uploaded_file($images["tmp_name"][$i],
									$target_dir . $images["name"][$i]);

									$imageName = $images["name"][$i];

									$queryImg = "INSERT INTO tbgallery (user_id, filename)
										VALUES ('$userId', '$imageName')";
										$mysqli->query($queryImg);

									}
								} else {
									echo $images["name"][$i] . " is too big or not correct format <br><br>";
								}

							}

							}
						}

			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}


			if ($userId){
				$query2 = "SELECT DISTINCT filename FROM tbgallery WHERE user_id = '$userId'";
				$res2 = $mysqli->query($query2);
				echo "
					<h3>Image Gallery</h3>
					<div class='row imageGallery'>";

				while($row2 = mysqli_fetch_assoc($res2)){
					echo "
								<div class='col-3' style='background-image: url(gallery/" . $row2['filename'] . ")'> </div>
					";
				}
				echo "</div>";

			}


		?>


	</div>
</body>
</html>
