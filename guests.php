<?php
include "settings.php";
include "header_guest.php";
include "header.php";

include "search2.php";
?>


<?php
/*
<script>
window.onbeforeunload = function() { return "The use of the brower's back button can have a nasty side affect. Please do not use."; };
</script>
*/
?>

<div id="toparea2">&nbsp;</div>
<?php

if ($_GET['logout'] == "logout") {
	session_destroy();
	print "<script>alert('You have been logged out');</script>";
}


$uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$_SESSION['guest_uri'] = $uri;
$check_login = $reservation->check_login();
if ($check_login == "FALSE") {
	// show login/register
   $reservation->login_screen($uri);

} else {

	$sql = "SELECT `contactID`,`first`,`last` from `contacts` WHERE `uuname` = '$_SESSION[uuname]'";
	$result = $reservation->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		$billing_name = "$row[first] $row[last]";
		if ($row['contactID'] == $_GET['c']) {
			$ok = "1";
		} else {
			$ok = "2";
		}
	}


                  // KBYG
                  $sql2 = "
                  SELECT
                    `af_df_unified2`.`kbyg`.`fileName`

                  FROM
                    `reserve`.`reservations`,
                    `reserve`.`charters`,
                    `af_df_unified2`.`destinations`,
                    `af_df_unified2`.`kbyg`

                  WHERE
                    `reserve`.`reservations`.`reservationID` = '$_GET[res]'
                     AND `reserve`.`reservations`.`charterID` = `reserve`.`charters`.`charterID`
                     AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`kbyg`.`boatID`
                     AND `reserve`.`charters`.`destinationID` = `af_df_unified2`.`kbyg`.`destinationID`

                  LIMIT 1
                  ";
                  $kbyg = "";
						$result2 = $reservation->new_mysql($sql2);
						while ($row2 = $result2->fetch_assoc()) {
                     $kbyg = "<a href=\"http://www.liveaboardfleet.net/aggressor/upload/$row2[fileName]\" target=_blank class=\"details-top\">Know Before You Go</a>";
                  }



            print "
            <div id=\"result_wrapper\">
               <div id=\"result_pos1\">
                  <div id=\"result_pos2\">
							<br>
                     <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\">
                        <tr>
                           <td>
                              <img src=\"../ResImages/generic-DTW.jpg\" width=\"850\">
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">
                                 <tr>";
												if ($_GET['logout'] != "logout") {
	                                    print "<td width=\"566\" class=\"details-top\">&nbsp;&nbsp;$kbyg&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"guests.php?logout=logout\" class=\"details-top\">Logout</a></td>";
												} else {
                                       print "<td width=\"566\" class=\"details-top\">&nbsp;</td>";
												}
												print "
                                    <td width=\"283\" align=\"right\" class=\"details-top\">&nbsp;</td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                     </table>
                    <div style=\"clear:both;\"></div>
            ";

            print "<div id=\"result_pos3\">";
            print "<div id=\"create_new_account\">";

            $sql = "
            SELECT
               DATE_FORMAT(`charters`.`start_date`, '%b %d, %Y') AS 'start_date',
               `charters`.`start_date` AS 'date2',
               DATEDIFF(`charters`.`start_date`,NOW()) AS 'days',
               `boats`.`name`,
               `boats`.`reservationist_email`,
               `reservations`.`reservationID`,
               `reservations`.`reservation_date`,
               DATE_FORMAT(`reservations`.`reservation_date`, '%b %e, %Y') AS 'res_due_date',
               `reservations`.`total_res_pax` AS 'pax',
               `reservations`.`charterID`,
               `charters`.`boatID`,
               `destinations`.`description`,
               `reservations`.`total_res_gross_balance`,
               `reservations`.`show_as_suspended`,
               `charters`.`destination`,
               `charters`.`embarkment`,
               `charters`.`disembarkment`,
               `charters`.`itinerary`

            FROM
               `reservations`,`charters`,`boats`,`destinations`

            WHERE
               `reservations`.`reservationID` = '$_GET[res]'
               AND `reservations`.`charterID` = `charters`.`charterID`
               AND `charters`.`boatID` = `boats`.`boatID`
               AND `charters`.`destinationID` = `destinations`.`destinationID`

            ";
            $result = $reservation->new_mysql($sql);
            while ($row = $result->fetch_assoc()) {
					$itinerary = $row['itinerary'];
				}

				print " <br><span class=\"result-title-text\">Online Guest System</span><br><b>Itinerary:</b> $itinerary";

				/*
				 print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

				<span class=\"details-description\"><font color=blue>Please do not use your browsers back button.</font></span>";
				*/

				if ($ok == "2") {
					if ($_GET['logout'] == "logout") {
						print "<br><br><font color=green size=4>You have been logged out.</font>";

						print "<br><br><br><br><br><br>
						<span class=\"result-title-text\">
						Visit Aggressor Fleet and Dancer Fleet's <a href=\"http://www.aggressor.com\">website</a> or click <a href=\"index.php\">here</a> to make another reservation.
						</span>
						<br><br>";


					} else {
						print "<br><br><font color=red>Sorry, but the reservation link was incorrect or this is not your registration.<br></font>";
					}
					print "</div></div></div><div></div>";
					die;
				}

				$sql = "
				SELECT
					DATE_FORMAT(`charters`.`start_date`, '%b %d, %Y') AS 'start_date',
					`charters`.`start_date` AS 'date2',
					DATEDIFF(`charters`.`start_date`,NOW()) AS 'days',
					`boats`.`name`,
					`boats`.`reservationist_email`,
					`reservations`.`reservationID`,
					`reservations`.`reservation_date`,
					DATE_FORMAT(`reservations`.`reservation_date`, '%b %e, %Y') AS 'res_due_date',
					`reservations`.`total_res_pax` AS 'pax',
					`reservations`.`charterID`,
					`charters`.`boatID`,
					`destinations`.`description`,
					`reservations`.`total_res_gross_balance`,
					`reservations`.`show_as_suspended`,
					`charters`.`destination`,
					`charters`.`embarkment`,
					`charters`.`disembarkment`,
					`charters`.`itinerary`

				FROM
					`reservations`,`charters`,`boats`,`destinations`

				WHERE
					`reservations`.`reservationID` = '$_GET[res]'
					AND `reservations`.`charterID` = `charters`.`charterID`
					AND `charters`.`boatID` = `boats`.`boatID`
					AND `charters`.`destinationID` = `destinations`.`destinationID`

				";
				$result = $reservation->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
					if ($row['show_as_suspended'] == "1") {
						print "<br><br><span class=\"details-description\"><font color=red>This reservation is no longer active.</font></span><br><br>\n";
						die;
					}

					$row['pax'] = substr($row['pax'],0,-3);

					$date = strtotime(date("Y-m-d", strtotime($row['date2'])) . " -90 day");


					$due_date = date('M d, Y', $date);
					$due_date2 = date("Ymd", $date);

					if ($due_date2 <= $row['reservation_date']) {
						$due_date = $row['res_due_date'];
					}

					$sql3 = "SELECT SUM(`payment_amount`) AS 'payment_amount' FROM `reservation_payments` WHERE `reservationID` = '$row[reservationID]' AND `payment_type` IN ('Online - CC','Credit Card','Check','Wire')";
					$result3 = $reservation->new_mysql($sql3);
					while ($row3 = $result3->fetch_assoc()) {
						$payments = $row3['payment_amount'];
					}
					$total_due = $row['total_res_gross_balance'] - $payments;

					// discounts
					$sql3 = "SELECT SUM(`inventory`.`DWC_discount`) AS 'DWC_discount' FROM `inventory` WHERE `inventory`.`reservationID` = '$row[reservationID]' GROUP BY `inventory`.`reservationID`";
					$result3 = $reservation->new_mysql($sql3);
					while ($row3 = $result3->fetch_assoc()) {
						$discounts = $row3['DWC_discount'];
					}



					print "<br><br>";
					print "<table border=0  width=800 class=\"details-description\">
					<tr><td width=\"266\"><b>Reservation Details</b></td><td width=\"267\"><b>Stateroom Details</b></td><td width=\"267\"><b>Your trip starts in $row[days] days.</b></td></tr>";
					print "
					<tr>
						<td valign=top width=\"266\">
							<table border=0>
							<tr><td>Confirmation:</td><td>$row[reservationID]</td></tr>
							<tr><td>Departure Date:</td><td>$row[start_date]</td></tr>
							<tr><td>Passengers:</td><td>$row[pax]</td></tr>
							<td colspan=2>$row[name]<br>
                     <tr><td>Destination:</td><td>$row[destination]</td></tr>
							<tr><td>Embark:</td><td>$row[embarkment]</td></tr>
							<tr><td>Disembark:</td><td>$row[disembarkment]</td></tr>
							</table>
							<br>
							";

							switch ($_SESSION['contact_type']) {
							case "consumer":
							case "reseller_manager":
							case "reseller_agent":
							print "
							<table border=0>
							<tr><td colspan=2><b>Balance</b></td></tr>
							<tr><td>Total:</td><td>$".number_format($row['total_res_gross_balance'],2)."</td></tr>
							";
							if ($discounts > 0) { 
								print "<tr><td>Discounts:</td><td>$".number_format($discounts,2)."</td></tr>";
							}
							//$total_due = $total_due - $discounts;
							print "
							<tr><td>Payments:</td><td>$".number_format($payments,2)."</td></tr>
							<!--<tr><td>Balance:</td><td>$".number_format($total_due,2)."</td></tr>-->
							<tr><td>Due Date:</td><td>$due_date</td></tr></table>";
							break;
							}

							if ($total_due > 0) {

							   $start_year = date("Y");
							   $end_year = $start_year + 10;

							   for ($i = $start_year; $i < $end_year; $i++) {
							      $year .= "<option>$i</option>";
							   }


								print "<div id=\"payment\" style=\"display:none\">
								<div id=\"payment2\">
									<form name=\"myform\">
									<input type=\"hidden\" name=\"reservationID\" value=\"$row[reservationID]\">
									<b>Credit Card Payment</b><br>

							      <div id=\"cc\">
						         <table border=0>
						            <tr>
					               <td colspan=2 align=left><img src=\"CC-Visa.jpg\" alt=\"Visa Accepted\" title=\"Visa Accepted\"><img src=\"CC-MCard.jpg\" alt=\"Master Card Accepted\" title=\"Master Card Accepted\"></td>
					            </tr>
					         	</table>
						      </div>
								<table border=0 width=500>
								   <tr><td></td><td>Name On Card:</td><td><input type=\"text\" name=\"cc_name\" value=\"$billing_name\" size=40></td></tr>
									<tr><td></td><td>Amount:</td><td>$ <input type=\"text\" name=\"amount\" placeholder=\"example 200.00\" size=38></td></tr>
								   <tr><td></td><td>Credit Card Number:</td><td><input type=\"text\" name=\"cc_num\" size=40 onchange=\"cctype(this.form)\"></td></tr>
								   <tr><td></td><td>Expiration Date:</td><td>
								      <select name=\"exp_month\">
							   	   <option value=\"01\">Jan (01)</option>
								      <option value=\"02\">Feb (02)</option>
								      <option value=\"03\">Mar (03)</option>
								      <option value=\"04\">Apr (04)</option>
								      <option value=\"05\">May (05)</option>
								      <option value=\"06\">Jun (06)</option>
								      <option value=\"07\">Jul (07)</option>
								      <option value=\"08\">Aug (08)</option>
								      <option value=\"09\">Sep (09)</option>
								      <option value=\"10\">Oct (10)</option>
								      <option value=\"11\">Nov (11)</option>
								      <option value=\"12\">Dec (12)</option>
								      </select>
								      &nbsp;
								      <select name=\"exp_year\">
							         $year
							      </select>
						      </td></tr>
							   <tr><td></td><td>CVV Number:</td><td><input type=\"text\" name=\"cc_cvv\" size=10> <a href=\"javascript:void(0)\" name=\"cvvQ\" onclick=\"document.getElementById('cvv2').style.display='inline'\">?</a> </td></tr>
							";

							if ($_SESSION['contact_type'] != "consumer") {
								print "<tr><td colspan=3><b><font color=blue>Please note by using a credit card, your commission will be reduced by 3%.</font></b></td></tr>";
							}

							print "

								<tr><td colspan=3><input type=\"checkbox\" name=\"policy\" value=\"checked\" onclick=\"document.getElementById('checkout').style.display='inline'\"> I agree to the WayneWorks Marine, LLC <a href=\"https://gis.liveaboardfleet.com/gis/_POLICY.html\" target=_blank>Payment Policy</a>.</td></tr>
							   <tr><td></td><td></td><td><br><input type=\"image\" src=\"buttons/bt-pay.png\" name=\"checkout\" id=\"checkout\" style=\"display:none\" onclick=\"checkout2(this.form);return false;\">&nbsp;<input type=\"image\" src=\"buttons/btn-cancel2.png\" onclick=\"document.getElementById('payment').style.display='none';return false;\"></td></tr>
							 </table>
							";

						   print "<div id=\"cvv2\" style=\"display:none\">
						   <img src=\"cvv-visa.gif\" width=\"200\"><br><a href=\"javascript:void(0)\" onclick=\"document.getElementById('cvv2').style.display='none'\">Close</a>
						   </div>";
							?>
						   <script>
                                function cctype(myform) {
                                        $.get('cctype2.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#cc").html(php_msg);
                                        });
                                }

                                function checkout2(myform) {
                                        $.post('checkout2.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                          if (php_msg == "E") {
                                             alert('Your credit card could not be processed due to an error. Please contact your bank to find out why it will not process.');
                                          }
                                          if (php_msg == "D") {
                                             alert('Your credit card was declined. Please contact your bank to find out why it will not process.');
                                          }
                                          if (php_msg == "A") {
                                             $("#payment2").html('<span class="details-description"><br><font color=green>Your payment was accepted. Loading please wait...</font><br></span>');
                                             setTimeout(function()
                                                {
                                                window.location.replace('<?=$_SESSION['guest_uri']?>')
                                                }
                                             ,2000);

                                          }
                                        });
                                }

						   </script>


							<?php
							print "
								</div>
								</div>";
	                     switch ($_SESSION['contact_type']) {
   	                  case "consumer":
      	               case "reseller_manager":
         	            case "reseller_agent":
								print "
								<br><a href=\"javascript:void(0)\" onclick=\"document.getElementById('payment').style.display='inline'\" >Make Payment</a><br>
								";
								break;
								}
							}

							switch ($_SESSION['contact_type']) {
							case "consumer":
							case "reseller_manager":
							case "reseller_agent":
	
							print "
							<a href=\"invoice.php?r=$_GET[res]\" target=_blank>View Invoice</a><br>
                     <a href=\"mailto:$row[reservationist_email]?subject=Confirmation $row[reservationID]\">Contact Agent</a>
							";
							break;
							}

						print "
						</td>
						<td valign=top colspan=2 width=\"534\">

						";
                     $sql2 = "
                     SELECT
                        `inventory`.`inventoryID`,
                        `inventory`.`charterID`,
                        `inventory`.`reservationID`,
                        `inventory`.`passengerID`,
                        `inventory`.`bunk`,
                        `contacts`.`first`,
                        `contacts`.`last`,
                        `inventory`.`login_key`

                     FROM
                        `inventory`,`contacts`

                     WHERE
                        `inventory`.`reservationID` = '$row[reservationID]'
                        AND `inventory`.`passengerID` = `contacts`.`contactID`

                     ORDER BY `inventory`.`bunk` ASC
                     ";

							$swap = "No";
							if ($_SESSION['contact_type'] != "consumer") {
								$result2 = $reservation->new_mysql($sql2);
								while ($row2 = $result2->fetch_assoc()) {
									$counter++;
								}
								if ($counter > 5) {
									$swap = "Yes";
								}
							}
							$result2 = $reservation->new_mysql($sql);
							while ($row2 = $result2->fetch_assoc()) {
								if (($row2['passengerID'] == "61531204") or ($row2['passengerID'] == "61531879") or ($row2['passengerID'] == "61531880")) {
									$text_version = "1";
								}
							}

							if ($text_version == "1") {
							print "
If you are a returning guest, enter your name and birth month to search for your existing guest record. If you are unable to locate a match but have traveled with us before, please email <a href=\"mailto:$row[reservationist_email]\">$row[reservationist_email]</a>. Your space is still reserved.<br><br>
<hr>
							";
							} else {

								print "
								Thank you for choosing to travel with us. Visit our <a href=\"http://www.aggressor.com\" target=_blank>website</a> to learn more about the yacht and destination. Visit <a href=\"http://www.liveaboardvacations.com\" target=_blank>LiveAboardVacations</a>, our travel department for Aggressor Fleet & Dancer Fleet for planning your hotel, tours and airline arrangements.<br><br><hr>";

							}



							$result2 = $reservation->new_mysql($sql2);
							while ($row2 = $result2->fetch_assoc()) {
								$bunk = substr($row2['bunk'],-3);
								//$button = "<input type=\"image\" src=\"buttons/bt-guest-info.png\" onclick=\"window.open('https://gis.liveaboardfleet.com/gis/index.php/$row2[passengerID]/$row2[reservationID]/$row2[charterID]/$row2[login_key]');return false;\">";
								$name = "$row2[first] $row2[last]";
								$button = "";
								if (($row2['passengerID'] == "61531204") or ($row2['passengerID'] == "61531879") or ($row2['passengerID'] == "61531880")) {
									$button = "<form name=\"myform\"><input type=\"hidden\" name=\"inventoryID\" value=\"$row2[inventoryID]\"><input type=\"hidden\" name=\"bunk\" value=\"$bunk\"><input type=\"image\" src=\"buttons/bt-add-guest.png\" onclick=\"add_guest_$row2[inventoryID](this.form);return false;\">";
									if ($swap == "Yes") {
										$button .= "<input type=\"image\" src=\"buttons/bt-gender.png\" onclick=\"change_gender_$row2[inventoryID](this.form);return false;\">";
									}
									$button .= "</form>";
									$name = "";
								}
								$sex = $reservation->get_sex($row2['charterID'],$row2['bunk']);
								// Override if proper profile is loaded
								if ($row2['passengerID'] != "61531204") {
									switch ($row2['passengerID']) {
										case "61531879": // male
										$sex = "<img src=\"../resellers/icn-male.jpg\">";
										break;

										case "61531880": // female
										$sex = "<img src=\"../resellers/icn-female.jpg\">";
										break;

										default:
										$sql3 = "SELECT `sex` FROM `contacts` WHERE `contactID` = '$row2[passengerID]'";
										$result3 = $reservation->new_mysql($sql3);
										while ($row3 = $result3->fetch_assoc()) {
											if ($row3['sex'] == "male") {
												$sex = "<img src=\"../resellers/icn-male.jpg\">";
											}
											if ($row3['sex'] == "female") {
												$sex = "<img src=\"../resellers/icn-female.jpg\">";
											}
										}
										break;
									}
								}


								print "<div id=\"guest_$row2[inventoryID]\">
									<table border=0 width=550><tr>
									<td align=left>$sex</td>
									<td width=50>Stateroom:</td>
									<td width=50>$bunk</td>
									<td width=200>$name</td>
									<td width=300>$button</td>
									</tr></table><hr>
								</div>\n";
								?>
								<script>
                                function change_gender_<?=$row2['inventoryID']?>(myform) {
                                        $.get('change_gender.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#guest_<?=$row2['inventoryID']?>").html(php_msg);
                                        });
                                }


                                function add_guest_<?=$row2['inventoryID']?>(myform) {
                                        $.get('add_guest.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#guest_<?=$row2['inventoryID']?>").html(php_msg);
                                        });
                                }
								</script>
								<?php
							}


						print "

						</td>

						</td>
					</tr>


					";



					print "</table>";
				}


				print "</div></div>";
		print "</div></div></div>";

}





?>
