<?php

class Reservation {

        public $linkID;

        function __construct($linkID){ $this->linkID = $linkID; }

        public function new_mysql($sql) {

                $result = $this->linkID->query($sql) or die($this->linkID->error.__LINE__);
                return $result;
        }

		public function get_boats() {

			$options = "";
			$boats = array();
			foreach ($_GET['boats'] as $boat) {
				$boats[$boat] = $boat;
			}
			$sql = "SELECT * FROM `reserve`.`boats` WHERE `reserve`.`boats`.`status` = 'Active' AND `reserve`.`boats`.`boatID` NOT IN ('39') ORDER BY `reserve`.`boats`.`name` ASC";
			$result = $this->new_mysql($sql);
			while($row = $result->fetch_assoc()) {
				$i = $row['boatID'];
				if ($boats[$i] == $row['boatID']) {
					$checked = "selected";
				} else {
					$checked = "";
				}
				$options .= "<option value=\"$row[boatID]\" $checked>$row[name]</option>";
			}
			return $options;
		}

		public function check_login() {

			if (($_SESSION['uuname'] != "") && ($_SESSION['uupass'] != "")) {
				$sql = "SELECT * FROM `contacts` WHERE `uuname` = '$_SESSION[uuname]' AND `uupass` = '$_SESSION[uupass]'";
				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
					$status = "TRUE";
				
				}
				if ($status != "TRUE") {
					$status = "FALSE";
				}
			} else {
				$status = "FALSE";
			}
			return $status;
		}

		public function return_url_parts() {
				if (is_array($_GET['boats'])) {
	            foreach ($_GET['boats'] as $boat) {
   	            $this_boats .= "&boats[]=$boat";
      	      }
				}

            $name = urlencode($_GET['name']);
            $varU = "charter=$_GET[charter]&type=$_GET[type]&name=$name&start_date=$_GET[start_date]&end_date=$_GET[end_date]&passengers=$_GET[passengers]$this_boats";
				return $varU;

		}

		public function login_screen($uri) {
				$_SESSION['uri'] = $uri;
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
         				   	      <tr>
			            	   	      <td width=\"263\" class=\"details-top\">&nbsp;</td>
			               	   	   <td width=\"303\" class=\"details-top\">&nbsp;</td>
			                        	<td width=\"283\" align=\"right\" class=\"details-top\">&nbsp;</td>
					                  </tr>
				   	            </table> 
					            </td>
								</tr>
	   	   	      </table>
		       	     <div style=\"clear:both;\"></div>
				";

            print "<div id=\"result_pos3\">";

				$varU = $this->return_url_parts();

				print "
					<br><span class=\"result-title-text\">Login</span><br><br>
					<span class=\"details-description\">


					Welcome to the Aggressor Fleet online reservation system. To check availability or make an online reservation you are required to have an account. Please <b>Login</b> or if you are new to Aggressor Fleet click on the <b>Register</b> button to create your online account.   


					</span><br><br>
					<div id=\"login-scr\" align=\"center\">
					<form name=\"myform\" id=\"myform\">
					<table border=0 width=\"800\" cellpadding=0 cellspacing=3>
						<tr>
							<td valign=top width=\"300\"><img src=\"30Year-Blue-300px.png\" width=\"250\"></td>
							<td valign=top width=\"450\">
								<table border=\"0\" width=\"500\" cellpadding=\"3\" class=\"details-description\">
									<input type=\"hidden\" name=\"varU\" value=\"$varU\">
									<tr><td>Username:</td><td><input type=\"text\" name=\"uuname\" size=40></td></tr>
									<tr><td>Password:</td><td><input type=\"password\" name=\"uupass\" size=40></td></tr>
									<tr><td colspan=2 align=center><input type=\"image\" src=\"buttons/bt-login.png\" onclick=\"login(this.form);return false;\">&nbsp;&nbsp;


</td></tr>
									<tr><td align=center colspan=2><a href=\"javascript:void(0)\" onclick=\"forgot_password(this.form)\">Forgot Password</a>&nbsp;&nbsp;

									<a href=\"javascript:void(0)\" onclick=\"alert('If you have forgotten your user name, click <register> and you will be able to reset your account after validating your identity.')\">Forgot Username</a>
									</td></tr>

									<tr><td colspan=2 align=center><br>If you are new to Aggressor Fleet<br> please create an online account.<br><br>
<a href=\"javascript:void(0)\" onclick=\"location.href='register.php?$varU'\"><img src=\"buttons/bt-register.png\" border=0></a>
									</td></tr>

								</table>
							</td>
						</tr>
					</table>
					</form>
					</div>
				";

				?>

                                <script>
											function forgot_password(myform) {
                                        $.get('forgot_password.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#login-scr").html(php_msg);
                                        });
                                 }

                                function login(myform) {
                                        $.get('login.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
													 	if (php_msg.substring(0,5) == "https") {
															$("#login-scr").html('<span class="details-description"><br><font color=green>Login successfull. Loading please wait...</font><br></span>');
															setTimeout(function()
																{
                                          		window.location.replace(php_msg)
																}
															,2000);
                                          } else {
                                          	$("#login-scr").html(php_msg);
														}
                                        });
                                }
                                </script>


				<?php

				print "</div></div></div></div>";
		}

		public function mask ( $str, $start = 0, $length = null ) {
			$mask = preg_replace ( "/\S/", "*", $str );
			if( is_null ( $length )) {
				$mask = substr ( $mask, $start );
				$str = substr_replace ( $str, $mask, $start );
			}else{
				$mask = substr ( $mask, $start, $length );
				$str = substr_replace ( $str, $mask, $start, $length );
			}
			return $str;
		}

		public function register() {
            $varU = $this->return_url_parts();

            print "
            <div id=\"result_wrapper\">
               <div id=\"result_pos1\">
                  <div id=\"result_pos2\">
                     <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\">
                        <tr>
                           <td>
                              <img src=\"../ResImages/generic-DTW.jpg\" width=\"850\">
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">
                                 <tr>
                                    <td width=\"263\" class=\"details-top\">&nbsp;</td>
                                    <td width=\"303\" class=\"details-top\">&nbsp;</td>
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
            print "
               <br><span class=\"result-title-text\">Online Reservation System</span><br><br><span class=\"details-description\">Please type in your contact details to create your online account.</span><br><br>

				";
				?>
	         <script>
   	      function validateForm() {

               var inputString = document.forms["myform"]["fname"].value;
               var findme = "@";

               if ( inputString.indexOf(findme) > -1 ) {
                  alert('The first name field is not an email field');
                  return false;
               }

               var inputString2 = document.forms["myform"]["lname"].value;
               var findme2 = "@";

               if ( inputString2.indexOf(findme2) > -1 ) {
                  alert('The last name field is not an email field');
                  return false;
               }

                 var x=document.forms["myform"]["fname"].value;
                 if (x==null || x=="") {
                         alert("First Name is required.");
                         return false;
                 }
                 var x=document.forms["myform"]["lname"].value;
                 if (x==null || x=="") {
                         alert("Last Name is required.");
                         return false;
                 }
                 var x=document.forms["myform"]["birth_month"].value;
                 if (x==null || x=="") {
                         alert("Birth Month is required.");
                         return false;
                 }
                 var x=document.forms["myform"]["birth_year"].value;
                 if (x==null || x=="") {
                         alert("Birth Year is required.");
                         return false;
                 }
						return true;
				}
				</script>


				<?php
				print "
            <form name=\"myform\" id=\"myform\">
               <table border=0 width=700>
                  <tr><td width=50>&nbsp;</td><td colspan=2 class=\"view_details-description\">Contact Name (as it appears on your passport)</td></tr>

                  <tr><td></td>
                  <td width=325><input type=\"text\" name=\"fname\" id=\"fname\" size=\"40\" placeholder=\"First Name\"></td><td width=325><input type=\"text\" name=\"lname\" id=\"lname\" size=40 placeholder=\"Last Name\"></td></tr>

                  <tr><td></td><td class=\"view_details-description\">Birth Month</td><td class=\"view_details-description\">Birth Year</td></tr>
                  
                  <tr><tr><td></td><td><select name=\"birth_month\" id=\"birth_month\">
                     <option value=\"\">Birth Month</option>
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
               </select></td><td><input type=\"text\" name=\"birth_year\" id=\"birth_year\" placeholder=\"1987\"></td></tr>
					</table>
					<div id=\"register\">
					<table border=0 width=700>
					<input type=\"hidden\" name=\"varU\" value=\"$varU\">
					<tr><td width=50>&nbsp;</td><td width=325>&nbsp;</td><td width=325><br><input type=\"image\" src=\"buttons/bt-continue.png\" onclick=\"if(validateForm() ) {lookup(this.form);};return false;\"></td></tr>
					</table>
					";
					?>

                                <script>
                                function lookup(myform) {
                                        $.get('lookup.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#register").html(php_msg);
                                        });
                                }
                                </script>

					<?php
					print "
               </div>
            </form>

				</div></div></div></div>";


		}

      public function register_part2() {
            print "
            <div id=\"result_wrapper\">
               <div id=\"result_pos1\">
                  <div id=\"result_pos2\">
                     <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\">
                        <tr>
                           <td>
                              <img src=\"../ResImages/generic-DTW.jpg\" width=\"850\">
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">
                                 <tr>
                                    <td width=\"263\" class=\"details-top\">&nbsp;</td>
                                    <td width=\"303\" class=\"details-top\">&nbsp;</td>
                                    <td width=\"283\" align=\"right\" class=\"details-top\">&nbsp;</td>
                                 </tr>
                              </table> 
                           </td>
                        </tr>
                     </table>
                    <div style=\"clear:both;\"></div>
            ";

            print "<div id=\"result_pos3\">";

				$sql = "SELECT * FROM `contacts` WHERE `contactID` = '$_GET[c]'";
				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {

					// find the country
					$sql2 = "
					SELECT
						`contacts`.`countryID`

					FROM
						`contacts`,`countries`

					WHERE
						`contacts`.`contactID` = '$_GET[c]'
						AND `contacts`.`countryID` = `countries`.`countryID`
					";

					$result2 = $this->new_mysql($sql2);
					while ($row2 = $result2->fetch_assoc()) {
						switch($row2['countryID']) {
							case "2":
							print "
			            <form name=\"MyForm\" id=\"MyForm\">
							<br><br>
							<div id=\"human\">

                     <table border=0 width=700 class=\"details-description\">
                     <tr><td width=50>&nbsp;</td><td colspan=2>Welcome back $row[first] $row[last]. Just a few more questions before we can create your online account. But first, we need to verify you are a human.<br><br>
							(International) Enter + then your area code and phone number. Do not include dashes. (Example +3459495551) It will automatically enter the 011 in front of the telephone number. <br>
							(US) type in +1 and the rest of your number. Example: +17067377687
							</td></tr>
                     </table>
							<br>
							<table border=\"0\" width=700 class=\"details-description\">
							<tr><td width=50></td><td colspan=2><b>Verify via SMS (Text)</b><br>We will now text a verification code to your cell phone. You will be prompted to enter the verification code on the next page.</td></tr>
							<input type=\"hidden\" name=\"contactID\" value=\"$_GET[c]\">
							<tr><td width=50>&nbsp;</td><td>Please type in your cell phone: <input type=\"text\" name=\"cell\" id=\"cell\"> <input type=\"image\" src=\"buttons/bt-text.png\" onclick=\"sendcode(this.form);return false;\"><br><br></td></tr>

      		         <tr><td width=50></td><td colspan=2><b><br>Verify via Phone (voice)</b><br>We will now call with a verification code to your phone. You will be prompted to enter the verification code on the next page.</td></tr>
            		   <tr><td width=50>&nbsp;</td><td>Please type in your phone number: <input type=\"text\" name=\"phone\" id=\"phone\"> <input type=\"image\" src=\"buttons/bt-call.png\" onclick=\"sendcodephone(this.form);return false;\"></td></tr>
							</table>
							";
							?>
                                <script>
                                function sendcode(myform) {
                                        $.get('sendcode.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#human").html(php_msg);
                                        });
                                }
                                </script>

                                <script>
                                function sendcodephone(myform) {
                                        $.get('sendcodephone.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#human").html(php_msg);
                                        });
                                }
                                </script>
							<?php
							print "
							</form>
							</div>";
							break;

							default:
								// international
                     print "
                     <form name=\"MyForm\" id=\"MyForm\">
                     <br><br>
                     <table border=0 width=700 class=\"details-description\">
                     <tr><td width=50>&nbsp;</td><td colspan=2>Welcome back $row[first] $row[last]. Just a few more questions before we can create your online account. But first, we need to verify you are a human.</td></tr>
                     </table>
                     <br><br>
                     <div id=\"human\">
                     <table border=\"0\" width=700 class=\"details-description\">
                     <tr><td width=50></td><td colspan=2><b>Verify via SMS (Text)</b><br>We will now send a verification code to your cell phone.</td></tr>
                     <input type=\"hidden\" name=\"contactID\" value=\"$_GET[c]\">
                     <tr><td width=50>&nbsp;</td><td>Please type in your cell phone: <input type=\"text\" name=\"cell\" id=\"cell\"> <input type=\"image\" src=\"buttons/bt-text.png\" onclick=\"sendcode(this.form);return false;\"></td></tr>

                     </table>
                     ";
                     ?>
                                <script>
                                function sendcode(myform) {
                                        $.get('sendcode.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#human").html(php_msg);
                                        });
                                }
                                </script>

                     <?php
                     print "
                     </form>
                     </div>";


							break;
						}
					}
				}
				print "<br><span class=\"details-description\"><center>Standard messaging and data rates may apply.</center></span>";
				print "</div>";
				print "</div></div></div>";

		}
		public function get_specials($boatID,$start,$end) {

			$sql = "
			SELECT
				`af_df_unified2`.`specials`.*

			FROM
				`af_df_unified2`.`destinations`,
				`af_df_unified2`.`specials_destinations`,
				`af_df_unified2`.`specials`

			WHERE
				`af_df_unified2`.`destinations`.`boatID` = '$boatID'
				AND `af_df_unified2`.`destinations`.`id` = `af_df_unified2`.`specials_destinations`.`destination_id`
				AND `af_df_unified2`.`specials_destinations`.`special_id` = `af_df_unified2`.`specials`.`id`
				#AND `af_df_unified2`.`specials`.`start_date` BETWEEN '$start' AND '$end'

			";

			$result = $this->new_mysql($sql);
         while($row = $result->fetch_assoc()) {
				if (($start >= $row['start_date']) or ($end <= $row['end_date'])) {
					$counter++;
				}
			}
			return $counter;
		}

		public function search() {
			switch ($_GET['passengers']) {
				case "1":
				//$this->search_one_pax();
            $this->search_more_then_one_pax();
				break;

				default:
				$this->search_more_then_one_pax();
				break;
			}
		}

		// not using anymore
		public function search_one_pax() {
         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            $this->login_screen($uri);

         } else {
				// begin

				?>
				<div id="dvLoading"></div>
				<script>
				$(window).load(function(){
				  $('#dvLoading').fadeOut(2000);
				});
				</script>

				<?php

	         $old_date = $_GET['start_date'];              // returns Saturday, January 30 10 02:06:34
   	      $old_date_timestamp = strtotime($old_date);
      	   $start = date('Ymd', $old_date_timestamp);
         	$start2 = date('Y-m-d', $old_date_timestamp);

	         $old_date2 = $_GET['end_date'];              // returns Saturday, January 30 10 02:06:34
   	      $old_date_timestamp2 = strtotime($old_date2);
      	   $end = date('Ymd', $old_date_timestamp2);
         	$end2 = date('Y-m-d', $old_date_timestamp2);

	         // 29-Oct-2013

   	      if (is_array($_GET['boats'])) {
      	      foreach ($_GET['boats'] as $boat) {
         	      if ($boat != "") {
            	      $boats .= "'$boat',";
               	}
	            }
   	         if ($boats != "") {
      	         $boats = substr($boats,0,-1);
         	   }
	         }

	         if ($boats == "") {
   	         $sql = "SELECT `boatID` from `boats` WHERE `status` = 'Active' ORDER BY `name` ASC";
      	      $result = $this->new_mysql($sql);
         	   while ($row = $result->fetch_assoc()) {
            	   $boats .= "'$row[boatID]',";
	            }
   	         $boats = substr($boats,0,-1);
	         }

				//print "Sorry, Robert is at it again. Use PAX > 1 to test.<br>";

					// TEST

					$sql = "
					SELECT
						`reserve`.`charters`.`charterID`,
						`reserve`.`boats`.`boatID`,
						SUBSTR(`reserve`.`inv1`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inv1`.`bunk`)-1) AS 'room',
						`reserve`.`inv1`.`status`
					FROM
			         `reserve`.`charters`,`reserve`.`destinations`,`af_df_unified2`.`destinations`,`reserve`.`rooms`

			         LEFT JOIN `boats` ON reserve.boats.boatID = reserve.charters.boatID
			         LEFT JOIN inventory AS inv1 on reserve.charters.charterID = reserve.inv1.charterID AND reserve.inv1.status IN ('avail','booked','tentative')
						LEFT JOIN contacts ON reserve.inv1.passengerID = reserve.contacts.contactID

					WHERE
	   	         `reserve`.`charters`.`start_date` BETWEEN '$start' AND '$end'
   	   	      AND `reserve`.`charters`.`boatID` IN ($boats)

				      AND `reserve`.`charters`.`statusID` NOT IN ('7')
				      AND `reserve`.`charters`.`destinationID` = `reserve`.`destinations`.`destinationID`
				      AND `reserve`.`boats`.`name` = `af_df_unified2`.`destinations`.`name`
				      AND `reserve`.`charters`.`statusID` IN ('1','2','3','6','14','18','19','20')
						AND SUBSTR(`reserve`.`inv1`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inv1`.`bunk`)-1) = `reserve`.`rooms`.`room_number`
						AND `reserve`.`rooms`.`allow_single_pax` = 'Yes'
						AND `reserve`.`rooms`.`total_pax` = '2'

					GROUP BY charterID

					ORDER BY `reserve`.`boats`.`name` ASC, `reserve`.`charters`.`charterID` ASC, `reserve`.`inv1`.`bunk` ASC
					";


		         if (is_array($_GET['boats'])) {
      		      foreach ($_GET['boats'] as $boat) {
            		   $this_boats .= "&boats[]=$boat";
		            }
		         }


					$result = $this->new_mysql($sql);
					while ($row = $result->fetch_assoc()) {
						$sql2 = "
						SELECT
							SUBSTR(`reserve`.`inventory`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inventory`.`bunk`)-1) AS 'room'

						FROM
							`reserve`.`inventory`, `reserve`.`rooms`
							LEFT JOIN contacts ON reserve.inventory.passengerID = reserve.contacts.contactID

						WHERE
							`reserve`.`inventory`.`charterID` = '$row[charterID]'
							AND SUBSTR(`reserve`.`inventory`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inventory`.`bunk`)-1) = `reserve`.`rooms`.`room_number`
							AND `reserve`.`rooms`.`allow_single_pax` = 'Yes'
	                  AND `reserve`.`rooms`.`total_pax` = '2'

						GROUP BY room
						";


