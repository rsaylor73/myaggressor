<?php
include "settings.php";

$check_login = $reservation->check_login();
if ($check_login == "FALSE") {
	die;
}

$sql = "SELECT `passengerID` FROM `inventory` WHERE `inventoryID` = '$_GET[inventoryID]'";
$result = $reservation->new_mysql($sql);
while ($row = $result->fetch_assoc()) {
	switch ($row['passengerID']) {
		case "61531879":
			// male
			$sql2 = "UPDATE `inventory` SET `passengerID` = '61531880' WHERE `inventoryID` = '$_GET[inventoryID]'";
			$result2 = $reservation->new_mysql($sql2);
			if ($result2 == "TRUE") {
				print "This space has been assigned as a female space. Press F5 to re-load.<br>";
			}
		break;

		case "61531880":
		// female
         $sql2 = "UPDATE `inventory` SET `passengerID` = '61531879' WHERE `inventoryID` = '$_GET[inventoryID]'";
         $result2 = $reservation->new_mysql($sql2);
         if ($result2 == "TRUE") {
            print "This space has been assigned as a male space. Press F5 to re-load.<br>";
         }
      break;

	}
}
?>
