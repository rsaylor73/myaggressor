<?php
	include "settings.php";
	if(isset($_SESSION['sessionID'])) {
		if ($_POST['qb'] != "") {
			// Temp book the bunk

			// Get charterID
			$sql = "
			SELECT 
				`charterID`,`sessionID`,`userID` 

			FROM 
				`inventory` 

			WHERE 
				`inventoryID` = '$_POST[qb]'
			";
			$result = $reservation->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {


				$charterID = $row['charterID'];
				if (($row['sessionID'] != 0) && ($row['sessionID'] != $_SESSION['sessionID']) && ($row['userID'] != "0")) {
					print "This space is temporary on hold by another guest.";
					die;
				}
				$sql2 = "
				SELECT
					count(`inventory`.`inventoryID`) AS 'total'

				FROM
					`inventory`

				WHERE
					`inventory`.`sessionID` = '$_SESSION[sessionID]'
					AND `inventory`.`charterID` = '$row[charterID]'

				GROUP BY `inventory`.`charterID`
				";
				$result2 = $reservation->new_mysql($sql2);
				while ($row2 = $result2->fetch_assoc()) {
					$total = $row2['total'];
				}
			}


			switch ($_SESSION['contact_type']) {
               case "consumer":
					$stop = "4";
					break;

					default:
					$stop = "40";
					break;
			}

			if ($total == $stop) {
				print "
                  <form name=\"MyForm\"><input type=\"hidden\" name=\"qb\" value=\"$_POST[qb]\">
                  <input type=\"image\" src=\"buttons/bt-booknow.png\" name=\"inventoryID_$_POST[qb]\" id=\"inventoryID_$_POST[qb]\" onclick=\"quickbook".$_POST['qb']."(this.form);return false;\">
                  </form>";

				print "<br><font color=red>You can not book for then 4 staterooms.</font>";
                  ?>            
                                <script>
                                function quickbook<?php echo $_POST['qb'];?>(myform) {
                                        $.post('quick_book.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#inv_<?php echo $_POST['qb'];?>").html(php_msg);
                                        });
                                }
                                </script>
                  <?php

				die;
			}
			$timestamp = date("U");
			$timestamp = $timestamp + 1800;

			$sql = "UPDATE `inventory` SET `passengerID` = '61531204', `status` = 'tentative', `sessionID` = '$_SESSION[sessionID]', `timestamp` = '$timestamp', `donotmove_passenger` = '1'  WHERE `inventoryID` = '$_POST[qb]'";
			$result = $reservation->new_mysql($sql);

            /* -- Single multiple pax check -- */
            $sql2 = "
            SELECT
               `inventory`.`bunk`

            FROM
               `inventory`

            WHERE
               `inventory`.`charterID` = '$charterID'
               AND `inventory`.`sessionID` = '$_SESSION[sessionID]'

            ";
            $result2 = $reservation->new_mysql($sql2);
            while ($row2 = $result2->fetch_assoc()) {
               $bunks_array[] = $row2['bunk'];
            }
            foreach ($bunks_array as $key=>$value) {
               //print "$key => $value<br>";
					$value = substr($value,4,3);
					$value = substr($value,0,-1);
					$counter[$value]++;
            }

				foreach ($counter as $ok) {
					if ($ok == "1") {
						$check++;
					}
					//print "T: $ok<br>";
				}

				if ($check > 2) {
		         $sql3 = "UPDATE `inventory` SET `passengerID` = '', `status` = 'avail', `sessionID` = '', `timestamp` = '', `donotmove_passenger` = ''  WHERE `inventoryID` = '$_POST[qb]'";
					$result3 = $reservation->new_mysql($sql3);
					print "<font color=red>Error: we detected placing single passengers in multiple staterooms. The stateroom was not booked. Click release to close this message.</font>";
				}

            /* -- End Single multiple pax check -- */



			print "<form name=\"MyForm\"><input type=\"hidden\" name=\"cancel\" value=\"$_POST[qb]\">
			<input type=\"image\" src=\"buttons/bt-cancel.png\" onclick=\"quickbook".$_POST['qb']."(this.form);return false;\"> <a href=\"javascript:void(0)\" title=\"You have reserved this space for 30 minutes. To checkout click Continue.\">reserved</a>
			</form>";
                  ?>            
                                <script>
                                function quickbook<?php echo $_POST['qb'];?>(myform) {
                                        $.post('quick_book.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#inv_<?php echo $_POST['qb'];?>").html(php_msg);
                                        });
                                }
                                </script>
                  <?php

		}

		if ($_POST['cancel'] != "") {
			// Clear the space
         $sql = "UPDATE `inventory` SET `passengerID` = '', `status` = 'avail', `sessionID` = '', `timestamp` = '', `donotmove_passenger` = '' WHERE `inventoryID` = '$_POST[cancel]'";
         $result = $reservation->new_mysql($sql);
			$_SESSION['found_before'] = "";
			print "
                  <form name=\"MyForm\"><input type=\"hidden\" name=\"qb\" value=\"$_POST[cancel]\">
                  <input type=\"image\" src=\"buttons/bt-booknow.png\" name=\"inventoryID_$_POST[cancel]\" id=\"inventoryID_$_POST[cancel]\" onclick=\"quickbook".$_POST['cancel']."(this.form);return false;\">
                  </form>";

						?>
                                <script>
                                function quickbook<?php echo $_POST['cancel'];?>(myform) {
                                        $.post('quick_book.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#inv_<?php echo $_POST['cancel'];?>").html(php_msg);
                                        });
                                }
                                </script>
						<?php

		}


	}
?>
