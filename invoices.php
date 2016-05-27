<?php

$ip = $_SERVER['REMOTE_ADDR'];

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


include "settings.php";


switch ($ip) {
	case "98.142.222.59":
	case "45.16.74.253":
	case "50.193.242.188":
	// ok
	break;

	default:
   print "<font color=red>Access Denied</font>";
   die;
	break;
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
$row3 = $result->fetch_assoc();

/*
$sql = "
SELECT
	`c`.`company`,
	`c`.`address1`,
	`c`.`address2`,
	`c`.`city`,
	`c`.`state`,
	`c`.`province`,
	`c`.`zip`,
	`c`.`email`,
	`c`.`logo`,
	`cn`.`country`

FROM
	`reservations` r, `contacts` c, `countries` cn

WHERE
	`r`.`reservationID` = '$_GET[r]'
	AND `r`.`reseller_agentID` = `c`.`reseller_agentID`
	AND `c`.`countryID` = `cn`.`countryID`
";

$result3 = $common->new_mysql($sql);
$row3 = $result3->fetch_assoc();
*/

$sql = "
SELECT
   `i`.`bunk`,
   DATE_FORMAT(`c`.`start_date`, '%d %b %Y') AS 'charter_date',
	DATE_FORMAT(`r`.`reservation_date`, '%d %b %Y') AS 'reservation_date',
   DATE_FORMAT(DATE_ADD(`c`.`start_date`,interval `c`.`nights` day) , '%d %b %Y') AS 'end_date',
   `c`.`nights`,
   `i`.`bunk_price` + `c`.`add_on_price_commissionable` AS 'bunk_price',
   `b`.`name`,
   `b`.`boatID`,
	`c`.`embarkment`,
	`c`.`disembarkment`,
	`c`.`itinerary`,
	`c`.`charterID`,
	`i`.`manual_discount` + `i`.`DWC_discount` AS 'discount',
	`ct`.`first`,
	`ct`.`last`

FROM
   `reservations` r, `inventory` i, `charters` c, `boats` b, `reseller_agents` ra, `contacts` ct

WHERE
   `r`.`reservationID` = '$_GET[r]'
   AND `r`.`reseller_agentID` = `ra`.`reseller_agentID`
   AND `ra`.`resellerID` = '$_GET[rid]'
   AND `r`.`reservationID` = `i`.`reservationID`
   AND `i`.`charterID` = `c`.`charterID`
   AND `c`.`boatID` = `b`.`boatID`
	AND `i`.`passengerID` = `ct`.`contactID`

";
$today = date("d M Y");

print '
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.$_GET['r'].'</title>
<style type="text/css">
body {
        margin-top: 0px;
        font-family: Tahoma, Geneva, sans-serif;
        font-size: 12px;
}

table, th, td {
    border: 1px solid #D2E4F0;
}
table {
    border-collapse: collapse;
}
.Sub-Title {
 color: #0B4487;
 font-family: "Times New Roman", Times, serif;
 font-size: 16px;
 font-weight: bold;
}        
.default-font {
  font-family: Tahoma, Geneva, sans-serif;
  font-size: 12px;
  display: table-cell;
}      
</style> 
</head>  
<body link="#0B4487" vlink="#0B4487" alink="#0B4487">

';

print "<img src=\"logo/$row3[logo]\" width=\"300\" height=\"125\">";
print '       
       
       
     <table width="750" border="0" cellspacing="0" cellpadding="8">
       <tr>
         <td width="353" rowspan="2"><strong><b>Send Payments to:</b></strong><br />
';
print "
   $row3[company]<br>
   $row3[address1]<br>
   ";
   if ($row3['address2'] != "") {
      print "$row3[address2]<br>";
   }
   print "
   $row3[city], $row3[state]$row3[province] $row3[zip]<br>
   $row3[country]<br><br>
   $row3[phone]
";
print '
         </td>
         <td height="26" align="right"><strong class="Sub-Title">Confirmation # '.$_GET['r'].'</strong></td>
       </tr>
       <tr>
         <td align="right" valign="top"><p><strong>Invoice Date:</strong> '.$today.'</p>Rates are quoted in USD per person.</td>
       </tr>
       <tr>
         <td valign="middle"><strong>Email:</strong> <a href="mailto:'.$row3['email'].'?subject=Confirmation '.$_GET['r'].'">'.$row3['email'].'</a><br>
           <br>
         </td>
         <td height="35" align="right">&nbsp;</td>
       </tr>
     </table>
     <table width="750" cellpadding="4" cellspacing="0">
       <tr>
         <td bgcolor="#D2E4F0"><span class="Sub-Title">Reservation Information</span></td>
       </tr></table>
';

$result = $common->new_mysql($sql);
while ($row2 = $result->fetch_assoc()) {

  if ($boat == "") {
	 // vars here
    $charter_date = $row2['charter_date'];
	 $nights = $row2['nights'];
	 $reservation_date = $row2['reservation_date'];
	 $embarkment = $row2['embarkment'];
	 $disembarkment = $row2['disembarkment'];
	 $end_date = $row2['end_date'];
	 $itinerary = $row2['itinerary'];
	 

    print '
    <table width="750" cellpadding="4" cellspacing="0">
       <tr>
         <td width="288"><strong>Yacht:</strong> '.$row2['name'].'</td>
         <td width="444"><span class="default-font"><strong>Itinerary:</strong>'.$itinerary.'</span></td>
       </tr></table>
    ';

    print '
    <table width="750" cellpadding="4" cellspacing="0">
    <tr>
         <td width="113"><b>Reservation Date</b></td>
         <td width="81" align="center"><p><strong>Departure</strong></p></td>
         <td width="76" align="center"><b>Return</b></td>
         <td width="54" align="center"><b>Nights</b></td>
         <td width="187" align="center"><b>Embark</b></td>
         <td width="187" align="center"><b>Disembark</b></td>
    </tr>
       <tr>
         <td align="center">'.$reservation_date.'</td>
         <td align="center">'.$charter_date.' </td>
         <td align="center">'.$end_date.'</td>
         <td align="center">'.$nights.'</td>
         <td align="center">'.$embarkment.'</td>
         <td align="center">'.$disembarkment.'</td>
       </tr>
    </table>
    <table  width="750" cellpadding="4" cellspacing="0">
       <tr>
         <td colspan="5" bgcolor="#D2E4F0"><span class="Sub-Title">Passenger Name &amp; Pricing</span></td>
       </tr></table>';

    $boat = $row2['name'];
  }
}

print '
       <table  width="750" cellpadding="4" cellspacing="0">
       <tr>
         <td align="left"><b>Name</b></td>
         <td align="center" width="90"><b>Price</b></td>
         <td align="center" width="90"><b>Adjustments</b></td>
         <td align="center" width="165"><b>Stateroom Type</b></td>
         <td align="right" width="165"><div align="right"><b>Rate</b></div></td>
       </tr>
';

$pax = 0;
$result = $common->new_mysql($sql);
while ($row2 = $result->fetch_assoc()) {
  $default_commission = $row['default_commission'] * .01;
  $comm = $row2['bunk_price'] * $default_commission;
  $bunk_price = $row2['bunk_price'] - $comm;
  $stateroom = explode("-",$row2['bunk']);

  $sql3 = "SELECT `cabin_type` FROM `bunks` WHERE `boatID` = '$row2[boatID]' AND CONCAT(`cabin`,`bunk`) = '$stateroom[1]'";
  $result3 = $common->new_mysql($sql3);
  $row3 = $result3->fetch_assoc();
  $net = $bunk_price - $row2['discount'];
  $total = $total + $bunk_price;
  $total_discount = $total_discount + $row2['discount'];
  $pax++;
  print '

       <tr>
         <td align="left">'.$row2['first'].' '.$row2['last'].'</td>
         <td align="center">$'.number_format($bunk_price,2,'.',',').'</td>
         <td align="center">$'.number_format($row2['discount'],2,'.',',').'</td>
         <td align="center">'.$row3['cabin_type'].'</td>
         <td align="right"><div align="right">$'.number_format($net,2,'.',',').'</div></td>
       </tr>
  ';
}

$total = $total - $total_discount;

print '
       <tr>
         <td colspan=2><b>Number of Passengers: </b>'.$pax.'</td>
         <td>&nbsp;</td>
         <td align="right" colspan="2"><div align="right"><b>Total Reservation Rate:</b> $'.number_format($total,2,'.',',').'</div></td>
       </tr>
   </table>


     <table width="750" cellpadding="4" cellspacing="0">
       <tr>
         <td bgcolor="#D2E4F0"><span class="Sub-Title">Total Due</span></td>
       </tr>
       <tr>
         <td align="right"><b>Total Due:</b> $'.number_format($total,2,'.',',').'</td>
       </tr>
     </table>
</body>
</html>
';
?>
