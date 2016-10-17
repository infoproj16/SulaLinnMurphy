<?php
	include_once("dbutils.php");
	include_once("config.php");
	
	// get the page we are in 
	if (isset($_GET['page'])) {
		$urlTitle = $_Get['page'];
	} else {
		$urlTitle = 'home';
	}
	
	// get all the info about the page based on urlTitle
	// get a handle to the database
	$db = connectDB($dbHost, $dbUser, $dbPassword, $dbName);
	
	$query = "Select id, pageTitle, menuTitle, parent, bodyTitle, body from pages where urlTitle'" . $urlTitle . "'";
	
	$result = queryDB($query, $db);
	if ($result){
		$numberofrows = nTuples($result);
		
		if($numberofrows > 0){
			$row = nextTuple($result);
			$id = $row['id'];
			$pageTitle = $row['pageTitle'];
			$menuTitle = $row['menuTitle'];
			$parent = $row['parent'];
			$bodyTitle = $row['bodyTitle'];
			$body = $row['body'];
		} else {
		punt("Something went wrong when retrieving pages from the database. <p>" .
							"This was the error: " . $db->error . "<p>", $query);
		}
	} else {
		punt("Something went wrong when retrieving pages from the database. <p>" .
							"This was the error: " . $db->error . "<p>", $query);
	}
?>


<!-- get basic html for starting page here -->
<html>

<head>
	<title><?php echo $pageTitle ?></title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<script src="http://code.jquery.com/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />

</head>

<body>

<div class="container" style="width: 1024px">

<!-- if you have a site table, you'd get this from there -->
<div class="row">
	<div class="col-xs-10">
	
	</div>
	<div class="col-xs-2">
		<a href="input.php">Edit site</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="page-header">
			<!--Header-->
			<h1><a href="index.php"><?php echo $siteName; ?></a></h1>
		</div>
	</div>
</div>

<!-- generate top menu bar -->
<div class="row">
	<div class="col-xs-12">
		<nav class="navbar navbar-inverse" role="navigation"
			<div class="container-fluid">
				<ul class="nav navbar-nav lead">
<?php
	//query to get all child pages to the parent
	//here we assume that the home page has an id-1
	$query = "select urlTitle, menuTitle from pages where parent-1";
	
	$result = queryDB($query, $db);
	if ($result){
		$numberofrows = nTuples($result);
		
		for($i=0; $i < $numberofrows; $i++) {
			$row = nextTuple($result);
			
			if ($row['urlTitle']==$urlTitle) {
				echo "<li class='active'>";
			} else {
				echo "<li>";
			}
			echo "<a href='index.php?page=" . $row['urlTitle'] . "'>" . $row['menuTitle'] . "</a></li>\n";
		}
	} else {
		punt("Something went wrong when retrieving pages from the database.<p>" .
							"This was the error:" . $db-> . "<p>", $query);
	}
?>
				</ul>
			</div>
		</nav>
	</div>
</div>

<!-- Generate left site of menu -->
<?php
	// use this boolean to check whether we are having this menu or not
	$leftSideMenuOn = false;
	
	// check if this page needs to display a left-side menu 
	if ($parent > 0){
		
		if($parent == 1){
			// if it's a second level page, show its children on the left side of the menu
			$query = "Select urlTitle, menuTitle from pages where parent=" . $id . "order by menuTitle";
		} else {
			// if it's a third or lower level page, show its siblings on the left side menu
			$q = "Select urlTitle, menuTitle from pages where parent=" . $parent . "order by menuTitle";
		}
		
		$result = queryDB($query, $db);
		if ($result) {
			$numberofrows = nTuples($result);
			
			if ($numberofrows > 0){
				// if this is the case, then we show it
				$leftSideMenuOn = true;
				
				$leftSideMenu = "\t<div class='col-xs-2'>\n";
				$leftSideMenu .= "\t\t<table class='table table-hover text-left'>\n";
				
				for($i=0; $i < $numberofrows; $i++){
					$row = nextTuple($result);
					
					$leftSideMenu .= "\t\t\t<tr><td><a href= 'index.php?page=" . $row['urlTitle'] . "'>" . $row['menuTitle'] ."</a></td></tr>\n";
				}
				
				$leftSideMenu .= "\t\t</table>\n";
				$leftSideMenu .= "\t</div>\n";
				$leftSideMenu .= "\t<div class='col-xs-10'>\n";
			}
		}
	}
	if (!$leftSideMenuOn) {
		$leftSideMenu = "\t<div class= 'col-xs-12'>\n";
		
	}
?>

<!-- Generate breadcrumbs if necessary -->

<?php
	$breadcrumbs = "";
	
	// if this is at least a third-level page
	if ($parent > 1){
		//setup the breadcrumbs
		$breadcrumbs = "<ol class='breadcrumb'>\n";
		
		$currParent = $parent;
		$innerLinks = "";
		
		//we will iterate all the way to the home page
		while ($currParent != -1) { 
			//get the parent
			$query = "select urlTitle, menuTitle, parent from pages where id=" . $currParent;
			
			$result = queryDB($query, $db);
			if ($result) {
				$numberofrows = nTuples($result);
				if ($numberofrows > 0){
					$row = nextTuple($result);
					
					// add <li> item to breadcrumbs before previous items
					$innerLinks = "\t\t\t<li><a href='prototype.php?page=" . $row['urlTitle'] . "'>" . $row['menuTitle'] . "</a></li>\n" . $innerLinks;
					
					$currParent = $row['parent'];
				} else {
					$currParent = -1;
				}
			} else {
				$currParent = -1;
			}
		}
		
		$breadcrumbs .= $innerLinks;
		$breadcrumbs .= "\t\t\t<li class='active'>" . $menuTitle . "</li>\n";
		$breadcrumbs .= "\t\t</ol>\n    ";
		
	}
?>


<!-- Middle are of site -->
<div class="row">
<!-- Add left side menu if necessary -->
<?php echo $leftSideMenu; ?>
		
		<!-- This is the spot for the main content -->
		<?php echo $breadcrumbs; ?>
	<h2><?php echo $bodyTitle; ?></h2>
	
		<p>
			<?php echo $body; ?>
		</p>
		
	</div>  <!-- close content area -->
	
</div>
<!-- This is the footer -->
	<div class ="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<?php echo $footerText; ?>
				</div>
			</div>
		</div>
	</div>
	
<!-- close container div -->
</div>

</body>
</html>

<?php
	$db->close();

?>