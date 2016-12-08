<?php
    include_once("dbutils.php");
    include_once("config.php");
    // get the page we are in
    if (isset($_GET['page'])) {
        $urlTitle = $_GET['page'];
    } else {
        $urlTitle = 'home';
    }
	
    if (isset($_GET['organizationID'])) {
        $OrganizationID = $_GET['organizationID'];
    } else {
        $OrganizationID = 1;
    }    
    // get all the information about the page based on urlTitle
    // get a handle to the database
    $db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);
    $query = "select * from pages where urlTitle='" . $urlTitle . "' AND OrganizationID=" . $OrganizationID . ";";
    
    $result = queryDB($query, $db);
    if ($result) {
        $numberofrows = nTuples($result);
        
        if ($numberofrows > 0) {
            $row = nextTuple($result);
            $id = $row['PageID'];
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

<!-- get basic html for starting the page here -->
<html>

<div id="container">
		<div class="row">
			<div class="col-xs-10">
        
			</div>
			<div class="col-xs-2">
				<button class="button"><a href="input.php?OrganizationID=<?php echo $OrganizationID ?>">Edit site</a></button>
			</div>
		</div>
</div>
<header>
    <title><?php echo $pageTitle ?></title>
	

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="layout1.css" type="text/css" />
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />


<div class="row">
    <div class="col-xs-12">
        <div class="page-header">
            <!-- Header -->
            <h1><a href="layout1.php"><?php echo $siteName; ?></a></h1>
        </div>
    </div>
</div>

	    	<nav>
	
    			<ul>
        			<li class="start selected"><a href="#">Home</a></li>
					
<?php   
    // query to get all child pages to the parent
    // here we assume that the home page has an id=1
    $query = "select urlTitle, menuTitle from pages where parent=1 AND OrganizationID=" . $OrganizationID . ";";
    
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
            echo "<a href='layout1.php?page=" . $row['urlTitle'] . "&organizationID=" . $OrganizationID .  "'>" . $row['menuTitle'] . "</a></li>\n";
        }
    } else {
        punt("Something went wrong when retrieving pages from the database.<p>" .
                          "This was the error: " . $db->error . "<p>", $query);
    }
?>
                </ul>

        </nav>
</header>

<!-- Generate left-side menu if necessary -->
<?php
    // use this boolean to check whether we are having this menu or not
    $rightSideMenuOn = false;

    // check if this page needs to display a left-side menu
    if ($parent > 0) {
        
        if ($parent == 1) {
            // if it's a second level page, show its children on the left side menu
            $query = "select urlTitle, menuTitle from pages where parent=" . $id . " AND OrganizationID=" . $OrganizationID . " order by menuTitle;";
        } else {
            // if it's a third or lower level page, show its siblings on the left side menu
            $query = "select urlTitle, menuTitle from pages where parent=" . $parent . " AND OrganizationID=" . $OrganizationID . " order by menuTitle";
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
                    
                    $rightSideMenu .= "\t\t\t<tr><td><a href='layout1.php?page=" . $row['urlTitle'] . "&organizationID=" . $OrganizationID . "'>". $row['menuTitle'] ."</a></td></tr>\n";
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
            $query = "select urlTitle, menuTitle, parent and organizationID from pages where pageID=" . $currParent . " AND OrganizationID=" . $OrganizationID . ";";
            
            $result = queryDB($query, $db);
            if ($result) {
                $numberofrows = nTuples($result);
                if ($numberofrows > 0) {
                    $row = nextTuple($result);
                    
                    // add <li> item to breadcrumbs before the previous items, because we are moving up the hierarchy
                    $innerLinks = "\t\t\t<li><a href=layout1.php?page=". $row['urlTitle'] . "&organizationID=" . $OrganizationID . "'>" . $row['menuTitle'] . "</a></li>\n" . $innerLinks;
                    
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

    <div id="body" class="

		
		<section id="content" class="two-column with-right-sidebar">

	    <article>			
		

        <!-- This is the spot for the main content -->
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
