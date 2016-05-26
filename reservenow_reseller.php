<?php
require "settings.php";

	if ($_SESSION['uuname'] != "") {

		// get the commission
		$commission = "0";
		$sql = "SELECT `commission` FROM `resellers` WHERE `resellerID` = '$_SESSION[resellerID]'";
		$result = $reservation->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			$commission = $row['commission'];
		}

      // Get total cost
      $sql = "
      SELECT
         `inventory`.`inventoryID`,
         `inventory`.`bunk`,
         `inventory`.`bunk_price` + `charters`.`add_on_price_commissionable` AS 'bunk_price',
         `inventory`.`bunk_description`

      FROM
         `inventory`,`charters`

      WHERE
         `inventory`.`charterID` = '$_GET[charter]'
         AND `charters`.`charterID` = '$_GET[charter]'
         AND `inventory`.`sessionID` = '$_SESSION[sessionID]'
      ";

      $pax = 0; 
      $total = 0; 
      $result = $reservation->new_mysql($sql);
      while ($row = $result->fetch_assoc()) {
         $found = "1";
         $pax++;
         
                     $temp_d = "";
                     $discount = $reservation->find_discount($_GET['charter'],$row['bunk_price']);
                     if (is_array($discount)) {
                        foreach ($discount as $value) {
                           if ($value > $temp_d) {
                              if(!is_array($value)) {
                                 $temp_d = $value;
                              }
                           }
                        }
                     }
         if ($temp_d > 0) {
            $new_price = $row['bunk_price'] - $temp_d;
            $total = $total + $new_price;
            $gdr = $discount[$temp_d][0];

				//print "Inventory ID $row[inventoryID] - Bunk price $row[bunk_price] - DWC $temp_d<br>";
		      $sql2 = "UPDATE `inventory` SET `DWC_discount` = '$temp_d', `general_discount_reason` = '$gdr' WHERE `inventoryID` = '$row[inventoryID]'";
				$result2 = $reservation->new_mysql($sql2);


         } else {
            $total = $total + $row['bunk_price'];
         } 
      }  


		$balance = $total;
		$today = date("Ymd");
		$contact_name = "$_SESSION[first] $_SESSION[last]";

      // Determin charter date for payment policy
      $sql = "SELECT UNIX_TIMESTAMP(`start_date`) AS 'start_date' FROM `charters` WHERE `charterID` = '$_GET[charter]'";
      $result = $reservation->new_mysql($sql);
      while ($row = $result->fetch_assoc()) {
         $start_date_epoch = $row['start_date'];
      }
      $today_date = date("U");
      $diff = $start_date_epoch - $today_date;
      if ($diff > 7776000) { // 90 days
         $policy = "3"; // only deposit required
      } else {
         $policy = "5"; // full amount required
      }

      switch ($policy) {
	      case "3":
	      $deposit = $total * .40;
   	   break;
		
			case "5":
		   $deposit = $total;
		   break;
		}

		$status = "AWAITING DEPOSIT";

		// look up the reseller agent ID
		$sql = "SELECT `reseller_agentID` FROM `contacts` WHERE `reseller_agentID` IS NOT NULL AND `contactID` = '$_SESSION[contactID]'";
		$result = $reservation->new_mysql($sql);
		$row = $result->fetch_assoc();
		$reseller_agentID = $row['reseller_agentID'];
		if ($reseller_agentID == "") {
			print "<br><br>Error: your account is not linked to any reseller profile. Please contact your Aggressor Fleet reseller partner.<br>";
			die;
		}

		// switch contactID to primary_contactID if the user is a reseller 3rd party
		$sql3 = "SELECT `contact_type` FROM `contacts` WHERE `contactID` = '$_SESSION[contactID]'";
		$result3 = $reservation->new_mysql($sql3);
		while ($row3 = $result3->fetch_assoc()) {
			$contact_type = $row3['contact_type'];
		}

		if ($contact_type == "reseller_third_party") {
			$sql3 = "SELECT `primary_contactID` FROM `reseller_3rd_party` WHERE `resellerID` = '$_SESSION[resellerID]'";
			$result3 = $reservation->new_mysql($sql3);
			while ($row3 = $result3->fetch_assoc()) {
				$primary_contactID = $row3['primary_contactID'];
			}
		} else {
			$primary_contactID = $_SESSION['contactID'];
		}


		$sql2 = "
	   INSERT INTO `reservations`
	   (`reseller_agentID`,`charterID`,`reservation_date`,`userID`,`reservation_contactID`,
	   `reservation_type`,`nonrefundable_deposit`,`nine_month_payment`,`contact_name_changeable`,`reservation_sourceID`,
	   `total_res_gross_balance`,`total_res_payments`,`total_res_discounts`,`total_res_vouchers`,`total_res_net_balance`,
	   `total_res_commission`,`total_res_pax`,`reservation_status`,`payment_policy_id`)
				
	   VALUES

	   ('$reseller_agentID','$_GET[charter]','$today','176','$primary_contactID',
	   'Single','$deposit','$balance','$contact_name','23',
	   '$total','0.00','0.00','0.00','$balance',
	   '0.00','$pax','$status','$policy')
		";

		$contactID = $_SESSION['contactID'];
		$result2 = $reservation->new_mysql($sql2);
		$reservationID = $reservation->linkID->insert_id;

		// Update Inventory
		$sql2 = "UPDATE `inventory` SET `reservationID` = '$reservationID', `commission_at_time_of_booking` = '$commission', `passengerID` = '61531204', `status` = 'booked' WHERE `charterID` = '$_GET[charter]' AND `sessionID` = '$_SESSION[sessionID]'";
		$result2 = $reservation->new_mysql($sql2);

		// Add contact profile to inventory
		$sql3 = "SELECT * FROM `inventory` WHERE `reservationID` = '$reservationID'";
		$result3 = $reservation->new_mysql($sql3);
		while ($row3 = $result3->fetch_assoc()) {
			$i = "passenger_";
			$i .= $row3['inventoryID'];
			$this_passenger = $_GET[$i];
			switch ($this_passenger) {
				case "male":
				$sql4 = "UPDATE `inventory` SET `passengerID` = '61531879' WHERE `inventoryID` = '$row3[inventoryID]' AND `reservationID` = '$reservationID'";
				$result4 = $reservation->new_mysql($sql4);
				break;

				case "female":
            $sql4 = "UPDATE `inventory` SET `passengerID` = '61531880' WHERE `inventoryID` = '$row3[inventoryID]' AND `reservationID` = '$reservationID'";
            $result4 = $reservation->new_mysql($sql4);
				break;

				default:
            $sql4 = "UPDATE `inventory` SET `passengerID` = '61531204' WHERE `inventoryID` = '$row3[inventoryID]' AND `reservationID` = '$reservationID'";
            $result4 = $reservation->new_mysql($sql4);
				break;
			}
		}

		// Clear Session
		$sql2 = "UPDATE `inventory` SET `sessionID` = '', `timestamp` = '' WHERE `charterID` = '$_GET[charter]' AND `sessionID` = '$_SESSION[sessionID]'";
		$result2 = $reservation->new_mysql($sql2);

		// Email agent
		$pax = 0;
		$sql3 = "SELECT * FROM `inventory` WHERE `reservationID` = '$reservationID'";
      $result3 = $reservation->new_mysql($sql3);
      while ($row3 = $result3->fetch_assoc()) {
			$pax++;
		}

		$sql3 = "
		SELECT
			`boats`.`reservationist_email`,
			`boats`.`name`,
			`charters`.`nights`,
			DATE_FORMAT(`charters`.`start_date`,'%m/%d/%Y') AS 'start_date',
			`status_comments`.`comment`

		FROM
			`charters`,`boats`,`status_comments`

		WHERE
			`charters`.`charterID` = '$_GET[charter]'
			AND `charters`.`boatID` = `boats`.`boatID`
			AND `charters`.`status_commentID` = `status_comments`.`status_commentID`
		";
		$result3 = $reservation->new_mysql($sql3);
		while ($row3 = $result3->fetch_assoc()) {
			$subj = "New Online Reseller Reservation - $row3[name] (Conf $reservationID)";
			$msg = "
			$row3[name] Agent,<br>There is a new online <b>Reseller</b> reservation. Please review <a href=\"https://reservations.aggressor.com/reservation_manage_single_reservation.php?reservationID=$reservationID\">$reservationID</a><br><br>

						<table border=0 width=600>
						<tr bgcolor=\"#C2C2C2\"><td colspan=4><b>Reservation Information</b></td></tr>
						<tr>
							<td><b>Yacht:</b></td>
							<td>$row3[name]</td>
							<td><b>Passengers:</b></td>
							<td>$pax</td>
						</tr>
						<tr>
							<td><b>Departure:</b></td>
							<td>$row3[start_date]</td>
							<td><b>Days:</b></td>
							<td>$row3[nights]</td>
						</tr>
						<tr>
							<td><b>Status:</b></td>
							<td colspan=3>$row3[comment]</td>
						</tr>
						<tr>
							<td><b>Guest Comments:</b></td>
							<td colspan=3>$_GET[details]</td>
						</tr>
						</table>
						<br><br>
						<b><font color=red>THIS IS A SERVICE EMAIL. DO NOT REPLY BACK.</font></b><br>";

						$name = $row3['name'];
						$agent_email = $row3['reservationist_email'];
						mail($row3['reservationist_email'],$subj,$msg,$headers);
						mail('crs@aggressor.com',$subj,$msg,$headers);
						
		}

		print "
		<br><br>
		<table width=800>


		<tr><td width=50>&nbsp;</td><td><br>$contact_name,
		<br><br>
		Your confirmation number is <b>$reservationID</b>.<br><br>
		</td></tr>

      <tr><td width=50>&nbsp;</td><td bgcolor=\"#E3F6CE\">
      <b>Thank you for making your reservation through our Online Reservation System.</b><br>
      An email confirmation has been sent to $_SESSION[email] with a link to your reservation where you can add guests, make payments and review your invoice. You may also click the button below to view your reservation details.
      <br><br><b><font color=red>Please do not use your browser \"Back\" button.</font></b><br>
      </td></tr>


		<tr><td>&nbsp;</td><td><br>
		<input type=\"image\" src=\"buttons/bt-dashboard.png\" onclick=\"location.href='guests.php?res=$reservationID&c=$contactID';return false;\">&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"index.php?s=1\" target=_blank><img src=\"buttons/bt-makeanother.png\" border=0></a><br>";

		?>
		<!-- Google Code for Completed Reservation Conversion Page https://developers.google.com/analytics/devguides/collection/analyticsjs/ecommerce-->
		<script type="text/javascript">
		ga('require', 'ecommerce');
		ga('ecommerce:addTransaction', {
		 'id': '<?=$reservationID;?>',
		 'affiliation': 'Aggressor Fleet',
		 'revenue': '10',
		 'shipping': '0',
		 'tax': '0',
		 'currency': 'USD'  // local currency code.
		});

		ga('ecommerce:addItem', {
		 'id': '<?=$reservationID;?>',
		 'name': '<?=$name;?>',
		 'sku': 'N/A',
		 'category': 'Online Reservation',
		 'price': '10',
		 'quantity': '1',
		 'currency': 'USD' // local currency code.
		});

		ga('ecommerce:send');
		</script>

		<?php

		// email contact

		// Get template
		$sql4 = "SELECT `crs` FROM `af_df_unified2`.`auto_emails` WHERE `id` = '1'";
		$result4 = $reservation->new_mysql($sql4);
		while ($row4 = $result4->fetch_assoc()) {
			$msg = $row4['crs'];
		}

		$contact_name = "$_SESSION[first] $_SESSION[last]";
		$url = "$site_url/guests.php?res=$reservationID&c=$contactID";

		$msg = str_replace("#guest_name#",$contact_name,$msg);
		$msg = str_replace("#url#",$url,$msg);
		$msg = str_replace("#agent_email#",$agent_email,$msg);

		$subj = "View Online Reservation - $name (Conf $reservationID)";
		mail($_SESSION['email'],$subj,$msg,$headers);
		print "</table>";

	} else {
		$msg = "Your session has expired. Please re-start your search to log back in.";
		$reservation->general_error($msg);
	}

?>
