<?php 
$viewFile = basename($view) . '.view.php';
if (!is_readable($viewFile)) {
    header("HTTP/1.1 404 Not Found");
    $viewFile = '404.view.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Ephemera</title>
    <style type="text/css">
	    html, body {margin: 0; padding: 0;}
	    body {
	    	background-image: url(visual/bgr.jpg);
	    	background-repeat: repeat;
	    	background-position: center top;
	    }
	    #page {
	    	background-image: url(visual/pane_bgr.jpg);
	    	background-repeat: repeat-y;
	    	background-position: center top;
	    	width: 586px;
	    	margin: 0 auto;
	    	background-color: #617125;
	    	color: white;
	    	font-family: sans-serif;
	    }
	    #content {padding: 0 60px; text-align: center;}
	    #content p {margin-top: 0;}
	    form {margin: 0; padding: 0;}
	    a { color: white; }
	</style> 
</head>
<body>
<div id="page">
    <img alt="Ephemera" src="visual/pane_top.jpg" height="275" width="586" />
	<div id="content">
<?php if (!empty($viewFile)) {
    require_once($viewFile);
} ?>

	</div>
	<img alt="Ephemera" src="visual/pane_bottom.jpg" width="586" height="71" style="margin-bottom: -10px;" />
</div>
</body>
</html>
