<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


include "settings.php";

$sql = "SELECT `itinerary` FROM `itinerary` WHERE `boatID` = '$_GET[boatID]' ORDER BY `itinerary` ASC";
$result = $common->new_mysql($sql);
$options = "<option value=\"\">--Select--</option>";
while ($row = $result->fetch_assoc()) {
	$options .= "<option>$row[itinerary]</option>";
}
print "<td>Itinerary:</td><td><select name=\"itinerary\" style=\"width:250px;\">$options</select></td>";

?>
