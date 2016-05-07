<?php

class Common {

        public $linkID;

        function __construct($linkID){ $this->linkID = $linkID; }

        public function new_mysql($sql) {

                $result = $this->linkID->query($sql) or die($this->linkID->error.__LINE__);
                return $result;
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


		public function login() {
	      $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
				//include "class/consummer.class.php";
				$reservation = new Reservation($linkID);
            $reservation->login_screen($uri);
				die;
			} else {
				$this->my_profile();
			}
		}

		public function header_top() {
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
		}

      public function dive_map() {
            print "
            <div id=\"result_wrapper\">
               <div id=\"result_pos1\">
                  <div id=\"result_pos2\">
                     <br>
                     <table border=\"0\" width=\"950\" cellpadding=\"0\" cellspacing=\"0\">
                        <tr>
                           <td>
                           ";

                           $map = "ok";
                           include "dive_map.php";

                           print "
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table border=\"0\" width=\"950\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">
                                 <tr>
                                    <td width=\"263\" class=\"details-top\">&nbsp;&nbsp;MyAggressor</td>
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
      }


		public function header_bot() {
          print "</div></div></div></div>";
		}

      public function myaggressor() {

         ?>








<table width="817" border="0" cellspacing="0" cellpadding="0">
 <tbody>
   <tr>
     <td valign="top"><table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="217">
       <tr>
         <td><br>
         
         <img src="avatar/default_avatar.png" width="200"><br>
         <span id="myaggressor">
         <?php
         print "$_SESSION[first] $_SESSION[last]<br>$_SESSION[city], $_SESSION[state]$_SESSION[province]<br>";
         ?>
         </span>
         <input type="button" style="width:200px;" value="Update Profile" class="btn btn-primary" onclick="document.location.href='profile.php';"><br><br>
         <input type="button" style="width:200px;" value="My Reservations" class="btn btn-primary" onclick="document.location.href='myreservations.php';"><br>




         </td>
       </tr>
       <tr>
         <td height="47"><img name="ProfilePageMenu02" src="buttons/bt-myreservations.png" width="185" height="32" id="ProfilePageMenu02" alt="" onclick="document.location.href='myreservations.php';" /></td>
       </tr>
      <?php
            switch ($_SESSION['contact_type']) {
               case "reseller_manager":
               case "reseller_agent":
               case "reseller_third_party":
               print "<tr><td height=\"47\"><input type=\"button\" value=\"Reseller Portal\" onclick=\"document.location.href='resellerportal.php'\"></td></tr>";
               break;
            }
      ?>
       <tr>
         <td height="47">&nbsp;</td>
       </tr>
       <tr>
         <td height="47">&nbsp;</td>
       </tr>
       <tr>
         <td height="47">&nbsp;</td>
       </tr>
       <tr>
         <td height="47">&nbsp;</td>
       </tr>
     </table></td>
     <td align="center" valign="top"><table text-align:="text-align:" center;="center;"" bgcolor="#ffffff" border="0" cellpadding="8" cellspacing="0" width="600">
       <tr>
         <td width="200" colspan="3" valign="top"><p style="text-align: center"><img src="images/Ad_CR-Profile1.jpg" alt=""/></p></td>
       </tr>
     </table></td>
   </tr>
 </tbody>
</table>




         <?php


      }










		public function consumer_portal_view() {
         ?>

<table width="817" border="0" cellspacing="0" cellpadding="0">
 <tbody>
   <tr>
     <td valign="top"><table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="217">
       <tr>
         <td height="47"><img name="ProfilePageMenu01" src="buttons/bt-updateprofile.png" width="185" height="32" id="ProfilePageMenu01" alt="" onclick="document.location.href='profile.php';" /></td>
       </tr>
       <tr>
         <td height="47"><img name="ProfilePageMenu02" src="buttons/bt-myreservations.png" width="185" height="32" id="ProfilePageMenu02" alt="" onclick="document.location.href='myreservations.php';" /></td>
       </tr>
       <tr>
         <td height="47">&nbsp;</td>
       </tr>
       <tr>
         <td height="47">&nbsp;</td>
       </tr>
       <tr>
         <td height="47">&nbsp;</td>
       </tr>
       <tr>
         <td height="47">&nbsp;</td>
       </tr>
     </table></td>
     <td align="center" valign="top"><table text-align:="text-align:" center;="center;"" bgcolor="#ffffff" border="0" cellpadding="8" cellspacing="0" width="600">
       <tr>
         <td width="200" colspan="3" valign="top"><p style="text-align: center"><img src="images/Ad_CR-Profile1.jpg" alt=""/></p></td>
       </tr>
     </table></td>
   </tr>
 </tbody>
</table>




			<?php


		}


		public function reseller_portal_view() {
			?>

			<table width="817" border="0" cellspacing="0" cellpadding="0">
			  <tbody>
			    <tr>
			      <td valign="top"><table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="217">
			        <tr>
			          <td height="47"><img name="ProfilePageMenu01" src="buttons/bt-updateprofile.png" width="185" height="32" id="ProfilePageMenu01" alt="" onclick="document.location.href='profile.php';" /></td>
			        </tr>
			        <tr>
			          <td height="47"><img name="ProfilePageMenu02" src="buttons/bt-myreservations.png" width="185" height="32" id="ProfilePageMenu02" alt="" onclick="document.location.href='myreservations.php';" /></td>
			        </tr>
			        <tr>
			          <td height="47"><img name="ProfilePageMenu03" src="buttons/bt-allreservations.png" width="185" height="32" id="ProfilePageMenu03" alt="" onclick="document.location.href='agentreservations.php';" /></td>
			        </tr>
				<?php
				if ($_SESSION['contact_type'] == "reseller_manager") {
				?>
			        <tr>
			          <td height="47"><img name="ProfilePageMenu04" src="buttons/bt-manage-agents.png" width="185" height="32" id="ProfilePageMenu04" alt="" onclick="document.location.href='agents.php';" /></td>
			        </tr>
				<?php
				}
				?>
			        <tr>
			          <td height="47">&nbsp;</td>
			        </tr>
			        <tr>
			          <td height="47">&nbsp;</td>
			        </tr>
			      </table></td>
			      <td valign="top"><table text-align:="text-align:" center;="center;"" bgcolor="#ffffff" border="0" cellpadding="8" cellspacing="0" width="600">
			        <tr>
			          <td colspan="3">


				<p><strong>Welcome to the Reseller Reservation System.</strong> We have provided the following 
           Agent Resources to assist you with marketing Aggressor Fleet.</p>
           <blockquote>
             <blockquote>
               <blockquote>
                 <p><strong>You have access to:</strong><br />
                   &nbsp;&nbsp;&#8226; Download logos &amp; banners to use on your website<br />
                   &nbsp;&nbsp;&#8226; Download high resolution destination photos &amp; HD videos<br />
                   &nbsp;&nbsp;&#8226; Download high resolution yacht photos &amp; floor plan layouts<br />
                   &nbsp;&nbsp;&#8226; View our year round group charter/FREE spaces </p>
               </blockquote></blockquote></blockquote>


							</td>
			        </tr>
			        <tr>
						<!--
			          <td width="200" align="center"><img name="ProfilePagetextresellers02" src="buttons/bt-DEMAspecials.png" width="188" height="32" id="ProfilePagetextresellers02" alt="" onclick="window.open('https://www.aggressor.com/reservations/DEMA-GroupSpecial.pdf')" /></td>
						-->
                  <td width="200" align="center"><a href="http://www.aggressor.com/resellerspecials.php" target=_blank><img src="buttons/bt-newdeals.png" border="0"></a></td>

			          <td width="200" align="center"><a href="http://www.liveaboardfleet.com/Reseller-Images/Resellers-Images.html" target="_blank"><img name="ProfilePagetextresellers03" src="buttons/bt-imagegallery.png" width="188" height="32" id="ProfilePagetextresellers03" alt="" /></a></td>
			          <td width="200" align="center"><a href="ComplimentarySpaces.pdf" target="_blank"><img name="ProfilePagetextresellers04" src="buttons/bt-groupspecials.png" width="188" height="32" id="ProfilePagetextresellers04" alt="" /></a></td>
			        </tr>
			        <tr>
			          <td colspan="3"><p style="text-align: center">If you have any questions, please contact Tom Gebhardt, Director of Sales at: <br />
			            <a href="mailto:sales@aggressor.com">sales@aggressor.com</a> or +706-933-2531</p>
			            <p style="text-align: center; font-size: 11px;">The download and use of all images and videos provided by WayneWorks Marine, LLC <br />
			              dba Aggressor Fleet , are subject to agreement of our <a href="terms.html" target=_blank>Terms and Conditions</a>.</p></td>
			        </tr>
			      </table></td>
			    </tr>
			  </tbody>
			</table>

			<?php

		}

