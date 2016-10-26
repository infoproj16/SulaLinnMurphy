<html>






<?php
//
// Code to handle input from form 
//
if(isset($_POST['submit'])){
	// only run if form submitted
	
	$Email = $_POST['Email'];
	$Password = $_POST['Password'];

	if(!$Director){
		punt('Please enter Email.');
	}
	if(!$MovieTitle){
		punt('Please enter Password.');
	}


	
	$insert = 'INSERT INTO Movies(Director,MovieTitle,Year,url)VALUES ("'. $Director .'","'. $MovieTitle .'" ,"'. $Year .'" , "'. $url .'");';
	//connect to datebase
	$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
	
	// run insert statement
	$result = queryDB($insert, $db);
	
	//echo("Successfully entered")
	
}

?>


<form action="action_page.php">
  <div class="imgcontainer">
    <img src="Earth.jpg" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

    <button type="submit">Login</button>
  </div>

  <div class="container" style="background-color:#f1f1f1">

  </div>
</form>
</html>