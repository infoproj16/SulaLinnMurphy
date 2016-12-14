<?php
    include_once("config.php");
    
    // for php >=5.4
    session_start();
    if (session_status() === PHP_SESSION_ACTIVE) {
        if (!isset($_SESSION['email'])) {
            // if this variable is not set, then kick user back to login screen
            header("Location: template1.php");
        }
    } else {
        echo ("session not active");
exit;
    }
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="styles.css" type="text/css" />
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />

				<!-- Bootstrap code -->        
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


</head>
<body>
<div id="container">

    <header>
	<div class="width">
    		<h1><a href="/">Student Org</a></h1>

	    	<nav>
	
    			<ul>
        			<li class="start selected"><a href="#">Home</a></li>
        	    		<li class=""><a href="#">About</a></li>
         	   		<li><a href="">Calendar</a></li>
          	  		<li><a href="#">Events</a></li>
          	 		<li class="end"><a href="#">Contact</a></li>
					<li><a href="Requests.php">PageRequests</a></li>
					<li><a href="Logout.php">LogOut</a></li>
        		</ul>
	
    		</nav>


       	</div>
		
		

    </header>


    <div id="body" class="width">
	
		        <div class="row">
            <div class="col-xs-12">
<?php
//
// input from form
//
include_once('config.php');
include_once('dbutils.php');

if(isset($_POST['submit'])) {
    // only run if form was submitted
    
    //get data from form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];


    // database connection
    $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

    // check for required fields
    $isComplete = true;
    $errorMessage = "";
    if(!$email) {
        $errorMessage .= "Please enter a email address.";
        $isComplete = false;
    } else {
        $email = makeStringSafe($db, $email);
    }
    
    if(!$password) {
        $errorMessage .= "Please enter a password.";
        $isComplete = false;
    //} else {
      //  $Country = makeStringSafe($db, $Country);
    }
    
    //if(!$password2) {
        //$errorMessage .= "Please entter password again.";
        //$isComplete = false;
    //} else {
      //  $Birthday = makeStringSafe($db, $Birthday);
   // }
	
	//if ($password != $password2) {
		//$errorMessage .="passwords arnt the same.";
		//$isComplete = false;
	//}
    
    if (!$isComplete) {
        punt($errorMessage);
    }

    //check for duplicated
    $query = "SELECT * FROM users WHERE email='" . $email . "';";
    $result = queryDB($query, $db);
    if (nTuples($result) > 0) {
        //duplicate
        $row = nextTuple($result);
		$hashedpass = $row['hashedpass'];
		
		// compare entered password to the password on the database
		if ($hashedpass == crypt($password, $hashedpass)) {
			// password was entred correctly
			
			// start session
			if (session_start()){
				$_SESSION['email'] = $email;
				header('Location: input.php');
				exit;
			} else {
				// if we can't start a session
				punt('Unable to start session when loggin in.');
			}
		} else {
			punt('Wrong password. <a href="template1.php">Try Again</a>.');
		
		}
	
	} else {
		// email entered is not in the users table
		punt('this email is not in our system. <a href="template1.php">Try again</a>.');
	}
	
	
}

?>
            </div>
        </div>

        
        <div class="row">
            <div class="col-xs-12">

			<iframe src="https://calendar.google.com/calendar/embed?src=en.usa%23holiday%40group.v.calendar.google.com&ctz=America/Chicago" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>

       
        <aside class="sidebar big-sidebar right-sidebar">
	
            <ul>	
               <li>
                    <h4><b>Sidebar</b></h4>
                    <ul class="blocklist">
                        <li><a class="selected" href="#">Content</a></li>
                        <li><a href="#">Content</a></li>
                        <li><a href="#">Content</a></li>
                        <li><a href="#">Content</a></li>
                        <li><a href="#">Content</a></li>
                    </ul>
                </li>
                
               

		
               
                <li>
			<h4><b>News</b></h4>
			<ul class="newslist">
				<li>
					<p><span class="newslist-date">Date</span>
			                   News content</p>
				</li>

				<li>
					<p><span class="newslist-date">Date</span>
			                   News content </p>
 
				</li>
			</ul>
                </li>
              
            </ul>
		
        </aside>
    	<div class="clear"></div>
    </div>
    <footer>
            <p>&copy; SulaLin</p>
    </footer>
</div>
</body>
</html>