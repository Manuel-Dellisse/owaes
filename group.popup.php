<?
	include "inc.default.php"; // should be included in EVERY file 
	$oSecurity = new security(FALSE); // not needed to be logged in  
	$oGroup = new group($_GET["id"]); 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head> 
    </head>
    <body id="profile">  
		<? echo $oGroup->html("templates/grouppopup.html"); ?> 
    </body>
</html>
