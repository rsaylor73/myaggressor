<?php
require "settings.php";
if ($_SESSION['uuname'] != "") {

	if (($_GET['action'] == "") and ($_POST['action'] == "")) {

		$year = date("Y");
		$year2 = $year - 95;
		for($x=$year2; $x < $year; $x++) {
			$birthday_year .= "<option>$x</option>";
		}

		$sql = "
		SELECT
			`charterID`,`passengerID`

		FROM
			`inventory`

		WHERE
			`inventory`.`inventoryID` = '$_GET[inventoryID]'

		";
		$result = $reservation->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			$charter = $row['charterID'];
			$contactID = $row['passengerID'];
		}

		$sex = $reservation->get_sex($charter,$_GET['bunk']);

                        // Override if proper profile is loaded
                        if ($contactID != "61531204") {
                           switch ($contactID) {
                              case "61531879": // male
                              $sex = "<img src=\"../resellers/icn-male.jpg\">";
										$sex2 = "<input type=\"hidden\" name=\"sex\" value=\"male\">";
                              break;

                              case "61531880": // female
                              $sex = "<img src=\"../resellers/icn-female.jpg\">";
										$sex2 = "<input type=\"hidden\" name=\"sex\" value=\"female\">";
                              break;
                           }
                        }


		print "
		<div id=\"lookup\" style=\"display:inline\">
		<form name=\"myform\">
		<input type=\"hidden\" name=\"inventoryID\" value=\"$_GET[inventoryID]\">
		<input type=\"hidden\" name=\"charter\" value=\"$charter\">
		<input type=\"hidden\" name=\"bunk\" value=\"$_GET[bunk]\">
		<input type=\"hidden\" name=\"action\" value=\"lookup\">
		$sex2
		<table width=\"550\" border=\"0\" cellpadding=3 cellspacing=0>
		<tr><td>Stateroom:</td><td>$_GET[bunk]</td></tr>
		<tr><td>First Name:</td><td><input type=\"text\" name=\"fname\" id=\"fname\" required size=40 placeholder=\"as it appears in guest passport\"></td></tr>
		<tr><td>Last Name:</td><td><input type=\"text\" name=\"lname\" id=\"lname\" required size=40 placeholder=\"as it appears in guest passport\"></td></tr>
		<tr><td>Birthday Month:</td><td><select name=\"birth_month\">
			<option value=\"01\">Jan</option>
			<option value=\"02\">Feb</option>
			<option value=\"03\">Mar</option>
			<option value=\"04\">Apr</option>
			<option value=\"05\">May</option>
			<option value=\"06\">Jun</option>
			<option value=\"07\">Jul</option>
			<option value=\"08\">Aug</option>
			<option value=\"09\">Sep</option>
			<option value=\"10\">Oct</option>
			<option value=\"11\">Nov</option>
			<option value=\"12\">Dec</option>
			</select>
		</td></tr>
		<tr><td>Birth Year:</td><td><select name=\"birth_year\" onchange=\"document.getElementById('ok$_GET[bunk]').style.display='inline'\"><option value=\"\">Select Year</option>$birthday_year</select></td></tr>
		<tr><td>Stateroom Gender:</td><td>$sex</td></tr>
		<tr id=\"ok$_GET[bunk]\" style=\"display:none\"><td colspan=2 align=right><input type=\"image\" src=\"buttons/bt-search.png\" onclick=\"if(validateForm() ) {lookup_guest(this.form);};return false;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
		<tr><td colspan=2><hr></td></tr>
		</table>
		</form>
		</div>";

		?>
                        <script>
            function validateForm() {

                 var x=document.getElementById('fname').value;
                 if (x==null || x=="") {
                         alert("First name is required.");
                         return false;
                 }

                 var x=document.getElementById('lname').value;
                 if (x==null || x=="") {
                         alert("Last name is required.");
                         return false;
                 }
						return true;

				}
                                function lookup_guest(myform) {
                                        $.get('add_guest.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#lookup").html(php_msg);
                                        });
                                }
                        </script>
		<?php


	}

	if ($_GET['action'] == "lookup") {
		print "<div id=\"lookup\">";

      $sex = $reservation->get_sex2($_GET['charter'],$_GET['bunk']);


      $sql = "
         SELECT
            `contacts`.`contactID`,
            `contacts`.`first`,
            `contacts`.`last`,
            `contacts`.`city`,
            `contacts`.`state`,
            `contacts`.`zip`,
				`contacts`.`sex`,
				`contacts`.`donotbook`

         FROM
            `contacts`

         WHERE
            SUBSTRING(`contacts`.`date_of_birth`, 1,6) = '$_GET[birth_year]$_GET[birth_month]'
            AND `contacts`.`first` = '$_GET[fname]'
            AND `contacts`.`last` = '$_GET[lname]'
				AND `contacts`.`sex` = '$_GET[sex]'

      ";
      $result = $reservation->new_mysql($sql);
      while ($row = $result->fetch_assoc()) {
         if ($s == "") {
            print "<table border=0 width=500 class=\"details-description\">
            <tr><td colspan=2>We found one or more records in our database, please select your city.</td></tr>";
            $s = "1";
         }
         $length = strlen($row['city']) / 2;
         $city = $reservation->mask($row['city'],'3');

/*
			if ($_GET['sex_bypass'] == "checked") {
				// clear checks
				$sex_stop = "0";
			} else {
				if ($sex == "male") {
					if ($row['sex'] == "female") {
						$sex_stop = "1";
					}
				}
				if ($sex == "female") {
					if ($row['sex'] == "male") {
						$sex_stop = "1";
					}
				}				
			}

*/

         print "<tr><td width=250>City: $city</td><td width=250 align=center>";
				if ($sex_stop != "1") {
					if ($row['donotbook'] == "") {

						print "
						<form name=\"myform\">
						<input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
						<input type=\"hidden\" name=\"sex_bypass\" value=\"$_GET[sex_bypass]\">
						<input type=\"hidden\" name=\"contactID\" value=\"$row[contactID]\">
						<input type=\"hidden\" name=\"inventoryID\" value=\"$_GET[inventoryID]\">
						<input type=\"hidden\" name=\"action\" value=\"get_guest_details\">
						<input type=\"hidden\" name=\"sex\" value=\"$_GET[sex]\">";
						print "
						<input type=\"image\" src=\"buttons/bt-select.png\" onclick=\"lookup_guest(this.form);return false;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</form></td></tr>
						<tr><td colspan=2><hr></td></tr>
						";
					} else {
						print "<font color=red>Please call Aggressor Fleet - unable to book guest.</font></td></tr>
						<tr><td colspan=2><hr></td></tr>";
					}
				
	         	$found = "1";
				} else {
					//print "<font color=red>Sorry, the guest's gender is a conflict on this cabin type. Press F5 to add another guest or call 1-800-932-6237 to speak with an Aggressor Fleet and Dancer Fleet agent.</font>";
					//die;
				}
      }

		?>
                        <script>
                                function lookup_guest(myform) {
                                        $.get('add_guest.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#lookup").html(php_msg);
                                        });
                                }
                        </script>
		<?php
		if ($found == "1") {
			print "</table>";
		}

		if ($found != "1") {
			print "<div id=\"add_guest\">
			<form name=\"myform\">
			<input type=\"hidden\" name=\"action\" value=\"add_new_guest_reservation\">
			<input type=\"hidden\" name=\"inventoryID\" value=\"$_GET[inventoryID]\">
			<input type=\"hidden\" name=\"fname\" value=\"$_GET[fname]\">
			<input type=\"hidden\" name=\"lname\" value=\"$_GET[lname]\">
			<input type=\"hidden\" name=\"birth_month\" value=\"$_GET[birth_month]\">
			<input type=\"hidden\" name=\"birth_year\" value=\"$_GET[birth_year]\">
			<input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
			<input type=\"hidden\" name=\"sex_bypass\" value=\"$_GET[sex_bypass]\">
			";
         print "<table border=0 width=500 class=\"details-description\">
			<tr><td>Stateroom:</td><td>$_GET[bunk]</td></tr>
			<tr><td>Guest:</td><td>$_GET[fname] $_GET[lname]</td></tr>
			<tr><td>Address:</td><td><input type=\"text\" name=\"address1\" id=\"address1\" size=40></td></tr>
			<tr><td>Address Line 2:</td><td><input type=\"text\" name=\"address2\" size=40></td></tr>
			<tr><td>City:</td><td><input type=\"text\" name=\"city\" id=\"city\" size=40></td></tr>";

			$state = "<option value=\"\">--Select--</option>";
			$sql2 = "SELECT * FROM `state` ORDER BY `state_abbr` ASC";
         $result2 = $reservation->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
				$state .= "<option>$row2[state_abbr]</option>";
			}

			$country = "<option value=\"\">--Select--</option><option value=\"2\">USA</option>";
			$sql2 = "SELECT * FROM `countries` ORDER BY `country` ASC";
			$result2 = $reservation->new_mysql($sql2);
			while ($row2 = $result2->fetch_assoc()) {
				$country .= "<option value=\"$row2[countryID]\">$row2[country]</option>";
			}

			for ($y=1; $y < 32; $y++) {
				if ($y < 10) {
					$days .= "<option value=\"0$y\">$y</option>";
				} else {
					$days .= "<option>$y</option>";
				}
			}

			print "<tr><td>Country:</td><td><select name=\"countryID\" id=\"countryID\" onchange=\"get_country()\">$country</select></td></tr>
			<tr id=\"state\" style=\"display:none\"><td>State:</td><td><select name=\"state\">$state</select></td></tr>
			<tr id=\"province\" style=\"display:none\"><td>Province:</td><td><input type=\"text\" name=\"province\" size=40></td></tr>
			<tr><td>Zip:</td><td><input type=\"text\" name=\"zip\" id=\"zip\" size=40></td></tr>
			<tr><td>Email:</td><td><input type=\"text\" name=\"email\" id=\"email\" size=40></td></tr>
			";
			if ($_GET['sex'] == "male") {
				print "<tr><td>Gender:</td><td><input type=\"radio\" name=\"sex\" id=\"sex\" value=\"male\" checked>Male <input type=\"radio\" name=\"sex\" id=\"sex\" value=\"female\" disabled>Female</td></tr>";
			} else {
            print "<tr><td>Gender:</td><td><input type=\"radio\" name=\"sex\" id=\"sex\" value=\"male\" disabled>Male <input type=\"radio\" name=\"sex\" id=\"sex\" value=\"female\" checked>Female</td></tr>";
			}

			switch ($_GET['birth_month']) {
				case "01":
				case "1":
				$month = "Jan";
				break;

            case "02":
            case "2":
            $month = "Feb";
            break;

            case "03":
            case "3":
            $month = "Mar";
            break;

            case "04":
            case "4":
            $month = "Apr";
            break;

            case "05":
            case "5":
            $month = "May";
            break;

            case "06":
            case "6":
            $month = "Jun";
            break;

            case "07":
            case "7":
            $month = "Jul";
            break;

            case "08":
            case "8":
            $month = "Aug";
            break;

            case "09":
            case "9":
            $month = "Sep";
            break;

            case "10":
            $month = "Oct";
            break;

            case "11":
            $month = "Nov";
            break;

            case "12":
            $month = "Dec";
            break;
	
			}

			print "
			<tr><td>Birthday:</td><td><select name=\"birth_day\" id=\"birth_day\">$days</select></td></tr>
			<tr><td></td><td><input type=\"image\" src=\"buttons/bt-save.png\" onclick=\"if(validateForm() ) {add_guest(this.form);};return false;\"></td></tr>
			";

			?>
			<script>
			function get_country() {
				if (document.getElementById('countryID').value == "2") {
					document.getElementById('state').style.display='table-row';
					document.getElementById('province').style.display='none';
				} else {
               document.getElementById('state').style.display='none';
               document.getElementById('province').style.display='table-row';
				}
			}


            function validateForm() {

						var radios = document.getElementsByName('sex')
						for (var i = 0; i < radios.length; i++) {
							if (radios[i].checked) {
								var ok = "ok";
							}
						}
						if (ok != "ok") {
							alert('Gender is required.');
							return false;
						}


                 var x=document.getElementById('address1').value;
                 if (x==null || x=="") {
                         alert("Address is required.");
                         return false;
                 }

                 var x=document.getElementById('city').value;
                 if (x==null || x=="") {
                         alert("City is required.");
                         return false;
                 }

                 var x=document.getElementById('zip').value;
                 if (x==null || x=="") {
                         alert("Zip is required.");
                         return false;
                 }

                 var x=document.getElementById('email').value;
                 if (x==null || x=="") {
                         alert("Email is required.");
                         return false;
                 }

                  return true;
            }

            function add_guest(myform) {
            	$.get('add_guest.php',
               $(myform).serialize(),
               function(php_msg) {
               	$("#add_guest").html(php_msg);
               });
            }

			</script>

			<?php

			print "<tr><td colspan=2><hr></td></tr>";
			print "</table></form></div>";
		}
		print "</div>";
	}


	if ($_GET['action'] == "add_new_guest_reservation") {

		$today = date("Ymd");
		$dob = $_GET['birth_year'].$_GET['birth_month'].$_GET['birth_day'];
		$sql = "INSERT INTO `contacts` (`first`,`last`,`address1`,`address2`,`city`,`state`,`province`,`zip`,`countryID`,`email`,`date_created`,`date_of_birth`,`sex`) VALUES
		('$_GET[fname]','$_GET[lname]','$_GET[address1]','$_GET[address2]','$_GET[city]','$_GET[state]','$_GET[province]','$_GET[zip]','$_GET[countryID]','$_GET[email]','$today','$dob','$_GET[sex]')";
		$result = $reservation->new_mysql($sql);

		// get last ID
		$contactID = $reservation->linkID->insert_id;

		// UPDATE Inventory
      $login_key = md5(uniqid(rand(), true));

		$sql2 = "UPDATE `inventory` SET `passengerID` = '$contactID', `login_key` = '$login_key' WHERE `inventoryID` = '$_GET[inventoryID]'";
		$result2 = $reservation->new_mysql($sql2);

		// generate and send gis

			$first = $_GET['fname'];
			$last = $_GET['lname'];
			$email = $_GET['email'];

         // get charter ID
         $sql2 = "SELECT `charterID`,`reservationID` FROM `inventory` WHERE `inventoryID` = '$_GET[inventoryID]'";
         $result2 = $reservation->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
            $charterID = $row2['charterID'];
            $reservationID = $row2['reservationID'];
         }

         // get boatname
         $sql2 = "
         SELECT 
            `boats`.`name`,
            `charters`.`start_date`

         FROM
            `charters`,`boats`

         WHERE
            `charters`.`charterID` = '$charterID'
            AND `charters`.`boatID` = `boats`.`boatID`
         ";
         $result2 = $reservation->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
            $name = $row2['name'];
            $start_date = $row2['start_date'];
         }
         
         // kbyg 
         $sql2 = "
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
            `reserve`.`inventory`.`inventoryID` = '$_GET[inventoryID]'
            AND `reserve`.`inventory`.`passengerID` = `reserve`.`contacts`.`contactID`
            AND `reserve`.`inventory`.`charterID` = `reserve`.`charters`.`charterID`
            AND `reserve`.`charters`.`boatID` = `reserve`.`boats`.`boatID`

            AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`kbyg`.`boatID`
            AND `reserve`.`charters`.`destinationID` = `af_df_unified2`.`kbyg`.`destinationID`
         ";
         $result2 = $reservation->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
            $fileName = $row2['fileName'];
				$reservationist_email = $row2['reservationist_email'];
				$login_key = $row2['login_key'];
         }

         // create GIS profile
         $sql2 = "SELECT * FROM `guestform_status` WHERE `passengerID` = '$contactID' AND `charterID` = '$charterID'";
         $result2 = $reservation->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
            $err = "1";
         }
         if ($err != "1") {
            $sql2 = "INSERT INTO `guestform_status` (`passengerID`,`charterID`) VALUES ('$contactID','$charterID')";
            $result2 = $reservation->new_mysql($sql2);
         }  
         
         $sql2 = "UPDATE `inventory` SET `passengerID` = '$contactID', `login_key` = '$login_key' WHERE `inventoryID` = '$_GET[inventoryID]'";
         $result2 = $reservation->new_mysql($sql2);
         if ($result2 == "TRUE") {
            // send GIS
            $_URL = "https://gis.liveaboardfleet.com/gis/index.php/";
            $guest_url = $_URL.$contactID."/".$reservationID."/".$charterID."/".$login_key;


				// Add to notes
				$note_date = date("Ymd");
				$sql4 = "INSERT INTO `notes` (`note_date`,`table_ref`,`fkey`,`user_id`,`title`,`note`) 
				VALUES 
				('$note_date','inventory','$_GET[inventoryID]','CRS','GIS Link','Link sent to (guest contact) $first $last - <a href=\"$guest_url\" target=_blank>GO TO GIS PROFILE</a>')";

				$result4 = $reservation->new_mysql($sql4);
   
            $guest_name = "$first $last";
            $email_subject = 'Guest Profile for '.$guest_name.' - Embark Date '.date("M-d-Y",strtotime($start_date)).' (#'.$reservationID.' )' . $name;
            $kbyg = "<a href=\"http://www.liveaboardfleet.net/aggressor/upload/$fileName\" target=_blank>Know Before You Go</a>";

				// legacy
			   $server2   = "mysql";
			   $username2 = "root";
			   $password2 = "F7m9dSz0";
			   $database3 = "reserve";
			   $database7 = "af_df_unified2";
		      $af = mysql_connect($server2,$username2,$password2);
		      $unify = mysql_connect($server2,$username2,$password2,true);
		      mysql_select_db($database3, $af);
		      mysql_select_db($database7, $unify);
                  // get direct or reseller
                  $sql_who = "
                  SELECT
                     `reseller_agents`.`resellerID`

                  FROM
                     `reservations`,`reseller_agents`

                  WHERE
                     `reservations`.`reservationID` = '$_POST[reservationID]'
                     AND `reservations`.`reseller_agentID` = `reseller_agents`.`reseller_agentID`

                  ";
                  $result_who = mysql_query($sql_who,$af);
                  for ($y=0; $y < mysql_num_rows($result_who); $y++) {
                     $row_who = mysql_fetch_assoc($result_who);
                     $resellerID = $row_who['resellerID'];
                  }
                  if ($resellerID == "19") {
                     $type = "CRS";
                  } else {
                     $type = "Reseller";
                  }
                  $sql_msg = "SELECT `message` FROM `gis_email` WHERE `type` = '$type'";
                  $result_msg = mysql_query($sql_msg, $unify);
                  for ($m=0; $m < mysql_num_rows($result_msg); $m++) {
                     $row_msg = mysql_fetch_assoc($result_msg);
                     $new_msg = str_replace("#kbyg#",$kbyg,$row_msg['message']);
                     $new_msg = str_replace("#guest_url#",$guest_url,$new_msg);

                  }
				// end legacy


            
            $email_message = "Dear $guest_name,<br><br>
				$new_msg
         ";
         $sendemail = mail($email,$email_subject,$email_message,$headers);
		}
		// re-paint the screen

		print "<br>The guest was added. Loading...<br>\n";
		?>
		<script>
		setTimeout(function()
      {
      window.location.replace('<?=$_SESSION['guest_uri']?>')
      }
      ,2000);

		</script>
		<?php
	}

	if ($_GET['action'] == "get_guest_details") {
		print "<div id=\"lookup\">";
		$sql = "
		SELECT
			`contacts`.*,
			`countries`.`country`

		FROM
			`contacts`,`countries`

		WHERE
			`contacts`.`contactID` = '$_GET[contactID]'
			AND `contacts`.`countryID` = `countries`.`countryID`

		";
		$result = $reservation->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			print "
			<form name=\"myform\">
			<input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
			<input type=\"hidden\" name=\"sex_bypass\" value=\"$_GET[sex_bypass]\">
			<input type=\"hidden\" name=\"action\" value=\"assign_guest\">
			<input type=\"hidden\" name=\"inventoryID\" value=\"$_GET[inventoryID]\">
			<input type=\"hidden\" name=\"contactID\" value=\"$row[contactID]\">
			<table border=0 width=\"500\">
			<tr><td>First Name:</td><td>$row[first]</td></tr>
			<tr><td>Last Name:</td><td>$row[last]</td></tr>
			<tr><td>Address:</td><td><input type=\"text\" name=\"address1\" value=\"$row[address1]\" size=40></td></tr>
			<tr><td>Address 2:</td><td><input type=\"text\" name=\"address2\" value=\"$row[address2]\" size=40></td></tr>
			<tr><td>City:</td><td><input type=\"text\" name=\"city\" value=\"$row[city]\" size=40></td></tr>";
			if ($row['countryID'] == "2") {
				$sql2 = "SELECT * FROM `state` ORDER BY `state_abbr` ASC";
				$result2 = $reservation->new_mysql($sql2);
				while($row2 = $result2->fetch_assoc()) {
					if ($row2['state_abbr'] == $row['state']) {
						$state .= "<option selected>$row2[state_abbr]</option>";
					} else {
						$state .= "<option>$row2[state_abbr]</option>";
					}
				}
				print "<tr><td>State:</td><td><select name=\"state\">$state</select></td></tr>";
			} else {
				print "<tr><td>Province:</td><td><input type=\"text\" name=\"province\" value=\"$row[province]\" size=40></td></tr>\n";
			}
			$sql2 = "SELECT * FROM `countries` ORDER BY `country` ASC";
			$result2 = $reservation->new_mysql($sql2);
			while ($row2 = $result2->fetch_assoc()) {
				if ($row2['countryID'] == $row['countryID']) {
					$country .= "<option value=\"$row2[countryID]\" selected>$row2[country]</option>";
				} else {
					$country .= "<option value=\"$row2[countryID]\">$row2[country]</option>";
				}
			}
			if ($row['sex'] == "male") {
				$c1 = "checked";
			}
			if ($row['sex'] == "female") {
				$c2 = "checked";
			}
			if (($c1 == "") && ($c2 == "")) {
				$c1 = "checked";
				$c3 = "<font color=red>Please select a sex</font>";
			}
			print "<tr><td>Country:</td><td><select name=\"countryID\">$country</select></td></tr>
			<tr><td>Zip:</td><td><input type=\"text\" name=\"zip\" value=\"$row[zip]\" size=40></td></tr>
			<tr><td>Email:</td><td><input type=\"text\" name=\"email\" value=\"$row[email]\" size=40></td></tr>
			";
         if ($_GET['sex'] == "male") {
            print "<tr><td>Gender:</td><td><input type=\"radio\" name=\"sex\" id=\"sex\" value=\"male\" checked>Male <input type=\"radio\" name=\"sex\" id=\"sex\" value=\"female\" disabled>Female</td></tr>";
         } else {
            print "<tr><td>Gender:</td><td><input type=\"radio\" name=\"sex\" id=\"sex\" value=\"male\" disabled>Male <input type=\"radio\" name=\"sex\" id=\"sex\" value=\"female\" checked>Female</td></tr>";
         }
			print "
			<tr><td colspan=2 align=right><input type=\"image\" src=\"buttons/bt-save.png\" onclick=\"add_guest(this.form);return false;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
			<tr><td colspan=2><hr></td></tr>
			</table>
			</form>
			";

			?>
                        <script>
                                function add_guest(myform) {
                                        $.get('add_guest.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
														if (php_msg == "fail") {
															alert('The contact profile failed to update. Please check your details and be sure not to use any symbols and try again.');
														}
														if (php_msg == "S") {
															alert('The gendor specified does not match the bunk type. If you are booking a couple click the couple checkbox then click Add Guest.');
														}
														if (php_msg == "ok") {
																$("#lookup").html('<span class="details-description"><br><font color=green>The guest was updated and added to your reservation. Loading please wait...</font><br></span>');
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



			print "</div>";
		}

	}

	if ($_GET['action'] == "assign_guest") {

		// Update Guest
		$sql = "UPDATE `contacts` SET `address1` = '$_GET[address1]', `address2` = '$_GET[address2]', `city` = '$_GET[city]', `state` = '$_GET[state]', `province` = '$_GET[province]', `countryID` = '$_GET[countryID]', `email` = '$_GET[email]',
		`sex` = '$_GET[sex]', `zip` = '$_GET[zip]' WHERE `contactID` = '$_GET[contactID]'";
		$result = $reservation->new_mysql($sql);
		if ($result == "TRUE") {

			// check sex
			$sql2 = "
			SELECT
				`inventory`.`charterID`,
				`inventory`.`bunk`

			FROM
				`inventory`

			WHERE
				`inventory`.`inventoryID` = '$_GET[inventoryID]'
			";
			$result2 = $reservation->new_mysql($sql2);
			while ($row2 = $result2->fetch_assoc()) {
		      $sex = $reservation->get_sex2($row2['charterID'],$row2['bunk']);
			}

			$stop_sex = "0";
			if ($_GET['sex_bypass'] == "checked") {
				// clear checks
				$stop_sex = "0";
			} else {
				if ($sex == "male") {
					if ($_GET['sex'] == "female") {
						$stop_sex = "1";
					}
				}
				if ($sex == "female") {
					if ($_GET['sex'] == "male") {
						$stop_sex = "1";
					}
				}
			}

			if ($stop_sex == "1") {
				print "S";
				die;
			}

	      // Assign Guest
		   $login_key = md5(uniqid(rand(), true));

			// get contact name
			$sql2 = "SELECT `first`,`last`,`email` FROM `contacts` WHERE `contactID` = '$_GET[contactID]'";
         $result2 = $reservation->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
				$first = $row2['first'];
				$last = $row2['last'];
				$email = $row2['email'];
			}

			// get charter ID
			$sql2 = "SELECT `charterID`,`reservationID` FROM `inventory` WHERE `inventoryID` = '$_GET[inventoryID]'";
         $result2 = $reservation->new_mysql($sql2);
			while ($row2 = $result2->fetch_assoc()) {
				$charterID = $row2['charterID'];
				$reservationID = $row2['reservationID'];
			}

			// get boatname
			$sql2 = "
			SELECT 
				`boats`.`name`,
				`charters`.`start_date`

			FROM
				`charters`,`boats`

			WHERE
				`charters`.`charterID` = '$charterID'
				AND `charters`.`boatID` = `boats`.`boatID`
			";
         $result2 = $reservation->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
				$name = $row2['name'];
				$start_date = $row2['start_date'];
			}

			// kbyg
		   $sql2 = "
		   SELECT 
		      `reserve`.`inventory`.`inventoryID`,
		      `reserve`.`inventory`.`charterID`,
		      `reserve`.`inventory`.`passengerID`,
		      `reserve`.`inventory`.`reservationID`,
		      `reserve`.`contacts`.`first`,
		      `reserve`.`contacts`.`last`,
		      `reserve`.`contacts`.`email`,
		      `reserve`.`contacts`.`contactID`,
		      `reserve`.`boats`.`fleet`,
		      `reserve`.`boats`.`name`,
		      `af_df_unified2`.`kbyg`.`fileName`,
		      `reserve`.`charters`.`start_date`
		   FROM
		      `reserve`.`inventory`,
		      `reserve`.`contacts`,
		      `reserve`.`charters`,
		      `reserve`.`boats`,
		      `af_df_unified2`.`kbyg`

		   WHERE
		      `reserve`.`inventory`.`inventoryID` = '$_GET[inventoryID]'
		      AND `reserve`.`inventory`.`passengerID` = `reserve`.`contacts`.`contactID`
		      AND `reserve`.`inventory`.`charterID` = `reserve`.`charters`.`charterID`
		      AND `reserve`.`charters`.`boatID` = `reserve`.`boats`.`boatID`

		      AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`kbyg`.`boatID`
		      AND `reserve`.`charters`.`destinationID` = `af_df_unified2`.`kbyg`.`destinationID`
		   ";
         $result2 = $reservation->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
				$fileName = $row2['fileName'];
			}


			// create GIS profile
			$sql2 = "SELECT * FROM `guestform_status` WHERE `passengerID` = '$_GET[contactID]' AND `charterID` = '$charterID'";
         $result2 = $reservation->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
				$err = "1";
			}
			if ($err != "1") {
				$sql2 = "INSERT INTO `guestform_status` (`passengerID`,`charterID`) VALUES ('$_GET[contactID]','$charterID')";
				$result2 = $reservation->new_mysql($sql2);
			}

   	   $sql2 = "UPDATE `inventory` SET `passengerID` = '$_GET[contactID]', `login_key` = '$login_key' WHERE `inventoryID` = '$_GET[inventoryID]'";
      	$result2 = $reservation->new_mysql($sql2);
	      if ($result2 == "TRUE") {
				// send GIS
	         $_URL = "https://gis.liveaboardfleet.com/gis/index.php/";
   	      $guest_url = $_URL.$_GET['contactID']."/".$reservationID."/".$charterID."/".$login_key;

	         $guest_name = "$first $last";
   	      $email_subject = 'Guest Profile for '.$guest_name.' - Embark Date '.date("M-d-Y",strtotime($start_date)).' (#'.$reservationID.') ' . $name;
	         $kbyg = "<a href=\"http://www.liveaboardfleet.net/aggressor/upload/$fileName\" target=_blank>Know Before You Go</a>";

            // Add to notes
            $note_date = date("Ymd");
            $sql4 = "INSERT INTO `notes` (`note_date`,`table_ref`,`fkey`,`user_id`,`title`,`note`) 
            VALUES  
            ('$note_date','inventory','$_GET[inventoryID]','CRS','GIS Link','Link sent to (guest contact) $first $last - <a href=\"$guest_url\" target=_blank>GO TO GIS PROFILE</a>')";
            
            $result4 = $reservation->new_mysql($sql4);

            // legacy
            $server2   = "mysql";
            $username2 = "root";
            $password2 = "F7m9dSz0";
            $database3 = "reserve";
            $database7 = "af_df_unified2";
            $af = mysql_connect($server2,$username2,$password2);
            $unify = mysql_connect($server2,$username2,$password2,true);
            mysql_select_db($database3, $af);
            mysql_select_db($database7, $unify);
                  // get direct or reseller
                  $sql_who = "
                  SELECT
                     `reseller_agents`.`resellerID`

                  FROM
                     `reservations`,`reseller_agents`

                  WHERE
                     `reservations`.`reservationID` = '$_POST[reservationID]'
                     AND `reservations`.`reseller_agentID` = `reseller_agents`.`reseller_agentID`

                  ";
                  $result_who = mysql_query($sql_who,$af);
                  for ($y=0; $y < mysql_num_rows($result_who); $y++) {
                     $row_who = mysql_fetch_assoc($result_who);
                     $resellerID = $row_who['resellerID'];
                  }
                  if ($resellerID == "19") {
                     $type = "CRS";
                  } else {
                     $type = "Reseller";
                  }
                  $sql_msg = "SELECT `message` FROM `gis_email` WHERE `type` = '$type'";
                  $result_msg = mysql_query($sql_msg, $unify);
                  for ($m=0; $m < mysql_num_rows($result_msg); $m++) {
                     $row_msg = mysql_fetch_assoc($result_msg);
                     $new_msg = str_replace("#kbyg#",$kbyg,$row_msg['message']);
                     $new_msg = str_replace("#guest_url#",$guest_url,$new_msg);

                  }
            // end legacy



		      $email_message = "Dear $guest_name,<br><br>
				$new_msg
	      ";
         $sendemail = mail($email,$email_subject,$email_message,$headers);





   	      print "ok";
      	} else {
         	print "fail";
	      }
		} else {
			print "fail";
		}


	}

}
?>
