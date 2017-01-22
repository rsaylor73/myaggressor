<?php
require "settings.php";

	if ($_SESSION['uuname'] != "") {

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
	      `inventory`.`charterID` = '$_POST[charter]'
			AND `charters`.`charterID` = '$_POST[charter]'
   	   AND `inventory`.`sessionID` = '$_SESSION[sessionID]'
	   ";

	   $pax = 0;
		$total = 0;
   	$result = $reservation->new_mysql($sql);
	   while ($row = $result->fetch_assoc()) {
   	   $found = "1";
      	$pax++;

                     $temp_d = "";
                     $discount = $reservation->find_discount($_POST['charter'],$row['bunk_price']);
                     if (is_array($discount)) {
                        foreach ($discount as $value) {
                           if ($value > $temp_d) {
                              if(!is_array($value)) {
	                              $temp_d = $value;
										}
                           }
                        }
                     }

		// ------------------

                        $sql_b = "
                        SELECT
                                `sb`.`bunkID`,
                                `sb`.`value`,
                                `bk`.`cabin`,
                                `bk`.`bunk`,
                                `b`.`abbreviation`,
                                CONCAT(`b`.`abbreviation`,'-',`bk`.`cabin`,`bk`.`bunk`) AS 'location'
                        FROM
                                `af_df_unified2`.`specials_bunks` sb,
                                `reserve`.`bunks` bk,
                                `reserve`.`boats` b

                        WHERE
                                `sb`.`discountID` = '".$discount[$temp_d][1]."'
                                AND `sb`.`bunkID` = `bk`.`bunkID`
                                AND `bk`.`boatID` = `b`.`boatID`

                        ";
                        $check_discount = $temp_d; // this is here incase the query is empty
                        $result_b = $reservation->new_mysql($sql_b);
                        $num_rows = $result_b->num_rows;
                        if ($num_rows > 0) {
                                $check_discount = "";
                                while ($row_b = $result_b->fetch_assoc()) {
                                        if ($row_b['location'] == $row['bunk']) {
                                                $check_discount = $temp_d;

                                        }
                                }
                        }

		// -----------------


			if ($check_discount > 0) {
				$new_price = $row['bunk_price'] - $check_discount;
            			$total = $total + $new_price;
				$gdr = $discount[$temp_d][0];
			} else {
			      $total = $total + $row['bunk_price'];
			}
	   }

	   // Determin charter date for payment policy
	   $sql = "SELECT UNIX_TIMESTAMP(`start_date`) AS 'start_date' FROM `charters` WHERE `charterID` = '$_POST[charter]'";
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


	
		$sql = "
		SELECT 
			`contacts`.*,
			`countries`.`country`

		FROM 
			`contacts`,`countries`

		WHERE `contacts`.`uuname` = '$_SESSION[uuname]'
		AND `contacts`.`countryID` = `countries`.`countryID`
		";

		$result = $reservation->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			$authnet_login = "5ZuC46WbX";
			$authnet_key = "53mr9jJwEL4689qn";

			require_once('authorizenet.class.php');
			$cc_num_v = preg_replace("/[^0-9]/","", $_POST['cc_num']);
         $a = new authorizenet_class;
         $a->add_field('x_login', $authnet_login);
         $a->add_field('x_tran_key', $authnet_key);
         $a->add_field('x_version', '3.1');
         $a->add_field('x_type', 'AUTH_CAPTURE');

	// test enabled
         $a->add_field('x_test_request', 'TRUE');    // Just a test transaction
	// end test
         $a->add_field('x_relay_response', 'FALSE');
         $a->add_field('x_delim_data', 'TRUE');
         $a->add_field('x_delim_char', '|');
         $a->add_field('x_encap_char', '');
 	 $a->add_field('x_email_customer', 'FALSE');
         $a->add_field('x_ship_to_first_name', $row['first']);
         $a->add_field('x_ship_to_last_name', $row['last']);
         $a->add_field('x_ship_to_address', $row['address1']);
         $a->add_field('x_ship_to_city', $row['city']);

			if ($row['countryID'] == "2") {
 	        $a->add_field('x_ship_to_state', $row['state']);
			} else {
           $a->add_field('x_ship_to_state', $row['province']);
			}
         $a->add_field('x_ship_to_zip', $row['zip']);
         $a->add_field('x_ship_to_country', $row['country']);
         $a->add_field('x_first_name', $row['first']);
         $a->add_field('x_last_name', $row['last']);
         $a->add_field('x_address', $row['address1']);
         $a->add_field('x_city', $row['city']);
         if ($row['countryID'] == "2") {
 	        $a->add_field('x_state', $row['state']);
			} else {
           $a->add_field('x_state', $row['province']);
			}
         $a->add_field('x_zip', $row['zip']);
         $a->add_field('x_country', $row['country']);
         $a->add_field('x_email', $row['email']);
         $a->add_field('x_phone', $row['phone1']);
         $a->add_field('x_description', "Charter $_POST[charter]");
         $a->add_field('x_method', 'CC');
         $a->add_field('x_card_num', $cc_num_v);   // test successful visa
         $a->add_field('x_amount', $_POST['payment_amount']);
			$exp_date = $_POST['exp_month'] . $_POST['exp_year'];
         $a->add_field('x_exp_date', $exp_date);    // march of 2008
         $a->add_field('x_card_code', $_POST['cvv']);    // Card CAVV Security code

			switch ($a->process()) {
				case 1: // Accepted
      	   				//echo $a->get_response_reason_text();
					// Make Reservation and record the payment

					$today = date("Ymd");
					$contact_name = "$row[first] $row[last]";

					$balance = $total - $_POST['payment_amount'];

					if ($balance > 0) {
						$status = "DEPOSIT RECEIVED";
					} else {
						$status = "PAID IN FULL";
					}

					$sql2 = "
				   INSERT INTO `reservations`
				   (`reseller_agentID`,`charterID`,`reservation_date`,`userID`,`reservation_contactID`,
				   `reservation_type`,`nonrefundable_deposit`,`nine_month_payment`,`contact_name_changeable`,`reservation_sourceID`,
				   `total_res_gross_balance`,`total_res_payments`,`total_res_discounts`,`total_res_vouchers`,`total_res_net_balance`,
				   `total_res_commission`,`total_res_pax`,`reservation_status`,`payment_policy_id`)
				
				   VALUES

				   ('86505023','$_POST[charter]','$today','150','$row[contactID]',
				   'Single','$deposit','$balance','$contact_name','23',
				   '$total','$_POST[payment_amount]','0.00','0.00','$balance',
				   '0.00','$pax','$status','$policy')
					";

					$contactID = $row['contactID'];
				
					$result2 = $reservation->new_mysql($sql2);
					$reservationID = $reservation->linkID->insert_id;


                                        // My Aggressor Points - 10/30/2016 - RBS
                                        $sql2 = "INSERT INTO `points_earned_log` (`contactID`,`points_earned`,`date`,`event_details`) VALUES 
                                        ('$contactID','400','$today','New reservation $reservationID')";
                                        $result2 = $reservation->new_mysql($sql2);
                                        $sql2 = "SELECT `points` FROM `contacts` WHERE `contactID` = '$contactID'";
                                        $result2 = $reservation->new_mysql($sql2);
                                        while ($row2 = $result2->fetch_assoc()) {
                                                $points = $row2['points'] + 400;
                                                $sql3 = "UPDATE `contacts` SET `points` = '$points' WHERE `contactID` = '$contactID'";
                                                $result3 = $reservation->new_mysql($sql3);
                                        }
                                        // end points

					//print "RESID $reservationID<br>\n";

					// Update Inventory

					// This needs to be put into a loop
					//$sql2 = "UPDATE `inventory` SET `reservationID` = '$reservationID', `commission_at_time_of_booking` = '0', `passengerID` = '61531204', `status` = 'booked',`DWC_discount` = '$temp_d', `general_discount_reason` = '$gdr' WHERE `charterID` = '$_POST[charter]' AND `sessionID` = '$_SESSION[sessionID]'";
                                        $sql2 = "UPDATE `inventory` SET `reservationID` = '$reservationID', `commission_at_time_of_booking` = '0', `passengerID` = '61531204', `status` = 'booked' WHERE `charterID` = '$_POST[charter]' AND `sessionID` = '$_SESSION[sessionID]'";

					$result2 = $reservation->new_mysql($sql2);

					// Add contact profile to inventory
					$sql3 = "SELECT * FROM `inventory` WHERE `reservationID` = '$reservationID'";
					$result3 = $reservation->new_mysql($sql3);
					while ($row3 = $result3->fetch_assoc()) {
						$i = "passenger_";
						$i .= $row3['inventoryID'];
						$this_passenger = $_POST[$i];


						// update discounts
						$temp_d = "";
						$discount = $reservation->find_discount($_POST['charter'],$row3['bunk_price']);
						if (is_array($discount)) {
							foreach ($discount as $value) {
								if ($value > $temp_d) {
									if(!is_array($value)) {
										$temp_d = $value;
									}
								}
							}
						}

			                        $sql_b = "
			                        SELECT
			                                `sb`.`bunkID`,
			                                `sb`.`value`,
			                                `bk`.`cabin`,
			                                `bk`.`bunk`,
			                                `b`.`abbreviation`,
			                                CONCAT(`b`.`abbreviation`,'-',`bk`.`cabin`,`bk`.`bunk`) AS 'location'
			                        FROM
			                                `af_df_unified2`.`specials_bunks` sb,
			                                `reserve`.`bunks` bk,
			                                `reserve`.`boats` b

			                        WHERE
			                                `sb`.`discountID` = '".$discount[$temp_d][1]."'
			                                AND `sb`.`bunkID` = `bk`.`bunkID`
			                                AND `bk`.`boatID` = `b`.`boatID`
			
			                        ";
			                        $check_discount = $temp_d; // this is here incase the query is empty
						$new_gdr = "";
			                        $result_b = $reservation->new_mysql($sql_b);
			                        $num_rows = $result_b->num_rows;
			                        if ($num_rows > 0) {
							// we will follow bunks
			                                $check_discount = "";
			                                while ($row_b = $result_b->fetch_assoc()) {
			                                        if ($row_b['location'] == $row3['bunk']) {
			                                                $check_discount = $temp_d;
									$new_gdr = $gdr;
			                                        }
			                                }
			                        } else {
							// apply discount to all bunks
							$check_discount = $temp_d;
							$new_gdr = $gdr;
						}

						// end discounts

// Notes
// The discount did not work
// on the last test. - RBS Jan 20, 2017

						switch ($this_passenger) {
							case "male":
							$sql4 = "UPDATE `inventory` SET `passengerID` = '61531879', `DWC_discount` = '$check_discount', 
							`general_discount_reason` = '$new_gdr' WHERE `inventoryID` = '$row3[inventoryID]' AND `reservationID` = '$reservationID'";
							$result4 = $reservation->new_mysql($sql4);
							break;

							case "female":
				                        $sql4 = "UPDATE `inventory` SET `passengerID` = '61531880', `DWC_discount` = '$check_discount',
							`general_discount_reason` = '$new_gdr'  WHERE `inventoryID` = '$row3[inventoryID]' AND `reservationID` = '$reservationID'";
				                        $result4 = $reservation->new_mysql($sql4);

							break;

							default:
				                        $sql4 = "UPDATE `inventory` SET `passengerID` = '61531204', `DWC_discount` = '$check_discount',
							`general_discount_reason` = '$new_gdr'  WHERE `inventoryID` = '$row3[inventoryID]' AND `reservationID` = '$reservationID'";
				                        $result4 = $reservation->new_mysql($sql4);

							break;
						}
					}

					// Set primary passenger
					$sql2 = "SELECT * FROM `inventory` WHERE `reservationID` = '$reservationID'";
					$result2 = $reservation->new_mysql($sql2);
					while ($row2 = $result2->fetch_assoc()) {
						$ii = "inv_";
						$ii .= $row2['inventoryID'];

						if (($_POST['primary'] == $ii) and ($_POST['primary'] != "")) {
							$login_key = md5(uniqid(rand(), true));
							$sql3 = "UPDATE `inventory` SET `passengerID` = '$_SESSION[contactID]', `login_key` = '$login_key' WHERE `inventoryID` = '$row2[inventoryID]'";
							$result3 = $reservation->new_mysql($sql3);
							// send GIS link

							$login_key = md5(uniqid(rand(), true));
							$sql2 = "UPDATE `inventory` SET `passengerID` = '$contactID', `login_key` = '$login_key' WHERE `inventoryID` = '$_GET[inventoryID]'";

							// generate and send gis
							$first = $_SESSION['first'];
							$last = $_SESSION['last'];
							$email = $_SESSION['email'];

							// get charter ID
							$sql42 = "SELECT `charterID`,`reservationID` FROM `inventory` WHERE `inventoryID` = '$row2[inventoryID]'";
							$result42 = $reservation->new_mysql($sql42);
							while ($row42 = $result42->fetch_assoc()) {
								$charterID = $row42['charterID'];
								$reservationID = $row42['reservationID'];
							}

							// get boatname
							$sql42 = "
							SELECT 
								`boats`.`name`,
								`charters`.`start_date`

							FROM
								`charters`,`boats`
	
							WHERE
								`charters`.`charterID` = '$charterID'
								AND `charters`.`boatID` = `boats`.`boatID`
							";
							$result42 = $reservation->new_mysql($sql42);
							while ($row42 = $result42->fetch_assoc()) {
								$name = $row42['name'];
								$start_date = $row42['start_date'];
							}

							// kbyg 
							$sql42 = "
							SELECT 
								`reserve`.`inventory`.`inventoryID`,
								`reserve`.`inventory`.`charterID`,
								`reserve`.`inventory`.`passengerID`,
								`reserve`.`inventory`.`login_key`,
								`reserve`.`inventory`.`reservationID`,
								`reserve`.`contacts`.`first`,
								`reserve`.`contacts`.`last`,
								`reserve`.`contacts`.`email`,
								`reserve`.`contacts`.`contactID`,
								`reserve`.`boats`.`fleet`,
								`reserve`.`boats`.`name`,
								`reserve`.`boats`.`reservationist_email`,
								`af_df_unified2`.`kbyg`.`fileName`,
								`reserve`.`charters`.`start_date`
							FROM
								`reserve`.`inventory`,
								`reserve`.`contacts`,
								`reserve`.`charters`,
								`reserve`.`boats`,
								`af_df_unified2`.`kbyg`

							WHERE
								`reserve`.`inventory`.`inventoryID` = '$row2[inventoryID]'
								AND `reserve`.`inventory`.`passengerID` = `reserve`.`contacts`.`contactID`
								AND `reserve`.`inventory`.`charterID` = `reserve`.`charters`.`charterID`
								AND `reserve`.`charters`.`boatID` = `reserve`.`boats`.`boatID`
								AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`kbyg`.`boatID`
								AND `reserve`.`charters`.`destinationID` = `af_df_unified2`.`kbyg`.`destinationID`
							";
							$result42 = $reservation->new_mysql($sql42);
							while ($row42 = $result42->fetch_assoc()) {
								$fileName = $row42['fileName'];
								$reservationist_email = $row42['reservationist_email'];
								$login_key = $row42['login_key'];
							}

							// create GIS profile
							$sql42 = "SELECT * FROM `guestform_status` WHERE `passengerID` = '$contactID' AND `charterID` = '$charterID'";
							$result42 = $reservation->new_mysql($sql42);
								while ($row42 = $result42->fetch_assoc()) {
									$err = "1";
								}
								if ($err != "1") {
									$sql42 = "INSERT INTO `guestform_status` (`passengerID`,`charterID`) VALUES ('$contactID','$charterID')";
									$result42 = $reservation->new_mysql($sql42);
								}

								$sql42 = "UPDATE `inventory` SET `passengerID` = '$contactID', `login_key` = '$login_key' WHERE `inventoryID` = '$row2[inventoryID]'";
								$result42 = $reservation->new_mysql($sql42);
								if ($result42 == "TRUE") {
									// send GIS
									$_URL = "https://gis.liveaboardfleet.com/gis/index.php/";
									$guest_url = $_URL.$contactID."/".$reservationID."/".$charterID."/".$login_key;
									// Add to notes
									$note_date = date("Ymd");
									$sql44 = "INSERT INTO `notes` (`note_date`,`table_ref`,`fkey`,`user_id`,`title`,`note`) 
									VALUES 
									('$note_date','inventory','$row2[inventoryID]','CRS','GIS Link','Link sent to (guest contact) $first $last - <a href=\"$guest_url\" target=_blank>GO TO GIS PROFILE</a>')";
									$result44 = $reservation->new_mysql($sql44);
									$guest_name = "$first $last";
									$email_subject = 'Guest Profile for '.$guest_name.' - Embark Date '.date("M-d-Y",strtotime($start_date)).' (#'.$reservationID.') ' . $name;
									$kbyg = "<a href=\"http://www.liveaboardfleet.net/aggressor/upload/$fileName\" target=_blank>Know Before You Go</a>";
									// legacy (No - we can not use this Rich this is no longer supported by PHP - Robert
									//$server2   = "mysql";
									//$username2 = "root";
									//$password2 = "F7m9dSz0";
									//$database3 = "reserve";
									//$database7 = "af_df_unified2";
									//$af = mysql_connect($server2,$username2,$password2);
									//$unify = mysql_connect($server2,$username2,$password2,true);
									//mysql_select_db($database3, $af);
									//mysql_select_db($database7, $unify);

									// get direct or reseller
									$sql_who = "
									SELECT
										`reseller_agents`.`resellerID`

									FROM
										`reservations`,`reseller_agents`

									WHERE
										`reservations`.`reservationID` = '$reservationID'
										AND `reservations`.`reseller_agentID` = `reseller_agents`.`reseller_agentID`

									";
									$resultW = $reservation->new_mysql($sql_who);
									while ($rowW = $resultW->fetch_assoc()) {
										$resellerID = $rowW['resellerID'];
									}
									//$result_who = mysql_query($sql_who,$af);
									//for ($y=0; $y < mysql_num_rows($result_who); $y++) {
										//$row_who = mysql_fetch_assoc($result_who);
										//$resellerID = $row_who['resellerID'];
									//}
									if ($resellerID == "19") {
										$type = "CRS";
									} else {
										$type = "Reseller";
									}
									$sql_msg = "SELECT `message` FROM `af_df_unified2`.`gis_email` WHERE `type` = '$type'";
									$result_msg = $reservation->new_mysql($sql_msg);
									while ($row_msg = $result_msg->fetch_assoc()) {
								                $new_msg = str_replace("#kbyg#",$kbyg,$row_msg['message']);
								                $new_msg = str_replace("#guest_url#",$guest_url,$new_msg);
									}
									//$result_msg = mysql_query($sql_msg, $unify);
									//for ($m=0; $m < mysql_num_rows($result_msg); $m++) {
										//$row_msg = mysql_fetch_assoc($result_msg);
										//$new_msg = str_replace("#kbyg#",$kbyg,$row_msg['message']);
										//$new_msg = str_replace("#guest_url#",$guest_url,$new_msg);
									//}
									// end legacy
									$email_message = "Dear $guest_name,<br><br>$new_msg";
									$sendemail = mail($email,$email_subject,$email_message,$headers);
								}
							// end GIS
						}
					}

					// Clear Session
					$sql2 = "UPDATE `inventory` SET `sessionID` = '', `timestamp` = '' WHERE `charterID` = '$_POST[charter]' AND `sessionID` = '$_SESSION[sessionID]'";
					$result2 = $reservation->new_mysql($sql2);

					// Record Payment
					$sql2 = "INSERT INTO `reservation_payments` (`reservationID`,`payment_amount`,`payment_date`,`payment_type`,`comment`) VALUES
					('$reservationID','$_POST[payment_amount]','$today','Online - CC','Payment made via CRS by $contact_name')";
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
						`charters`.`charterID` = '$_POST[charter]'
						AND `charters`.`boatID` = `boats`.`boatID`
						AND `charters`.`status_commentID` = `status_comments`.`status_commentID`

					";
					$result3 = $reservation->new_mysql($sql3);
					while ($row3 = $result3->fetch_assoc()) {
						$subj = "New Online Reservation - $row3[name] (Conf $reservationID)";

						$msg = "
						$row3[name] Agent,<br>There is a new online reservation. Please review <a href=\"https://reservations.aggressor.com/reservation_manage_single_reservation.php?reservationID=$reservationID\">$reservationID</a><br><br>

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
							<td colspan=3>$_POST[details]</td>
						</tr>
						</table>
						<br><br>
						<b><font color=red>THIS IS A SERVICE EMAIL. DO NOT REPLY BACK.</font></b><br>";

						$name = $row3['name'];
						$agent_email = $row3['reservationist_email'];
						mail($row3['reservationist_email'],$subj,$msg,$headers);
						mail('crs@aggressor.com',$subj,$msg,$headers);
						
					}

					$_SESSION['contact_name'] = $contact_name;
					$_SESSION['reservationID'] = $reservationID;
					$_SESSION['contactID'] = $contactID;

					/*
					// this is now displayed in order_processed.php

					print "
					<br><br>
					<table width=800>


					<tr><td width=50>&nbsp;</td><td><br>$contact_name,
					<br><br>
					Your confirmation number is <b>$reservationID</b>.<br><br>
					</td></tr>

               <tr><td width=50>&nbsp;</td><td bgcolor=\"#E3F6CE\">
                  <b>Thank you for making your payment through our Online Reservation System.</b><br>
                  An email confirmation has been sent to $_SESSION[email] with a link to your reservation where you can add guests, make payments and review your invoice. You may also click the button below to view your reservation details.
                  <br><br><b><font color=red>Please do not use your browser \"Back\" button. It could result in multiple payments being submitted.</font></b><br>
               </td></tr>


					<tr><td>&nbsp;</td><td><br>
					<input type=\"image\" src=\"buttons/bt-dashboard.png\" onclick=\"location.href='guests.php?res=$reservationID&c=$contactID';return false;\">&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"index.php?s=1\" target=_blank><img src=\"buttons/bt-makeanother.png\" border=0></a><br>";

					*/
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
					/*
					$msg = "Dear $_SESSION[first] $_SESSION[last],<br><br>

					Thank you for booking your vacation with Aggressor Fleet and Dancer Fleet. To manage your reservation; view your invoice, make a payment and assign guests if you've booked 2 or more, please click on the following link which will take 
					you to you to your <a href=\"$site_url/guests.php?res=$reservationID&c=$contactID\">Reservation</a>.

					If you have a question feel free to call us at 1-800-348-2628 or +1-706-993-2531. You can also email your Aggressor Fleet and Dancer Fleet travel specialist $agent_email.<br><br>

					Thank you for your business,<br>
					Aggressor Fleet and Dancer Fleet.<br>
					www.aggressor.com<br>
					www.dancerfleet.com<br><br>
					209 Hudson Trace<br>
					Augusta, GA 30907<br>
					USA<br>
					";
					*/

					mail($_SESSION['email'],$subj,$msg,$headers);

					print "</table>";



				break;

				case 2:  // Declined
					echo "D";
	         	//echo $a->get_response_reason_text();
					// send back to form
   	      break;

				case 3:  // Error
					echo "E";
         		//echo $a->get_response_reason_text();
					//send back to form
					//$_GET['charter'] = $_POST['charter'];
					//include "reservenow.php";
	         break;

				default:
					echo "E";
				break;
			}
		}
	} else {
		$msg = "Your session has expired. Please re-start your search to log back in.";
		$reservation->general_error($msg);
	}

?>
