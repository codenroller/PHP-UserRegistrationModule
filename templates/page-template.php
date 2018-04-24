<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>
    <?php
        if ( !empty($GLOBALS['TEMPLATE']['title']) )
        {
            echo $GLOBALS['TEMPLATE']['title'];
        }
    ?>
    </title>
    <!-- CSS files and libraries -->
    <?php
        if (!empty($GLOBALS['TEMPLATE']['css_libraries']))
        {
            echo $GLOBALS['TEMPLATE']['css_libraries'];
        }        
    ?>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <?php
        if (!empty($GLOBALS['TEMPLATE']['css_files']))
        {
            echo $GLOBALS['TEMPLATE']['css_files'];
        }        
    ?>
</head>
<body>
<div id="main">
	<div id="header">
	<?php
        if (!empty($GLOBALS['TEMPLATE']['header']))
        {
            echo $GLOBALS['TEMPLATE']['header'];
        }
    ?>
	</div>
	<div id="content">
    <?php
        if (!empty($GLOBALS['TEMPLATE']['content']))
        {
            echo $GLOBALS['TEMPLATE']['content'];
        }
    ?>
	</div>
	
	<div id="footer">
	<?php
        if (!empty($GLOBALS['TEMPLATE']['footer']))
        {
            echo $GLOBALS['TEMPLATE']['footer'];
        }
    ?>
	</div>
</div>

<div id="scripts">
    <!-- JS files and libraries -->
    <?php
        if (!empty($GLOBALS['TEMPLATE']['js_libraries']))
        {
            echo $GLOBALS['TEMPLATE']['js_libraries'];
        }  
        if (!empty($GLOBALS['TEMPLATE']['js_scripts']))
        {
            echo $GLOBALS['TEMPLATE']['js_scripts'];
        }  
    ?>
</div>
</body>
</html>