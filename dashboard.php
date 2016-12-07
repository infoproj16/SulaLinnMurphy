<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Profile Page</title>
	<link rel="stylesheet" type="text/css" href="dashboard.css" />
</head>
<?php
    include_once("dbutils.php");
    include_once("config.php");

    
    //
    // We use this bit of code to generate a list of possible parents for the data entry portion
    //
    
    // get a handle to the database
    $db = connectDB($dbHost, $dbUser, $dbPassword, $dbName);
    
    // prepare sql statement
    $query = "select id, urlTitle from pages order by parent;";
    
    // execute sql statement
    $result = queryDB($query, $db);
        
    // check if it worked
    if ($result) {
        $numberofrows = nTuples($result);
        
        // this one is for input
        $selectStatement = "<select class='form-control' name='parent'>\n";
    
        // this one is for editing    
        $editselectStatement = "<select class='form-control' name='editparent' id='editparent'>\n";
        
        for ($i=0; $i<$numberofrows; $i++) {
            $row = nextTuple($result);
            $selectStatement = $selectStatement . "\t<option value='" . $row['id'] . "'>" . $row['urlTitle'] . "</option>\n";
            $editselectStatement = $editselectStatement . "\t<option value='" . $row['id'] . "'>" . $row['urlTitle'] . "</option>\n";
        }
        
        $selectStatement = $selectStatement . "</select>\n";
        $editselectStatement = $editselectStatement . "</select>\n";
    } else {
        punt("Something went wrong when retrieving pages from the database.<p>" .
                          "This was the error: " . $db->error . "<p>", $query);
    }
?>


<body>	
	<header>
		<div class="wrapper">
			<!--<a href="#"><img src="img/Logo.jpg" alt="mysquare logo" title="mysquare - checkin and go home!" /></a>
			-->
			<span id="usernav"><a href="login1.php">Logout</a> - <a href="#">Settings</a> - <a href="templatechoice.php">Create a Website</a>- <a href="dasboard.php">My Profile<span><img src="avatar.png" width="40" height="40" /></span></a></span>
		</div>
	</header>
	
	<nav>
		<ul id="n" class="clearfix">
			<li><a href="#">Browse Student Orgs</a></li>
			<li class="sel"><a href="#">Profile</a></li>
		</ul>
	</nav>
	
	<div id="content" class="clearfix">
		<section id="left">
			<div id="userStats" class="clearfix">
				<div class="pic">
					<a href="#"><img src="avatar.png" width="150" height="150" /></a>
				</div>
				
				<div class="data">
					<h1>Patricia Lin</h1>
					<h3>Naperville, IL</h3>
					<div class="sep"></div>
				<!--	<ul class="numbers clearfix">
						<li>Reputation<strong>185</strong></li>
						<li>Checkins<strong>344</strong></li>
						<li class="nobrdr">Days Out<strong>127</strong></li>
					</ul>-->
				</div>
			</div>
			
		</section>
		
		<section id="right">
			<div class="gcontent">
				<div class="head"><h1>Student Orgs</h1></div>
				<div class="boxy">
					<p>Here are the student organizations that You are a part of!</p>
					
				<!--	<div class="badgeCount">
						<a href="#"><img src="img/foursquare-badge.png" /></a>
						<a href="#"><img src="img/foursquare-badge.png" /></a>
						<a href="#"><img src="img/foursquare-badge.png" /></a>
					</div>
					-->
				</div>
			</div>

		</section>
	</div>
</body>
</html>
