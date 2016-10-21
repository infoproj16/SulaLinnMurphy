<?php
    include_once("dbutils.php");
    include_once("config.php");

     // get data from fields
    $id = $_POST['id'];
    $urlTitle = $_POST['urlTitle'];
    $pageTitle = $_POST['pageTitle'];
    $menuTitle = $_POST['menuTitle'];
    $bodyTitle = $_POST['bodyTitle'];
    $parent = $_POST['parent'];
    $body = $_POST['body'];
    
    
    // check that we have the data we need
    if (!$id) {
        echo "Hey, you didn't add an id. Please <a href='input.php'>try again</a>";
        exit;
    }

    if (!$urlTitle) {
        echo "Hey, you didn't add a url title. Please <a href='input.php'>try again</a>";
        exit;
    }
    
    if (!$menuTitle) {
        echo "Hey, you didn't add a menu title. Please <a href='input.php'>try again</a>";
        exit;
    }
    
    if (!$bodyTitle) {
        echo "Hey, you didn't add a body title. Please <a href='input.php'>try again</a>";
        exit;
    }
    
    if (!$pageTitle) {
        echo "Hey, you didn't add a page title. Please <a href='input.php'>try again</a>";
        exit;
    }
    
    if (!$parent) {
        echo "Hey, you didn't add a parent. Please <a href='input.php'>try again</a>";
        exit;
    }
    
    if ($parent == $id) {
	echo "Hey, you can't be your own parent";
	exit;
    }
    
        
    if (!$body) {
        echo "Hey, you didn't add a body. Please <a href='input.php'>try again</a>";
        exit;
    }
    
    // get a handle to the database
    $db = connectDB($dbHost, $dbUser, $dbPassword, $dbName);

    // add escape characters to text    
    $pageTitle = $db->real_escape_string($pageTitle);
    $menuTitle = $db->real_escape_string($menuTitle);
    $bodyTitle = $db->real_escape_string($bodyTitle);
    $body = $db->real_escape_string($body);

    
    // check if url title is already in the table
    $urlCheckQuery = "select * from pages where urlTitle='" . $urlTitle . "' AND id!=". $id;
    $result = queryDB($urlCheckQuery, $db);
    if ($result) {
        $numberofrows = nTuples($result);
        if ($numberofrows > 0) {
            punt("The url title " . $urlTitle . " already exists in the database." .
                              "<p>Please <a href='input.php'>try again</a>");
        }
    } else {
        punt("Could not check if email was already in table.<p>" . $db->error, $emailCheckQuery);
    }
    
    $updateQuery = "UPDATE pages SET urlTitle='" . $urlTitle
	. "', pageTitle='" . $pageTitle
        . "', menuTitle='" . $menuTitle 
	. "', bodyTitle='" . $bodyTitle 
	. "', body='" . $body
	. "', parent=" . $parent
	. " WHERE id = " . $id . ";";
    
    $result = queryDB($updateQuery, $db);
    
    if ($result) {
        echo "Page edited";
    } else {
        echo "soemthing bad happened with the query. " . $db->error . " This was the query: " . $updateQuery;    
    }
    
?>