<?php
    include_once("dbutils.php");
    include_once("config.php");
    // get the page we are in
    if (isset($_GET['page'])) {
        $urlTitle = $_GET['page'];
    } else {
        $urlTitle = 'home';
    }
    
    // get all the information about the page based on urlTitle
    // get a handle to the database
    $db = connectDB($dbHost, $dbUser, $dbPassword, $dbName);
    $query = "select id, pageTitle, menuTitle, parent, bodyTitle, body from pages where urlTitle='" . $urlTitle . "'";
    
    $result = queryDB($query, $db);
    if ($result) {
        $numberofrows = nTuples($result);
        
        if ($numberofrows > 0) {
            $row = nextTuple($result);
            $id = $row['id'];
            $pageTitle = $row['pageTitle'];
            $menuTitle = $row['menuTitle'];
            $parent = $row['parent'];
            $bodyTitle = $row['bodyTitle'];
            $body = $row['body'];
        } else {
        punt("Something went wrong when retrieving pages from the database.<p>" .
                          "This was the error: " . $db->error . "<p>", $query);
        }
    } else {
        punt("Something went wrong when retrieving pages from the database.<p>" .
                          "This was the error: " . $db->error . "<p>", $query);
    }   
?>

<html>
	
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="styles.css" type="text/css" />
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
</head>
<body>
	<title><?php echo $pageTitle ?></title>
<div id="container">

    <header>
	<div class="width">
    		<h1><a href="layout3.php"><?php echo $siteName; ?></a></h1>
       	</div>
    </header>

    <nav>
	<div class="width">
    		<ul>
        		<li class="start selected"><a href="layout3.php">Home</a></li>
				
				<?php   
    // query to get all child pages to the parent
    // here we assume that the home page has an id=1
    $query = "select urlTitle, menuTitle from pages where parent=1";
    
    $result = queryDB($query, $db);
    if ($result) {
        $numberofrows = nTuples($result);
        
        for($i=0; $i < $numberofrows; $i++) {
            $row = nextTuple($result);
            
            if ($row['urlTitle']==$urlTitle) {
                echo "<li class='active'>";
            } else {
                echo "<li>";
            }
            echo "<a href='layout3.php?page=" . $row['urlTitle'] . "'>" . $row['menuTitle'] . "</a></li>\n";
        }
    } else {
        punt("Something went wrong when retrieving pages from the database.<p>" .
                          "This was the error: " . $db->error . "<p>", $query);
    }
?>

        	</ul>
	</div>
    </nav>

	<div id="container">
		<div class="row">
			<div class="col-xs-10">
        
			</div>
			<div class="col-xs-2">
				<button class="button"><a href="input.php">Edit site</a></button>
			</div>
		</div>
</div>
<!-- Generate left-side menu if necessary -->
<?php
    // use this boolean to check whether we are having this menu or not
    $rightSideMenuOn = false;

    // check if this page needs to display a left-side menu
    if ($parent > 0) {
        
        if ($parent == 1) {
            // if it's a second level page, show its children on the left side menu
            $query = "select urlTitle, menuTitle from pages where parent=" . $id . " order by menuTitle";
        } else {
            // if it's a third or lower level page, show its siblings on the left side menu
            $query = "select urlTitle, menuTitle from pages where parent=" . $parent . " order by menuTitle";
        }
        
        $result = queryDB($query, $db);
        if ($result) {
            $numberofrows = nTuples($result);
            
            if ($numberofrows > 0) {
                // if this is the case, then we show it
                $rightSideMenuOn = true;
                
                $rightSideMenu = "\t<div class='col-xs-2'>\n";
                $rightSideMenu .= "\t\t<table class='table table-hover text-right'>\n";
            
                for($i=0; $i < $numberofrows; $i++) {
                    $row = nextTuple($result);
                    
                    $rightSideMenu .= "\t\t\t<tr><td><a href='layout3.php?page=" . $row['urlTitle'] . "'>". $row['menuTitle'] ."</a></td></tr>\n";
                }
                
                $rightSideMenu .= "\t\t</table>\n";
                $rightSideMenu .= "\t</div>\n";
                $rightSideMenu .= "\t<div class='col-xs-10'>\n";
            } 
        }
    }
    if (!$rightSideMenuOn) {
        $rightSideMenu = "\t<div class='col-xs-12'>\n";
    }
