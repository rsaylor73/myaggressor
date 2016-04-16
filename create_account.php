<?php
include "settings.php";

print "<center><span class=\"details-description\">";

if ($_SESSION['sessionID'] != "") {
	if ($_SESSION['random'] != "") {


		$sql = "UPDATE `contacts` SET `uuname` = '$_GET[uuname]', `uupass` = '$_GET[uupass1]' WHERE `contactID` = '$_GET[contactID]'";
		$result = $reservation->new_mysql($sql);
		if ($result == "TRUE") {
			print "<br>Your account has been created. To continue please <a href=\"$_SESSION[uri]\">login</a>.";
			//print "Test: $_SESSION[uri]<br>";
		} else {
			print "<br><center><font color=red>There was an error creating your account. Please try again.</font></center><br>";
		}

	}
} else {
	print "<br><font color=red>Your session has expired. Please try again.</font><br>\n";
}

print "</center></span>";
?>
