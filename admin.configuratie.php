<?php
	include "inc.default.php"; // should be included in EVERY file

	$oSecurity = new security(TRUE);

	if (!$oSecurity->admin()) stop("admin");

	$oPage->addJS("script/admin.js");
	$oPage->addCSS("style/admin.css");
?>
<!DOCTYPE html>
<html>
	<head>
		<? echo $oPage->getHeader(); ?>
	</head>
	<body id="index">
		<? echo $oPage->startTabs(); ?>
		<div class="body">
			<div class="container">
				<h1>Configuratie paneel</h1>
				<form method="POST">
					<fieldset>
						<legend>Start waardes</legend>
						<p>
							<label for="txtCredits">Credits:</label><br/>
							<input type="text" name="txtCredits" id="txtCredits" value="<? echo settings("startvalues", "credits"); ?>"/>
						</p>
						<p>
							<label for="txtPhysical">Physical:</label><br/>
							<input type="text" name="txtPhysical" id="txtPhysical" value="<? echo settings("startvalues", "physical"); ?>"/>
						</p>
						<p>
							<label for="txtSocial">Social:</label><br/>
							<input type="text" name="txtSocial" id="txtSocial" value="<? echo settings("startvalues", "social"); ?>"/>
						</p>
						<p>
							<label for="txtMental">Mental:</label><br/>
							<input type="text" name="txtMental" id="txtMental" value="<? echo settings("startvalues", "mental"); ?>"/>
						</p>
						<p>
							<label for="txtEmotional">Emotional:</label><br/>
							<input type="text" name="txtEmotional" id="txtEmotional" value="<? echo settings("startvalues", "emotional"); ?>"/>
						</p>
					</fieldset>
					<fieldset>
						<legend>Tijdzone en lokatie</legend>
						<p>
							<label for="txtTimezone">Tijdzone:</label><br/>
							<input type="text" name="txtTimezone" id="txtTimezone" value="<? echo settings("date", "timezone"); ?>"/>
						</p>
						<p>
							<label for="txtLatitude">Latitude:</label><br/>
							<input type="text" name="txtLatitude" id="txtLatitude" value="<? echo settings("geo", "latitude"); ?>"/>
						</p>
						<p>
							<label for="txtLongitude">Longitude:</label><br/>
							<input type="text" name="txtLongitude" id="txtLongitude" value="<? echo settings("geo", "longitude"); ?>"/>
						</p>
					</fieldset>
					<fieldset>
						<legend>Credits</legend>
						<p>
							<label for="txtMax">Max:</label><br/>
							<input type="text" name="txtMax" id="txtMax" value="<? echo settings("credits", "max"); ?>"/>
						</p>
						<p>
							<label for="txtEenheid">Eenheid:</label><br/>
							<input type="text" name="txtEenheid" id="txtEenheid" value="<? echo settings("credits", "name", "1"); ?>"/>
						</p>
						<p>
							<label for="txtMeervoud">Meervoud:</label><br/>
							<input type="text" name="txtMeervoud" id="txtMeervoud" value="<? echo settings("credits", "name", "x"); ?>"/>
						</p>
						<p>
							<label for="txtOverdracht">Overdracht:</label><br/>
							<input type="text" name="txtOverdracht" id="txtOverdracht" value="<? echo settings("credits", "name", "overdracht"); ?>"/>
						</p>
					</fieldset>
					<fieldset>
						<legend>Mail</legend>
						<p>
							<label for="rbSMTP">SMTP</label>
							<input type="radio" name="rbSMTP" id="rbSMTP" value="<? echo settings("mail", "smtp"); ?>" <? print((settings("mail", "smtp") == "TRUE") ? "checked='checked'" : ""); ?>/>
						</p>
						<p>
							<label for="txtHost">Host:</label><br/>
							<input type="text" name="txtHost" id="txtHost" value="<? echo settings("mail", "Host"); ?>"/>
						</p>
						<p>
							<label for="rbAuth">Authentication</label>
							<input type="radio" name="rbAuth" id="rbAuth" value="<? echo settings("mail", "SMTPAuth"); ?>" <? print((settings("mail", "SMTPAuth") == "TRUE") ? "checked='checked'" : ""); ?>/>
						</p>
						<p>
							<label for="txtSecure">Secure:</label><br/>
							<input type="text" name="txtSecure" id="txtSecure" value="<? echo settings("mail", "SMTPSecure"); ?>"/>
						</p>
						<p>
							<label for="txtPort">Port:</label><br/>
							<input type="text" name="txtPort" id="txtPort" value="<? echo settings("mail", "Port"); ?>"/>
						</p>
						<p>
							<label for="txtUsername">Username:</label><br/>
							<input type="text" name="txtUsername" id="txtUsername" value="<? echo settings("mail", "Username"); ?>"/>
						</p>
						<p>
							<label for="txtPasswd">Password:</label><br/>
							<input type="password" name="txtPasswd" id="txtPasswd" value="<? echo md5(settings("mail", "Password")); ?>"/>
						</p>
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>
