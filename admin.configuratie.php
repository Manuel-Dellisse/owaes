<?php
	include "inc.default.php"; // should be included in EVERY file

	$oSecurity = new security(TRUE);

	if (!$oSecurity->admin()) stop("admin");

	$oPage->addJS("script/admin.js");
	$oPage->addCSS("style/admin.css");

	function prepareAndExecuteStmt($key, $val, $dbPDO) {
		$query = "UPDATE `tblConfig` SET `value` = ? WHERE `key` LIKE ?";

		$stmt = $dbPDO->prepare($query);
		$stmt->bindParam(1, $val);
		$stmt->bindParam(2, $key);
		$stmt->execute();
	}

	if (isset($_POST["btnOpslaan"])) {
		$dbPDO = new PDO("mysql:host=" . $arConfig["database"]["host"] . ";dbname=" . $arConfig["database"]["name"], $arConfig["database"]["user"], $arConfig["database"]["password"]);

		prepareAndExecuteStmt("startvalues.credits", $_POST["txtCredits"], $dbPDO);
		prepareAndExecuteStmt("startvalues.physical", $_POST["txtPhysical"], $dbPDO);
		prepareAndExecuteStmt("startvalues.social", $_POST["txtSocial"], $dbPDO);
		prepareAndExecuteStmt("startvalues.mental", $_POST["txtMental"], $dbPDO);
		prepareAndExecuteStmt("startvalues.emotional", $_POST["txtEmotional"], $dbPDO);

		$test = "FALSE";

		if (isset($_POST["chkVisibility"])) {
			$test = "TRUE";
		}

		prepareAndExecuteStmt("startvalues.visibility", $test, $dbPDO);
		prepareAndExecuteStmt("date.timezone", $_POST["txtTimezone"], $dbPDO);
		prepareAndExecuteStmt("geo.latitude", $_POST["txtLatitude"], $dbPDO);
		prepareAndExecuteStmt("geo.longitude", $_POST["txtLongitude"], $dbPDO);
		prepareAndExecuteStmt("credits.max", $_POST["txtMax"], $dbPDO);
		prepareAndExecuteStmt("credits.name.1", $_POST["txtEenheid"], $dbPDO);
		prepareAndExecuteStmt("credits.name.x", $_POST["txtMeervoud"], $dbPDO);
		prepareAndExecuteStmt("credits.name.overdracht", $_POST["txtOverdracht"], $dbPDO);

		$test = "FALSE";

		if (isset($_POST["chkSMTP"])) {
			$test = "TRUE";
		}

		prepareAndExecuteStmt("mail.smtp", $test, $dbPDO);
		prepareAndExecuteStmt("mail.Host", $_POST["txtHost"], $dbPDO);

		$test = "FALSE";

		if (isset($_POST["chkAuth"])) {
			$test = "TRUE";
		}

		prepareAndExecuteStmt("mail.SMTPAuth", $test, $dbPDO);
		prepareAndExecuteStmt("mail.SMTPSecure", $_POST["txtSecure"], $dbPDO);
		prepareAndExecuteStmt("mail.Port", $_POST["txtPort"], $dbPDO);
		prepareAndExecuteStmt("mail.Username", $_POST["txtUsername"], $dbPDO);

		$pwd = null;

		if ($_POST["txtPasswd"] != "" || md5($_POST["txtPasswd"]) != md5("") || $_POST["txtPasswd"] != md5("")) {
			$query = "SELECT `value` FROM `tblConfig` WHERE `key` LIKE 'mail.Password'";
			$result = $dbPDO->query($query);
			$pwd = $_POST["txtPasswd"];

			foreach ($result as $p) {
				if ($pwd != $p["value"]) {
					$pwd = md5($pwd);
				}
			}
		}

		prepareAndExecuteStmt("mail.Password", $pwd, $dbPDO);

		redirect(filename());
	}
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
				<div class="row">
					<? echo $oSecurity->me()->html("user.html"); ?>
				</div>
				<div class="main market admin">
					<? include "admin.menu.xml"; ?>
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
							<p>
								<label for="chkVisibility">Visibility</label>
								<input type="checkbox" name="chkVisibility" id="chkVisibility" value="visibility" <?  print((settings("startvalues", "visibility") == "TRUE") ? "checked='checked'" : ""); ?>/>
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
								<label for="chkSMTP">SMTP</label>
								<input type="checkbox" name="chkSMTP" id="chkSMTP" value="smtp" <? print((settings("mail", "smtp") == "TRUE") ? "checked='checked'" : ""); ?>/>
							</p>
							<p>
								<label for="txtHost">Host:</label><br/>
								<input type="text" name="txtHost" id="txtHost" value="<? echo settings("mail", "Host"); ?>"/>
							</p>
							<p>
								<label for="chkAuth">Authentication</label>
								<input type="checkbox" name="chkAuth" id="chkAuth" value="SMTPAuth" <? print((settings("mail", "SMTPAuth") == "TRUE") ? "checked='checked'" : ""); ?>/>
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
								<input type="password" name="txtPasswd" id="txtPasswd" value="<? echo settings("mail", "Password"); ?>"/>
							</p>
						</fieldset>
						<input type="submit" name="btnOpslaan" value="Opslaan" class="btn btn-default btn-save"/>
					</form>
				</div>
			</div>
			<? echo $oPage->endTabs(); ?>
		</div>
	<div class="footer">
        	<? echo $oPage->footer(); ?>
        </div>
	<script>
		function enableDisableFields(state, fields) {
			var lenFields = fields.length;

			for (var i = 0; i < lenFields; i++) {
				fields[i].disabled = !state;
			}
		}

		var chkSMTP = document.getElementById("chkSMTP");
		var txtHost = document.getElementById("txtHost");
		var chkAuth = document.getElementById("chkAuth");
		var txtSecure = document.getElementById("txtSecure");
		var txtPort = document.getElementById("txtPort");
		var txtUsername = document.getElementById("txtUsername");
		var txtPasswd = document.getElementById("txtPasswd");

		var fields = [txtHost, chkAuth, txtSecure,
			txtPort, txtUsername, txtPasswd];

		enableDisableFields(chkSMTP.checked, fields);

		chkSMTP.addEventListener("click", function() {
			enableDisableFields(chkSMTP.checked, fields);
		});
	</script>
	</body>
</html>