      public function show_reseller_menu() {
               $this->reseller_portal_view();
      }

      public function my_profile() {
            $this->dive_map();
            switch ($_SESSION['contact_type']) {
               case "consumer":
               $this->myaggressor();
               break;

               case "reseller_manager":
               case "reseller_agent":
               case "reseller_third_party":
               //$this->reseller_portal_view();
               $this->myaggressor();
               break;
            }
         $this->header_bot();
      }


		public function my_profileOLD() {
            $this->header_top();
				switch ($_SESSION['contact_type']) {
					case "consumer":
					$this->consumer_portal_view();
					break;

					case "reseller_manager":
					case "reseller_agent":
					case "reseller_third_party":
					$this->reseller_portal_view();
					break;
				}
			$this->header_bot();
		}

		public function country_list($id) {
			$sql = "SELECT `country`,`countryID` FROM `countries` ORDER BY `country` ASC";
			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				if ($id == $row['countryID']) {
					$options .= "<option selected value=\"$row[countryID]\">$row[country]</option>";
				} else {
      	      $options .= "<option value=\"$row[countryID]\">$row[country]</option>";
				}
			}
			return $options;
		}

		public function state_list() {
			$sql = "SELECT `state_abbr` FROM `state` ORDER BY `state_abbr` ASC";
         $result = $this->new_mysql($sql);
         while ($row = $result->fetch_assoc()) {
				$options .= "<option value=\"$row[state_abbr]\">$row[state_abbr]</option>";
			}
			return $options;
		}

		public function manage_agents() {
         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            //include "class/consummer.class.php";
            $reservation = new Reservation($linkID);
            $reservation->login_screen($uri);
				die;
         }
			$this->header_top();
            switch ($_SESSION['contact_type']) {
               case "consumer":
               $type = "Consumer";
               break;

               case "reseller_manager":
               $type = "Reseller Manager";
               break;

               case "reseller_agent":
               $type = "Reseller Agent";
               break;

               case "reseller_third_party":
               $type = "Reseller Third Party";
               break;
            }
			if ($type == "Reseller Manager") {
	         print "<br><span class=\"result-title-text\">Manage Agents</span><br><br>
   	      <span class=\"details-description\">";

				$sql = "
				SELECT
					`resellers`.`resellerID`,
					`resellers`.`company`

				FROM
					`contacts`,`reseller_agents`,`resellers`

				WHERE
					`contacts`.`contactID` = '$_SESSION[contactID]'
					AND `contacts`.`reseller_agentID` = `reseller_agents`.`reseller_agentID`
					AND `reseller_agents`.`resellerID` = `resellers`.`resellerID`

				";
				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
					print "<br>Agents for <b>$row[company]</b><br><br>";
					print "<input type=\"button\" name=\"new_agent\" value=\"Add New Agent\" onclick=\"document.location.href='add_agent.php'\">&nbsp;<input type=\"button\" name=\"company_info\" value=\"Company Logo\" onclick=\"document.location.href='company_info.php'\"><br><hr>";
					$_SESSION['resellerID'] = $row['resellerID'];

					// list agents
					$sql2 = "
					SELECT
						`reseller_agents`.*

					FROM
						`reseller_agents`

					WHERE
						`reseller_agents`.`resellerID` = '$row[resellerID]'
						
					ORDER BY `reseller_agents`.`status` ASC, `reseller_agents`.`last` ASC, `reseller_agents`.`first` ASC
					";
					print "<table border=0 width=90%>
					<tr>
						<td><b>Name</b></td>
						<td><b>Email</b></td>
						<td><b>Status</b></td>
						<td><b>Action</b></td>
					</tr>";

					$result2 = $this->new_mysql($sql2);
					while ($row2 = $result2->fetch_assoc()) {
						$i++;
						if ($i % 2 == 0) {
							$bgcolor = "#F0F0F0";
						} else {
							$bgcolor = "#FFFFFF";
						}
						print "<tr bgcolor=\"$bgcolor\"><td>$row2[first] $row2[last]</td><td>$row2[email]</td><td>$row2[status]</td><td><a href=\"agent_edit.php?id=$row2[reseller_agentID]\">Edit</a></td></tr>";
					}

					print "</table>";
				}


				print "</span>";

			} else {
				print "<font color=red>ACCESS DENIED</font>";
			}
			$this->header_bot();
		}

		public function myreservations() {
         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            //include "class/consummer.class.php";
            $reservation = new Reservation($linkID);
            $reservation->login_screen($uri);
				die;
         }
         $this->header_top();
         print "<br><span class=\"result-title-text\">My Reservations ($_SESSION[first] $_SESSION[last])</span><br><br>
         <span class=\"details-description\">";

			$sql = "
			SELECT
				`reservations`.`reservationID`,
				`boats`.`name`,
				DATE_FORMAT(`charters`.`start_date`,'%b %e, %Y') AS 'start_date',
				`charters`.`nights`

			FROM
				`reservations`,`charters`,`boats`

			WHERE
				`reservations`.`reservation_contactID` = '$_SESSION[contactID]'
				AND `reservations`.`charterID` = `charters`.`charterID`
				AND `charters`.`boatID` = `boats`.`boatID`
				AND `reservations`.`show_as_suspended` = '0'

			ORDER BY `charters`.`start_date` ASC
			";

			print "<table border=0 width=99%>
			<tr>
				<td><b>Confirmation #</b></td>
				<td><b>Yacht</b></td>
				<td><b>Embarkment Date</b></td>
				<td><b>Nights</b></td>
				<td>&nbsp;</td>
			</tr>";

			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				print "<tr><td>$row[reservationID]</td><td>$row[name]</td><td>$row[start_date]</td><td>$row[nights]</td><td><a href=\"guests.php?res=$row[reservationID]&c=$_SESSION[contactID]\">Assign Guests</a> | 
				<a href=\"gis.php?res=$row[reservationID]\">GIS</a></td></tr>";
				$found = "1";
			}
			if ($found != "1") {
					print "<tr><td colspan=5><br><br><center>Sorry, you do not have any reservations to display.</center><br><br></td></tr>";
			}

			print "</table>";


         print "</span>";
         $this->header_bot();

		}

		public function save_update_profile() {
         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            //include "class/consummer.class.php";
            $reservation = new Reservation($linkID);
            $reservation->login_screen($uri);
         }
         $this->header_top();
         print "<br><span class=\"result-title-text\">My Profile ($_SESSION[first] $_SESSION[last])</span><br><br>
         <span class=\"details-description\">";

			$sql = "UPDATE `contacts` SET `address1` = '$_POST[address1]', `address2` = '$_POST[address2]', `city` = '$_POST[city]', `state` = '$_POST[state]', `province` = '$_POST[province]', `countryID` = '$_POST[countryID]',`zip` = '$_POST[zip]',
			`phone1` = '$_POST[phone1]', `phone2` = '$_POST[phone2]', `phone3` = '$_POST[phone3]', `phone4` = '$_POST[phone4]', `uupass` = '$_POST[uupass]' WHERE `contactID` = '$_SESSION[contactID]'";
			$result = $this->new_mysql($sql);
			if ($result == "TRUE") {
				print "<br>Your profile has been updated.<br>";
			} else {
				print "<br><font color=red>There was a problem updating your profile. </font><br>";
			}


			print "</span>";

         $this->header_bot();

		}

		public function update_profile() {
         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            //include "class/consummer.class.php";
            $reservation = new Reservation($linkID);
            $reservation->login_screen($uri);
         }
         $this->header_top();

			$sql = "SELECT * FROM `contacts` WHERE `contactID` = '$_SESSION[contactID]'";
			$result = $this->new_mysql($sql);
			$row = $result->fetch_assoc();

         print "<br><span class=\"result-title-text\">My Profile ($_SESSION[first] $_SESSION[last])</span><br><br>
         <span class=\"details-description\">
				<form name=\"myform\" action=\"profile.php\" method=\"post\">
				<input type=\"hidden\" name=\"section\" value=\"update\">
				<table border=0 width=90%>
				<tr><td>Name:</td><td>$row[first] $row[middle] $row[last]</td></tr>
				<tr><td>Address Line 1:</td><td><input type\"text\" name=\"address1\" size=40 value=\"$row[address1]\"></td></tr>
				<tr><td>Address Line 2:</td><td><input type=\"text\" name=\"address2\" size=40 value=\"$row[address2]\"></td></tr>
				<tr><td>City:</td><td><input type=\"text\" name=\"city\" size=40 value=\"$row[city]\"></td></tr>
				";
				$states = $this->state_list();
				if ($row['countryID'] == "2") {
					print "<tr><td>State:</td><td><select name=\"state\"><option selected>$row[state]</option>$states</select></td></tr>";
				} else {
					print "<tr><td>Province:</td><td><input type=\"text\" name=\"province\" value=\"$row[province]\" size=40></td></tr>";
				}
				$countries = $this->country_list($row['countryID']);

				print "
				<tr><td>Zip:</td><td><input type=\"text\" name=\"zip\" value=\"$row[zip]\" size=40></td></tr>
				<tr><td>Country:</td><td><select name=\"countryID\"><option selected value=\"$row[countryID]\">$row[country]</option>$countries</select></td></tr>
				<tr><td>Email:</td><td><input type=\"text\" name=\"email\" value=\"$row[email]\" size=40></td></tr>
				";

				if ($row['phone1_type'] != "") {
					print "<tr><td>$row[phone1_type] phone:</td><td><input type=\"text\" name=\"phone1\" value=\"$row[phone1]\" size=40></td></tr>";
				}

            if ($row['phone2_type'] != "") {
               print "<tr><td>$row[phone2_type] phone:</td><td><input type=\"text\" name=\"phone2\" value=\"$row[phone2]\" size=40></td></tr>";
            }

            if ($row['phone3_type'] != "") {
               print "<tr><td>$row[phone3_type] phone:</td><td><input type=\"text\" name=\"phone3\" value=\"$row[phone3]\" size=40></td></tr>";
            }

            if ($row['phone4_type'] != "") {
               print "<tr><td>$row[phone4_type] phone:</td><td><input type=\"text\" name=\"phone4\" value=\"$row[phone4]\" size=40></td></tr>";
            }

				print "<tr><td>Username:</td><td>$row[uuname]</td></tr>
				<tr><td>Password:</td><td><input type=\"text\" name=\"uupass\" value=\"$row[uupass]\" size=40></td></tr>
				";

				switch ($row['contact_type']) {
					case "consumer":
					$type = "Consumer";
					break;

					case "reseller_manager":
					$type = "Reseller Manager";
					break;

					case "reseller_agent":
					$type = "Reseller Agent";
					break;

					case "reseller_third_party":
					$type = "Reseller Third Party";
					break;
				}

				print "<tr><td>Account Type:</td><td>$type</td></tr>";
				print "<tr><td>&nbsp;</td><td><input type=\"submit\" value=\"Update\"></td></tr>";
				print "
				</table>
				</form>

         </span>
         </div>
         ";
         $this->header_bot();

		}

      public function is_general_reseller() {
         if ($_SESSION['contact_type'] != "consumer") {
            return "TRUE";
         } else {
            return "FALSE";
         }
      }


		public function is_reseller() {
			if ($_SESSION['contact_type'] == "reseller_manager") {
				return "TRUE";
			} else {
				return "FALSE";
			}
		}

		public function eval_reseller($msg) {
			if ($msg == "FALSE") {
				print "<br><font color=red>Sorry, you do not have proper access or your session has timmed out. If this is error please log back in.</font><br><br>";
				die;
			}
		}

		public function agent_save() {
         $this->header_top();
         print "<br><span class=\"result-title-text\">Add Agent</span><br><br>
         <span class=\"details-description\">";

				$dob = $_POST['birth_year'] . $_POST['birth_month'] . $_POST['birth_day'];

            // check if user is a reseller manager
            $cont = $this->is_reseller();
            $this->eval_reseller($cont);

				$sql = "INSERT INTO `reseller_agents` (`resellerID`,`status`,`first`,`last`,`address1`,`address2`,`city`,`state`,`zip`,`countryID`,`phone1`,`phone1_type`,`phone2`,`phone2_type`,`phone3`,`phone3_type`,`email`)
				VALUES ('$_SESSION[resellerID]','Active','$_POST[first]','$_POST[last]','$_POST[address1]','$_POST[address2]','$_POST[city]','$_POST[state]','$_POST[zip]','$_POST[country]','$_POST[phone1]','Home','$_POST[phone2]','Work',
				'$_POST[phone3]','Mobile','$_POST[email]')
				";

				$result = $this->new_mysql($sql);
				if ($result == "TRUE") {
					$reseller_agentID = $this->linkID->insert_id;

					$sql2 = "
					INSERT INTO `contacts` (`first`,`last`,`address1`,`address2`,`city`,`state`,`province`,`zip`,`countryID`,`phone1`,`phone1_type`,`phone2`,`phone2_type`,`phone3`,`phone3_type`,`email`,`sex`,`date_of_birth`,`reseller_agentID`,`uuname`,`uupass`,`contact_type`)
					VALUES
					(
					'$_POST[first]','$_POST[last]','$_POST[address1]','$_POST[address2]','$_POST[city]','$_POST[state]','$_POST[province]','$_POST[zip]','$_POST[country]','$_POST[phone1]','Home','$_POST[phone2]','Work','$_POST[phone3]','Mobile','$_POST[email]',
					'$_POST[sex]','$dob','$reseller_agentID','$_POST[uuname]','$_POST[uupass]','$_POST[contact_type]'
					)
					";
					$result2 = $this->new_mysql($sql2);
					if ($result2 == "TRUE") {
						print "<br>The reseller agent was added. Click <a href=\"agents.php\">here</a> to return to the agent list.<br><br>";
					} else {
						print "<br><font color=red>The contact failed to add for the reseller agent.<br><br></font>";
					}
				} else {
					print "<br><font color=red>The reseller agent failed to add.</font><br><br>";
				}

         print "</span>";
         $this->header_bot();

		}

		public function gis() {
         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            //include "class/consummer.class.php";
            $reservation = new Reservation($linkID);
            $reservation->login_screen($uri);
				die;
         }
         $this->header_top();

         // check if user is a reseller
         $cont = $this->is_general_reseller();
         $this->eval_reseller($cont);

			print "<br><span class=\"result-title-text\">GIS - Conf # $_GET[res]</span><br><br>
         <span class=\"details-description\">";

			$sql = "
			SELECT
				`b`.`name`,
				DATE_FORMAT(`ch`.`start_date`, '%b %d, %Y') AS 'start_date',
				`ch`.`nights`

			FROM
				`reservations` r, `boats` b, `charters` ch

			WHERE
            `r`.`reservationID` = '$_GET[res]'
            AND `r`.`charterID` = `ch`.`charterID`
            AND `ch`.`boatID` = `b`.`boatID`
			";

         $result = $this->new_mysql($sql);
         while ($row = $result->fetch_assoc()) {
				print "<b>$row[name]</b><br>Embarkment $row[start_date]<br>Nights: $row[nights]<br><br>";
			}

			$sql = "
			SELECT
				`r`.`reservationID`,
				`c`.`first`,
				`c`.`last`,
				`i`.`bunk`,
				`i`.`passengerID`,
				`i`.`inventoryID`,
				`i`.`charterID`,
				`b`.`name`,
				`b`.`abbreviation`

			FROM
				`reservations` r, `reseller_agents`, `inventory` i, `contacts` c, `charters` ch, `boats` b

			WHERE
				`r`.`reservationID` = '$_GET[res]'
				AND `r`.`charterID` = `ch`.`charterID`
				AND `ch`.`boatID` = `b`.`boatID`
				AND `r`.`reseller_agentID` = `reseller_agents`.`reseller_agentID`
				AND `reseller_agents`.`resellerID` = '$_SESSION[resellerID]'
				AND `i`.`reservationID` = '$_GET[res]'
				AND `i`.`passengerID` = `c`.`contactID`
			";

			print "<table border=0 width=500>
			<tr><td width=20><img src=\"images/incomplete_icon.gif\"></td><td>Means the task has not been completed by the customer.</td></tr>
			<tr><td width=20><img src=\"images/complete_icon.gif\"></td><td>Means the task has been completed by the customer.</td></tr>
			</table>";

			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$stateroom = str_replace($row['abbreviation'],'',$row['bunk']);
				$stateroom = str_replace("-","",$stateroom);

				if (($row['passengerID'] == "61531879") or ($row['passengerID'] == "61531880")) {
					$gis_link = "<a href=\"javascript:void(0)\" title=\"Please assign a guest to send a GIS link\">N/A</an>";
					$travel_plans = "";
				} else {
					$gis_link = "<a href=\"send_gis.php?id=$row[inventoryID]\" onclick=\"return confirm('By clicking OK the GIS link will be emailed to the guests email address.')\" target=_blank>Send GIS</a>";
					$travel_plans = " (<a href=\"travel_plans.php?id=$row[inventoryID]\" target=_blank>Manage travel plans</a>)";
				}

				// get GIS status
				$s = "";
				$sql2 = "SELECT * FROM `guestform_status` WHERE `passengerID` = '$row[passengerID]' AND `charterID` = '$row[charterID]'";

				$result2 = $this->new_mysql($sql2);
				while ($row2 = $result2->fetch_assoc()) {
					$s[0] = $row2['general'];
					$s[1] = $row2['waiver'];
					$s[2] = $row2['policy'];
					$s[3] = $row2['emcontact'];
					$s[4] = $row2['requests'];
					$s[5] = $row2['insurance'];
					$s[6] = $row2['travel'];
					$s[7] = $row2['rentals'];
					$s[8] = $row2['confirmation'];
				}

				$green = "<img src=\"images/complete_icon.gif\">";
				$red = "<img src=\"images/incomplete_icon.gif\">";
				for ($n=0; $n < 9; $n++) {
					if ($s[$n] > "0") { $s[$n] = $green;} else { $s[$n] = $red;}
				}
				print "<h2><span class=\"result-title-text\">$row[first] $row[last]</span>$travel_plans</h2>
				<table border=0 width=100%>
				<tr>
					<td width=75><b>Stateroom</b></td>
					<td width=75><b>GIS</b></td>
					<td width=60><b>General</b></td>
					<td width=60><b>Waiver</b></td>
					<td width=60><b>Policy</b></td>
					<td width=60><b>Emergency</b></td>
					<td width=60><b>Restrictions</b></td>
					<td width=60><b>Insurance</b></td>
					<td width=60><b>Travel</b></td>
					<td width=60><b>Rentals</b></td>
					<td width=60><b>Conf</b></td>
				</tr>
				<tr>
					<td>$stateroom</td>
					<td>$gis_link</td>
					<td>$s[0]</td>
	            <td>$s[1]</td>
               <td>$s[2]</td>
               <td>$s[3]</td>
               <td>$s[4]</td>
               <td>$s[5]</td>
               <td>$s[6]</td>
               <td>$s[7]</td>
               <td>$s[8]</td>

				</tr>
				</table>
				<hr>";

				$found = "1";
			}
			if ($found != "1") {
				print "<center><font color=blue>Sorry, there are no guests assigned to this reservation.</font></center>";
			}

			

			print "</span>";
		}

		public function travel_plans() {
         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            //include "class/consummer.class.php";
            $reservation = new Reservation($linkID);
            $reservation->login_screen($uri);
            die;
         }
         $this->header_top();

         // check if user is a reseller
         $cont = $this->is_general_reseller();
         $this->eval_reseller($cont);


			if ($_GET['action'] == "delete") {
				$sql = "DELETE FROM `guest_flights` WHERE `flight_id` = '$_GET[fid]'";
            $result = $this->new_mysql($sql);
			}

			if ($_GET['action'] == "save") {

				$new_date = date("Y-m-d", strtotime($_GET['date']));
				$new_date .= " $_GET[hour]:$_GET[min]:00";

				$sql = "INSERT INTO 	`guest_flights` (`passengerID`,`charterID`,`airport`,`airline`,`flight_num`,`date`,`flight_type`) VALUES ('$_GET[passengerID]','$_GET[charterID]','$_GET[airport]','$_GET[airline]','$_GET[flight_num]','$new_date','$_GET[flight_type]')";
				$result = $this->new_mysql($sql);

			}

			$sql = "
			SELECT
				`i`.`reservationID`,
				`r`.`charterID`,
				`c`.`first`,
				`c`.`last`,
				`b`.`name`,
            DATE_FORMAT(`ch`.`start_date`, '%b %d, %Y') AS 'start_date'

			FROM
				`inventory` i, `contacts` c, `reservations` r, `charters` ch, `boats` b

			WHERE
				`i`.`inventoryID` = '$_GET[id]'
				AND `i`.`passengerID` = `c`.`contactID`
				AND `i`.`reservationID` = `r`.`reservationID`
				AND `r`.`charterID` = `ch`.`charterID`
				AND `ch`.`boatID` = `b`.`boatID`

			";

			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
	         print "<br><span class=\"result-title-text\">Travel Plans - $row[first] $row[last] -  Conf # $row[reservationID]<br>$row[name] - $row[start_date]</span><br><br>
   	      <span class=\"details-description\">";
				$passengerID = $_GET['id'];
				$charterID = $row['charterID'];
			}

			$sql = "
			SELECT
				DATE_FORMAT(`gf`.`date`, '%b %d, %Y - %H:%i') AS 'date',
				`gf`.`flight_id`,
				`gf`.`airport`,
				`gf`.`airline`,
				`gf`.`flight_num`,
				`gf`.`flight_type`

			FROM
				`guest_flights` gf

			WHERE
				`gf`.`passengerID` = '$passengerID'
				AND `gf`.`charterID` = '$charterID'

			";

			print "
            <table border=0 width=100%>
            <tr>
               <td width=100><b>Airport</b></td>
               <td width=100><b>Airline</b></td>
               <td width=75><b>Flight Number</b></td>
               <td width=75><b>Flight Type</b></td>
               <td width=150><b>Date/Time</b></td>
            </tr>";

				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
					print "<tr>
					<td>$row[airport]</td>
					<td>$row[airline]</td>
					<td>$row[flight_num]</td>
					<td>$row[flight_type]</td>
					<td>$row[date]
					<form style=\"display:inline\">
						<input type=\"hidden\" name=\"id\" value=\"$passengerID\">
						<input type=\"hidden\" name=\"fid\" value=\"$row[flight_id]\">
						<input type=\"hidden\" name=\"action\" value=\"delete\">
						<input type=\"submit\" value=\"Delete\" onclick=\"return confirm('You are about to delete this flight.')\">
					</form>
					</td>";
					$found = "1";
				}

				if ($found != "1") {
					print "<tr><td colspan=5><font color=blue>No travel info found.</font></td></tr>";
				}	

				for ($x=1; $x < 24; $x++) {
					if ($x < 10) {
						$hour .= "<option>0$x</option>";
					} else {
						$hour .= "<option>$x</option>";
					}
				}

            for ($x=1; $x < 60; $x++) {
               if ($x < 10) {
                  $min .= "<option>0$x</option>";
               } else {
                  $min .= "<option>$x</option>";
               }
            }


				print "
				<tr><td colspan=5>
            <form name=\"myform\" action=\"travel_plans.php\" method=\"get\">
            <input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">
            <input type=\"hidden\" name=\"passengerID\" value=\"$passengerID\">
            <input type=\"hidden\" name=\"charterID\" value=\"$charterID\">
            <input type=\"hidden\" name=\"action\" value=\"save\"><br><br>
				</td>
				</tr>

				<tr>
               <td width=100><b>Airport</b></td>
               <td width=100><b>Airline</b></td>
               <td width=75><b>Flight Number</b></td>
               <td width=75><b>Flight Type</b></td>
               <td width=150><b>Date/Time</b></td>
				</tr>";

				print "
				<tr>
					<td><input type=\"text\" name=\"airport\" size=10></td>
					<td><input type=\"text\" name=\"airline\" size=10></td>
					<td><input type=\"text\" name=\"flight_num\" size=10></td>
					<td><select name=\"flight_type\"><option>OUTBOUND</option><option>INBOUND</option></select></td>
					<td><input type=\"text\" name=\"date\" id=\"flight_date\"><br><select name=\"hour\">$hour</select>:<select name=\"min\">$min</select> <input type=\"submit\" value=\"Save\"></td>
				</tr>
				</table>";


		}

		public function pre_send_gis() {
         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            //include "class/consummer.class.php";
            $reservation = new Reservation($linkID);
            $reservation->login_screen($uri);
            die;
         }
         $this->header_top();

         // check if user is a reseller
         $cont = $this->is_general_reseller();
         $this->eval_reseller($cont);

         print "<br><span class=\"result-title-text\">Send GIS</span><br><br>
         <span class=\"details-description\">";

			$sql = "
			SELECT
				`contacts`.`first`,
				`contacts`.`last`,
				`contacts`.`email`,
				`b`.`name`,
				`ch`.`charterID`,
				`r`.`reservationID`,
				`contacts`.`contactID`,
				`i`.`login_key`,
				`ch`.`start_date`,
				`i`.`inventoryID`

			FROM
				`inventory` i, `contacts`, `reservations` r, `reseller_agents`, `charters` ch, `boats` b

			WHERE
				`i`.`inventoryID` = '$_GET[id]'
				AND `i`.`passengerID` = `contacts`.`contactID`
				AND `i`.`reservationID` = `r`.`reservationID`
            AND `r`.`reseller_agentID` = `reseller_agents`.`reseller_agentID`
            AND `reseller_agents`.`resellerID` = '$_SESSION[resellerID]'
				AND `i`.`charterID` = `ch`.`charterID`
				AND `ch`.`boatID` = `b`.`boatID`

			";

			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$guest_name = "$row[first] $row[last]";
				$contactID = $row['contactID'];
				$reservationID = $row['reservationID'];
				$charterID = $row['charterID'];
				$login_key = $row['login_key'];
				$email = $row['email'];
				$start_date = $row['start_date'];
				$name = $row['name'];
				$this->send_gis($contactID,$reservationID,$charterID,$login_key,$email,$guest_name,$start_date,$name);

				// add note
				$today = date("Ymd");
				$sql2 = "INSERT INTO `notes` (`note_date`,`table_ref`,`fkey`,`user_id`,`title`,`note`) VALUES ('$today','inventory','$row[inventoryID]','RRS','GIS Link','Reseller $_SESSION[first] $_SESSION[last] (ID # $_SESSION[resellerID]) sent GIS Link to $guest_name')";
				$result2 = $this->new_mysql($sql2);

				print "<br><br>The GIS was sent to $guest_name. You may now close this window or tab.<br><br>";
			}


			print "</span>";

		}

		public function send_gis($contactID,$reservationID,$charterID,$login_key,$email,$guest_name,$start_date,$name) {
            $_URL = "https://gis.liveaboardfleet.com/gis/index.php/";
            $guest_url = $_URL.$contactID."/".$reservationID."/".$charterID."/".$login_key;

				$sql = "
				SELECT `message` FROM `af_df_unified2`.`gis_email` WHERE `id` = '2'
				";

				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
					$msg = str_replace("#guest_url",$guest_url,$row['message']);
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
            `reserve`.`inventory`.`inventoryID` = '$_GET[id]'
            AND `reserve`.`inventory`.`passengerID` = `reserve`.`contacts`.`contactID`
            AND `reserve`.`inventory`.`charterID` = `reserve`.`charters`.`charterID`
            AND `reserve`.`charters`.`boatID` = `reserve`.`boats`.`boatID`

            AND `reserve`.`charters`.`boatID` = `af_df_unified2`.`kbyg`.`boatID`
            AND `reserve`.`charters`.`destinationID` = `af_df_unified2`.`kbyg`.`destinationID`
         ";
         $result2 = $this->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
            $fileName = $row2['fileName'];
         }
            $kbyg = "<a href=\"http://www.liveaboardfleet.net/aggressor/upload/$fileName\" target=_blank>Know Before You Go</a>";

				$msg = str_replace("#kbyg#",$kbyg,$msg);

				// email headers - This is fine tuned, please do not modify
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$headers .= "From: Aggressor Fleet <info@aggressor.com>\r\n";
				$headers .= "Reply-To: Aggressor Fleet <info@aggressor.com>\r\n";
				$headers .= "X-Priority: 3\r\n";
				$headers .= "X-Mailer: PHP/" . phpversion()."\r\n";

            $email_subject = 'Guest Profile for '.$guest_name.' - Embark Date '.date("M-d-Y",strtotime($start_date)).' (#'.$reservationID.') ' . $name;
				mail($email,$email_subject,$msg,$headers);

		}

      public function agentreservations() {
         $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
         $check_login = $this->check_login();
         if ($check_login == "FALSE") {
            // show login/register
            //include "class/consummer.class.php";
            $reservation = new Reservation($linkID);
            $reservation->login_screen($uri);
				die;
         }
         $this->header_top();

            // check if user is a reseller
            $cont = $this->is_general_reseller();
            $this->eval_reseller($cont);


         print "<br><span class=\"result-title-text\">Agent Reservations</span><br><br>
         <span class=\"details-description\">";

			if ($_GET['res'] != "") {
				$res = "AND `reservations`.`reservationID` = '$_GET[res]'";
			}

         $sql = "
         SELECT
            `reservations`.`reservationID`,
            `boats`.`name`,
            DATE_FORMAT(`charters`.`start_date`,'%b %e, %Y') AS 'start_date',
            `charters`.`nights`,
	    `reseller_agents`.`first`,
	    `reseller_agents`.`last`

         FROM
            `reservations`,`charters`,`boats`,`reseller_agents`

         WHERE
			  `reservations`.`reseller_agentID` = `reseller_agents`.`reseller_agentID`
			  AND `reseller_agents`.`resellerID` = '$_SESSION[resellerID]'
          AND `reservations`.`charterID` = `charters`.`charterID`
            AND `charters`.`boatID` = `boats`.`boatID`
            AND `reservations`.`show_as_suspended` = '0'
			$res

         ORDER BY `charters`.`start_date` DESC
         ";

			$result = $this->new_mysql($sql);
			$total_rows = $result->num_rows;

			if ($_GET['count'] == "") {
				$current = "0";
			} else {
				$current = $_GET['count'];
			}

			$next = $current + 20;

			$sql .= "LIMIT $current,20";

			$current_end = $current + 20;

			$current_start = $current_end - 19;

			if ($current > 1) {
				$prev = $current - 20;
			}

			print "<table border=0 width=100%><tr><td valign=top width=400>";

			if ($current_end > $total_rows) { $current_end = "1"; }

         print "Showing $current_start - $current_end of ".number_format($total_rows). " reservations<br>";
			if ($next <= $total_rows) {
				print "<a href=\"agentreservations.php?count=$next\">Next Page</a>";
			}
			if ($prev > -1) {
				print " | <a href=\"agentreservations.php?count=$prev\">Previous Page</a>";
			}

			print "<br><br>";

			print "</td><td valign=top>
			<form action=\"agentreservations.php\" method=\"get\">
			<input type=\"text\" name=\"res\" placeholder=\"Type in a confirmation number\" size=40> <input type=\"submit\" value=\"Search\">
			<input type=\"button\" value=\"Reset\" onclick=\"document.location.href='agentreservations.php'\">
			</form>
			</td></tr></table>";

         print "<table border=0 width=100%>
         <tr>
            <td><b>Confirmation #</b></td>
				<td><b>Agent</b></td>
            <td><b>Yacht</b></td>
            <td><b>Embark Date</b></td>
            <td><b>Nights</b></td>
            <td><b>Invoice</b></td>
				<td><b>GIS</b></td>
         </tr>";

         $result = $this->new_mysql($sql);
         while ($row = $result->fetch_assoc()) {
				$i++;
				if ($i % 2) {
					$bgcolor = "#F0F0F0";
				} else {
					$bgcolor="#FFFFFF";
				}

				switch ($_SESSION['contact_type']) {
					case "reseller_manager":
					case "reseller_agent":
					//$invoice = "<a href=\"invoicer.php?r=$row[reservationID]\" target=_blank>Reseller Invoice</a>&nbsp;|&nbsp;<a href=\"invoice.php?r=$row[reservationID]\" target=_blank>Client Invoice</a>";
               $invoice = "<a href=\"invoice.php?r=$row[reservationID]\" target=_blank>Aggressor Invoice</a> | <a href=\"generate_invoice.php?r=$row[reservationID]&rid=$_SESSION[resellerID]\" target=_blank>Generate Invoice</a>";
					break;

					default:
               $invoice = "<a href=\"generate_invoice.php?r=$row[reservationID]&rid=$_SESSION[resellerID]\" target=_blank>Generate Invoice</a>";
					break;

				}

            print "<tr bgcolor=$bgcolor>
					<td><a href=\"guests.php?res=$row[reservationID]&c=$_SESSION[contactID]\">$row[reservationID]</a></td>
					<td>$row[first] $row[last]</td>
					<td>$row[name]</td>
					<td>$row[start_date]</td>
					<td>$row[nights]</td>
					<td>$invoice</td>
					<td><a href=\"gis.php?res=$row[reservationID]\">GIS</a></td>
					</tr>";
            $found = "1";
         }
         if ($found != "1") {
				if ($_GET['res'] == "") {
	            print "<tr><td colspan=5><br><br><center>Sorry, you do not have any reservations to display.</center><br><br></td></tr>";
				} else {
               print "<tr><td colspan=5 align=\"center\"><br><br>The reservation number entered is not valid.<br></td></tr>";
				}
         }

         print "</table>";


         print "</span>";
         $this->header_bot();

      }

		public function company_info($msg) {
         $this->header_top();

         print "<br><span class=\"result-title-text\">Company Info</span><br><br>
         <span class=\"details-description\">";

			if ($_SESSION['contact_type'] != "reseller_manager") {
				print "<b><font color=red>Access Denied!</font></b>";
				print "</span>";
				$this->header_bot();
				die;
			}

			$sql = "SELECT * FROM `reseller_3rd_party` WHERE `resellerID` = '$_SESSION[resellerID]'";
			$result = $this->new_mysql($sql);
			$row = $result->fetch_assoc();
			print "$msg";
			print "
			<form action=\"update_company.php\" method=\"post\" enctype=\"multipart/form-data\">
			<table border=0 width=80%>
			<tr><td>Address Line 1:</td><td><input type=\"text\" name=\"address1\" value=\"$row[address1]\" size=40></td></tr>
			<tr><td>Address Line 2:</td><td><input type=\"text\" name=\"address2\" value=\"$row[address2]\" size=40></td></tr>
			<tr><td>City:</td><td><input type=\"text\" name=\"city\" value=\"$row[city]\" size=40></td></tr>
			<tr><td>State or Province:</td><td><input type=\"text\" name=\"state\" value=\"$row[state]\" size=40></td></tr>
			<tr><td>Country:</td><td><input type=\"text\" name=\"country\" value=\"$row[country]\" size=40></td></tr>
			<tr><td>Zip Code:</td><td><input type=\"text\" name=\"zip\" value=\"$row[zip]\" size=40></td></tr>
			<tr><td>Phone:</td><td><input type=\"text\" name=\"phone\" value=\"$row[phone]\" size=40></td></tr>
			<tr><td>Email:</td><td><input type=\"text\" name=\"email\" value=\"$row[email]\" size=40></td></tr>
			<tr><td>Logo:</td><td><input type=\"file\" name=\"logo\">";
			if ($row['logo'] != "") {
				print "<br><img src=\"logo/$row[logo]\" width=300>";
			}
			print "</td></tr>
			<tr><td>Default Commission: <br>(Whole Number Only)</td><td><input type=\"text\" name=\"default_commission\" value=\"$row[default_commission]\" size=40></td></tr>
			<tr><td colspan=2><input type=\"submit\" value=\"Save\"></td></tr>
			</table>
			</form>";

         print "</span>";
         $this->header_bot();
		}

		public function update_company() {

         if ($_SESSION['contact_type'] != "reseller_manager") {
            print "<b><font color=red>Access Denied!</font></b>";
            print "</span>";
            $this->header_bot();
            die;
         }

         $fileName = $_FILES['logo']['name'];
         $tmpName = $_FILES['logo']['tmp_name'];
         if ($fileName != "") {
						$new11 = date("U");
						$fileName = $new1 . $fileName;
                  move_uploaded_file("$tmpName", "logo/$fileName");
						$logo1 = "`logo` = '$fileName'";
						$logo2a = ",`logo`";
						$logo2b = ",'$fileName'";
         }

         $sql = "SELECT * FROM `reseller_3rd_party` WHERE `resellerID` = '$_SESSION[resellerID]'";
			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$found = "1";
			}

			if ($found == "1") {
				// update
				$sql = "UPDATE `reseller_3rd_party` SET `address1` = '$_POST[address1]', `address2` = '$_POST[address2]', `city` = '$_POST[city]', `state` = '$_POST[state]', `country` = '$_POST[country]',
				`zip` = '$_POST[zip]', `phone` = '$_POST[phone]', `email` = '$_POST[email]', `default_commission` = '$_POST[default_commission]' $logo1 WHERE `resellerID` = '$_SESSION[resellerID]'";

			} else {
				// insert
				$sql = "INSERT INTO `reseller_3rd_party` (`resellerID`,`address1`,`address2`,`city`,`state`,`country`,`zip`,`phone`,`email`,`default_commission`$logo2a) VALUES
				('$_SESSION[resellerID]','$_POST[address1]','$_POST[address2]','$_POST[city]','$_POST[state]','$_POST[country]','$_POST[zip]','$_POST[phone]','$_POST[email]','$_POST[default_commission]'$logo2b)";
			}

			$result = $this->new_mysql($sql);
			if ($result == "TRUE") {
				$msg = "<br><font color=green>The company info was updated.</font><br>";
			} else {
				$msg = "<br><font color=red>The company info failed to update.</font><br>";
			}
			$this->company_info($msg);

		}


		public function add_agents() {
         $this->header_top();
         print "<br><span class=\"result-title-text\">Add Agent</span><br><br>
         <span class=\"details-description\">";

				// check if user is a reseller manager
				$cont = $this->is_reseller();
				$this->eval_reseller($cont);

            $countries = $this->country_list($row['countryID']);

				$months = "
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
				";

				for ($i=1; $i < 32; $i++) {
					if ($i < 10) {
						$days .= "<option value=\"0$i\">$i</option>";
					} else {
						$days .= "<option value=\"$i\">$i</option>";
					}
				}

				$year_end = date("Y");
				$year_end = $year_end - 16;
				$year_start = $year_end - 80;

				for ($i=$year_start; $i < $year_end; $i++) {
					$years .= "<option>$i</option>";
				}

            print "<form action=\"agent_save.php\" name=\"myform\" method=\"post\">
            <input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">
            <input type=\"hidden\" name=\"section\" value=\"update\">
				<input type=\"hidden\" name=\"rr\" value=\"1\">
            <table border=\"0\" width=90%>
            <tr><td width=\"200\">First Name:</td><td><input type=\"text\" name=\"first\" value=\"$row[first]\" size=40></td></tr>
            <tr><td>Last Name:</td><td><input type=\"text\" name=\"last\" value=\"$row[last]\" size=40></td></tr>
            <tr><td>Address 1:</td><td><input type=\"text\" name=\"address1\" value=\"$row[address1]\" size=40></td></tr>
            <tr><td>Address 2:</td><td><input type=\"text\" name=\"address2\" value=\"$row[address2]\" size=40></td></tr>
            <tr><td>City:</td><td><input type=\"text\" name=\"city\" value=\"$row[city]\" size=40></td></tr>
            <tr><td>State: Only for the US</td><td><input type=\"text\" name=\"state\" value=\"$row[state]\" size=40></td></tr>
				<tr><td>Province:</td><td><input type=\"text\" name=\"province\" size=40></td></tr>
            <tr><td>Zip:</td><td><input type=\"text\" name=\"zip\" value=\"$row[zip]\" size=40></td></tr>
            <tr><td>Country:</td><td><select name=\"country\">$countries</select></td></tr>
				<tr><td>Gender:</td><td><input type=\"radio\" name=\"sex\" value=\"male\" checked> Male <input type=\"radio\" name=\"sex\" value=\"female\"> Female</td></tr>
				<tr><td>Birth Month:</td><td><select name=\"birth_month\">$months</select></td></tr>
				<tr><td>Birth Day:</td><td><select name=\"birth_day\">$days</select></td></tr>
				<tr><td>Birth Year:</td><td><select name=\"birth_year\">$years</select></td></tr>
            ";
            print "<tr><td>Home Phone:</td><td><input type=\"text\" name=\"phone1\" value=\"$row[phone1]\" size=40></td></tr>";
            print "<tr><td>Work Phone:</td><td><input type=\"text\" name=\"phone2\" value=\"$row[phone2]\" size=40></td></tr>";
            print "<tr><td>Cell Phone:</td><td><input type=\"text\" name=\"phone3\" value=\"$row[phone3]\" size=40></td></tr>";
            print "<tr><td>Email:</td><td><input type=\"text\" name=\"email\" value=\"$row[email]\" size=40></td></tr>";
				print "<tr><td>Username:</td><td><input type=\"text\" name=\"uuname\" value=\"$row2[uuname]\" onblur=\"check_uuname(this.form)\" size=40>&nbsp;<div id=\"info1\" style=\"display:inline\"></div></td></tr>";
            print "
            <tr><td>Password:</td><td><input type=\"text\" name=\"uupass\" value=\"$row2[uupass]\" size=40></td></tr>
            <tr><td>Reseller Type:</td><td><select name=\"contact_type\">";
            print "<option value=\"reseller_agent\">Reseller Agent</option>
            <option value=\"reseller_third_party\">Reseller Third Party</option>
            <option value=\"reseller_manager\">Reseller Manager</option></select></td></tr>";

            print "<tr><td colspan=2><input type=\"submit\" value=\"Continue\" id=\"Continue\" disabled></td></tr>";
            print "</table>
            </form>";

			?>
                                <script>
                                 function check_uuname(myform) {
                                        $.get('check_uuname.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#info1").html(php_msg);
                                        });
                                 }
											</script>
			<?php
         print "</span>";
         $this->header_bot();

		}


		public function edit_agents() {
         $this->header_top();
         print "<br><span class=\"result-title-text\">Edit Agent</span><br><br>
         <span class=\"details-description\">";

			$sql = "
			SELECT
				`countries`.`country`,
				`ra`.*

			FROM
				`reseller_agents` ra

			LEFT JOIN countries ON `ra`.`countryID` = `countries`.`countryID`

			WHERE
				`ra`.`reseller_agentID` = '$_GET[id]'
				AND `ra`.`resellerID` = '$_SESSION[resellerID]'
			";

			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {


            $countries = $this->country_list($row['countryID']);

				print "<form action=\"agent_edit.php\" method=\"post\">
				<input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">
				<input type=\"hidden\" name=\"section\" value=\"update\">
				<table border=\"0\" width=90%>
				<tr><td width=\"200\">First Name:</td><td><input type=\"text\" name=\"first\" value=\"$row[first]\" size=40></td></tr>
				<tr><td>Last Name:</td><td><input type=\"text\" name=\"last\" value=\"$row[last]\" size=40></td></tr>
				<tr><td>Address 1:</td><td><input type=\"text\" name=\"address1\" value=\"$row[address1]\" size=40></td></tr>
				<tr><td>Address 2:</td><td><input type=\"text\" name=\"address2\" value=\"$row[address2]\" size=40></td></tr>
				<tr><td>City:</td><td><input type=\"text\" name=\"city\" value=\"$row[city]\" size=40></td></tr>
				<tr><td>State:</td><td><input type=\"text\" name=\"state\" value=\"$row[state]\" size=40></td></tr>
				<tr><td>Zip:</td><td><input type=\"text\" name=\"zip\" value=\"$row[zip]\" size=40></td></tr>
				<tr><td>Country:</td><td><select name=\"country\">$countries</select></td></tr>
				";
				if ($row['phone1_type'] != "") {
					print "<tr><td>$row[phone1_type] Phone:</td><td><input type=\"text\" name=\"phone1\" value=\"$row[phone1]\" size=40></td></tr>";
				}
            if ($row['phone2_type'] != "") {
               print "<tr><td>$row[phone2_type] Phone:</td><td><input type=\"text\" name=\"phone2\" value=\"$row[phone2]\" size=40></td></tr>";
            }
            if ($row['phone3_type'] != "") {
               print "<tr><td>$row[phone3_type] Phone:</td><td><input type=\"text\" name=\"phone3\" value=\"$row[phone3]\" size=40></td></tr>";
            }
            if ($row['phone4_type'] != "") {
               print "<tr><td>$row[phone4_type] Phone:</td><td><input type=\"text\" name=\"phone4\" value=\"$row[phone4]\" size=40></td></tr>";
            }
				print "<tr><td>Email:</td><td><input type=\"text\" name=\"email\" value=\"$row[email]\" size=40></td></tr>";

				$sql2 = "
				SELECT
					`contacts`.`contactID`,
					`contacts`.`uuname`,
					`contacts`.`uupass`,
					`contacts`.`contact_type`

				FROM
					`contacts`

				WHERE
					`contacts`.`reseller_agentID` = '$_GET[id]'

				LIMIT 1
				";
				$result2 = $this->new_mysql($sql2);
				while ($row2 = $result2->fetch_assoc()) {
               if ($row2['uuname'] != "") {
                  print "<tr><td>Username:</td><td>$row2[uuname]</td></tr>";
               } else {
                  print "<tr><td>Username:</td><td><input type=\"hidden\" name=\"create_contact\" value=\"yes\"><input type=\"text\" name=\"uuname\" value=\"$row2[uuname]\" size=40></td></tr>";
               }

					print "
					<tr><td>Password:</td><td><input type=\"text\" name=\"uupass\" value=\"$row2[uupass]\" size=40></td></tr>
					<tr><td>Reseller Type:</td><td><select name=\"contact_type\">";
					if ($row2['contact_type'] != "") {
						print "<option selected>$row2[contact_type]</option>";
					}
					print "<option value=\"reseller_agent\">Reseller Agent</option>
					<option value=\"reseller_third_party\">Reseller Third Party</option>
					<option value=\"reseller_manager\">Reseller Manager</option></select></td></tr>";
					$found = "1";
				}

				if ($found != "1") {
					$sql3 = "
					SELECT * FROM `contacts` WHERE `first` = '$row[first]' AND `last` = '$row[last]' AND `email` = '$row[email]'
					";
					$result3 = $this->new_mysql($sql3);
					while ($row3 = $result3->fetch_assoc()) {
						if ($header_found == "") {
							print "<tr bgcolor=#F0F0F0><td colspan=2>We found the following contact records matching the reseller contact details: Please note if there are multiple contacts below for the same person please contact an Aggressor Fleet agent to have the contacts merged.  <br><br><b>Please note: IF the selected user already has a consumer login the same username/password will be used for their reseller login and the form below will not be used.</b></td></tr>";
							$header_found = "1";
							$checked = "checked";
						}
						$row3['city'] = substr($row3['city'],0,3);
						$row3['city'] .= "****";
						print "<tr><td colspan=2><input type=\"radio\" $checked name=\"contactID\" value=\"$row3[contactID]\"> $row3[first] $row3[last] - City: $row3[city]</td></tr>";
						$checked = "";
					}
					if ($header_found == "1") {
						print "<tr><td colspan=2><input type=\"radio\" name=\"contactID\" value=\"new\"> Setup a new contact</td></tr>";
					}

					print "<tr bgcolor=#F0F0F0><td colspan=2>This reseller does not have a login. Please select a unique username below and the type of reseller access.</td></tr>";
					if ($row2['uuname'] != "") {
						print "<tr><td>Username:</td><td>$row2[uuname]</td></tr>";
					} else {
	               print "<tr><td>Username:</td><td><input type=\"hidden\" name=\"create_contact\" value=\"yes\"><input type=\"text\" name=\"uuname\" value=\"$row2[uuname]\" size=40></td></tr>";
					}
					print "
               <tr><td>Password:</td><td><input type=\"text\" name=\"uupass\" value=\"$row2[uupass]\" size=40></td></tr>
               <tr><td>Reseller Type:</td><td><select name=\"contact_type\">";
               print "<option value=\"reseller_agent\">Reseller Agent</option>
               <option value=\"reseller_third_party\">Reseller Third Party</option>
               <option value=\"reseller_manager\">Reseller Manager</option></select></td></tr>";
				}

				print "<tr><td colspan=2><input type=\"submit\" value=\"Continue\"></td></tr>";
				print "</table>
				</form>";
			}
		

			print "</span>";
         $this->header_bot();


		}

		public function update_agent() {
         $this->header_top();
			$sql = "SELECT `resellerID` FROM `reseller_agents` WHERE `reseller_agentID` = '$_POST[id]' AND `reseller_agents`.`resellerID` = '$_SESSION[resellerID]'";
			$result = $this->new_mysql($sql);
			$row = $result->fetch_assoc();
			if ($_SESSION['resellerID'] == "1") {
				print "<br><font color=red>Sorry, your session has expired. Please log back in.</font><br>";
				die;
			}
			if ($row['resellerID'] != $_SESSION['resellerID']) {
				print "<br><font color=red>Sorry, there was an error verifing some data. Please contact Aggressor Fleet.</font><br>";
				die;
			}

			$sql = "
			UPDATE `reseller_agents` SET
			`first` = '$_POST[first]',
			`last` = '$_POST[last]',
			`address1` = '$_POST[address1]',
			`address2` = '$_POST[address2]',
			`city` = '$_POST[city]',
			`state` = '$_POST[state]',
			`zip` = '$_POST[zip]',
			`countryID` = '$_POST[country]',
			`email` = '$_POST[email]'
			WHERE `reseller_agentID` = '$_POST[id]'
			";

			$result = $this->new_mysql($sql);
			if ($result == "TRUE") {
				print "<br>The reseller agent was updated.<br><br>Click <a href=\"agents.php\">here</a> to return to the list of agents.<br><br>";
			} else {
				print "<br><font color=red>There was an error updating the reseller agent.</font>";
				die;
			}
			//print "Update SQL:<br>$sql<br>";

			if (($_POST['contactID'] != "") && ($_POST['contactID'] != "new")) {
				// check if username already entered
				$blank_uu = "1";
				$sql2 = "SELECT `uuname` FROM `contacts` WHERE `contactID` = '$_POST[contactID]'";
				$result2 = $this->new_mysql($sql2);
				while ($row2 = $result2->fetch_assoc()) {
					if ($row2['uuname'] == "") {
						$blank_uu = "1";
					} else {
						$blank_uu = "0";
					}
				}
				if ($blank_uu == "1") {
					// check if uu exists
					if ($_POST['uuname'] != "") {
						$sql2 = "SELECT `uuname` FROM `contacts` WHERE `uuname` = '$_POST[uuname]' AND `contactID` != '$_POST[contactID]'";
						$result2 = $this->new_mysql($sql2);
						while ($row2 = $result->fetch_assoc()) {
							print "<br><font color=red>Sorry, that username is already in use.</font><br>";
							die;
						}
						// inject uu info
						$add_uu = ", `uuname` = '$_POST[uuname]', `uupass` = '$_POST[uupass]'";
					}
				}

				// update existing contact
				$sql2 = "UPDATE `contacts` SET `reseller_agentID` = '$_POST[id]', `contact_type` = '$_POST[contact_type]' $add_uu WHERE `contactID` = '$_POST[contactID]'";
				$result2 = $this->new_mysql($sql2);
				if ($add_uu == "") {
					print "<br>The reseller already had a login to our reservation system. Please inform your agent to use their existing login.<br>";
				} else {
					print "<br>The username <b>$_POST[uuname]</b> and password <b>$_POST[uupass]</b> was created for $_POST[first] $_POST[last]. Please be sure to email that to $_POST[email]<br><br>";
				}
				//print "<br><br>Linking accounts:<br>$sql2<br>";
			}

			if ($_POST['contactID'] == "new") {
				// check username if exists...
            $sql2 = "SELECT `uuname` FROM `contacts` WHERE `uuname` = '$_POST[uuname]'";
            $result2 = $this->new_mysql($sql2);
            while ($row2 = $result2->fetch_assoc()) {
					print "<br><font color=red>Sorry, the username selected is already in use.</font><br>";
					die;
				}

				// insert new contact
				$sql2 = "INSERT INTO `contacts` (`first`,`last`,`address`,`address2`,`city`,`state`,`zip`,`countryID`,`email`,`reseller_agentID`,`uuname`,`uupass`,`contact_type`) VALUES
				('$_POST[first]','$_POST[last]','$_POST[address1]','$_POST[address2]','$_POST[city]','$_POST[state]','$_POST[country]','$_POST[email]','$_POST[id]','$_POST[uuname]','$_POST[uupass]','$_POST[contact_type]')";

				//print "Creating new contact:<br>$sql2<br>";
				$result2 = $this->new_mysql($sql2);
				if ($result2 == "TRUE") {
               print "<br>The username <b>$_POST[uuname]</b> and password <b>$_POST[uupass]</b> was created for $_POST[first] $_POST[last]. Please be sure to email that to $_POST[email]<br><br>";
				} else {
					print "<br><font color=red>There was an error creating the contact.</font><br>";
				}
			}

			// update RRS password
			if (($_POST['contactID'] == "") && ($_POST['uuname'] == "") && ($_POST['uupass'] != "")) {

				$sql = "SELECT `contacts`.`contactID` FROM `contacts` WHERE `contacts`.`reseller_agentID` = '$_POST[id]' LIMIT 1";
				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
					$sql2 = "UPDATE `contacts` SET `uupass` = '$_POST[uupass]',`contact_type` = '$_POST[contact_type]' WHERE `contactID` = '$row[contactID]'";
					$result2 = $this->new_mysql($sql2);
				}
			}

			// update RSS uuser and pw
         if (($_POST['contactID'] == "") && ($_POST['uuname'] != "") && ($_POST['uupass'] != "")) {
            
            $sql = "SELECT `contacts`.`contactID` FROM `contacts` WHERE `contacts`.`reseller_agentID` = '$_POST[id]' LIMIT 1";
            $result = $this->new_mysql($sql);
            while ($row = $result->fetch_assoc()) {
               $sql2 = "UPDATE `contacts` SET `uuname` = '$_POST[uuname]',`uupass` = '$_POST[uupass]',`contact_type` = '$_POST[contact_type]' WHERE `contactID` = '$row[contactID]'";
               $result2 = $this->new_mysql($sql2);
            }
         }




         $this->header_bot();

		}
}
?>
