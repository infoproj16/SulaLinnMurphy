<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Choose Your Template</title>
<link rel="stylesheet" href="styles.css" type="text/css" />
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
</head>
<body>
<div id="container">

    <header>
	<div class="width">
    		<h1>Pick a Template</h1>
	</div>

    </header>


    <div id="body" class="width">

<form>
    <div class="layout-selector">
        <input id="layout1" type="radio" name="layout1" value="layout1" onclick="window.location='/~pjlin/project1/layout1/layout1.php';"/>
        <label class="layout layout1" for="layout1"></label>
        <input id="layout2" type="radio" name="layout2" value="layout2" onclick="window.location='/~pjlin/project1/layout2/layout2.php';"/>
        <label class="layout layout2" for="layout2"></label>
		<input id="layout3" type="radio" name="layout3" value="layout3" onclick="window.location='/~pjlin/project1/layout3/layout3.php';"/>
        <label class="layout layout3" for="layout3"></label>
	</div>
</form>
		
		<div class="clear"></div>
	</div>
    <footer>
		<?php echo $footerText; ?>
	</footer>
</div>
</body>
</html>