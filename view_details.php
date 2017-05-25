<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
session_start();
include "settings.php";
include "header.php";

if (is_array($_GET['boats'])) {
	foreach ($_GET['boats'] as $boat) {
   	$this_boats .= "&boats[]=$boat";
	}
}



// check login

         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $reservation->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            $reservation->login_screen($uri);

         } else {



// end check


   $bg = "1";
   include "search.php";

	// update to check account type logged in and use only consumer - TO DO
	// added check of session variable 'reseller_agentID' to if, resolving reported problem
	// also do search to see if any are avail and if not then load details instead of details_pax1

	if ($_GET['passengers'] == "1" && $_SESSION['reseller_agentID']=='') {
        
	 $sql = "
         SELECT 
            `reserve`.`inventory`.`bunk_price` + `reserve`.`charters`.`add_on_price_commissionable` + `reserve`.`charters`.`add_on_price` AS 'bunk_price', 
            `reserve`.`inventory`.`bunk`, 
            `reserve`.`inventory`.`inventoryID`, 
            `reserve`.`inventory`.`reservationID`, 
            `reserve`.`inventory`.`status`, 
            `reserve`.`inventory`.`userID`, 
            `reserve`.`inventory`.`sessionID`, 
            `reserve`.`inventory`.`bunk_description`, 
            `reserve`.`inventory`.`passengerID`, 
            `reserve`.`inventory`.`sessionID` 

         FROM 
            `reserve`.`inventory`,`charters` 

         WHERE 
            `reserve`.`inventory`.`charterID` = '$_GET[charter]' 
            AND `reserve`.`charters`.`charterID` = '$_GET[charter]' 
            AND `reserve`.`inventory`.`passengerID` IN ('61531204','0') 
            AND `reserve`.`inventory`.`reservationID` = '' 
            AND (`reserve`.`inventory`.`sessionID` = '$_SESSION[sessionID]' OR `reserve`.`inventory`.`sessionID` = '') 
         ";

	$found_pax_1 = "0";
        $result = $reservation->new_mysql($sql);
        	while ($row = $result->fetch_assoc()) {
            $sex = $reservation->get_sex2($_GET['charter'],$row['bunk']);
            if ($sex == $_SESSION['sex']) {
					$found_pax_1 = "1";
				}
			}
	}
	
	if (($_GET['passengers'] == "1") && ($found_pax_1 == "1")) {
		$reservation->details_pax1();
	} else {
		$reservation->details();
	}
	}
?>
