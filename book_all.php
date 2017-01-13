<?php
	include "settings.php";
	if(isset($_SESSION['sessionID'])) {

		if(is_array($_GET['boats'])) {
			foreach ($_GET['boats'] as $key=>$value) {
				$this_boats .= "&boats[]=$value";
			}
		}

		$type = "bunks" . $_GET['type'];

		$sql = "
		SELECT 
			`d1`.`$type` AS 'bunks'

		FROM
	                `reserve`.`charters` c,
        	        `af_df_unified2`.`destinations` d1,
	                `reserve`.`destinations` d2

                 WHERE
                 	`c`.`charterID` = '$_GET[charter]'
                 	AND `c`.`boatID` = `d1`.`boatID`
              	 	AND `d1`.`name` = '$_GET[name]'
	                AND `c`.`destinationID` = `d2`.`destinationID`
                 ";

		$result = $reservation->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			$bunks = $row['bunks'];
		}

		$bunk_arr = explode(",",$bunks);
		foreach($bunk_arr as $key=>$value) {
			$bunk_list .= "'$value',";
		}
		$bunk_list = trim($bunk_list,",");

		// get inventory
		$sql = "
		SELECT
			`i`.`inventoryID`,
			`i`.`charterID`,
			`i`.`bunk`

		FROM
			`inventory` i

		WHERE
			`i`.`charterID` = '$_GET[charter]'
			AND `i`.`bunk` IN ($bunk_list)
			AND `i`.`status` = 'avail'
			AND `i`.`passengerID` = ''
		";

		$result = $reservation->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			$timestamp = date("U");
			$timestamp = $timestamp + "2700"; // changed to 45 mins was 30 mins
			$sql2 = "UPDATE `inventory` SET `passengerID` = '61531204', `status` = 'tentative', `sessionID` = '$_SESSION[sessionID]', `timestamp` = '$timestamp', 
			`donotmove_passenger` = '1'  WHERE `inventoryID` = '$row[inventoryID]'";
			$result2 = $reservation->new_mysql($sql2);
		}

		$name = urlencode($_GET['name']);
		$url = "bookit.php?charter=$_GET[charter]&name=$name&passengers=$_GET[passengers]$this_boats";
		header('Location: '.$url);
	}
?>
