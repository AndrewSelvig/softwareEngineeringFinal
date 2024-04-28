<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup</title>
  <link rel="stylesheet" href="signup.css">
  <!-- <script src="signup.js" defer></script> -->
</head>
<body>
<header>
  <div class="logo">
    <a href="home.html">
      <img src="logo-placeholder.png" alt="Store Logo">
    </a>
  </div>
  <div class="search-bar">
    <input type="text" placeholder="Search for products">
  </div>
  <div class="account-actions">
    <a href="login.html" class="signin-button">Sign In / Create Account</a>
    <a href="help.html" class="help-button">Help</a>
    <a href="cart.html"><img src="cart-placeholder.png" alt="Cart"></a>
  </div>
</header>

<div class="signup-container">
  <h2>Create Account</h2>
  <!-- action="/submit-your-signup-form-handler" method="POST" -->
  <form action="signup.html" method="post" id="signupForm">
	<div class="form-group">
      <label for="firstname">First Name:</label>
      <input type="firstname" id="firstname" name="firstname" required>
    </div>
	<div class="form-group">
      <label for="lastname">Last Name:</label>
      <input type="lastname" id="lastname" name="lastname" required>
    </div>
	<!-- Email is replaced with Username to match the database -->
    <div class="form-group">
      <label for="email">Username:</label>
      <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
    </div>
    <div class="form-group">
      <label for="confirm-password">Confirm Password:</label>
      <input type="password" id="confirm-password" name="confirm-password" required>
    </div>
    <button type="submit">Sign Up</button>
    <p id="message" type="message" name="message">
		<?php
			function Create_User($New_Username, $New_Password, $FName, $LName) {
				$databaseFileName = 'sqlite:SE.db';
				$SQL_Connection = new PDO($databaseFileName);

				$SQL_Query = "SELECT username FROM USERS WHERE username = ?;";
				
				$Statement = $SQL_Connection->prepare($SQL_Query);
				//$Statement = $SQL_Connection->query($SQL_Query);
				$Statement->bindParam(1, $New_Username, PDO::PARAM_STR);
				if (!$Statement->execute()) {
					// Handle query execution error
					$Error_Message = "Error executing query in Create_User function at line " . __LINE__ . ": " . $Statement->error;
					$Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
					error_log(date("[Y-m-d H:i:s]") . " " . $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL, 3, "error.log");
					return false;
				}
				//$Result = $Statement->get_result();
				$Result = $Statement->fetchAll(PDO::FETCH_ASSOC);

				if (count($Result) > 0) {
					// if entered username already exists
					return "Username Already Exists";
				}
				else { // if username does not exist create a userID and create the user in the DB
					
					$UniqID = abs(crc32(uniqid()));
					$Random = mt_rand();
					$UserID = $UniqID . $Random;

					// hash the password
					$Hashed_Password = password_hash($New_Password, PASSWORD_DEFAULT);
					$SQL_Query = "INSERT INTO users VALUES (?, ?, ?, ?, ?)";
					$Statement = $SQL_Connection->prepare($SQL_Query);
					
					$Statement->bindParam(1, $UserID, PDO::PARAM_INT);
					$Statement->bindParam(2, $New_Username, PDO::PARAM_STR);
					$Statement->bindParam(3, $Hashed_Password, PDO::PARAM_STR);
					$Statement->bindParam(4, $FName, PDO::PARAM_STR);
					$Statement->bindParam(5, $LName, PDO::PARAM_STR);
					//$Statement->bind_param("sssss", $UserID, $New_Username, $Hashed_Password, $FName, $LName);
					
					if (!$Statement->execute()) {
						// Handle insertion error
						$Error_Message = "Error inserting user in Create_User function at line " . __LINE__ . ": " . $Statement->error;
						$Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
						error_log(date("[Y-m-d H:i:s]") . " " . $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL, 3, "error.log");
						return false;
					}
					// User successfully created
					return true;
				}
			}
			
			
			function Call_Create_User() {
				$username = $_POST["email"];
				$password = $_POST["password"];
				$firstName = $_POST["firstname"];
				$lastName = $_POST["lastname"];
				
				$createUserSuccess = Create_User($username, $password, $firstName, $lastName);
				$createUserFailMessage = "User " . $username . "could not be created.";
				$createUserSuccessMessage = "User " . $username . " was made successfully!";
				
				if ($createUserSuccess == true) {
					echo $createUserSuccessMessage;
				} elseif ($createUserSuccess == false) {
					echo $createUserFailMessage;
				} else {
					echo $createUserSuccess;
				}
			}
			//echo "HERE2";
			Call_Create_User();
			//echo "HERE";
		?>
	</p>
  </form>
  <p class="login-link">Already have an account? <a href="login.html">Log in here</a>.</p>
</div>

<footer>
  <p>© 2024 Electronics Store. All rights reserved.</p>
  <p>Privacy Policy | Terms of Use | Contact Us</p>
</footer>

</body>
</html>