//print "SQL 2: $sql2<hr>";


						$result2 = $this->new_mysql($sql2);
						while($row2 = $result2->fetch_assoc()) {
							// check if room meets criteria
							$sql3 = "
							SELECT
								COUNT(`inventory`.`inventoryID`) AS 'total_bunks',
								COUNT(CASE WHEN `reserve`.`inventory`.`status` = 'booked' THEN `reserve`.`inventory`.`status` END) AS 'booked',
								COUNT(CASE WHEN `reserve`.`inventory`.`status` = 'tentative' THEN `reserve`.`inventory`.`status` END) AS 'tentative',
								COUNT(CASE WHEN `reserve`.`inventory`.`status` = 'avail' THEN `reserve`.`inventory`.`status` END) AS 'avail'

							FROM
								`inventory`
								LEFT JOIN contacts ON reserve.inventory.passengerID = reserve.contacts.contactID

							WHERE
								`inventory`.`charterID` = '$row[charterID]'
								AND `inventory`.`bunk` LIKE '%$row2[room]%'
							";
                     $result3 = $this->new_mysql($sql3);
                     while ($row3 = $result3->fetch_assoc()) {
								//print "Ch $row[charterID] | room $row2[room] | Avail $row3[avail]<br>";
								if ($row3['avail'] == "1") {
						
									// get the sex of the other bunk
		                     $sql4 = "
      		               SELECT
            		            `reserve`.`inventory`.`bunk`,
		                        `reserve`.`inventory`.`status`,
      		                  SUBSTR(`reserve`.`inventory`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inventory`.`bunk`)-1) AS 'room',
            		            `reserve`.`contacts`.`sex`

		                     FROM
      		                  `inventory`
            		            LEFT JOIN contacts ON reserve.inventory.passengerID = reserve.contacts.contactID

		                     WHERE
      		                  `inventory`.`charterID` = '$row[charterID]'
            		            AND `inventory`.`bunk` LIKE '%$row2[room]%'
										AND `inventory`.`status` IN ('booked','tentative')
                  		   ";
									$sex = "";
									$result4 = $this->new_mysql($sql4);
									$row4 = $result4->fetch_assoc();
									// match the sex to the logged in guest
									if ($row4['sex'] == $_SESSION['sex']) {
										$charterID = $row['charterID'];
										$charter[$charterID] = $charterID;
										//print "Charter $row[charterID] : bunk $row4[bunk] ok<br>";


									}
								}
							}
						}

					}

					foreach ($charter as $value) {
						$foundCharter = "1";
					}
					if ($foundCharter == "1") {
		            print "
      		      <div id=\"result_wrapper\">
		            <div id=\"result_pos1\">
      		      <div id=\"result_pos2\">
            		<div id=\"result_pos3A\">";
                  print "<table border=0 cellpadding=\"8\" cellspacing=\"0\"  width=\"820\">";

						foreach($charter as $value) {
							// Do charter query here
							$sql8 = "
				         SELECT
				            DATE_FORMAT(`reserve`.`charters`.`start_date`, '%b %e, %Y') AS 'date',
            				DATE_FORMAT(`reserve`.`charters`.`start_date`, '%Y-%m-%d') AS 's_date',
				            DATE_FORMAT(`reserve`.`charters`.`start_date`,'%Y%m%d') AS 'search_start_date',
				            DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%b %d, %Y') AS 'end_date',
				            DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%Y%m%d') AS 'search_end_date',

				            `reserve`.`charters`.`nights`,
				            `reserve`.`charters`.`charterID`,
				            `reserve`.`charters`.`boatID`,
				            `reserve`.`boats`.`name`,
				            `reserve`.`destinations`.`description`,
				            `af_df_unified2`.`destinations`.`boat_image`

							FROM
								`reserve`.`charters`, `reserve`.`boats`, `reserve`.`destinations`, `af_df_unified2`.`destinations`

							WHERE
								`reserve`.`charters`.`charterID` = '$value'
								AND `reserve`.`charters`.`boatID` = `reserve`.`boats`.`boatID`
								AND `reserve`.`boats`.`boatID` = `af_df_unified2`.`destinations`.`boatID`
								AND `reserve`.`charters`.`destinationID` = `reserve`.`destinations`.`destinationID`

							LIMIT 1
							";
							//print "$sql8<br>";
							$result8 = $this->new_mysql($sql8);
							while ($row8 = $result8->fetch_assoc()) {
								if ($this_name != $row8['name']) {
									if ($this_name != "") {
										print "</td></tr></table>";
									}
									$name = urlencode($row8['name']);
				               if ($y % 2) {
				                  $bgcolor = "bgcolor=#dcecfb";
				                  $bc = "#dcecfb";
				               } else { 
				                  $bgcolor = "bgcolor=#FFFFFF";
				                  $bc = "#FFFFFF";
				               }  
				               $row8['boat_image'] = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$row8['boat_image']);
									print "<tr $bgcolor>
										<td width=\"250\" valign=top><img src=\"$row8[boat_image]\"></td>
										<td valign=top width=\"430\"><span class=\"result-week-text\"><b><span class=\"result-title-text\">$row8[name]</span></b></span><br><br>
											<table border=0 width=\"540\">
												<tr class=\"result-week-text\">
					                        <td width=57>&nbsp;</td>
					                        <td width=125><b>Start</b></td>
					                        <td width=125><b>End</b></td>
					                        <td width=50><b>Nights</b></td>
					                        <td width=183><b>Destination</b></td>
					                   </tr>";
									$this_name = $row8['name'];
									$this_description = "";
									$counter = "0";
									$y++;
								}

				            // lookup discount
				            $is_discount = $this->find_discount2($row8['charterID']);
				            $discount = "";
				            if ($is_discount == "true") {
				               $discount = "<img src=\"specials.gif\">";
				            }  
				            // end lookup discount
				            print "<tr class=\"result-week-text\">
				            <td>$discount</td>
				            <td><a href=\"view_details.php?datepicker=$_GET[datepicker]&name=$name&charter=$row8[charterID]&start_date=$_GET[start_date]&end_date=$_GET[end_date]&passengers=$_GET[passengers]&pass1_gend=$_GET[pass1_gend]&pass2_gend=$_GET[pass2_gend]&pass3_gend=$_GET[pass3_gend]&pass4_gend=$_GET[pass4_gend]$this_boats\">$row8[date]</a></td>
				            <td>$row8[end_date]</td><td>$row8[nights]</td><td>$row8[description]</td>
								</tr>";


				            $found = "1";
			   	         // find specials or uc
                        $sql3a = "
                        SELECT 
                           `statuses`.`name` AS `status_name`,
                           `status_comments`.`comment`

                        FROM
                           `charters`,`status_comments`,`statuses`

                        WHERE
                           `charters`.`charterID` = '$row8[charterID]'
                           AND `charters`.`statusID` IN ('19','20')
                           AND `charters`.`statusID` = `statuses`.`statusID`
                           AND `charters`.`status_commentID` = `status_comments`.`status_commentID`
                        ";
                        $result3a = $this->new_mysql($sql3a);
                        while ($row3a = $result3a->fetch_assoc()) {
                           print "<tr class=\"result-week-text\">
                           <td>&nbsp;</td>
                           <td colspan=4><font color=\"#084B7B\"><b>$row3a[status_name]:</b> $row3a[comment]<br><br></font></td>
                           </tr>";
                        }
							}
						}

			         print "</tr></table></tr></table>";
						print "</div></div></div></div>";
					}

					if ($foundCharter != "1") {
						$this->search_more_then_one_pax();
					}

					// END TEST

			// end
			}
		}


		public function search_more_then_one_pax() {

         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            $this->login_screen($uri);

         } else {



			print "
			<div id=\"result_wrapper\">
				<div id=\"result_pos1\">
					<div id=\"result_pos2\">
						<div id=\"result_pos3A\">";
			$month = substr($_GET['datepicker'],0,2);
			$year = substr($_GET['datepicker'],2,4);

			$today_month = date("m");
			$today_year = date("Y");

			switch ($month) {
				case "01":
				$end = "31";
				$mon = "Jan";
            $next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=02$year'\" value=\"Feb $year &gt;&gt;\">";

				$test = date("Y");
				if ($test < $year) {
					$pyear = $year -1;
					$prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=12$pyear'\" value=\"&lt;&lt; Dec $pyear\">";
				}

				break;

				case "02":
				$end = "28";
				if (($year == "2016") or ($year == "2020") or ($year == "2024") or ($year == "2028")) {
					$end = "29";
				}
				$mon = "Feb";
            $next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=03$year'\" value=\"Mar $year &gt;&gt;\">";

				if ($today_year < $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=01$year'\" value=\"&lt;&lt; Jan $year\">";

				}

				break;

				case "03":
				$end = "31";
				$mon = "Mar";
            $next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=04$year'\" value=\"Apr $year &gt;&gt;\">";
            if ($today_year <= $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=02$year'\" value=\"&lt;&lt; Feb $year\">";
            }
				break;

				case "04":
				$end = "30";
				$mon = "Apr";
            $next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=05$year'\" value=\"May $year &gt;&gt;\">";
            if ($today_year <= $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=03$year'\" value=\"&lt;&lt; Mar $year\">";
            }
				break;

				case "05":
				$end = "31";
				$mon = "May";
            $next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=06$year'\"  value=\"Jun $year &gt;&gt;\">";
            if ($today_year <= $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=04$year'\" value=\"&lt;&lt; Apr $year\">";
            }
				break;

				case "06":
				$end = "30";
				$mon = "Jun";
            $next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=07$year'\" value=\"Jul $year  &gt;&gt;\">";
            if ($today_year <= $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=05$year'\" value=\"&lt;&lt; May $year\">";
            }
				break;

				case "07":
				$end = "31";
				$mon = "Jul";
            $next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=08$year'\" value=\"Aug $year &gt;&gt;\">";
            if ($today_year <= $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=06$year'\" value=\"&lt;&lt; Jun $year\">";
            }
				break;

				case "08":
				$end = "31";
				$mon = "Aug";
            $next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=09$year'\" value=\"Sep $year &gt;&gt;\">";
            if ($today_year <= $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=07$year'\" value=\"&lt;&lt; Jul $year\">";
            }
				break;

				case "09":
				$end = "30";
				$mon = "Sep";
            $next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=10$year'\" value=\"Oct $year &gt;&gt;\">";

            if ($today_year <= $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=08$year'\" value=\"&lt;&lt; Aug $year\">";
            }
				break;

				case "10":
				$end = "31";
				$mon = "Oct";
            $next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=11$year'\" value=\"Nov $year &gt;&gt;\">";
            if ($today_year <= $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=09$year'\" value=\"&lt;&lt; Sep $year\">";
            }
				break;

				case "11":
				$end = "30";
				$mon = "Nov";
				$next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=12$year'\" value=\"Dec $year &gt;&gt;\">";
            if ($today_year <= $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=10$year'\" value=\"&lt;&lt; Oct $year\">";
            }
				break;

				case "12":
				$end = "31";
				$mon = "Dec";
				$next_year = $year + 1;
				$next = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=01$next_year'\" value=\"Jan $next_year &gt;&gt;\">";
            if ($today_year <= $year) {
               $prev = "<input type=\"button\" class=\"month_toggle_buttons\" onclick=\"document.location.href='$uri&datepicker=11$year'\" value=\"&lt;&lt; Nov $year\">";
            }
				break;
			}

			$smon['01'] = "Jan";
         $smon['02'] = "Feb";
         $smon['03'] = "Mar";
         $smon['04'] = "Apr";
         $smon['05'] = "May";
         $smon['06'] = "Jun";
         $smon['07'] = "Jul";
         $smon['08'] = "Aug";
         $smon['09'] = "Sep";
         $smon['10'] = "Oct";
         $smon['11'] = "Nov";
         $smon['12'] = "Dec";
			$selected_month = $smon[$month];


			print "<center>";
			if ($next != "") {
				$selected_year = substr($_GET['datepicker'],2,4);
				print "$prev <label for=\"cm\" title=\"You are currently viewing the month of $selected_month\"><span class=\"middle_month\">$selected_month $selected_year</span></label> $next";
			}

			print "</center>";

			$_GET['start_date'] = "01-$mon-$year";
			$_GET['end_date'] = "$end-$mon-$year";

			if ($_GET['start_date'] == "01--") {
				$m1 = date("M");
				$y1 = date("Y");
				$y2 = $y1 + 1;
				$_GET['start_date'] = "01-$m1-$y1";
				$_GET['end_date'] = "31-Dec-$y2";
			}


			$old_date = $_GET['start_date'];              // returns Saturday, January 30 10 02:06:34
			$old_date_timestamp = strtotime($old_date);
			$start = date('Ymd', $old_date_timestamp);
			$start2 = date('Y-m-d', $old_date_timestamp);

			$today = date("Ymd");
			if ($start < $today) {
				$start = $today;
			}

         $old_date2 = $_GET['end_date'];              // returns Saturday, January 30 10 02:06:34
         $old_date_timestamp2 = strtotime($old_date2);
         $end = date('Ymd', $old_date_timestamp2);
			$end2 = date('Y-m-d', $old_date_timestamp2);

			// 29-Oct-2013

			if (is_array($_GET['boats'])) {
				foreach ($_GET['boats'] as $boat) {
					if ($boat != "") {
						$boats .= "'$boat',";
					}
				}
				if ($boats != "") {
					$boats = substr($boats,0,-1);
				}
			}

			if ($boats == "") {
				$sql = "SELECT `boatID` from `boats` WHERE `status` = 'Active' ORDER BY `name` ASC";
				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
					$boats .= "'$row[boatID]',";
				}
				$boats = substr($boats,0,-1);
			}

			foreach ($_SESSION as $key=>$value) {
				//print "$key => $value<br>";
			}

			if ($_SESSION['contact_type'] != "consumer") {
				if ($_GET['passengers'] > 5) {
					$extra = ",'25','28','30'";
				}
			}

			$sql = "
			SELECT
				DATE_FORMAT(`reserve`.`charters`.`start_date`, '%b %e, %Y') AS 'date',
				DATE_FORMAT(`reserve`.`charters`.`start_date`, '%Y-%m-%d') AS 's_date',
				DATE_FORMAT(`reserve`.`charters`.`start_date`,'%Y%m%d') AS 'search_start_date',
            DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%b %d, %Y') AS 'end_date',
            DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%Y%m%d') AS 'search_end_date',

				`reserve`.`charters`.`nights`,
				`reserve`.`charters`.`charterID`,
				`reserve`.`charters`.`boatID`,
				`reserve`.`boats`.`name`,
				`reserve`.`charters`.`destination`,
				`reserve`.`charters`.`embarkment`,
				`reserve`.`charters`.`disembarkment`,
				`reserve`.`charters`.`itinerary`,
				count(`reserve`.inv1.status) AS 'total_avail',
				`reserve`.`destinations`.`description`,
				`af_df_unified2`.`destinations`.`boat_image`,
				`af_df_unified2`.`destinations`.`boat_text`

			FROM
				`reserve`.`charters`,`reserve`.`destinations`,`af_df_unified2`.`destinations`

			LEFT JOIN `boats` ON reserve.boats.boatID = reserve.charters.boatID
			LEFT JOIN inventory AS inv1 on reserve.charters.charterID = reserve.inv1.charterID AND reserve.inv1.status IN ('avail')

			WHERE
				`reserve`.`charters`.`start_date` BETWEEN '$start' AND '$end'
				AND `reserve`.`charters`.`boatID` IN ($boats)
		      AND `reserve`.`charters`.`statusID` NOT IN ('7')
				AND `reserve`.`charters`.`destinationID` = `reserve`.`destinations`.`destinationID`
				AND `reserve`.`boats`.`name` = `af_df_unified2`.`destinations`.`name`
				AND `reserve`.`charters`.`statusID` IN ('1','2','3','6','14','18','19','20'$extra)

		   GROUP BY charterID

			HAVING total_avail >= '$_GET[passengers]'

			ORDER BY `reserve`.`boats`.`name` ASC, 
			`reserve`.`charters`.`start_date` ASC,
         `reserve`.`destinations`.`description` ASC

			";
			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				if (($row['total_avail'] == "1") && ($_SESSION['contact_type'] == "consumer")) {
					// check the bunks for this charter
					// if none match the session gender add the charter id to
					// an array and append the sql above with charterid not in statement
					$sql2 = "SELECT `charterID`,`bunk` FROM `inventory` WHERE `charterID` = '$row[charterID]' AND `status` = 'avail'";
					$result2 = $this->new_mysql($sql2);
					while ($row2 = $result2->fetch_assoc()) {
						$get_gender = $this->get_sex2($row2['charterID'],$row2['bunk']);
						//print "Test: $get_gender<br>";
						if ($get_gender != $_SESSION['sex']) {
							$remove_charters .= "'$row2[charterID]',";
						}
					}
				}
			}
			if ($remove_charters != "") {
				$remove_charters = substr($remove_charters,0,-1);
				$sql_remove = "AND `reserve`.`charters`.`charterID` NOT IN ($remove_charters)";
			}

			// repaint the SQL query
         $sql = "
         SELECT
            DATE_FORMAT(`reserve`.`charters`.`start_date`, '%b %e, %Y') AS 'date',
            DATE_FORMAT(`reserve`.`charters`.`start_date`, '%Y-%m-%d') AS 's_date',
            DATE_FORMAT(`reserve`.`charters`.`start_date`,'%Y%m%d') AS 'search_start_date',
            DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%b %d, %Y') AS 'end_date',
            DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%Y%m%d') AS 'search_end_date',

            `reserve`.`charters`.`nights`,
            `reserve`.`charters`.`charterID`,
            `reserve`.`charters`.`boatID`,
            `reserve`.`boats`.`name`,
            `reserve`.`charters`.`destination`,
            `reserve`.`charters`.`embarkment`,
            `reserve`.`charters`.`disembarkment`,
            `reserve`.`charters`.`itinerary`,
            count(`reserve`.inv1.status) AS 'total_avail',
            `reserve`.`destinations`.`description`,
            `af_df_unified2`.`destinations`.`boat_image`,
            `af_df_unified2`.`destinations`.`boat_text`

         FROM
            `reserve`.`charters`,`reserve`.`destinations`,`af_df_unified2`.`destinations`

         LEFT JOIN `boats` ON reserve.boats.boatID = reserve.charters.boatID
         LEFT JOIN inventory AS inv1 on reserve.charters.charterID = reserve.inv1.charterID AND reserve.inv1.status IN ('avail')

         WHERE
            `reserve`.`charters`.`start_date` BETWEEN '$start' AND '$end'
            AND `reserve`.`charters`.`boatID` IN ($boats)
            AND `reserve`.`charters`.`statusID` NOT IN ('7')
            AND `reserve`.`charters`.`destinationID` = `reserve`.`destinations`.`destinationID`
            AND `reserve`.`boats`.`name` = `af_df_unified2`.`destinations`.`name`
            AND `reserve`.`charters`.`statusID` IN ('1','2','3','6','14','18','19','20'$extra)

			$sql_remove

         GROUP BY charterID

         HAVING total_avail >= '$_GET[passengers]'

         ORDER BY `reserve`.`boats`.`name` ASC,
         `reserve`.`charters`.`start_date` ASC,
         `reserve`.`destinations`.`description` ASC


         ";


			if (is_array($_GET['boats'])) {
				foreach ($_GET['boats'] as $boat) {
				   $this_boats .= "&boats[]=$boat";
				}
			}

			//print "SQL:<br><br>$sql<br>\n";
			print "<table border=0 cellpadding=\"4\" cellspacing=\"0\"  width=\"820\">";
         $result = $this->new_mysql($sql);
         while($row = $result->fetch_assoc()) {
				if ($this_name != $row['name']) {



					if ($this_name != "") {

						print "</td></tr></table>";
					}
					$name = urlencode($row['name']);

					if ($y % 2) {
						$bgcolor = "bgcolor=#dcecfb";
						$bc = "#dcecfb";
					} else {
						$bgcolor = "bgcolor=#FFFFFF";
						$bc = "#FFFFFF";
					}
					$row['boat_image'] = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$row['boat_image']);
					if ($foundit == "1") {
						print "<tr><td colspan=2><hr></td></tr>";
					}

					$foundit = "1";

					print "<tr >
						<td width=\"250\" valign=top><img src=\"$row[boat_image]\"></b></td><td width=\"570\" valign=top><b><span class=\"result-title-text\">$row[name]</span></b></span><br><br>
							<span class=\"boat_text\">$row[boat_text]</span></td></tr>
						<tr>
						<td valign=top colspan=2>



							<table border=0 width=\"800\">";
				
	                  print "<tr class=\"result-week-text\">
								<td width=57>&nbsp;</td>
                     	<td width=75><b>Start</b></td>
                     	<td width=75><b>End</b></td>
                     	<td width=50><b>Nights</b></td>
                     	<td width=105><b>Destination</b></td>
								<td width=105><b>Embark</b></td>
								<td width=105><b>Disembark</b></td>
                 	 </tr>";


					$this_name = $row['name'];
					$this_description = "";
					$counter = "0";
					$y++;
				}



				// lookup discount
				$is_discount = $this->find_discount2($row['charterID']);
				$discount = "";
				if ($is_discount == "true") {
					$discount = "<img src=\"specials.gif\">";
				}

				// end lookup discount

				print "<tr class=\"result-week-text\">
				<td>$discount</td>
				<td><a href=\"view_details.php?datepicker=$_GET[datepicker]&name=$name&charter=$row[charterID]&start_date=$_GET[start_date]&end_date=$_GET[end_date]&passengers=$_GET[passengers]&pass1_gend=$_GET[pass1_gend]&pass2_gend=$_GET[pass2_gend]&pass3_gend=$_GET[pass3_gend]&pass4_gend=$_GET[pass4_gend]$this_boats\">$row[date]</a></td>
				<td>$row[end_date]</td><td>$row[nights]</td><td>$row[destination]</td><td>$row[embarkment]</td><td>$row[disembarkment]</td></tr>";
				print "<tr><td></td><td colspan=7><b>Itinerary:</b> $row[itinerary]</td></tr>";
				$found = "1";

				// find specials or uc
				$sql3 = "
				SELECT
					`statuses`.`name` AS `status_name`,
					`status_comments`.`comment`,
					`status_comments`.`descr`

				FROM
					`charters`,`status_comments`,`statuses`

				WHERE
					`charters`.`charterID` = '$row[charterID]'
					AND `charters`.`statusID` IN ('19','20')
					AND `charters`.`statusID` = `statuses`.`statusID`
					AND `charters`.`status_commentID` = `status_comments`.`status_commentID`
				";
				$dc = 0;
				$result3 = $this->new_mysql($sql3);
				while ($row3 = $result3->fetch_assoc()) {
					print "<tr class=\"result-week-text\">
					<td>&nbsp;</td>
					<td colspan=6><font color=\"#308DD4\"><b>$row3[status_name]:</b> $row3[comment]</font></td>
					</tr>";

					if (($row3['status_name'] == "Unique Charters") and ($row3['descr'] != "")) {
						$row3['descr'] = str_replace("UNIQUE CHARTER: ","",$row3['descr']);
						print "<tr class=\"result-week-text\"><td>&nbsp;</td><td colspan=6><font color=\"#308DD4\">$row3[descr]</font></td></tr>";
					}
					print "<tr><td colspan=7><br><br></td></tr>";
					$dc = "1";
				}
				if ($dc == "0") {
					print "<tr><td colspan=5><br></td></tr>";
				}

			}

			if ($found != "1") {
            print "
                     <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\">
                        <tr>
                           <td>
                              <img src=\"../ResImages/generic-DTW.jpg\" width=\"850\">
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">
                                 <tr>
                                    <td width=\"263\" class=\"details-top\">&nbsp;</td>
                                    <td width=\"303\" class=\"details-top\">&nbsp;</td>
                                    <td width=\"283\" align=\"right\" class=\"details-top\">&nbsp;</td>
                                 </tr>
                              </table> 
                           </td>
                        </tr>
                     </table>
            ";



				print "<tr><td colspan=4><br><br><center><span class=\"result-week-text2\"><br>There are no charters with space available for your selected destination(s) and dates.</span><br><br><br><img src=\"30Year-Blue-300px.png\" width=\"250\"></center><br><br></td></tr>";

			}

			print "</tr></table></tr></table>";

			print "</div></div></div></div>";
			}
		}

	public function get_quick_ratesOLD($charterID,$bunks) {

		$array_bunks = explode(",",$bunks);
		foreach ($array_bunks as $value) {
			$bunk_list .= "'$value',";
		}
		$bunk_list = substr($bunk_list,0,-1);

		$sql = "
		SELECT
			`reserve`.`inventory`.`bunk_price` + `reserve`.`charters`.`add_on_price_commissionable` + `reserve`.`charters`.`add_on_price` AS 'bunk_price'
		FROM
			`reserve`.`inventory`,`charters`

		WHERE
			`reserve`.`inventory`.`charterID` = '$charterID'
			AND `reserve`.`charters`.`charterID` = '$charterID'
			AND `reserve`.`inventory`.`bunk` IN ($bunk_list)

		LIMIT 1
		";

		$result = $this->new_mysql($sql);
		$row = $result->fetch_assoc();
		return ($row['bunk_price']);
	}

	// 4-24-2015 - RS - updated so it only shows lowest price of available inventory. If non are avail then it will show the lowest price of the sold out.
   public function get_quick_rates($charterID,$bunks) {

      $array_bunks = explode(",",$bunks);
      foreach ($array_bunks as $value) {
         $bunk_list .= "'$value',";
      }
      $bunk_list = substr($bunk_list,0,-1);

      $sql = "
      SELECT
         `reserve`.`inventory`.`bunk_price` + `reserve`.`charters`.`add_on_price_commissionable` + `reserve`.`charters`.`add_on_price` AS 'bunk_price'
      FROM
         `reserve`.`inventory`,`charters`

      WHERE
         `reserve`.`inventory`.`charterID` = '$charterID'
         AND `reserve`.`charters`.`charterID` = '$charterID'
         AND `reserve`.`inventory`.`bunk` IN ($bunk_list)
         AND `inventory`.`status` = 'avail'
      LIMIT 1
      ";

		$bunk_list = str_replace(" ","",$bunk_list);

      $result = $this->new_mysql($sql);
      $row = $result->fetch_assoc();
      if ($row['bunk_price'] == "") {
      $sql = "
      SELECT
         `reserve`.`inventory`.`bunk_price` + `reserve`.`charters`.`add_on_price_commissionable` + `reserve`.`charters`.`add_on_price` AS 'bunk_price'
      FROM
         `reserve`.`inventory`,`charters`

      WHERE
         `reserve`.`inventory`.`charterID` = '$charterID'
         AND `reserve`.`charters`.`charterID` = '$charterID'
         AND `reserve`.`inventory`.`bunk` IN ($bunk_list)
		ORDER BY `bunk_price` DESC
      LIMIT 1
      ";
      $result = $this->new_mysql($sql);
      $row = $result->fetch_assoc();
      }

      return ($row['bunk_price']);
   }


   public function get_inventory($charterID,$bunks) {

		$ses_id = session_id();

      $array_bunks = explode(",",$bunks);
      foreach ($array_bunks as $value) {
         $bunk_list .= "'$value',";
      }
      $bunk_list = substr($bunk_list,0,-1);
     
		$bunk_list = str_replace(" ","",$bunk_list);
 
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
         `reserve`.`inventory`.`charterID` = '$charterID'
			AND `reserve`.`charters`.`charterID` = '$charterID'
         AND `reserve`.`inventory`.`bunk` IN ($bunk_list)
			AND `reserve`.`inventory`.`passengerID` IN ('61531204','0')
			AND `reserve`.`inventory`.`reservationID` = ''
			AND (`reserve`.`inventory`.`sessionID` = '$ses_id' OR `reserve`.`inventory`.`sessionID` = '')
			#AND `reserve`.`inventory`.`status` = 'avail'
      ";
      return $sql;
   }

	public function find_discount($charterID,$price) {
		$today = date("Y-m-d");
		$sql = "
		SELECT
			`reserve`.`charters`.`boatID`,
			`reserve`.`charters`.`start_date`,
			`af_df_unified2`.`specials_discounts`.`discount_type`,
			`af_df_unified2`.`specials_discounts`.`percent_off`,
			`af_df_unified2`.`specials_discounts`.`dollar_off`,
			`af_df_unified2`.`specials_discounts`.`price_override`,
			`af_df_unified2`.`specials_discounts`.`general_discount_reason`

		FROM
			`reserve`.`charters`,`af_df_unified2`.`specials_discounts`

		WHERE
			`reserve`.`charters`.`charterID` = '$charterID'
			AND `af_df_unified2`.`specials_discounts`.`boatID` = `reserve`.`charters`.`boatID`
			AND '$today' BETWEEN `af_df_unified2`.`specials_discounts`.`date_booked_start` AND `af_df_unified2`.`specials_discounts`.`date_booked_end`
			AND `reserve`.`charters`.`start_date` BETWEEN replace(`af_df_unified2`.`specials_discounts`.`travel_date_start`,'-','') AND replace(`af_df_unified2`.`specials_discounts`.`travel_date_end`,'-','')
		";

		$discount = array();
		$result = $this->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			switch ($row['discount_type']) {
				case "Dollar Off":
					$tvar = $row['dollar_off'];
					$discount[] = $row['dollar_off'];
					$discount[$tvar][0] = $row['general_discount_reason'];

				break;

				case "Percent Off":
					$temp = $price * ($row['percent_off'] * 0.01);
					$discount[] = $temp;
					$discount[$temp][0] = $row['general_discount_reason'];

				break;

				case "Price Override":
					$temp = $price - $row['price_override'];
					$discount[] = $temp;
					$discount[$temp][0] = $row['general_discount_reason'];
				break;
			}
		}
		return $discount;


	}


   public function find_discount2($charterID) {
      $today = date("Y-m-d");
      $sql = "
      SELECT
         `reserve`.`charters`.`boatID`,
         `reserve`.`charters`.`start_date`,
         `af_df_unified2`.`specials_discounts`.`discount_type`,
         `af_df_unified2`.`specials_discounts`.`percent_off`,
         `af_df_unified2`.`specials_discounts`.`dollar_off`,
         `af_df_unified2`.`specials_discounts`.`price_override`,
         `af_df_unified2`.`specials_discounts`.`general_discount_reason`

      FROM
         `reserve`.`charters`,`af_df_unified2`.`specials_discounts`

      WHERE
         `reserve`.`charters`.`charterID` = '$charterID'
         AND `af_df_unified2`.`specials_discounts`.`boatID` = `reserve`.`charters`.`boatID`
         AND '$today' BETWEEN `af_df_unified2`.`specials_discounts`.`date_booked_start` AND `af_df_unified2`.`specials_discounts`.`date_booked_end`
         AND `reserve`.`charters`.`start_date` BETWEEN replace(`af_df_unified2`.`specials_discounts`.`travel_date_start`,'-','') AND replace(`af_df_unified2`.`specials_discounts`.`travel_date_end`,'-','')
      ";

		$found = "false";
      $result = $this->new_mysql($sql);
      while ($row = $result->fetch_assoc()) {
			$found = "true";
      }
		return $found;

   }


	public function get_inv_count_one_pax($charter,$bunks) {

						$bunkz = explode(',',$bunks);
						foreach ($bunkz as $value) {
							$this_bunk .= "'$value',";
						}
						$this_bunk = substr($this_bunk,0,-1);

						$total_avail = 0;
                  $sql2 = "
                  SELECT
                     SUBSTR(`reserve`.`inventory`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inventory`.`bunk`)-1) AS 'room'

                  FROM
                     `reserve`.`inventory`, `reserve`.`rooms`
                     LEFT JOIN contacts ON reserve.inventory.passengerID = reserve.contacts.contactID

                  WHERE
                     `reserve`.`inventory`.`charterID` = '$charter'
							AND `reserve`.`inventory`.`bunk` IN ($this_bunk)
                     AND SUBSTR(`reserve`.`inventory`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inventory`.`bunk`)-1) = `reserve`.`rooms`.`room_number`
                     AND `reserve`.`rooms`.`allow_single_pax` = 'Yes'
                     AND `reserve`.`rooms`.`total_pax` = '2'

                  GROUP BY room
                  ";
                  //mail('robert@wayneworks.com','SQL',$sql2);

                  $result2 = $this->new_mysql($sql2);
                  while($row2 = $result2->fetch_assoc()) {
                     // check if room meets criteria
                     $sql3 = "
                     SELECT
                        COUNT(`inventory`.`inventoryID`) AS 'total_bunks',
                        COUNT(CASE WHEN `reserve`.`inventory`.`status` = 'booked' THEN `reserve`.`inventory`.`status` END) AS 'booked',
                        COUNT(CASE WHEN `reserve`.`inventory`.`status` = 'tentative' THEN `reserve`.`inventory`.`status` END) AS 'tentative',
                        COUNT(CASE WHEN `reserve`.`inventory`.`status` = 'avail' THEN `reserve`.`inventory`.`status` END) AS 'avail'

                     FROM
                        `inventory`
                        LEFT JOIN contacts ON reserve.inventory.passengerID = reserve.contacts.contactID

                     WHERE
                        `inventory`.`charterID` = '$charter'
                        AND `inventory`.`bunk` LIKE '%$row2[room]%'
                     ";
							//mail('robert@wayneworks.com','SQL',$sql3);
                     $result3 = $this->new_mysql($sql3);
                     while ($row3 = $result3->fetch_assoc()) {
                        //print "Ch $row[charterID] | room $row2[room] | Avail $row3[avail]<br>";
                        if ($row3['avail'] == "1") {

                           // get the sex of the other bunk
                           $sql4 = "
                           SELECT
                              `reserve`.`inventory`.`bunk`,
                              `reserve`.`inventory`.`status`,
                              SUBSTR(`reserve`.`inventory`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inventory`.`bunk`)-1) AS 'room',
                              `reserve`.`contacts`.`sex`

                           FROM
                              `inventory`
                              LEFT JOIN contacts ON reserve.inventory.passengerID = reserve.contacts.contactID

                           WHERE
                              `inventory`.`charterID` = '$charter'
                              AND `inventory`.`bunk` LIKE '%$row2[room]%'
                              AND `inventory`.`status` IN ('booked','tentative')
                           ";
                           $sex = "";
                           $result4 = $this->new_mysql($sql4);
                           $row4 = $result4->fetch_assoc();
                           // match the sex to the logged in guest
                           if ($row4['sex'] == $_SESSION['sex']) {
                              //$charterID = $row['charterID'];
                              //$charter[$charterID] = $charterID;
                              //print "Charter $row[charterID] : bunk $row4[bunk] ok<br>";
										$total_avail++;
									}
								}
							}
						}

		return $total_avail;
	}


   public function get_inv_count_one_pax_results($charter,$bunks) {

                  $bunkz = explode(',',$bunks);
                  foreach ($bunkz as $value) {
                     $this_bunk .= "'$value',";
                  }
                  $this_bunk = substr($this_bunk,0,-1);

                  $total_avail = 0;
                  $sql2 = "
                  SELECT
                     SUBSTR(`reserve`.`inventory`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inventory`.`bunk`)-1) AS 'room'

                  FROM
                     `reserve`.`inventory`, `reserve`.`rooms`
                     LEFT JOIN contacts ON reserve.inventory.passengerID = reserve.contacts.contactID

                  WHERE
                     `reserve`.`inventory`.`charterID` = '$charter'
                     AND `reserve`.`inventory`.`bunk` IN ($this_bunk)
                     AND SUBSTR(`reserve`.`inventory`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inventory`.`bunk`)-1) = `reserve`.`rooms`.`room_number`
                     AND `reserve`.`rooms`.`allow_single_pax` = 'Yes'
                     AND `reserve`.`rooms`.`total_pax` = '2'

                  GROUP BY room
                  ";
                  //mail('robert@wayneworks.com','SQL',$sql2);

                  $result2 = $this->new_mysql($sql2);
                  while($row2 = $result2->fetch_assoc()) {
                     // check if room meets criteria
                     $sql3 = "
                     SELECT
                        COUNT(`inventory`.`inventoryID`) AS 'total_bunks',
                        COUNT(CASE WHEN `reserve`.`inventory`.`status` = 'booked' THEN `reserve`.`inventory`.`status` END) AS 'booked',
                        COUNT(CASE WHEN `reserve`.`inventory`.`status` = 'tentative' THEN `reserve`.`inventory`.`status` END) AS 'tentative',
                        COUNT(CASE WHEN `reserve`.`inventory`.`status` = 'avail' THEN `reserve`.`inventory`.`status` END) AS 'avail'

                     FROM
                        `inventory`
                        LEFT JOIN contacts ON reserve.inventory.passengerID = reserve.contacts.contactID

                     WHERE
                        `inventory`.`charterID` = '$charter'
                        AND `inventory`.`bunk` LIKE '%$row2[room]%'
                     ";
                     //mail('robert@wayneworks.com','SQL',$sql3);
                     $result3 = $this->new_mysql($sql3);
                     while ($row3 = $result3->fetch_assoc()) {
                        //print "Ch $row[charterID] | room $row2[room] | Avail $row3[avail]<br>";
                        if ($row3['avail'] == "1") {

                           // get the sex of the other bunk
                           $sql4 = "
                           SELECT
										`reserve`.`inventory`.`inventoryID`,
                              `reserve`.`inventory`.`bunk`,
                              `reserve`.`inventory`.`status`,
                              SUBSTR(`reserve`.`inventory`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inventory`.`bunk`)-1) AS 'room',
                              `reserve`.`contacts`.`sex`

                           FROM
                              `inventory`
                              LEFT JOIN contacts ON reserve.inventory.passengerID = reserve.contacts.contactID

                           WHERE
                              `inventory`.`charterID` = '$charter'
                              AND `inventory`.`bunk` LIKE '%$row2[room]%'
                              AND `inventory`.`status` IN ('booked','tentative')
                           ";
                           $sex = "";
                           $result4 = $this->new_mysql($sql4);
                           $row4 = $result4->fetch_assoc();
                           // match the sex to the logged in guest
                           if ($row4['sex'] == $_SESSION['sex']) {
	                           $sql5 = "
   	                        SELECT
      	                        `reserve`.`inventory`.`inventoryID`,
         	                     `reserve`.`inventory`.`bunk`,
            	                  `reserve`.`inventory`.`status`,
               	               SUBSTR(`reserve`.`inventory`.`bunk`, 1, CHAR_LENGTH(`reserve`.`inventory`.`bunk`)-1) AS 'room'

                     	      FROM
                        	      `inventory`

	                           WHERE
   	                           `inventory`.`charterID` = '$charter'
      	                        AND `inventory`.`bunk` LIKE '%$row2[room]%'
         	                     AND `inventory`.`status` IN ('avail')
            	               ";
										$result5 = $this->new_mysql($sql5);
										while($row5 = $result5->fetch_assoc()) {
	                              $inventoryID .= "'$row5[inventoryID]',";
											$ff = "1";
										}
                              //$charterID = $row['charterID'];
                              //$charter[$charterID] = $charterID;
                              //print "Charter $row[charterID] : bunk $row4[bunk] ok<br>";
                              //$total_avail++;
                           }
                        }
                     }
                  }
		if ($ff == "1") {
			$ses_id = session_id();
			$inventoryID = substr($inventoryID,0,-1);
			$sql = "
			SELECT 
				`reserve`.`inventory`.`bunk_price` + `reserve`.`charters`.`add_on_price_commissionable` + `reserve`.`charters`.`add_on_price` AS 'bunk_price', 
				`reserve`.`inventory`.`bunk`, `reserve`.`inventory`.`inventoryID`, `reserve`.`inventory`.`reservationID`, 
				`reserve`.`inventory`.`status`, `reserve`.`inventory`.`userID`, `reserve`.`inventory`.`sessionID`, 
				`reserve`.`inventory`.`bunk_description`, `reserve`.`inventory`.`passengerID`, `reserve`.`inventory`.`sessionID` 

			FROM 
				`reserve`.`inventory`,
				`reserve`.`charters` 

			WHERE 
				`reserve`.`inventory`.`charterID` = '$charter' 
				AND `reserve`.`charters`.`charterID` = '$charter' 
				AND `reserve`.`inventory`.`bunk` IN ($this_bunk)
				AND `reserve`.`inventory`.`inventoryID` IN ($inventoryID) 
				AND `reserve`.`inventory`.`passengerID` IN ('61531204','0') 
				AND `reserve`.`inventory`.`reservationID` = '' 
				AND (`reserve`.`inventory`.`sessionID` = '$ses_id' OR `reserve`.`inventory`.`sessionID` = '') 
			";
		}
  	   return $sql;
   }


	public function details_pax1() {
         print "
         <div id=\"result_wrapper\">
            <div id=\"result_pos1\">
               <div id=\"result_pos2\">
               <br>
                  ";
         $boats_array = htmlentities(serialize($_GET['boats']));
         if (is_array($_GET['boats'])) {
         foreach ($_GET['boats'] as $boat) {
            $boats .= "$boat,";
         }
         $boats = substr($boats,0,-1);

         foreach ($_GET['boats'] as $boat2) {
            $this_boats .= "&boats[]=$boat2";
         }
         }

         $sql = "
         SELECT
            `af_df_unified2`.`destinations`.*,
            DATE_FORMAT(`reserve`.`charters`.`start_date`, '%b %d, %Y') AS 'start_date',
            DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%b %d, %Y') AS 'end_date',
            `reserve`.`destinations`.`description`,
            `reserve`.`boats`.`home_page`,
				`reserve`.`charters`.`embarkment`,
				`reserve`.`charters`.`disembarkment`,
				`reserve`.`charters`.`itinerary`,
				`reserve`.`charters`.`nights`

         FROM
            `reserve`.`charters`,
            `af_df_unified2`.`destinations`,
            `reserve`.`destinations`,
            `reserve`.`boats`

         WHERE
            `reserve`.`charters`.`charterID` = '$_GET[charter]'
            AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`destinations`.`boatID`
            AND `reserve`.`charters`.`boatID` = `reserve`.`boats`.`boatID`
            AND `af_df_unified2`.`destinations`.`name` = '$_GET[name]'

            AND `reserve`.`charters`.`destinationID` = `reserve`.`destinations`.`destinationID`

         ";
         $result = $this->new_mysql($sql);
         while($row = $result->fetch_assoc()) {
            $di = $row['destination_image'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $description = $row['description'];
            $home_page = $row['home_page'];
				$embarkment = $row['embarkment'];
				$disembarkment = $row['disembarkment'];
				$itinerary = $row['itinerary'];
				$nights = $row['nights'];
         }
         $di = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$di);
         print "

         <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\">
         <tr>
            <td>
               <img src=\"$di\" width=\"850\">
            </td>
         </tr>
         <tr><td>

            <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">

       <tr>
         <td width=\"33%\" class=\"details-top\">&nbsp;&nbsp;<a class=\"details-top\" href=\"javascript:history.back()\">&lt; Previous</a></td>
         <td width=\"33%\" class=\"details-top\" align=\"center\"><a href=\"$home_page\" target=_blank class=\"details-top\">Learn More</a></td>
         <td width=\"33%\" class=\"details-top\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"document.getElementById('email_a_friend').style.display='inline';\" class=\"details-top\">Email A Friend</a>&nbsp;&nbsp;</td>
         </tr>


            </table>
                  
         </td></tr>

<tr>
   <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
     <tbody>
       <tr>
         <td width=\"50%\"><strong><span class=\"details-title-text\">Date:</span> $start_date to $end_date ($nights nights)</strong></td>
         <td width=\"50%\" align=\"right\"><strong><span class=\"details-title-text\">Embarkment:</span> $embarkment</strong></td>
         </tr>

       <tr>
         <td width=\"50%\"><strong><span class=\"details-title-text\">Itinerary:</span> $itinerary</strong></td>
         <td width=\"50%\" align=\"right\"><strong><span class=\"details-title-text\">Disembarkment:</span> $disembarkment</strong></td>
         </tr>


       </tbody>
   </table></td>
  </tr>


         </table>
         <div style=\"clear:both;\"></div>
         ";


         $num1 = rand(2,50);
         $num2 = rand(2,50);
         $_SESSION['num1'] = $num1;
         $_SESSION['num2'] = $num2;
         $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


         print "
            <div id=\"email_a_friend\" style=\"display:none\">
               <div id=\"email_a_friend_inner\">
                  <form name=\"myform\">
                  <input type=\"hidden\" name=\"link\" value=\"$actual_link\">
                  <table border=0 cellspacing=0 cellpadding=3 width=\"320\">
                  <tr class=\"email_a_friend_text\"><td width=100>Your Name:</td><td><input type=\"text\" name=\"your_name\" size=25></td></tr>
                  <tr class=\"email_a_friend_text\"><td>Friends Name:</td><td><input type=\"text\" name=\"your_friend_name\" size=25></td></tr>
                  <tr class=\"email_a_friend_text\"><td>Friends Email:</td><td><input type=\"text\" name=\"your_friend_email\" size=25></td></tr>
                  <tr class=\"email_a_friend_text\"><td>Security Question:</td><td>What is $num1 plus $num2? <input type=\"text\" name=\"answer\" id=\"answer\" size=25></td></tr>
                  <tr class=\"email_a_friend_text\"><td>&nbsp;</td><td><input type=\"button\" value=\"Send Email\" onclick=\"sendemail(this.form)\">&nbsp;&nbsp;<a class=\"email_a_friend_text\" href=\"javascript:void(0)\" onclick=\"document.getElementById('email_a_friend').style.display='none';\">Close</a></td></tr>
                  </table>
                  ";



                  print "
                  </form>

               </div>
            </div>
         ";

         ?>
         <script>
                                function sendemail(myform) {
                                        $.post('send_email.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#email_a_friend_inner").html(php_msg);
                                        });
                                }
         </script>
         <?php


         print "<div id=\"result_pos3\">";

         print "<table border=\"0\" width=\"820\" cellspacing=3>";
         $result = $this->new_mysql($sql);
         while($row = $result->fetch_assoc()) {
            if ($this_name != $row['name']) {
               $this_name = $row['name'];
            }

            $name = urlencode($row['name']);
            $var1 = "&name=$name&start_date=$_GET[start_date]&end_date=$_GET[end_date]&passengers=$_GET[passengers]&pass1_gend=$_GET[pass1_gend]&pass2_gend=$_GET[pass2_gend]&pass3_gend=$_GET[pass3_gend]&pass4_gend=$_GET[pass4_gend]$this_boats";

			}

			// ROBERT1
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
				`reserve`.`boats`.`boatID`,
				`reserve`.`boats`.`abbreviation`,
				`reserve`.`bunks`.`cabin_type`

			FROM 
				`reserve`.`inventory`,`charters`,`boats`,`bunks` 

			WHERE 
				`reserve`.`inventory`.`charterID` = '$_GET[charter]' 
				AND `reserve`.`charters`.`charterID` = '$_GET[charter]' 
				AND `reserve`.`inventory`.`passengerID` IN ('61531204','0') 
				AND `reserve`.`inventory`.`reservationID` = '' 
				AND (`reserve`.`inventory`.`sessionID` = '$_SESSION[sessionID]' OR `reserve`.`inventory`.`sessionID` = '') 
				AND `reserve`.`boats`.`boatID` = `reserve`.`bunks`.`boatID`
				AND `reserve`.`inventory`.`bunk` = concat(`reserve`.`boats`.`abbreviation`,'-',`reserve`.`bunks`.`cabin`,`reserve`.`bunks`.`bunk`)
				AND `reserve`.`bunks`.`cabin_type` != ''

			";
//ROBERT2

			print "<table border=0 width=700>
			<tr><td colspan=4>

			We have located the following spaces matching your gender.<br><br>

			</td></tr>";

            print '
                  <tr>
                  <td width="90" class="view_details-description">Gender</td>
                     <td width="146" class="view_details-description">Stateroom</td>
							<td width="146" class="view_details-description">Room Type</td>
                     <td width="156" class="view_details-description">Amount</td>
                     <td width="166"></tr>
            ';



			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$sex = $this->get_sex2($_GET['charter'],$row['bunk']);
				if ($sex == $_SESSION['sex']) {
						$sex2 = $this->get_sex($_GET['charter'],$row['bunk']);

				      $bunk = substr($row['bunk'],-3);
				      //$bunk = substr($bunk,0,-1);


                  print "<tr>
                  <td class=\"details-description\">".$sex2."</td>
                  <td class=\"details-description\">$bunk</td>
						<td class=\"details-description\">$row[cabin_type]</td>";

                     $temp_d = "";
                     $discount = $this->find_discount($_GET['charter'],$row['bunk_price']);
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
      	               print "
         	            <td class=\"details-description\"><del><font color=red>$".number_format($row['bunk_price'])."</font></del> <ins style=\"text-decoration: none\">$".number_format($new_price)."</ins></td>
            	         ";
               	   } else {
                  	   print "
	                     <td class=\"details-description\">$".number_format($row['bunk_price'])."</td>
   	                  ";
      	            }
         	         print "
	                  <td id=\"inv_$row[inventoryID]\">";
   	               if ($row['sessionID'] == $_SESSION['sessionID']) {
      	               print "
         	            <form name=\"MyForm\"><input type=\"hidden\" name=\"cancel\" value=\"$row[inventoryID]\">
            	         <input type=\"image\" src=\"buttons/bt-cancel.png\" name=\"inventoryID_$row[inventoryID]\" id=\"inventoryID_$row[inventoryID]\" onclick=\"quickbook".$row['inventoryID']."(this.form);return false;\"> <a href=\"javascript:void(0)\" title=\"You have reserved this space for 30 minutes. To checkout click Continue.\">reserved</a>
               	      </form>
	                     </td>
                     </tr>";
                     ?>
                                <script>
                                function quickbook<?php echo $row['inventoryID'];?>(myform) {
                                        $.post('quick_book.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#inv_<?php echo $row['inventoryID'];?>").html(php_msg);
                                        });
                                }
                                </script>
                     <?php
                  	} else {
                     	if (($row['sessionID'] == "") && ($row['userID'] == "") or ($row['userID'] == "0")) {
                        	print "
	                        <form name=\"MyForm\"><input type=\"hidden\" name=\"qb\" value=\"$row[inventoryID]\">";
   	                     if ($_GET['passengers'] == "1") {
      	                     print "<input type=\"image\" src=\"buttons/bt-select.png\" name=\"inventoryID_$row[inventoryID]\" id=\"inventoryID_$row[inventoryID]\" onclick=\"quickbook".$row['inventoryID']."(this.form);return false;\">";
         	               } else {
            	               print "<input type=\"image\" src=\"buttons/bt-select.png\" name=\"inventoryID_$row[inventoryID]\" id=\"inventoryID_$row[inventoryID]\" onclick=\"quickbook".$row['inventoryID']."(this.form);return false;\">";
               	         }
	                        print "
   	                     </form>
      	                  </td>
         	               </tr>";
            	            ?>
                                <script>
                                function quickbook<?php echo $row['inventoryID'];?>(myform) {
                                        $.post('quick_book.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#inv_<?php echo $row['inventoryID'];?>").html(php_msg);
                                        });
                                }
                                </script>

                        <?php
               	      } else {
                  	      print "<input type=\"button\" value=\"On Hold\">";
                     	}
	                  }
			            $rand = rand(100,4000);

}}

			            print "
		               <tr><td colspan=4>&nbsp;</td><td><br><div id=\"result"; echo $rand; print "\"></div></td></tr>
		               <tr><td colspan=4>&nbsp;</td><td><br><div id=\"timeleft"; echo $rand; print "\" style=\"display:inline\"></div>
		               </table>
		               </td></tr>
               </table>
            ";

      ?>
      <script type="text/javascript">
      function refreshDiv() {
          $('#result<?=$rand;?>').load('check_booked.php?charter=<?=$_GET['charter'];?><?=$var1;?>', function(){ /* callback code here */ });
         $('#timeleft<?=$rand;?>').load('check_time2.php?charter=<?=$_GET['charter'];?>', function(){ /* callback code here */ });

      }
      setInterval(refreshDiv, 1000);
      </script>
		<?php






	print "</table>
	</div>";

         print "</div></div></div></div>";

	}




	public function details() {

         print "
			<div id=\"result_wrapper\">
				<div id=\"result_pos1\">
					<div id=\"result_pos2\">
					<br>
						";
			$boats_array = htmlentities(serialize($_GET['boats']));
			if (is_array($_GET['boats'])) {
			foreach ($_GET['boats'] as $boat) {
				$boats .= "$boat,";
			}
			$boats = substr($boats,0,-1);

         foreach ($_GET['boats'] as $boat2) {
            $this_boats .= "&boats[]=$boat2";
         }
			}

			$sql = "
			SELECT
				`af_df_unified2`.`destinations`.*,
				DATE_FORMAT(`reserve`.`charters`.`start_date`, '%b %d, %Y') AS 'start_date',
				DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%b %d, %Y') AS 'end_date',
				`reserve`.`destinations`.`description`,
				`reserve`.`boats`.`home_page`,
            `reserve`.`charters`.`embarkment`,
            `reserve`.`charters`.`disembarkment`,
            `reserve`.`charters`.`itinerary`,
            `reserve`.`charters`.`nights`

			FROM
				`reserve`.`charters`,
				`af_df_unified2`.`destinations`,
				`reserve`.`destinations`,
				`reserve`.`boats`

			WHERE
				`reserve`.`charters`.`charterID` = '$_GET[charter]'
				AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`destinations`.`boatID`
				AND `reserve`.`charters`.`boatID` = `reserve`.`boats`.`boatID`
				AND `af_df_unified2`.`destinations`.`name` = '$_GET[name]'

				AND `reserve`.`charters`.`destinationID` = `reserve`.`destinations`.`destinationID`

			";
         $result = $this->new_mysql($sql);
         while($row = $result->fetch_assoc()) {
				$di = $row['destination_image'];
				$start_date = $row['start_date'];
				$end_date = $row['end_date'];
				$description = $row['description'];
				$home_page = $row['home_page'];
            $embarkment = $row['embarkment'];
            $disembarkment = $row['disembarkment'];
            $itinerary = $row['itinerary'];
            $nights = $row['nights'];
			}
			$di = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$di);
			print "










			<table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
				<td>
					<img src=\"$di\" width=\"850\">
				</td>
			</tr>
			<tr><td>

				<table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">

       <tr>
         <td width=\"33%\" class=\"details-top\">&nbsp;&nbsp;<a class=\"details-top\" href=\"javascript:history.back()\">&lt; Previous</a></td>
         <td width=\"33%\" class=\"details-top\" align=\"center\"><a href=\"$home_page\" target=_blank class=\"details-top\">Learn More</a></td>
         <td width=\"33%\" class=\"details-top\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"document.getElementById('email_a_friend').style.display='inline';\" class=\"details-top\">Email A Friend</a>&nbsp;&nbsp;</td>
         </tr>


				<!--
					<tr>
						<td width=\"243\" class=\"details-top\">&nbsp;&nbsp;&nbsp;$start_date to $end_date</td>

						<td width=\"240\" class=\"details-top\">$description</td>


						<td width=\"366\" align=\"right\" class=\"details-top\">
							";if ($_SESSION['uuname'] != "") { print "<a href=\"logout.php\" class=\"details-top\">Logout</a>&nbsp;&nbsp;&nbsp;"; } print "
							<a href=\"$home_page\" target=_blank class=\"details-top\">Learn More</a>&nbsp;&nbsp;&nbsp;
							<a href=\"#\" class=\"details-top\"><a class=\"details-top\" href=\"javascript:history.back()\">Previous Page</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href=\"javascript:void(0)\" onclick=\"document.getElementById('email_a_friend').style.display='inline';\" class=\"details-top\">Email A Friend</a>&nbsp;</td>
					</tr>
				-->

				</table>
						
			</td></tr>

<tr>
   <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
     <tbody>
       <tr>
         <td width=\"50%\"><strong><span class=\"details-title-text\">Date:</span> $start_date to $end_date ($nights nights)</strong></td>
         <td width=\"50%\" align=\"right\"><strong><span class=\"details-title-text\">Embarkment:</span> $embarkment</strong></td>
         </tr>

       <tr>
         <td width=\"50%\"><strong><span class=\"details-title-text\">Itinerary:</span> $itinerary</strong></td>
         <td width=\"50%\" align=\"right\"><strong><span class=\"details-title-text\">Disembarkment:</span> $disembarkment</strong></td>
         </tr>

       </tbody>
   </table></td>
  </tr>


			</table>
			<div style=\"clear:both;\"></div>
			";

		
			$num1 = rand(2,50);
			$num2 = rand(2,50);	
			$_SESSION['num1'] = $num1;
			$_SESSION['num2'] = $num2;
         $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

         print "
            <div id=\"email_a_friend\" style=\"display:none\">
               <div id=\"email_a_friend_inner\">
						<form name=\"myform\">
						<input type=\"hidden\" name=\"link\" value=\"$actual_link\">
						<table border=0 cellspacing=0 cellpadding=3 width=\"320\">
						<tr class=\"email_a_friend_text\"><td width=100>Your Name:</td><td><input type=\"text\" name=\"your_name\" size=25></td></tr>
						<tr class=\"email_a_friend_text\"><td>Friends Name:</td><td><input type=\"text\" name=\"your_friend_name\" size=25></td></tr>
						<tr class=\"email_a_friend_text\"><td>Friends Email:</td><td><input type=\"text\" name=\"your_friend_email\" size=25></td></tr>
						<tr class=\"email_a_friend_text\"><td>Security Question:</td><td>What is $num1 plus $num2? <input type=\"text\" name=\"answer\" id=\"answer\" size=25></td></tr>
						<tr class=\"email_a_friend_text\"><td>&nbsp;</td><td><input type=\"button\" value=\"Send Email\" onclick=\"sendemail(this.form)\">&nbsp;&nbsp;<a class=\"email_a_friend_text\" href=\"javascript:void(0)\" onclick=\"document.getElementById('email_a_friend').style.display='none';\">Close</a></td></tr>
						</table>
						";



						print "
						</form>

               </div>
            </div>
         ";

			?>
			<script>
                                function sendemail(myform) {
                                        $.post('send_email.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#email_a_friend_inner").html(php_msg);
                                        });
                                }
			</script>
			<?php


			print "<div id=\"result_pos3\">";

			print "<table border=\"0\" width=\"820\" cellspacing=3>";
         $result = $this->new_mysql($sql);
         while($row = $result->fetch_assoc()) {
				if ($this_name != $row['name']) {
					$this_name = $row['name'];
				}

            $name = urlencode($row['name']);
				$var1 = "&name=$name&start_date=$_GET[start_date]&end_date=$_GET[end_date]&passengers=$_GET[passengers]&pass1_gend=$_GET[pass1_gend]&pass2_gend=$_GET[pass2_gend]&pass3_gend=$_GET[pass3_gend]&pass4_gend=$_GET[pass4_gend]$this_boats";


				if ($row['img1'] != "") {

					$row['img1'] = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$row['img1']);

					print "<tr><td colspan=\"3\"><hr></td></tr>";
					print "<tr>
						<td width=200 valign=top><img src=\"$row[img1]\" width=\"250\"></td>
						<td width=530 valign=top><span class=\"details-title-text\">$row[type1]</span><br><br><span class=\"details-description\">$row[desc1]</span></td>
						<td width=90 valign=top align=\"center\">";
						$price = $this->get_quick_rates($_GET['charter'],$row['bunks1']);

						// for multiple PAX
						if ($_GET['passengers'] > 1) {
							$total_inv = "0";
							$sql_inv = $this->get_inventory($_GET['charter'],$row['bunks1']);
							$result_inv = $this->new_mysql($sql_inv);

				         while($row2 = $result_inv->fetch_assoc()) {
								$total_inv++;
							}
						} else {
							$total_inv = $this->get_inv_count_one_pax($_GET['charter'],$row['bunks1']);
						}

						// check if bunks are avail
						if ($total_inv == "0") {
                     $sql_inv = $this->get_inventory($_GET['charter'],$row['bunks1']);
                     $result_inv = $this->new_mysql($sql_inv);

                     while($row2 = $result_inv->fetch_assoc()) {
                        $total_inv++;
                     }
						}


						// end multiple PAX

						if ($total_inv > 0) {
							$temp_d = "";
							$discount = $this->find_discount($_GET['charter'],$price);
							if (is_array($discount)) {
								foreach ($discount as $value) {
									if ($value > $temp_d) {
                              if(!is_array($value)) {
											$temp_d = $value;
										}
									}
								}
							}

							$quad_check = "";
							if (preg_match("/$row[type1]/i","Quad")) {
								$quad_check = "1";
							}

							if ($temp_d > 0) {
								if (($quad_check == "1") and ($_GET['passengers'] < 1)) {
                           $new_price = $price - $temp_d;
                           print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> <del class=\"details-prices2-red\">$" . number_format($price) ."</del> <ins style=\"text-decoration: none\" class=\"details-prices2\">$" . number_format($new_price) ."</ins></span><br><br><center>
									<span class=\"details-description\">Available for parties of 4</span></center><br>\n";
								} else {
									$new_price = $price - $temp_d;
									$_SESSION['boats'] = $_GET['boats'];
   	                     print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> <del class=\"details-prices2-red\">$" . number_format($price) ."</del> <ins style=\"text-decoration: none\" class=\"details-prices2\">$" . number_format($new_price) ."</ins></span><br><br>
									<center>";


									print "
									<form name=\"myform_img1\" style=\"display:inline\">
									<input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
									<input type=\"hidden\" name=\"boats\" value=\"$boats_array\">
									<input type=\"hidden\" name=\"type\" value=\"1\">
									<input type=\"hidden\" name=\"name\" value=\"$_GET[name]\">
									<input type=\"hidden\" name=\"passengers\" value=\"$_GET[passengers]\">
                           <div id=\"btn_img1\">
									<input type=\"image\" onclick=\"load_bunks_img1(this.form);return false;\" src=\"buttons/bt-select.png\" border=0>
									</div>
									</form>
									</center><br>\n";
								}

							} else {
                        if (($quad_check == "1") and ($_GET['passengers'] < 1)) {
                           print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br><center>
                           <span class=\"details-description\">Available for parties of 4</span></center><br>\n";
								} else {
									print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br>
									<center>
                           <form name=\"myform_img1\" style=\"display:inline\">
                           <input type=\"hidden\" name=\"boats\" value=\"$boats_array\">
                           <input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
                           <input type=\"hidden\" name=\"type\" value=\"1\">
                           <input type=\"hidden\" name=\"name\" value=\"$_GET[name]\">
                           <input type=\"hidden\" name=\"passengers\" value=\"$_GET[passengers]\">
                           <div id=\"btn_img1\">
                           <input type=\"image\" onclick=\"load_bunks_img1(this.form);return false;\" src=\"buttons/bt-select.png\" border=0>
									</div>
                           </form>
									</center><br>\n";
								}
							}
						} else {
                     print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br><center><a href=\"javascript:void(0)\" 
                     ><img src=\"buttons/bt-soldout.png\" border=0></a></center><br>\n";
						}

						print "</td>
					</tr>";
//RBS//
					print "<tr><td colspan=3>
					<div id=\"b_img1\">

					</div>
               <div id=\"b_img1_s\"> </div>
					</td></tr>";
				}

            if ($row['img2'] != "") {
               $row['img2'] = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$row['img2']);
               print "<tr><td colspan=\"3\"><hr></td></tr>";
               print "<tr>
                  <td width=200 valign=top><img src=\"$row[img2]\" width=\"250\"></td>
                  <td width=530 valign=top><span class=\"details-title-text\">$row[type2]</span><br><br><span class=\"details-description\">$row[desc2]</span></td>
                  <td width=90 valign=top align=\"center\">";
                  $price = $this->get_quick_rates($_GET['charter'],$row['bunks2']);


                  // for multiple PAX
                  if ($_GET['passengers'] > 1) {
                     $total_inv = "0";
                     $sql_inv = $this->get_inventory($_GET['charter'],$row['bunks2']);
                     $result_inv = $this->new_mysql($sql_inv);

                     while($row2 = $result_inv->fetch_assoc()) {
                        $total_inv++;
                     }
                  } else {
                     $total_inv = $this->get_inv_count_one_pax($_GET['charter'],$row['bunks2']);
                  }

                  // check if bunks are avail
                  if ($total_inv == "0") {
                     $sql_inv = $this->get_inventory($_GET['charter'],$row['bunks2']);
                     $result_inv = $this->new_mysql($sql_inv);

                     while($row2 = $result_inv->fetch_assoc()) {
                        $total_inv++;
                     }
                  }


                  if ($total_inv > 0) {
                     $temp_d = "";
                     $discount = $this->find_discount($_GET['charter'],$price);
                     if (is_array($discount)) {
                        foreach ($discount as $value) {
                           if ($value > $temp_d) {
                              if(!is_array($value)) {
	                              $temp_d = $value;
										}
                           }
                        }
                     }

							$quad_check = "";
                     if (preg_match("/$row[type2]/i","Quad")) {
                        $quad_check = "1";
                     }

                     if ($temp_d > 0) {
                        if (($quad_check == "1") and ($_GET['passengers'] < 1)) {
                           $new_price = $price - $temp_d;
                           print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> <del class=\"details-prices2-red\">$" . number_format($price) ."</del> <ins style=\"text-decoration: none\" class=\"details-prices2\">$".number_format($new_price)."</ins></span><br><br><center>
									<span class=\"details-description\">Available for parties of 4</span></center><br>\n";
								} else {
	                        $new_price = $price - $temp_d;
			                  print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> <del class=\"details-prices2-red\">$" . number_format($price) ."</del> <ins style=\"text-decoration: none\" class=\"details-prices2\">$".number_format($new_price)."</ins></span><br><br>
									<center>
                           <form name=\"myform_img2\" style=\"display:inline\">
                           <input type=\"hidden\" name=\"boats\" value=\"$boats_array\">
                           <input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
                           <input type=\"hidden\" name=\"type\" value=\"2\">
                           <input type=\"hidden\" name=\"name\" value=\"$_GET[name]\">
                           <input type=\"hidden\" name=\"passengers\" value=\"$_GET[passengers]\">
                           <div id=\"btn_img2\">
                           <input type=\"image\" onclick=\"load_bunks_img2(this.form);return false;\" src=\"buttons/bt-select.png\" border=0>
									</div>
                           </form>
									</center><br>\n";
								}
							} else {
                        if (($quad_check == "1") and ($_GET['passengers'] < 1)) {
                           print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br><center><span class=\"details-description\">Available for parties of 4</span><br>\n";
								} else {
		                     print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br>
									<center>
                           <form name=\"myform_img2\" style=\"display:inline\">
                           <input type=\"hidden\" name=\"boats\" value=\"$boats_array\">
                           <input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
                           <input type=\"hidden\" name=\"type\" value=\"2\">
                           <input type=\"hidden\" name=\"name\" value=\"$_GET[name]\">
                           <input type=\"hidden\" name=\"passengers\" value=\"$_GET[passengers]\">
									<div id=\"btn_img2\">
                           <input type=\"image\" onclick=\"load_bunks_img2(this.form);return false;\" src=\"buttons/bt-select.png\" border=0>
									</div>
                           </form>
									</center><br>\n";
								}

							}
						} else {
                     print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br><center><a href=\"javascript:void(0)\" 
                     ><img src=\"buttons/bt-soldout.png\" border=0></a></center><br>\n";
						}
						print "</td>
               </tr>";
               print "<tr><td colspan=3>
               <div id=\"b_img2\"> 
               </div>
					<div id=\"b_img2_s\"> </div>
               </td></tr>";

            }


            if ($row['img3'] != "") {
               $row['img3'] = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$row['img3']);
               print "<tr><td colspan=\"3\"><hr></td></tr>";
               print "<tr>
                  <td width=200 valign=top><img src=\"$row[img3]\" width=\"250\"></td>
                  <td width=530 valign=top><span class=\"details-title-text\">$row[type3]</span><br><br><span class=\"details-description\">$row[desc3]</span></td>
                  <td width=90 valign=top align=\"center\">";
                  $price = $this->get_quick_rates($_GET['charter'],$row['bunks3']);

                  // for multiple PAX
                  if ($_GET['passengers'] > 1) {
                     $total_inv = "0";
                     $sql_inv = $this->get_inventory($_GET['charter'],$row['bunks3']);
                     $result_inv = $this->new_mysql($sql_inv);

                     while($row2 = $result_inv->fetch_assoc()) {
                        $total_inv++;
                     }
                  } else {
                     $total_inv = $this->get_inv_count_one_pax($_GET['charter'],$row['bunks3']);
                  }

                  // check if bunks are avail
                  if ($total_inv == "0") {
                     $sql_inv = $this->get_inventory($_GET['charter'],$row['bunks3']);
                     $result_inv = $this->new_mysql($sql_inv);

                     while($row2 = $result_inv->fetch_assoc()) {
                        $total_inv++;
                     }
                  }

                  if ($total_inv > 0) {
                     $temp_d = "";
                     $discount = $this->find_discount($_GET['charter'],$price);
                     if (is_array($discount)) {
                        foreach ($discount as $value) {
                           if ($value > $temp_d) {
                              if(!is_array($value)) {
	                              $temp_d = $value;
										}
                           }
                        }
                     }

							$quad_check = "";
                     if (preg_match("/$row[type3]/i","Quad")) {
                        $quad_check = "1";
                     }

                     if ($temp_d > 0) {
                        if (($quad_check == "1") and ($_GET['passengers'] < 1)) {
                           $new_price = $price - $temp_d;
                           print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> <del class=\"details-prices2-red\">$" . number_format($price) ."</del> <ins style=\"text-decoration: none\" class=\"details-prices2\">$".number_format($new_price)."</ins></span><br><br><center>
                           <span class=\"details-description\">Available for parties of 4</span></center><br>\n";
								} else {
	                        $new_price = $price - $temp_d;
		                     print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> <del class=\"details-prices2-red\">$" . number_format($price) ."</del> <ins style=\"text-decoration: none\" class=\"details-prices2\">$".number_format($new_price)."</ins></span><br><br>
									<center>
                           <form name=\"myform_img3\" style=\"display:inline\">
                           <input type=\"hidden\" name=\"boats\" value=\"$boats_array\">
                           <input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
                           <input type=\"hidden\" name=\"type\" value=\"3\">
                           <input type=\"hidden\" name=\"name\" value=\"$_GET[name]\">
                           <input type=\"hidden\" name=\"passengers\" value=\"$_GET[passengers]\">
                           <div id=\"btn_img3\">
                           <input type=\"image\" onclick=\"load_bunks_img3(this.form);return false;\" src=\"buttons/bt-select.png\" border=0>
									</div>
                           </form>
									</center><br>\n";
								}
							} else {
                        if (($quad_check == "1") and ($_GET['passengers'] < 1)) {
                           print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br><center>
                           <span class=\"details-description\">Available for parties of 4</span></center><br>\n";
								} else {
	                        print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br>
									<center>
                           <form name=\"myform_img3\" style=\"display:inline\">
                           <input type=\"hidden\" name=\"boats\" value=\"$boats_array\">
                           <input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
                           <input type=\"hidden\" name=\"type\" value=\"3\">
                           <input type=\"hidden\" name=\"name\" value=\"$_GET[name]\">
                           <input type=\"hidden\" name=\"passengers\" value=\"$_GET[passengers]\">
                           <div id=\"btn_img3\">
                           <input type=\"image\" onclick=\"load_bunks_img3(this.form);return false;\" src=\"buttons/bt-select.png\" border=0>
									</div>
                           </form>
   		                  </center><br>\n";
								}
							}
                  } else {
                     print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br><center><a href=\"javascript:void(0)\" 
                     ><img src=\"buttons/bt-soldout.png\" border=0></a></center><br>\n";
                  }

						print "</td>
               </tr>";
               print "<tr><td colspan=3>
               <div id=\"b_img3\"> 
                     
               </div>
               <div id=\"b_img3_s\"> </div>
               </td></tr>";
            }


            if ($row['img4'] != "") {
               $row['img4'] = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$row['img4']);
               print "<tr><td colspan=\"3\"><hr></td></tr>";
               print "<tr>
                  <td width=200 valign=top><img src=\"$row[img4]\" width=\"250\"></td>
                  <td width=530 valign=top><span class=\"details-title-text\">$row[type4]</span><br><br><span class=\"details-description\">$row[desc4]</span></td>
                  <td width=90 valign=top align=\"center\">";
                  $price = $this->get_quick_rates($_GET['charter'],$row['bunks4']);

                  // for multiple PAX
                  if ($_GET['passengers'] > 1) {
                     $total_inv = "0";
                     $sql_inv = $this->get_inventory($_GET['charter'],$row['bunks4']);
                     $result_inv = $this->new_mysql($sql_inv);

                     while($row2 = $result_inv->fetch_assoc()) {
                        $total_inv++;
                     }
                  } else {
                     $total_inv = $this->get_inv_count_one_pax($_GET['charter'],$row['bunks4']);
                  }

                  // check if bunks are avail
                  if ($total_inv == "0") {
                     $sql_inv = $this->get_inventory($_GET['charter'],$row['bunks4']);
                     $result_inv = $this->new_mysql($sql_inv);

                     while($row2 = $result_inv->fetch_assoc()) {
                        $total_inv++;
                     }
                  }

                  if ($total_inv > 0) {
                     $temp_d = "";
                     $discount = $this->find_discount($_GET['charter'],$price);
                     if (is_array($discount)) {
                        foreach ($discount as $value) {
                           if ($value > $temp_d) {
                              if(!is_array($value)) {
	                              $temp_d = $value;
										}
                           }
                        }
                     }

                     $quad_check = "";
                     if (preg_match("/$row[type4]/i","Quad")) {
                        $quad_check = "1";
                     }


                     if ($temp_d > 0) {
                        if (($quad_check == "1") and ($_GET['passengers'] < 1)) {
                           $new_price = $price - $temp_d;
                           print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> <del class=\"details-prices2-red\">$" . number_format($price) ."</del> <ins style=\"text-decoration: none\" class=\"details-prices2\">$".number_format($new_price)."</ins></span><br><br><center>
                           <span class=\"details-description\">Available for parties of 4</span></center><br>\n";
								} else {
	                        $new_price = $price - $temp_d;
		                     print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> <del class=\"details-prices2-red\">$" . number_format($price) ."</del> <ins style=\"text-decoration: none\" class=\"details-prices2\">$".number_format($new_price)."</ins></span><br><br>
									<center>
                           <form name=\"myform_img4\" style=\"display:inline\">
                           <input type=\"hidden\" name=\"boats\" value=\"$boats_array\">
                           <input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
                           <input type=\"hidden\" name=\"type\" value=\"4\">
                           <input type=\"hidden\" name=\"name\" value=\"$_GET[name]\">
                           <input type=\"hidden\" name=\"passengers\" value=\"$_GET[passengers]\">
                           <div id=\"btn_img4\">
                           <input type=\"image\" onclick=\"load_bunks_img4(this.form);return false;\" src=\"buttons/bt-select.png\" border=0>
									</div>
                           </form>
									</center><br>\n";
								}
							} else {
                        if (($quad_check == "1") and ($_GET['passengers'] < 1)) {
                           print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br><center>
                           <span class=\"details-description\">Available for parties of 4</span></center><br>\n";
								} else {
	                        print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br>
									<center>
                           <form name=\"myform_img4\" style=\"display:inline\">
                           <input type=\"hidden\" name=\"boats\" value=\"$boats_array\">
                           <input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
                           <input type=\"hidden\" name=\"type\" value=\"4\">
                           <input type=\"hidden\" name=\"name\" value=\"$_GET[name]\">
                           <input type=\"hidden\" name=\"passengers\" value=\"$_GET[passengers]\">
                           <div id=\"btn_img4\">
                           <input type=\"image\" onclick=\"load_bunks_img4(this.form);return false;\" src=\"buttons/bt-select.png\" border=0>
									</div>
                           </form>
									</center><br>\n";
								}
							}
                  } else {
                     print "<span class=\"details-prices\">USD PP</span><span class=\"details-prices2\"> $" . number_format($price) ."</span><br><br><center><a href=\"javascript:void(0)\" 
                     ><img src=\"buttons/bt-soldout.png\" border=0></a></center><br>\n";
                  }

						print "</td>
               </tr>";
               print "<tr><td colspan=3>
               <div id=\"b_img4\"> 
                     
               </div>
               <div id=\"b_img4_s\"> </div>
               </td></tr>";

            }

			}


			print "</table>";

			?>
			<script>
         function load_bunks_img1(myform_img1) {
 			  document.getElementById('b_img1').style.display='inline';
           document.getElementById('btn_img1').style.display='none';
           scrollTo($('#b_img1_s'), 500);
 	        $.get('load_beds.php',
           $(myform_img1).serialize(),
           function(php_msg) {
    	       $("#b_img1").html(php_msg);
           });
         }

         function load_bunks_img2(myform_img2) {
           document.getElementById('b_img2').style.display='inline';
			  document.getElementById('btn_img2').style.display='none';
			  scrollTo($('#b_img2_s'), 500);
           $.get('load_beds.php',
           $(myform_img2).serialize(),
           function(php_msg) {
             $("#b_img2").html(php_msg);
           });

         }

         function load_bunks_img3(myform_img3) {
            document.getElementById('b_img3').style.display='inline';
           document.getElementById('btn_img3').style.display='none';
           scrollTo($('#b_img3_s'), 500);
           $.get('load_beds.php',
           $(myform_img3).serialize(),
           function(php_msg) {
             $("#b_img3").html(php_msg);
           });
         }

         function load_bunks_img4(myform_img4) {
            document.getElementById('b_img4').style.display='inline';
           document.getElementById('btn_img4').style.display='none';
           scrollTo($('#b_img4_s'), 500);
           $.get('load_beds.php',
           $(myform_img4).serialize(),
           function(php_msg) {
             $("#b_img4").html(php_msg);
           });
         }



			</script>
			<?

         print "</div></div></div></div>";

	}

   public function get_sex($charter,$bunk) {

		$sex = array();
		$bunk = substr($bunk,-3);
		$bunk = substr($bunk,0,-1);

      $sql = "
		SELECT
			`reserve`.`inventory`.`bunk`,
			`reserve`.`inventory`.`passengerID`

		FROM
			`reserve`.`inventory`

		WHERE
			`reserve`.`inventory`.`charterID` = '$charter'
			AND `reserve`.`inventory`.`bunk` LIKE '%$bunk%'

      ";

		$result = $this->new_mysql($sql);
      while($row = $result->fetch_assoc()) {
			if ($row['passengerID'] > 0) {
				//check sex of existing contact
				$sql2 = "SELECT `reserve`.`contacts`.`sex` FROM `reserve`.`contacts` WHERE `reserve`.`contacts`.`contactID` = '$row[passengerID]'";
				$result2 = $this->new_mysql($sql2);
				$row2 = $result2->fetch_assoc();
				$sex[] = $row2['sex'];
			}
		}

		$male = "0";
		$female = "0";

		if(is_array($sex)) {
			foreach ($sex as $value) {
				if ($value == "male") {
					$male++;
				}
				if ($value == "female") {
					$female++;
				}
			}
		}

		if (($male == "0") and ($female == "0")) {
			// mf
			$return_sex_value = "
			<label for=\"mf\"><img src=\"../resellers/icn-male_female.jpg\" title=\"Available for male or female guest\" border=0></label>";
		}

		if (($male > 0) and ($female > 0)) {
			// couple
			$return_sex_value = "
			<label for=\"couple\"><img src=\"../resellers/icn-male_female.jpg\" title=\"Available for male or female guest\" border=0></label>";
		}

		if (($male > 0) and ($female == "0")) {
			// male
			$return_sex_value = "
			<label for=\"male\"><img src=\"../resellers/icn-male.jpg\" title=\"Available for male guest only\" border=0></label>";
		}

		if (($male == 0) and ($female > 0)) {
			// female
			$return_sex_value = "
			<label for=\"female\"><img src=\"../resellers/icn-female.jpg\" title=\"Available for female guest only\"  border=0></label>";
		}

		if ($return_sex_value == "") {
			// no results
         $return_sex_value = "
         <label for=\"default\"><img src=\"../resellers/icn-male_female.jpg\" title=\"Available for male or female guest\"  border=0></label>";
		}
		return $return_sex_value;
   }

   public function get_sex2($charter,$bunk) {
      $sex = array();
      $bunk = substr($bunk,-3);
      $bunk = substr($bunk,0,-1);

      $sql = "
      SELECT
         `reserve`.`inventory`.`bunk`,
         `reserve`.`inventory`.`passengerID`

      FROM
         `reserve`.`inventory`

      WHERE
         `reserve`.`inventory`.`charterID` = '$charter'
         AND `reserve`.`inventory`.`bunk` LIKE '%$bunk%'

      ";

      $result = $this->new_mysql($sql);
      while($row = $result->fetch_assoc()) {
         if ($row['passengerID'] > 0) {
            //check sex of existing contact
            $sql2 = "SELECT `reserve`.`contacts`.`sex` FROM `reserve`.`contacts` WHERE `reserve`.`contacts`.`contactID` = '$row[passengerID]'";
            $result2 = $this->new_mysql($sql2);
            $row2 = $result2->fetch_assoc();
            $sex[] = $row2['sex'];
         }
      }

      $male = "0";
      $female = "0";

      if(is_array($sex)) {
         foreach ($sex as $value) {
            if ($value == "male") {
               $male++;
            }
            if ($value == "female") {
               $female++;
            }
         }
      }

      if (($male == "0") and ($female == "0")) {
         // mf
         $return_sex_value = "male_female";
      }

      if (($male > 0) and ($female > 0)) {
         // couple
         $return_sex_value = "male_female";
      }

      if (($male > 0) and ($female == "0")) {
         // male
         $return_sex_value = "male";
      }

      if (($male == 0) and ($female > 0)) {
         // female
         $return_sex_value = "female";
      }

      if ($return_sex_value == "") {
         // no results
         $return_sex_value = "male_female";
      }
      return $return_sex_value;
   }

	public function view_bunks() {

				$_GET['boats'] = stripslashes($_GET['boats']);

				$new_boats = unserialize($_GET['boats']);
	
            foreach ($new_boats as $boat) {
               $boats .= "$boat,";
            }
            $boats = substr($boats,0,-1);

            foreach ($new_boats as $boat2) {
               $this_boats .= "&boats[]=$boat2";
            }
	

				$new_name = $_GET['name'];
				if ($new_name == "Caño Island Okeanos") {
					$new_name = "Ca&ntilde;o Island Okeanos";
				}
            if ($new_name == "Caño Island Okeanos II") {
               $new_name = "Ca&ntilde;o Island Okeanos II";
            }

	         $sql = "
   	      SELECT 
      	      `af_df_unified2`.`destinations`.*,
         	   DATE_FORMAT(`reserve`.`charters`.`start_date`, '%b %d, %Y') AS 'start_date',
            	DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%b %d, %Y') AS 'end_date',
	            `reserve`.`destinations`.`description`

   	      FROM
      	      `reserve`.`charters`,
         	   `af_df_unified2`.`destinations`,
            	`reserve`.`destinations`

	         WHERE
	            `reserve`.`charters`.`charterID` = '$_GET[charter]'
   	         AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`destinations`.`boatID`
      	      AND `af_df_unified2`.`destinations`.`name` = '$new_name'

         	   AND `reserve`.`charters`.`destinationID` = `reserve`.`destinations`.`destinationID`

	         ";
   	      $result = $this->new_mysql($sql);
      	   while($row = $result->fetch_assoc()) {
         	   $di = $row['destination_image'];
	            $start_date = $row['start_date'];
   	         $end_date = $row['end_date'];
      	      $description = $row['description'];
					$bunks1 = $row['bunks1'];
	            $bunks2 = $row['bunks2'];
   	         $bunks3 = $row['bunks3'];
      	      $bunks4 = $row['bunks4'];
					$img1 = $row['img1'];
					$img2 = $row['img2'];
					$img3 = $row['img3'];
					$img4 = $row['img4'];
					$type1 = $row['type1'];
					$type2 = $row['type2'];
					$type3 = $row['type3'];
					$type4 = $row['type4'];
					$desc1 = $row['desc1'];
					$desc2 = $row['desc2'];
					$desc3 = $row['desc3'];
					$desc4 = $row['desc4'];
	         }


         $sql = "
         SELECT
            `af_df_unified2`.`destinations`.*,
            DATE_FORMAT(`reserve`.`charters`.`start_date`, '%b %d, %Y') AS 'start_date',
            DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%b %d, %Y') AS 'end_date',
            `reserve`.`destinations`.`description`,
            `reserve`.`boats`.`home_page`

         FROM
            `reserve`.`charters`,
            `af_df_unified2`.`destinations`,
            `reserve`.`destinations`,
            `reserve`.`boats`

         WHERE
            `reserve`.`charters`.`charterID` = '$_GET[charter]'
            AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`destinations`.`boatID`
            AND `reserve`.`charters`.`boatID` = `reserve`.`boats`.`boatID`
            AND `af_df_unified2`.`destinations`.`name` = '$_GET[name]'

            AND `reserve`.`charters`.`destinationID` = `reserve`.`destinations`.`destinationID`

         ";
         $result = $this->new_mysql($sql);
         while($row = $result->fetch_assoc()) {
            $di = $row['destination_image'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $description = $row['description'];
            $home_page = $row['home_page'];
         }

         $di = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$di);

   	      print "<table border=\"0\" width=\"800\" cellspacing=3>";

				print '
						<tr><td width="300">&nbsp;</td>
						<td width="90" class="view_details-description">Gender</td>
							<td width="146" class="view_details-description">Stateroom</td>
							<td width="156" class="view_details-description">Amount</td>
							<td width="166"></tr>
				';
				switch ($_GET['type']) {
					case "1":
						if (($_GET['passengers'] == "1") && ($_SESSION['contact_type'] == "consumer")) {
							$sql = $this->get_inv_count_one_pax_results($_GET['charter'],$bunks1);
						} else {
							$sql = $this->get_inventory($_GET['charter'],$bunks1);
						}
						$image = $img1;
						$type = $type1;
						$desc = $desc1;
					break;


					case "2":
                  if (($_GET['passengers'] == "1") && ($_SESSION['contact_type'] == "consumer")) {
                     $sql = $this->get_inv_count_one_pax_results($_GET['charter'],$bunks2);
                  } else {
                     $sql = $this->get_inventory($_GET['charter'],$bunks2);
                  }
						$image = $img2;
						$type = $type2;
						$desc = $desc2;
					break;


					case "3":
                  if (($_GET['passengers'] == "1") && ($_SESSION['contact_type'] == "consumer")) {
                     $sql = $this->get_inv_count_one_pax_results($_GET['charter'],$bunks3);
                  } else {
                     $sql = $this->get_inventory($_GET['charter'],$bunks3);
                  }
						$image = $img3;
						$type = $type3;
						$desc = $desc3;
					break;


					case "4":
                  if (($_GET['passengers'] == "1") && ($_SESSION['contact_type'] == "consumer")) {
                     $sql = $this->get_inv_count_one_pax_results($_GET['charter'],$bunks4);
                  } else {
                     $sql = $this->get_inventory($_GET['charter'],$bunks4);
                  }
						$image = $img4;
						$type = $type4;
						$desc = $desc4;
					break;
				}


				// if no results with PAX 1
				if (($_GET['passengers'] == "1") and ($sql == "")) {
	            switch ($_GET['type']) {
   	            case "1":
                     $sql = $this->get_inventory($_GET['charter'],$bunks1);
                  $image = $img1;
                  $type = $type1;
                  $desc = $desc1;
               break;

               
               case "2":
                     $sql = $this->get_inventory($_GET['charter'],$bunks2);
                  $image = $img2;
                  $type = $type2;
                  $desc = $desc2;
               break;

               
               case "3":
                     $sql = $this->get_inventory($_GET['charter'],$bunks3);
                  $image = $img3;
                  $type = $type3;
                  $desc = $desc3;
               break;

               
               case "4":
                     $sql = $this->get_inventory($_GET['charter'],$bunks4);
                  $image = $img4;
                  $type = $type4;
                  $desc = $desc4;
               break;
            	}
				}


	         $image = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$image);

            $name = urlencode($_GET['name']);
            $var1 = "&name=$name&start_date=$_GET[start_date]&end_date=$_GET[end_date]&passengers=$_GET[passengers]$this_boats";

				if ($sql != "") {
		         $result = $this->new_mysql($sql);
   		      while($row = $result->fetch_assoc()) {
						$bunk = substr($row['bunk'],-3);
							if (preg_match("/Quad/i",$row['bunk_description'])) {
								$sex = "Quad";
								// find out default sex for Quad
								$sql_q = "
								SELECT
									`contacts`.`sex`

								FROM
									`inventory`,`contacts`

								WHERE
									`inventory`.`charterID` = '$_GET[charter]'
									AND `inventory`.`bunk_description` LIKE '%Quad%'
									AND `inventory`.`passengerID` = `contacts`.`contactID`

								";
								//print "SQL: $sql_q<br><br>\n";
								$result_q = $this->new_mysql($sql_q);
								while ($row_q = $result_q->fetch_assoc()) {
									if (($so == "") && ($row_q['sex'] != "")) {
										$so = $row_q['sex'];
									}
								}
								switch ($so) {
									case "male":
									$sex = "
						         <a href=\"javascript:void(0)\" onclick=\"alert('Available for male guest only.')\"><img src=\"../resellers/icn-male.jpg\" border=0></a>";
									break;

									case "female":
									$sex = "
						         <a href=\"javascript:void(0)\" onclick=\"alert('Available for female guest only.')\"><img src=\"../resellers/icn-female.jpg\" border=0></a>";
									break;

									default:
									$sex = "
						         <a href=\"javascript:void(0)\" onclick=\"alert('Available for male or female guest.')\"><img src=\"../resellers/icn-male_female.jpg\" border=0></a>";
									break;
								}
								


							} else {
								$sex = $this->get_sex($_GET['charter'],$row['bunk']);
							}
						print "<tr><td width=\"250\">&nbsp;</td>
						<td class=\"details-description\">".$sex."</td>
						<td class=\"details-description\">$bunk</td>";

                     $temp_d = "";
                     $discount = $this->find_discount($_GET['charter'],$row['bunk_price']);
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
							print "
							<td class=\"details-description\"><del><font color=red>$".number_format($row['bunk_price'])."</font></del> <ins style=\"text-decoration: none\">$".number_format($new_price)."</ins></td>
							";
						} else {
                     print "
                     <td class=\"details-description\">$".number_format($row['bunk_price'])."</td>
                     ";
						}
						print "
						<td id=\"inv_$row[inventoryID]\">";
						if ($row['sessionID'] == $_SESSION['sessionID']) {
                     print "
                     <form name=\"MyForm\"><input type=\"hidden\" name=\"cancel\" value=\"$row[inventoryID]\">
                     <input type=\"image\" src=\"buttons/bt-cancel.png\" name=\"inventoryID_$row[inventoryID]\" id=\"inventoryID_$row[inventoryID]\" onclick=\"quickbook".$row['inventoryID']."(this.form);return false;\"> <a href=\"javascript:void(0)\" title=\"You have reserved this space for 30 minutes. To checkout click Continue.\">reserved</a>
                     </form>
                     </td>
                     </tr>";
                     ?>
                                <script>
                                function quickbook<?php echo $row['inventoryID'];?>(myform) {
                                        $.post('quick_book.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#inv_<?php echo $row['inventoryID'];?>").html(php_msg);
                                        });
                                }
                                </script>
                     <?php
						} else {
							if (($row['sessionID'] == "") && ($row['userID'] == "") or ($row['userID'] == "0")) {
								print "
								<form name=\"MyForm\"><input type=\"hidden\" name=\"qb\" value=\"$row[inventoryID]\">";
								if ($_GET['passengers'] == "1") {
									print "<input type=\"image\" src=\"buttons/bt-select.png\" name=\"inventoryID_$row[inventoryID]\" id=\"inventoryID_$row[inventoryID]\" onclick=\"quickbook".$row['inventoryID']."(this.form);return false;\">";
								} else {
                           print "<input type=\"image\" src=\"buttons/bt-booknow.png\" name=\"inventoryID_$row[inventoryID]\" id=\"inventoryID_$row[inventoryID]\" onclick=\"quickbook".$row['inventoryID']."(this.form);return false;\">";
								}
								print "
								</form>
								</td>
								</tr>";
								?>
                                <script>
                                function quickbook<?php echo $row['inventoryID'];?>(myform) {
                                        $.post('quick_book.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#inv_<?php echo $row['inventoryID'];?>").html(php_msg);
                                        });
                                }
                                </script>

								<?php
							} else {
								print "<input type=\"button\" value=\"On Hold\">";
							}
						}
					}
				} else {
					print "ERROR";
				}

				$rand = rand(100,4000);

				print "
					<tr><td colspan=4>&nbsp;</td><td><br><div id=\"result"; echo $rand; print "\"></div></td></tr>
					<tr><td colspan=4>&nbsp;</td><td><br><div id=\"timeleft"; echo $rand; print "\" style=\"display:inline\"></div>
					</table>
					</td></tr>
					</table>
				";

		?>
		<script type="text/javascript">
		function refreshDiv() {
		    $('#result<?=$rand;?>').load('check_booked.php?charter=<?=$_GET['charter'];?><?=$var1;?>', function(){ /* callback code here */ });
			$('#timeleft<?=$rand;?>').load('check_time2.php?charter=<?=$_GET['charter'];?>', function(){ /* callback code here */ });

		}
		setInterval(refreshDiv, 1000);
		</script>


		<?php

	}

	public function general_error($msg) {
		print "<span class=\"details-description\">";
		print "<br><br><font color=red>$msg</font><br><br>\n";
		print "</span>";
	}

	public function review_bunks() {

         print "
         <div id=\"result_wrapper\">
            <div id=\"result_pos1\">
               <div id=\"result_pos2\"><br>
                  ";

         foreach ($_GET['boats'] as $boat) {
            $boats .= "$boat,";
         }
         $boats = substr($boats,0,-1);

         foreach ($_GET['boats'] as $boat2) {
            $this_boats .= "&boats[]=$boat2";
         }

			// The & can not be passed in the URL
			if ($_GET['name'] == "Turks ") {
				$_GET['name'] = "Turks & Caicos Aggressor II";
			}

         $sql = "
         SELECT 
            `af_df_unified2`.`destinations`.*,
            DATE_FORMAT(`reserve`.`charters`.`start_date`, '%b %d, %Y') AS 'start_date',
            DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%b %d, %Y') AS 'end_date',
            `reserve`.`destinations`.`description`,
				`reserve`.`charters`.`embarkment`,
				`reserve`.`charters`.`disembarkment`

         FROM
            `reserve`.`charters`,
            `af_df_unified2`.`destinations`,
            `reserve`.`destinations`

         WHERE
            `reserve`.`charters`.`charterID` = '$_GET[charter]'
            AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`destinations`.`boatID`
            AND `af_df_unified2`.`destinations`.`name` = '$_GET[name]'

            AND `reserve`.`charters`.`destinationID` = `reserve`.`destinations`.`destinationID`

         ";


         $result = $this->new_mysql($sql);
         while($row = $result->fetch_assoc()) {
            $di = $row['destination_image'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $description = $row['description'];
            $bunks1 = $row['bunks1'];
            $bunks2 = $row['bunks2'];
            $bunks3 = $row['bunks3'];
            $bunks4 = $row['bunks4'];
            $img1 = $row['img1'];
            $img2 = $row['img2'];
            $img3 = $row['img3'];
            $img4 = $row['img4'];
            $type1 = $row['type1'];
            $type2 = $row['type2'];
            $type3 = $row['type3'];
            $type4 = $row['type4'];
            $desc1 = $row['desc1'];
            $desc2 = $row['desc2'];
            $desc3 = $row['desc3'];
            $desc4 = $row['desc4'];
				$embarkment = $row['embarkment'];
				$disembarkment = $row['disembarkment'];
         }
			$di = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$di);
         print "
         <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\">
         <tr>
            <td>
               <img src=\"$di\" width=\"850\">
            </td>
         </tr>
         <tr><td>
                  
            <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">
               <tr>
                  <td width=\"263\" class=\"details-top\">&nbsp;&nbsp;&nbsp;$start_date to $end_date</td>
                  
                  <td width=\"303\" class=\"details-top\">$embarkment / $disembarkment</td>
            

                  <td width=\"283\" align=\"right\" class=\"details-top\">&nbsp;&nbsp;&nbsp;<a href=\"#\" class=\"details-top\">

							";
								print "<a class=\"details-top\" href=\"javascript:history.back()\">Previous Page</a>&nbsp;&nbsp;&nbsp;";
					print "
					</td>
               </tr>
            </table> 
                  
         </td></tr>
         </table>





         <div style=\"clear:both;\"></div>
         ";

         print "<div id=\"result_pos3\">";
				print "<div id=\"bookit\">

            <form name=\"myform\" id=\"myform\">
				";

				print "<br><span class=\"details-title-text\">Review Reservation Details (Step 2 of 3)</span><br><br>";

				$today = date("Ymd");
				$three = date("Ymd", strtotime($today . '-3 year'));
				$sql = "
				SELECT
					`r`.`reservationID`,
					`r`.`reservation_date`,
					`r`.`reseller_agentID`,
					`rs`.`company`,
					`rs`.`resellerID`,
					`c`.`conversion_question`,
					IF(`r`.`reservation_date` < '$three', 'FALSE','TRUE') AS 'ask_referral'

				FROM
					`inventory` i, `reservations` r, `contacts` c, `reseller_agents` ra, `resellers` rs

				WHERE
					`i`.`passengerID` = '$_SESSION[contactID]'
					AND `i`.`reservationID` = `r`.`reservationID`
					AND `i`.`passengerID` = `c`.`contactID`
					AND `c`.`conversion_question` = ''
					AND `r`.`reservation_date` < '$today'
					AND `r`.`reseller_agentID` = `ra`.`reseller_agentID`
					AND `ra`.`resellerID` = `rs`.`resellerID`

				ORDER BY `r`.`reservation_date` DESC

				LIMIT 1

				";

				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
					$row['ask_referral'] = "TRUE";
					if (($row['conversion_question'] == "") && ($row['ask_referral'] == "TRUE")) {
						print "
							<table border=1 width=90%>
								<tr><td>
									<table border=0 width=100%>
										<tr><td>
											<br>
											<b>We detected you last booked with <font color=blue><u>$row[company]</u></font>. Do you still do business with $row[company]? <input type=\"checkbox\" name=\"agent_$row[resellerID]\"> (<font color=blue>Click the checkbox if you still do business with this company</font>)</b>
											<br>
										</td></tr>
									</table>
								</td></tr>
							</table><br><br>
						";
					}
				}

				print "
				<span class=\"details-description\">
				Please review your selection below and click <b>Book Your Reservation</b>. ";
				if ($_GET['passengers'] > 1) {
					print "If your selection is not correct, click <b>Previous Page</b> and make changes.";
				} else {
					print "If your selection is not correct click <a href=\"index.php?s=1\">search again</a>.";
				}
				print "
				<br><br>
				If you qualify for a <a href=\"http://www.aggressor.com/rates.php#moneysaving\" target=_blank>Money Saving Special</a>, please include the details in the notes section below before clicking <b>Book Your Reservation</b>.  An agent will verify the discount and apply it to your reservation within 2 business days.
				<br><br>
				</span><br>";

            switch ($_SESSION['contact_type']) {
               case "consumer":
					$me = "<td width=\"100\"  class=\"view_details-description\">Me</td>";
					break;
				}

				print "<table border=\"0\" width=\"800\">
                  <tr>
							<td width=\"75\">&nbsp;</td>
                     <td width=\"34\">&nbsp;</td>
                     <td width=\"132\" class=\"view_details-description\">Stateroom</td>
							<td width=\"300\" class=\"view_details-description\">Bed Type</td>
							$me
                     <td width=\"100\" class=\"view_details-description\">Amount</td>
							<td width=\"100\" class=\"view_details-description\">Gender</td>
                  </tr>";

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

				// get inventory and place into an array
				$inv = array();
				$counter = 0;
            $result = $this->new_mysql($sql);
            while ($row = $result->fetch_assoc()) {
					$counter++;
					$inv[] = $row['inventoryID'];
				}


				// look through inventory
				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
               $bunk = substr($row['bunk'],-3);
					// Quad Only
               if (preg_match("/Quad/i",$row['bunk_description'])) {
                  $sex = "Quad";
                  $quad = "yes";
                  // find out default sex for Quad
                  $sql_q = "
                  SELECT
                     `contacts`.`sex`
                   FROM
                     `inventory`,`contacts`
                   WHERE
                     `inventory`.`charterID` = '$_GET[charter]'
                     AND `inventory`.`bunk_description` LIKE '%Quad%'
                     AND `inventory`.`passengerID` = `contacts`.`contactID`
                  ";
						$result_q = $this->new_mysql($sql_q);
                  while ($row_q = $result_q->fetch_assoc()) {
                  	if (($so == "") && ($row_q['sex'] != "")) {
                     	$so = $row_q['sex'];
                     }
                  }
						switch ($so) {
							case "male":
							$sex2 = "male";
							$sex = "";
							break;
							case "female":
							$sex2 = "female";
							$sex = "";
							break;
						}
					} else {
	               $sex = $this->get_sex($_GET['charter'],$row['bunk']);
                  $sex2 = $this->get_sex2($_GET['charter'],$row['bunk']);
					}
					if ($sex2 == $_SESSION['sex']) {
						$ok_found_sex = "1";
					}
					if ($sex2 == "male_female") {
						$found_open_bunk = "1";
					}
					//print "TEST: $row[inventoryID] | $sex2 | $_SESSION[sex]<br>";

				}
				// end inventory

				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
					$bunk = substr($row['bunk'],-3);


                     if (preg_match("/Quad/i",$row['bunk_description'])) {
                        $sex = "Quad";
								$quad = "yes";
                        // find out default sex for Quad
                        $sql_q = "
                        SELECT
                           `contacts`.`sex`

                        FROM
                           `inventory`,`contacts`

                        WHERE
                           `inventory`.`charterID` = '$_GET[charter]'
                           AND `inventory`.`bunk_description` LIKE '%Quad%'
                           AND `inventory`.`passengerID` = `contacts`.`contactID`

                        ";
                        $result_q = $this->new_mysql($sql_q);
                        while ($row_q = $result_q->fetch_assoc()) {
                           if (($so == "") && ($row_q['sex'] != "")) {
                              $so = $row_q['sex'];
                           }
                        }
                        switch ($so) {
                           case "male":
									$sex2 = "male";
                           $sex = "
                           <a href=\"javascript:void(0)\" onclick=\"
                           document.getElementById('malefemale').style.display='none';
                           document.getElementById('male').style.display='inline';
                           document.getElementById('female').style.display='none';
                           \">
                           <img src=\"../resellers/icn-male.jpg\" border=0></a>";

                           break;

                           case "female":
									$sex2 = "female";
                           $sex = "
                           <a href=\"javascript:void(0)\" onclick=\"
                           document.getElementById('malefemale').style.display='none';
                           document.getElementById('male').style.display='none';
                           document.getElementById('female').style.display='inline';
                           \">
                           <img src=\"../resellers/icn-female.jpg\" border=0></a>";
                           break;

                           default:
                           $sex = "
                           <a href=\"javascript:void(0)\" onclick=\"
                           document.getElementById('malefemale').style.display='inline';
                           document.getElementById('male').style.display='none';
                           document.getElementById('female').style.display='none';
                           \">
                           <img src=\"../resellers/icn-male_female.jpg\" border=0></a>";

                           break;
                        }



                     } else {
                        $sex = $this->get_sex($_GET['charter'],$row['bunk']);
                        $sex2 = $this->get_sex2($_GET['charter'],$row['bunk']);

                     }





						//$sex2 = $this->get_sex2($_GET['charter'],$row['bunk']);
					print "<tr class=\"details-description\">
						<td>&nbsp;</td>
						<td>".$this->get_sex($_GET['charter'],$row['bunk'])."</td>
						<td>$bunk</td>
						<td>$row[bunk_description]</td>";

						$set_proper_sex = "";

		            switch ($_SESSION['contact_type']) {
      	         case "consumer":

						if ($_GET['passengers'] == "1") {
							if (($primary == "") and ($sex2 == $_SESSION['sex'])) {
								print "<td><input type=\"radio\" name=\"primary\" value=\"inv_$row[inventoryID]\" checked></td>";
								$primary = "1";
								$set_proper_sex = $_SESSION['sex'];
								$found_me = "1";
							} else {
								if ($found_open_bunk == "1") {
                           print "<td><input type=\"radio\" name=\"primary\" checked value=\"inv_$row[inventoryID]\"></td>";
	                        $primary = "1";
   	                     $set_proper_sex = $_SESSION['sex'];
      	                  $found_me = "1";
								} else {
            	         	print "<td><input type=\"radio\" name=\"primary\" disabled value=\"inv_$row[inventoryID]\"></td>";
								}
							}
						} else {



                     if (($primary == "")  and ($sex2 == $_SESSION['sex'])) {
                        print "<td><input type=\"radio\" name=\"primary\" value=\"inv_$row[inventoryID]\" checked></td>";
                        $primary = "1";
								$set_proper_sex = $_SESSION['sex'];
								$found_me = "1";
                     } else {
								if ($sex2 == "male_female") {
									print "<td><input type=\"radio\" name=\"primary\" value=\"inv_$row[inventoryID]\" checked></td>";
									$primary = "1";
									$set_proper_sex = $_SESSION['sex'];
									$found_me = "1";
								} else {
	                        print "<td><input type=\"radio\" disabled name=\"primary\" value=\"inv_$row[inventoryID]\"></td>";
								}
                     }  
						}
						break;

						default:
						$found_me = "1"; // bypass for reseller
						break;
						}

                     $temp_d = "";
                     $discount = $this->find_discount($_GET['charter'],$row['bunk_price']);
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
							print "
							<td><del><font color=red>$".number_format($row['bunk_price'])."</font></del> <ins style=\"text-decoration: none\">$".number_format($new_price)."</td>";
							switch ($sex2) {
								case "male":
								if ($quad == "yes") {
									$onchange = "onchange=do_quad('".$row['inventoryID']."')";
								}
								print "<td><select name=\"passenger_$row[inventoryID]\" id=\"passenger_$row[inventoryID]\" $onchange><option value=\"male\">Male</option></select></td></tr>";
								break;

								case "female":
								if ($quad == "yes") {
									$onchange = "onchange=do_quad('".$row['inventoryID']."')";
								}
                        print "<td><select name=\"passenger_$row[inventoryID]\" id=\"passenger_$row[inventoryID]\" $onchange><option value=\"female\">Female</option></select></td></tr>";
								break;

								default:
                        if ($quad == "yes") {
                           $onchange = "onchange=do_quad('".$row['inventoryID']."')";
                        }
								print "<td><select name=\"passenger_$row[inventoryID]\" id=\"passenger_$row[inventoryID]\" $onchange><option value=\"\">--Select--</option><option value=\"male\">Male</option><option value=\"female\">Female</option></select></td></tr>";
								break;
							}

							$total = $total + $new_price;
						} else {
                     print "
                     <td>$".number_format($row['bunk_price'])."</td>";
                     switch ($sex2) {
                        case "male":
                        if ($quad == "yes") {
                           $onchange = "onchange=do_quad('".$row['inventoryID']."')";
                        }
                        print "<td><select name=\"passenger_$row[inventoryID]\" id=\"passenger_$row[inventoryID]\" $onchange><option value=\"male\">Male</option></select></td></tr>";
                        break;

                        case "female":
                        if ($quad == "yes") {
                           $onchange = "onchange=do_quad('".$row['inventoryID']."')";
                        }
                        print "<td><select name=\"passenger_$row[inventoryID]\" id=\"passenger_$row[inventoryID]\" $onchange><option value=\"female\">Female</option></select></td></tr>";
                        break;

                        default:
                        if ($quad == "yes") {
                           $onchange = "onchange=do_quad('".$row['inventoryID']."')";
                        }
                        print "<td><select name=\"passenger_$row[inventoryID]\" id=\"passenger_$row[inventoryID]\" $onchange><option value=\"\">--Select--</option><option value=\"male\">Male</option><option value=\"female\">Female</option></select></td></tr>";
                        break;
                     }

                     $total = $total + $row['bunk_price'];
						}
				}

				?>
				<script>
				function do_quad(gender) {
					<?php
					// Get the selected element
					foreach ($inv as $value) {
						print "test=$value;";
					?>
						if (gender == test) {
							var test2 = 'passenger_' + test;
							var getSex = document.getElementById(test2).value;
						}

					<?php
					}
					// update all elements with the selected element
					foreach ($inv as $value2) {
						echo "var test2 = 'passenger_' + $value2;";
						?>
						document.getElementById(test2).value=getSex;

						<?php
					}


					?>



				}
				</script>

				<?php

				$_SESSION['reservation_token'] = rand(500,6000);
				print "<tr class=\"details-description\"><td colspan=2>&nbsp;</td><td colspan=\"5\"><textarea name=\"details\" cols=80 rows=3 placeholder=\"Type in notes for your travel specialist at Aggressor Fleet.\"></textarea>

	<br>			                  <div id=\"timeleft\" style=\"display:inline\"></div>


				</td></tr>";

				if ($found_me != "1") {
					print "<tr class=\"details-description\"><td>&nbsp;</td><td colspan=6><font color=red>We could not match your gender to a stateroom. Please click back and select a stateroom matching your gender.</font></td></tr>";
				}

				print "<tr class=\"details-description\"><td colspan=\"3\"><br><br>&nbsp;</td><td class=\"view_details-description\">Total: </td><td><b>$".number_format($total)."</b></td><td>


				";
				if ($found_me == "1") {
					print "
					<input type=\"image\"  id=\"button1\" src=\"buttons/bt-book-res.png\" onclick=\"bookit(this.form);return false;\">
					";
				}


				print "</td></tr>";
				print "<tr><td colspan=5>&nbsp;</td>

					<td align=right>
						<input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
						<input type=\"hidden\" name=\"pax\" value=\"$_GET[passengers]\">
						<input type=\"hidden\" name=\"name\" value=\"$_GET[name]\">
						<input type=\"hidden\" name=\"tk\" value=\"$_SESSION[reservation_token]\">

						

						
					</td>
				</tr>";

				?>
                                <script>
      function refreshDiv() {
          $('#timeleft').load('check_time2.php?charter=<?=$_GET['charter'];?>&t=1', function(){ /* callback code here */ });

      }
      setInterval(refreshDiv, 1000);

				<?php
            switch ($_SESSION['contact_type']) {
               case "consumer":
					$file = "reservenow.php";
					break;

					default:
					$file = "reservenow_reseller.php";
					break;
				}
				?>


                                function bookit(myform) {
								
											<?php

                                       foreach ($inv as $in) {
                                       ?>
                                       if (document.getElementById('passenger_<?=$in;?>').value == '') {
                                          var stop = 1;
                                       }
                                       <?php
                                       }

											?>
													if (stop != '1') {	
                                        $.get('<?=$file;?>',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#bookit").html(php_msg);
                                        });
													} else {
														alert('The gender is required');
													}
                                }



                                function bookit_orig(myform) {
                           
                                        $.get('reservenow.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#bookit").html(php_msg);
                                        });
                                }


                                </script>

				<?php

				print "</table>
				</form>
				</div>";


			print "</div></div></div></div>";
	}

	public function start_reservation() {
			print '
			<div id="toparea2">&nbsp;</div>
			';


         print "
         <div id=\"result_wrapper\">
            <div id=\"result_pos1\">
               <div id=\"result_pos2\">
	               <div id=\"logo2\">
   	               <center><img src=\"ResSys-Logos.png\"></center>
      	         </div>
                  ";

         $sql = "
         SELECT 
            `af_df_unified2`.`destinations`.*,
            DATE_FORMAT(`reserve`.`charters`.`start_date`, '%b %d, %Y') AS 'start_date',
            DATE_FORMAT(DATE_ADD(`reserve`.`charters`.`start_date`,interval `reserve`.`charters`.`nights` day) , '%b %d, %Y') AS 'end_date',
            `reserve`.`destinations`.`description`

         FROM
            `reserve`.`charters`,
            `af_df_unified2`.`destinations`,
            `reserve`.`destinations`

         WHERE
            `reserve`.`charters`.`charterID` = '$_POST[charter]'
            AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`destinations`.`boatID`
            AND `af_df_unified2`.`destinations`.`name` = '$_POST[name]'

            AND `reserve`.`charters`.`destinationID` = `reserve`.`destinations`.`destinationID`

         ";

         $result = $this->new_mysql($sql);
         while($row = $result->fetch_assoc()) {
            $di = $row['destination_image'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $description = $row['description'];
            $bunks1 = $row['bunks1'];
            $bunks2 = $row['bunks2'];
            $bunks3 = $row['bunks3'];
            $bunks4 = $row['bunks4'];
            $img1 = $row['img1'];
            $img2 = $row['img2'];
            $img3 = $row['img3'];
            $img4 = $row['img4'];
            $type1 = $row['type1'];
            $type2 = $row['type2'];
            $type3 = $row['type3'];
            $type4 = $row['type4'];
            $desc1 = $row['desc1'];
            $desc2 = $row['desc2'];
            $desc3 = $row['desc3'];
            $desc4 = $row['desc4'];
         }
			$di = str_replace("https://www.liveaboardfleet.com",'https://'.$_SERVER['HTTP_HOST'],$di);
         print "
         <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\">
         <tr>
            <td>
               <img src=\"$di\" width=\"850\">
            </td>
         </tr>
         <tr><td>
                  
            <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">
               <tr>
                  <td width=\"263\" class=\"details-top\">&nbsp;&nbsp;&nbsp;$start_date to $end_date</td>
                  
                  <td width=\"303\" class=\"details-top\">$description</td>
            

                  <td width=\"283\" align=\"right\" class=\"details-top\"></td>
               </tr>
            </table> 
                  
         </td></tr>
         </table>
         <div style=\"clear:both;\"></div>
         ";

         print "<div id=\"result_pos3\">";
            print "<br><span class=\"details-title-text\">Reservations</span><br><br>\n";

				// set session data
				foreach ($_POST as $key=>$value) {
					$_SESSION[$key] = $value;
				}


				print "<span class=\"details-description\">Please enter in your billing details below.<br></span><br>\n";

				print "
				<div id=\"res1\">
				<form name=\"MyForm\" id=\"MyForm\">
					<table border=0 width=700>
						<tr><td width=50>&nbsp;</td><td colspan=2 class=\"view_details-description\">Contact Name</td></tr>

						<tr><td></td>
						<td><input type=\"text\" name=\"fname\" size=\"40\" placeholder=\"First Name\"></td><td><input type=\"text\" name=\"lname\" size=40 placeholder=\"Last Name\"></td></tr>

						<tr><td></td><td class=\"view_details-description\">Birthday</td></tr>
						
						<tr><tr><td></td><td><select name=\"birth_month\">
							<option value=\"\">Select Birth Month</option>
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
					</select></td><td><input type=\"text\" name=\"birth_year\" placeholder=\"1987\"></td></tr>
					<tr><td colspan=2>&nbsp;</td><td><input type=\"image\" src=\"buttons/bt-continue.png\" onclick=\"lookup(this.form);return false;\"></td></tr>


					</table>
				</form>
				</div>
				";

				?>
                                <script>
                                function lookup(myform) {
                                        $.post('lookup.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#res1").html(php_msg);
                                        });
                                }
                                </script>
				<?php


			print "</div></div></div></div>";

	}


	public function logout() {

			session_destroy();
         print '
         <div id="toparea2">&nbsp;</div>
         ';


         print "
         <div id=\"result_wrapper\">
            <div id=\"result_pos1\">
               <div id=\"result_pos2\">
                  <div id=\"logo2\">
                  </div>
                  ";


         print "
         <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\">
         <tr>
            <td>
					<br>
               <img src=\"../ResImages/generic-DTW.jpg\" width=\"850\">
            </td>
         </tr>
         <tr><td>
                  
            <table border=\"0\" width=\"850\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">
               <tr>
                  <td width=\"263\" class=\"details-top\">&nbsp;&nbsp;&nbsp;</td>
                  
                  <td width=\"303\" class=\"details-top\">$description</td>
            

                  <td width=\"283\" align=\"right\" class=\"details-top\"></td>
               </tr>
            </table> 
                  
         </td></tr>
         </table>
         <div style=\"clear:both;\"></div>
         ";

                  print "<br><br><font color=green size=4>You have been logged out.</font>";

                  print "<br><br>
						<p align=center>
                  <span class=\"details-title-text\">
						Thank you for visiting the Aggressor Fleet<br>Online Reservation System.
						<br><br>
						<a href=\"http://www.aggressor.com\"><img src=\"buttons/bt-website-home.png\" border=0></a><br><br>
						<a href=\"index.php\"><img src=\"buttons/bt-another-res.png\" border=0></a>
                  </span>
						</p>
                  <br><br>";


	}


	// END CLASS

}

?>
