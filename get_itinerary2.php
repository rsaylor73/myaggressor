<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


include "settings.php";

$sql = "SELECT `destinationID`,`description` FROM `destinations` WHERE `boatID` = '$_GET[boatID]' ORDER BY `description` ASC";
$result = $common->new_mysql($sql);
$options = "<option value=\"\">--Select--</option>";
while ($row = $result->fetch_assoc()) {
	$options .= "<option value=\"$row[destinationID]\">$row[description]</option>";
}
print "<td>Destination:</td><td><select name=\"destination\" style=\"width:250px;\">$options</select></td>";

?>
