<?php
include "settings.php";

if ($_SESSION['sessionID'] != "") {

	$sql = "SELECT `uuname` FROM `contacts` WHERE `uuname` = '$_GET[uuname]'";
	$result = $reservation->new_mysql($sql);
	while($row = $result->fetch_assoc()) {
		$found = "1";
	}

	if ($found == "1") {
		print "<font color=red>Error: already used.</font>";
      if ($_GET['rr'] == "1") {
         print "<script>document.getElementById(\"Continue\").disabled = true;</script>";
      }

	} else {
		print "<font color=green>Ok</font>";
		if ($_GET['rr'] == "1") {
			print "<script>document.getElementById(\"Continue\").disabled = false;</script>";
		}
	}

}
?>
