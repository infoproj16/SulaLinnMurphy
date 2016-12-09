<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="layout1.css" type="text/css" />
    
    <script src="http://code.jquery.com/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>    
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
      
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    
    <title>Insert page feedback</title>
</head>

<div id="body" class="width">
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
    
     if (isset($_GET['OrganizationID'])) {
        $OrganizationID = $_GET['OrganizationID'];
    } else {
        $OrganizationID =1;
    }        
    
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
    $db = connectDB($DBHost, $DBUser, $DBPassword, $DBName);
    
    // add escape characters to text    
    $pageTitle = $db->real_escape_string($pageTitle);
    $menuTitle = $db->real_escape_string($menuTitle);
    $bodyTitle = $db->real_escape_string($bodyTitle);
    $body = $db->real_escape_string($body);

    // check if url title is already in the table
    $urlCheckQuery = "select * from pages where urlTitle='" . $urlTitle . "' AND OrganizationID=" . $OrganizationID . ";";
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
    $query = "insert into pages (urlTitle, pageTitle, menuTitle, parent, bodyTitle, body, OrganizationID)
        values ('" . $urlTitle . "', '" . $pageTitle . "', '" . $menuTitle . "', " .
        $parent . ", '" . $bodyTitle . "', '" . $body . "', '" . $OrganizationID . "');";
    
    // execute sql statement
    $result = queryDB($query, $db);
    
    // check if it worked
    if ($result) {
        echo $urlTitle . " was added to the database.";
        echo "<p>";
        echo "<p>";
        echo "<a href='input.php'>Add more pages</a>";
        echo "<p>";
        echo "<a href='layout1.php'>View Site</a>";
    } else {
        echo "Something went horribly wrong when adding " . $u . ".";
        echo "<p>This was the error: " . $db->error;
        echo "<p>This was the sql statement: " . $query;
        echo "<p>Please <a href='input.php'>try again</a>";
        echo "<p>";
        echo "<a href='layout1.php'>View Site</a>";
    }
    
    $db->close();
?>

</body>

</html>
