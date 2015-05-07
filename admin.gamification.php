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

	function createDateTime($mkTime) {
		$dt = explode(",", $mkTime);
		$t = explode("(", $dt[0]);
		$dt[0] = $t[1];
		$dt[5] = substr($dt[5], 0, 5);

		$date = date("d/m/Y", mktime(intval($dt[0]), intval($dt[1]), intval($dt[2]), intval($dt[3]), intval($dt[4]), intval($dt[5])));

		$time = date("H:m:s", mktime(intval($dt[0]), intval($dt[1]), intval($dt[2]), intval($dt[3]), intval($dt[4]), intval($dt[5])));

		$dateTime = array(
			"date" => $date,
			"time" => $time
		);

		return $dateTime;
	}

	function makeTime($dt) {
		$date = explode("/", $dt);
		$day = intval($date[0]);
		$month = intval($date[1]);
		$year = intval(substr($date[2], 0, 4));

		$time = explode(":", $date[2]);
		$hour = intval(substr($time[0], 5, strlen($time[0]) - 4));
		$minute = intval($time[1]);
		$second = intval($time[2]);

		$mkTime = "mktime(" . $hour . "," . $minute . "," . $second . "," . $month . "," . $day . "," . $year .  ")";

		return $mkTime;
	}

	if (isset($_POST["btnOpslaan"])) {
		/* Start waarden */
		if (isset($_POST["txtPhysical"])) prepareAndExecuteStmt("startvalues.physical", $_POST["txtPhysical"], $dbPDO);
		if (isset($_POST["txtSocial"])) prepareAndExecuteStmt("startvalues.social", $_POST["txtSocial"], $dbPDO);
		if (isset($_POST["txtMental"])) prepareAndExecuteStmt("startvalues.mental", $_POST["txtMental"], $dbPDO);
		if (isset($_POST["txtEmotional"])) prepareAndExecuteStmt("startvalues.emotional", $_POST["txtEmotional"], $dbPDO);

		/* ------------- */

		/* Levels */
		$i = 0;

		foreach ($arConfig["levels"] as $level) {
			if (isset($_POST["txtLevel" . $i . "Threshold"])) prepareAndExecuteStmt("levels." . $i . ".threshold", $_POST["txtLevel" . $i . "Threshold"], $dbPDO);
			if (isset($_POST["txtLevel" . $i . "Multiplier"])) prepareAndExecuteStmt("levels." . $i . ".multiplier", $_POST["txtLevel" . $i . "Multiplier"], $dbPDO);

			$i++;
		}

		/* ------------- */

		/* Warnings */
		$i = 1;

		foreach ($arConfig["warnings"] as $warning) {
			if (isset($_POST["txtW" . $i . "Schenkingen"])) prepareAndExecuteStmt("warnings." . $i . ".schenkingen", $_POST["txtW" . $i . "Schenkingen"], $dbPDO);
			if (isset($_POST["txtW" . $i . "Trans"])) prepareAndExecuteStmt("warnings." . $i . ".transactiediversiteit", $_POST["txtW" . $i . "Trans"], $dbPDO);
			if (isset($_POST["txtW" . $i . "Credits"])) prepareAndExecuteStmt("warnings." . $i . ".credits", $_POST["txtW" . $i . "Credits"], $dbPDO);
			if (isset($_POST["txtW" . $i . "Waardering"])) prepareAndExecuteStmt("warnings." . $i . ".waardering", $_POST["txtW" . $i . "Waardering"], $dbPDO);
			if (isset($_POST["txtW" . $i . "Physical"])) prepareAndExecuteStmt("warnings." . $i . ".physical", $_POST["txtW" . $i . "Physical"], $dbPDO);
			if (isset($_POST["txtW" . $i . "Social"])) prepareAndExecuteStmt("warnings." . $i . ".social", $_POST["txtW" . $i . "Social"], $dbPDO);
			if (isset($_POST["txtW" . $i . "Mental"])) prepareAndExecuteStmt("warnings." . $i . ".mental", $_POST["txtW" . $i . "Mental"], $dbPDO);
			if (isset($_POST["txtW" . $i . "Emotional"])) prepareAndExecuteStmt("warnings." . $i . ".emotional", $_POST["txtW" . $i . "Emotional"], $dbPDO);
			if (isset($_POST["txtW" . $i . "IndiSom"])) prepareAndExecuteStmt("warnings." . $i . ".indicatorsom", $_POST["txtW" . $i . "IndiSom"], $dbPDO);

			$i++;
		}

		/* ------------- */

		/* Crons */
		if (isset($_POST["txtCronsIndicators"])) prepareAndExecuteStmt("crons.indicators", $_POST["txtCronsIndicators"], $dbPDO);
		if (isset($_POST["txtHTWFD"])) prepareAndExecuteStmt("crons.hourstoworkfordelay", $_POST["txtHTWFD"], $dbPDO);
		if (isset($_POST["txtX"])) prepareAndExecuteStmt("crons.x", $_POST["txtX"], $dbPDO);

		/* ------------- */

		/* Datum */
		if (isset($_POST["txtDateSpeed"])) prepareAndExecuteStmt("date.speed", $_POST["txtDateSpeed"], $dbPDO);
		if (isset($_POST["dtStart"])) prepareAndExecuteStmt("date.start", makeTime($_POST["dtStart"]), $dbPDO);

		/* ------------- */

		/* Indicatoren */
		if (isset($_POST["txtIndicatorMultiplier"])) prepareAndExecuteStmt("indicatoren.multiplier", $_POST["txtIndicatorMultiplier"], $dbPDO);
		if (isset($_POST["txtOwaesAdd"])) prepareAndExecuteStmt("indicatoren.owaesadd", $_POST["txtOwaesAdd"], $dbPDO);

		/* ------------- */

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
					<h1>Spel configuraties</h1>
					<form id="frmGameConfig" method="POST">
						<fieldset>
							<legend>Start waarden</legend>
							<p>
								<label for="txtPhysical">Physical:</label><br/>
								<input type="number" name="txtPhysical" id="txtPhysical" value="<? echo settings("startvalues", "physical"); ?>"/>
							</p>
							<p>
								<label for="txtSocial">Social:</label><br/>
								<input type="number" name="txtSocial" id="txtSocial" value="<? echo settings("startvalues", "social"); ?>"/>
							</p>
							<p>
								<label for="txtMental">Mental:</label><br/>
								<input type="number" name="txtMental" id="txtMental" value="<? echo settings("startvalues", "mental"); ?>"/>
							</p>
							<p>
								<label for="txtEmotional">Emotional:</label><br/>
								<input type="number" name="txtEmotional" id="txtEmotional" value="<? echo settings("startvalues", "emotional"); ?>"/>
							</p>
						</fieldset>
						<fieldset>
							<legend>Levels</legend>
							<?
								$i = 0;

								foreach ($arConfig["levels"] as $level) {
									?>
									<h2>Level <? echo $i; ?></h2>
									<p>
										<label for="txtLevel<? print($i . "Threshold"); ?>">Threshold</label><br/>
										<input style="width: 75px;" type="number" name="txtLevel<?  print($i . "Threshold"); ?>" id="txtLevel<?  print($i . "Threshold"); ?>" value="<? echo $level["threshold"]; ?>"/>
									</p>
									<p>
										<label for="txtLevel<? print($i . "Multiplier"); ?>">Vermenigvuldigingsfactor</label><br/>
										<input style="width: 75px;" type="number" name="txtLevel<?  print($i . "Multiplier"); ?>" id="txtLevel<?  print($i . "Multiplier"); ?>" value="<? echo $level["multiplier"]; ?>"/>
									</p>
									<?
									$i++;
								}
							?>
						</fieldset>
						<fieldset>
							<legend>Warnings</legend>
							<?
								$i = 1;

								foreach ($arConfig["warnings"] as $warning) {
									?>
									<h2>Warning <? echo $i; ?></h2>
									<p>
										<label for="txtW<? print($i . "Schenkingen"); ?>">Schenkingen</label><br/>
										<input style="width: 75px;" type="number" name="txtW<? print($i . "Schenkingen"); ?>" id="txtW<? print($i . "Schenkingen"); ?>" value="<? echo $warning["schenkingen"]; ?>"/>
									</p>
									<p>
										<label for="txtW<? print($i . "Trans"); ?>">Transactiediversiteit</label><br/>
										<input style="width: 75px;" type="number" name="txtW<? print($i . "Trans"); ?>" id="txtW<? print($i . "Trans"); ?>" value="<? echo $warning["transactiediversiteit"]; ?>"/>
									</p>
									<p>
										<label for="txtW<? print($i . "Credits"); ?>">Credits</label><br/>
										<input style="width: 75px;" type="number" name="txtW<? print($i . "Credits"); ?>" id="txtW<? print($i . "Credits"); ?>" value="<? echo $warning["credits"]; ?>"/>
									</p>
									<p>
										<label for="txtW<? print($i . "Waardering"); ?>">Waardering</label><br/>
										<input style="width: 75px;" type="number" name="txtW<? print($i . "Waardering"); ?>" id="txtW<? print($i . "Waardering"); ?>" value="<? echo $warning["waardering"]; ?>"/>
									</p>
									<p>
										<label for="txtW<? print($i . "Physical"); ?>">Physical</label><br/>
										<input style="width: 75px;" type="number" name="txtW<? print($i . "Physical"); ?>" id="txtW<? print($i . "Physical"); ?>" value="<? echo $warning["physical"]; ?>"/>
									</p>
									<p>
										<label for="txtW<? print($i . "Social"); ?>">Social</label><br/>
										<input style="width: 75px;" type="number" name="txtW<? print($i . "Social"); ?>" id="txtW<? print($i . "Social"); ?>" value="<? echo $warning["social"]; ?>"/>
									</p>
									<p>
										<label for="txtW<? print($i . "Mental"); ?>">Mental</label><br/>
										<input style="width: 75px;" type="number" name="txtW<? print($i . "Mental"); ?>" id="txtW<? print($i . "Mental"); ?>" value="<? echo $warning["mental"]; ?>"/>
									</p>
									<p>
										<label for="txtW<? print($i . "Emotional"); ?>">Emotional</label><br/>
										<input style="width: 75px;" type="number" name="txtW<? print($i . "Emotional"); ?>" id="txtW<? print($i . "Emotional"); ?>" value="<? echo $warning["emotional"]; ?>"/>
									</p>
									<p>
										<label for="txtW<? print($i . "IndiSom"); ?>">Indicatorsom</label><br/>
										<input style="width: 75px;" type="number" name="txtW<? print($i . "IndiSom"); ?>" id="txtW<? print($i . "IndiSom"); ?>" value="<? echo $warning["indicatorsom"]; ?>"/>
									</p>
									<?
									$i++;
								}
							?>
						</fieldset>
						<fieldset>
							<legend>Taken planner</legend>
							<p>
								<label for="txtCronsIndicators">Indicatoren verlagen:</label><br/>
								<input type="text" name="txtCronsIndicators" id="txtCronsIndicators" value="<? echo settings("crons", "indicators"); ?>"/>
							</p>
							<p>
								<label for="txtHTWFD">Aantal uren werken voor delay:</label><br/>
								<input type="number" name="txtHTWFD" id="txtHTWFD" value="<? echo settings("crons", "hourstoworkfordelay"); ?>"/>
							</p>
							<p>
								<label for="txtX">x</label><br/>
								<input type="text" name="txtX" id="txtX" value="<? echo settings("crons", "x"); ?>"/>
							</p>
						</fieldset>
						<fieldset>
							<legend>Datum</legend>
							<p>
								<label for="txtDateSpeed">Snelheid:</label><br/>
								<input type="number" name="txtDateSpeed" id="txtDateSpeed" value="<? echo settings("date", "speed"); ?>"/>
							</p>
							<p>
								<label for="dStart">Start:</label><br/>
								<input type="date" name="dStart" id="dStart" value="<? echo createDateTime(settings("date", "start"))["date"]; ?>"/>
								<input type="time" name="tStart" id="tStart" value="<? echo createDateTime(settings("date", "start"))["time"]; ?>"/>
							</p>
						</fieldset>
						<fieldset>
							<legend>Indicatoren</legend>
							<p>
								<label for="txtIndicatorMultiplier">Vermenigvuldigingsfactor:</label><br/>
								<input type="number" name="txtIndicatorMultiplier" id="txtIndicatorMultiplier" value="<? echo settings("indicatoren", "multiplier"); ?>"/>
							</p>
							<p>
								<label for="txtOwaesAdd">Aantal toevoegen:</label><br/>
								<input type="number" name="txtOwaesAdd" id="txtOwaesAdd" value="<? echo settings("indicatoren", "owaesadd"); ?>"/>
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
	</body>
</html>