?>

<!-- Generate breadcrumbs if necessary -->
<?php
    $breadcrumbs = "";
    
    // if this is at least a third-level page (assuming home has parent -1 and is of id 1)
    if ($parent > 1) {
        // setup the breadcrumbs
        $breadcrumbs = "<ol class='breadcrumb'>\n";
        
        $currParent = $parent;
        $innerLinks = "";
        
        // we will iterate all the way to the home page and stop when the parent = -1, meaning we got to the home page
        while ($currParent != -1) {
            // get the parent
            $query = "select urlTitle, menuTitle, parent from pages where id=" . $currParent;
            
            $result = queryDB($query, $db);
            if ($result) {
                $numberofrows = nTuples($result);
                if ($numberofrows > 0) {
                    $row = nextTuple($result);
                    
                    // add <li> item to breadcrumbs before the previous items, because we are moving up the hierarchy
                    $innerLinks = "\t\t\t<li><a href=layout3.php?page=" . $row['urlTitle'] . "'>" . $row['menuTitle'] . "</a></li>\n" . $innerLinks;
                    
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

<?php echo $rightSideMenu; ?><!-- Generate left-side menu if necessary -->
<?php
    // use this boolean to check whether we are having this menu or not
    $rightSideMenuOn = false;

    // check if this page needs to display a left-side menu
    if ($parent > 0) {
        
        if ($parent == 1) {
            // if it's a second level page, show its children on the left side menu
            $query = "select urlTitle, menuTitle from pages where parent=" . $id . " order by menuTitle";
        } else {
            // if it's a third or lower level page, show its siblings on the left side menu
            $query = "select urlTitle, menuTitle from pages where parent=" . $parent . " order by menuTitle";
        }
        
        $result = queryDB($query, $db);
        if ($result) {
            $numberofrows = nTuples($result);
            
            if ($numberofrows > 0) {
                // if this is the case, then we show it
                $rightSideMenuOn = true;
                
                $rightSideMenu = "\t<div class='col-xs-2'>\n";
                $rightSideMenu .= "\t\t<table class='table table-hover text-right'>\n";
            
                for($i=0; $i < $numberofrows; $i++) {
                    $row = nextTuple($result);
                    
                    $rightSideMenu .= "\t\t\t<tr><td><a href='layout3.php?page=" . $row['urlTitle'] . "'>". $row['menuTitle'] ."</a></td></tr>\n";
                }
                
                $rightSideMenu .= "\t\t</table>\n";
                $rightSideMenu .= "\t</div>\n";
                $rightSideMenu .= "\t<div class='col-xs-10'>\n";
            } 
        }
    }
    if (!$rightSideMenuOn) {
        $rightSideMenu = "\t<div class='col-xs-12'>\n";
    }
?>

<!-- Generate breadcrumbs if necessary -->
<?php
    $breadcrumbs = "";
    
    // if this is at least a third-level page (assuming home has parent -1 and is of id 1)
    if ($parent > 1) {
        // setup the breadcrumbs
        $breadcrumbs = "<ol class='breadcrumb'>\n";
        
        $currParent = $parent;
        $innerLinks = "";
        
        // we will iterate all the way to the home page and stop when the parent = -1, meaning we got to the home page
        while ($currParent != -1) {
            // get the parent
            $query = "select urlTitle, menuTitle, parent from pages where id=" . $currParent;
            
            $result = queryDB($query, $db);
            if ($result) {
                $numberofrows = nTuples($result);
                if ($numberofrows > 0) {
                    $row = nextTuple($result);
                    
                    // add <li> item to breadcrumbs before the previous items, because we are moving up the hierarchy
                    $innerLinks = "\t\t\t<li><a href=layout3.php?page=" . $row['urlTitle'] . "'>" . $row['menuTitle'] . "</a></li>\n" . $innerLinks;
                    
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

<?php echo $rightSideMenu; ?>

    <div id="body" class="width">

		<section id="content">

	    <article>
			
		<?php echo $breadcrumbs; ?>
		<h2><?php echo $bodyTitle; ?></h2>
        <p>
        <?php echo $body; ?>
		
		</article>
        </section>
        
    	<div class="clear"></div>
    </div>
    <footer>
            <?php echo $footerText; ?>
    </footer>
</div>
</body>
</html>