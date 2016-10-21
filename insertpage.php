<html>

<head>
    <title>Insert page feedback</title>
</head>

<body>

<h1>
    Insert page feedback
</h1>

<?php
    include_once("dbutils.php");
    include_once("config.php");

    // get data from fields
    $urlTitle = $_POST['urlTitle'];
    $pageTitle = $_POST['pageTitle'];
    $menuTitle = $_POST['menuTitle'];
    $bodyTitle = $_POST['bodyTitle'];
    $parent = $_POST['parent'];
    $body = $_POST['body'];
    
    
    // check that we have the data we need
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
    $urlCheckQuery = "select * from pages where urlTitle='" . $urlTitle . "'";
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
    
    // prepare sql statement
    $query = "insert into pages (urlTitle, pageTitle, menuTitle, parent, bodyTitle, body)
        values ('" . $urlTitle . "', '" . $pageTitle . "', '" . $menuTitle . "', " .
        $parent . ", '" . $bodyTitle . "', '" . $body . "');";
    
    // execute sql statement
    $result = queryDB($query, $db);
    
    // check if it worked
    if ($result) {
        echo $urlTitle . " was added to the database.";
        echo "<p>";
        echo "<a href='input.php'>Add more pages</a>";
    } else {
        echo "Something went horribly wrong when adding " . $u . ".";
        echo "<p>This was the error: " . $db->error;
        echo "<p>This was the sql statement: " . $query;
        echo "<p>Please <a href='input.php'>try again</a>";
    }
    
    $db->close();
?>

</body>

</html>