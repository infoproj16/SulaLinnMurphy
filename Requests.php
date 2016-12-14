<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="styles1.css" type="text/css" />
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
         	   		<li><a href="template1calender.php">Calendar</a></li>
          	  		<li><a href="#">Events</a></li>
          	 		<li class="end"><a href="#">Contact</a></li>
					
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
    $FirstName = $_POST['FirstName'];
	$LastName = $_POST['LastName'];

	echo("Got FirstName LastNamee");
    // check for required fields
    $isComplete = true;
    $errorMessage = "";
 	    if(!$FirstName) {
        $errorMessage .= "Please enter FirstName again.";
        $isComplete = false;
    }
	    if(!$LastName) {
        $errorMessage .= "Please enter LastName again.";
        $isComplete = false;
    }

    if (!$isComplete) {
        punt($errorMessage);
    }
	
	// database connection
	$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
	$query = "Update users set Admin = 1 where FirstName='" . $FirstName . "' AND LastName='" . $LastName . "';";
    $result = queryDB($query, $db);
	echo ("Updated");
}

?>
            </div>
        </div>

						<!-- table with content -->
				<div class="row">
					<div class="col-xs-12">
					<table class="table table-hover">
					<!-- headers -->
					<thead>
						<tr>
							<th>Email </th>
							<th>FirstName</th>
							<th>LastName</th>
							<th>AdminLevel</th>
							<th> </th>

						</tr>
					</thead>
					
					<tbody>
				<?php
					if(!$db) {  
						// database connection
						$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
					}
					
					//set up query to retrieve records from movie table
					$query = "SELECT users.email, users.FirstName, users.LastName, users.Admin FROM users ;";
					
					// run the query
					$result = queryDB($query, $db);
					
					while($row = nextTuple($result)) {
						echo "\n <tr>"; 
						echo "<td>" . $row['email'] ."</td>";
						echo "<td>" . $row['FirstName'] . "</td>";
						echo "<td>" . $row['LastName'] . "</td>";
						echo "<td>" . $row['Admin'] ."</td>";
						
						
					}
				?>
					</tbody>
				</table
					<!--Enter OrgID Form and button-->

		</div>
	</div>
		<div class="row">
					<div class="col-xs-12">
		<form action="Requests.php" method="post">
			<div class="form-group">
				<label for="FirstName">FirstName</label>
				<input type="FirstName" class="form-control" name="FirstName"/>
			</div>
		  <!---passsword1-->  
			<div class="form-group">
				<label for="LastName">LastName</label>
				<input type="LastName" class="form-control" name="LastName"/>
			</div>
				

		<!-- creating the button -->    
			<button type="submit" class="btn btn-default" name="submit">Approve</button>
		</form>
        

    	<div class="clear"></div>
    </div>	
</div>	

    <footer>
            <p>&copy; SulaLin</p>
    </footer>
</div>
</div>
</div>
</body>
</html>