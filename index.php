
<?php

	session_start();

	$error = "";
	$success = "";

	if(array_key_exists("logout", $_GET)){

		unset($_SESSION);
		session_destroy();
		setcookie("id", "", time() - 60*60*24*356 );
		setcookie("id", "", time() - 60*60*24*356 );
		$_COOKIE["id"] = "";

	}else if((array_key_exists("id", $_SESSION)  AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'] )){

		header("Location: loggedinpage.php");

	}

	

	include("connection.php");

	if(array_key_exists('email', $_POST) OR array_key_exists('password', $_POST)){

		if($_POST['email'] == ''){

			$error = "<p>Enter Your Email</p>";

		}else if($_POST['password'] == ''){

			$error = "<p>Enter Your Password</p>";

		}else{

			if($_POST['sign'] == '1'){

				//code for For sign up part

				if($_POST['password'] != $_POST['ConfirmPassword']){

					$error = "<p> Password does't match</p>";
				}else{

					$query = "SELECT `id` FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'LIMIT 1";

					$result = mysqli_query($link, $query);


					if(mysqli_num_rows($result) > 0){
				
						$error = "<p>Email already registered..</p>";

					}else{


						$query = "INSERT INTO `users`(`email`, `password`) VALUES ('".mysqli_escape_string($link, $_POST['email'])."','".mysqli_escape_string($link, $_POST['password'])."')";

						if(mysqli_query($link, $query)){
					
							$query = "UPDATE `users` SET password = '". md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = '".mysqli_insert_id($link)."'LIMIT 1";

							if(mysqli_query($link, $query)){

								$success = "<p>Sign up successful please log in your account.</p>";
							}


							$_SESSION['id'] = mysqli_insert_id($link);
							


						}else{

							 $error = "<p> Could not sign you up- Please try again ";
						}
					}
				}		

			}else if($_POST['sign'] == '0'){

					//code for For login part

				$query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'LIMIT 1";

				$result = mysqli_query($link, $query); 
				$row = mysqli_fetch_array($result);

				if(isset($row)){

					$codedPassword = md5(md5($row['id']).$_POST['password']);

					if($codedPassword == $row['password']){

						$_SESSION['id'] = $row['id'];

							if(isset($_POST['stayloggedin']) AND $_POST['stayloggedin'] == '1'){

								setcookie('id', mysqli_insert_id($link), time() + 60*60*24*365 );

							}

							header("Location: loggedinpage.php");
					}else{

						 $error = "<p> Invalid Password or Email id..</p> ";
					}
				}else{

						 $error = "<p> Invalid Password or Email id..</p> ";
				}

			}else{

								$error = "<p>Email does't exist..please sign up</p>";
							}

				

			}		


	}


?>



<!DOCTYPE html>
<html>
<head>
	<title></title>

		<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="style2.css">
		
		<script type="text/javascript" src="jquery.min.js"></script>	
	<style>
		
	</style>
</head>
<body>
<div id="error" >
	
			<?php 
				if($error != ""){
					echo "<div id='error' class='alert alert-danger' role='alert'><strong>".$error."</strong></div>" ;
				}else if($success !=""){
					echo "<div id='error' class='alert alert-success' role='alert'><strong>".$success."</strong></div>" ;	
				}
			?>		

		</div>
<section class="user">
        <div class="user_options-container">
          <div class="user_options-text">
            <div class="user_options-unregistered">
              <h2 class="user_unregistered-title">Don't have an account?</h2>
              <p class="user_unregistered-text">Want to store your thought permanently and securely?</p>
              <button class="user_unregistered-signup" id="signup-button">Sign up</button>
            </div>
    
            <div class="user_options-registered">
              <h2 class="user_registered-title">Have an account?</h2>
              <p class="user_registered-text">Store your best life moments and secret in your online diary.</p>
              <button class="user_registered-login" id="login-button">Login</button>
            </div>
          </div>
    
          <div class="user_options-forms" id="user_options-forms">
            <div class="user_forms-login">
              <h2 class="forms_title">Login</h2>
              <form class="forms_form" method="post">
                
                  <div class="forms_field">
                    <input type="email" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" class="forms_field-input" required autofocus />
                  </div>
                  <div class="forms_field">
                    <input type="password" name="password" id="exampleInputPassword1" minlength="6" placeholder="Password" class="forms_field-input" required />
                  </div>
                
                <div class="forms_buttons">
					<div class="form-check">
					    <label class="form-check-label">
					      <input type="checkbox" name="stayloggedin" value="1" class="form-check-input">
					      Remember me
					    </label>
					  </div>
				  
				  <input type="hidden" name="sign" value="0" >
					  <button type="submit" name="submit" class="forms_buttons-action">Log In</button>
                </div>
              </form>
            </div>
            <div class="user_forms-signup">
              <h2 class="forms_title">Sign Up</h2>
              <form class="forms_form" method="post">
                
                  <div class="forms_field">
                    <input type="email" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" class="forms_field-input" required />
                  </div>
                  <div class="forms_field">
                    <input type="password" name="password" id="exampleInputPassword1" minlength="6" placeholder="Password" class="forms_field-input" required />
                  </div>
                  <div class="forms_field">
                    <input type="password" name="ConfirmPassword" id="exampleInputPassword1" placeholder="Confirm Password" class="forms_field-input" required />
                  </div>
                
                <div class="forms_buttons">
				  
				  <input type="hidden" name="sign" value="1" >
					  <button type="submit" name="submit" class="forms_buttons-action">Sign Up</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    <script>
        const signupButton = document.getElementById('signup-button'),
    loginButton = document.getElementById('login-button'),
    userForms = document.getElementById('user_options-forms')


signupButton.addEventListener('click', () => {
  userForms.classList.remove('bounceRight')
  userForms.classList.add('bounceLeft')
}, false);

loginButton.addEventListener('click', () => {
  userForms.classList.remove('bounceLeft')
  userForms.classList.add('bounceRight')
}, false);
    </script>

</body>
</html>