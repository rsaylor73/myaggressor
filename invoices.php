<?php

$ip = $_SERVER['REMOTE_ADDR'];

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


include "settings.php";

if ($ip != "98.142.222.59") {
	print "<font color=red>Access Denied</font>";
	die;
}

$sql = "
SELECT 
	`r3`.*,
	`rs`.`company`

FROM 
	`reseller_3rd_party` r3, `resellers` rs

WHERE 
	`r3`.`resellerID` = '$_GET[rid]'
	AND `r3`.`resellerID` = `rs`.`resellerID`

";

$result = $common->new_mysql($sql);
$row = $result->fetch_assoc();

$sql = "
SELECT
	`i`.`bunk`,
	DATE_FORMAT(`c`.`start_date`, '%m %b %Y') AS 'charter_date',
	`c`.`nights`,
	`i`.`bunk_price` + `c`.`add_on_price_commissionable` AS 'bunk_price',
	`b`.`name`,
	`b`.`boatID`

FROM
	`reservations` r, `inventory` i, `charters` c, `boats` b, `reseller_agents` ra

WHERE
	`r`.`reservationID` = '$_GET[r]'
	AND `r`.`reseller_agentID` = `ra`.`reseller_agentID`
	AND `ra`.`resellerID` = '$_GET[rid]'
	AND `r`.`reservationID` = `i`.`reservationID`
	AND `i`.`charterID` = `c`.`charterID`
	AND `c`.`boatID` = `b`.`boatID`

";

$today = date("d M Y");
print "<table border=0 width=720>
<tr>
	<td valign=top><img src=\"logo/$row[logo]\" width=\"300\"></td>
	<td valign=top align=right colspan=\"2\">
	<b>Confirmation # $_GET[r]</b><br>
	Invoice Date: $today<br><br>
	Mail Payments to:<br>
	$row[company]<br>
	$row[address1]<br>
	";
	if ($row['address2'] != "") {
		print "$row[address2]<br>";
	}
	print "
	$row[city], $row[state] $row[zip]<br>
	$row[country]<br><br>
   $row[email]<br>
   $row[phone]<br>
	</td>
</tr>
";

$result = $common->new_mysql($sql);
while ($row2 = $result->fetch_assoc()) {

	if ($boat == "") {
		print "<tr><td colspan=3><br><b>$row2[name] ($row2[charter_date])</td></tr>";
		$boat = $row2['name'];
		print "<tr><td><b>Stateroom</b></td><td><b>Nights</b></td><td><b>Price</b></td></tr>";
	}

	$default_commission = $row['default_commission'] * .01;

	//print "$row2[bunk_price] * $row[default_commission]<br>";

	$comm = $row2['bunk_price'] * $default_commission;

	//print "$row2[bunk_price] - $comm<br>";

	$bunk_price = $row2['bunk_price'] - $comm;


	$stateroom = explode("-",$row2['bunk']);

   $sql3 = "SELECT `cabin_type` FROM `bunks` WHERE `boatID` = '$row2[boatID]' AND CONCAT(`cabin`,`bunk`) = '$stateroom[1]'";
	$result3 = $common->new_mysql($sql3);
	$row3 = $result3->fetch_assoc();

	print "<tr><td width=33%>$stateroom[1] - $row3[cabin_type]</td><td width=33%>$row2[nights]</td><td width=33%>".number_format($bunk_price,2,'.',',')."</td></tr>";
	$total = $total + $bunk_price;
}

print "<tr><td colspan=3><br><br><b>Total Due: ".number_format($total,2,'.',',')." USD</b></td></tr>";

print "
</table>";

?>
