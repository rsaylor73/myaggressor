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

               // check if user is a reseller and active
               if ($row['contact_type'] != "consumer") {
                  if ($row['reseller_agentID'] == "") {
                     $stop = "1";
                  } else {
                     $sql2 = "SELECT `waiver`,`status` FROM `reseller_agents` WHERE `reseller_agentID` = '$row[reseller_agentID]'";
                     $result2 = $this->new_mysql($sql2);
                     while ($row2 = $result2->fetch_assoc()) {
                        if ($row2['status'] == "Inactive") {
                           $stop = "1";
                        }
                        // waiver
                        if ($row2['waiver'] == "No") {
                                $this->eula();
                                die;
                        }
                     }
                  }
               }


            }
            if ($status != "TRUE") {
               $status = "FALSE";
            }
         } else {
            $status = "FALSE";
         }

         // if other error
         if ($stop == "1") {
            $status = "FALSE";
         }

         return $status;
      }


        public function eula() {
		// the end of search.php gets cut off because of the eula loading so it gets duplicated here:
		print '
	        </td></tr></table>
	        <!-- end col -->
	        </div>
	        <!-- modal helper windows -->
	        <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Destinations</h4>
                                </div>
                                <div class="modal-body">
                                        <p>You may select multiple destinations by holding the command key on a Mac or the Ctrl key on Windows</p>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                        </div>
                </div>
	        </div>
		<!-- resp -->
	        <div class="col-md-8">
		<!-- e resp -->
		';
		// end of duplicating end part of search.php

                $sql = "SELECT `tos_web` FROM `af_df_unified2`.`auto_emails` WHERE `id` = '1'";
                $result = $this->new_mysql($sql);
                while ($row = $result->fetch_assoc()) {
                        $eula = $row['tos_web'];
                }


                $this->header_top();
                $ip = $_SERVER['REMOTE_ADDR'];
                $today = date("M  j, Y");
                print "<br><span class=\"result-title-text\">Reseller and Agent Terms of Agreement</span><br><br>
                <span class=\"details-description\">";

                print "<form action=\"agree.php\" method=\"post\">";
                print "<input type=\"hidden\" name=\"r\" value=\"3\">";

                print "$eula<br>";

                print "<br>Name: $_SESSION[first] $_SESSION[middle] $_SESSION[last]<br>
                IP Address: $ip<br>
                Date: $today<br><br>
                <input type=\"image\" src=\"buttons/bt-agree.png\" value=\"I Agree\"><br>";
                print "</form>";
                print "</span>";

                $this->header_bot();
        }

        public function agree() {
                $this->header_top();
                print "<br><span class=\"result-title-text\">Reseller and Agent Terms of Agreement</span><br><br>
                <span class=\"details-description\">";

                // To do - create PDF doc
                if ($_POST['r'] == "") {
                        print "<br><br><font color=red>Error: please click back and try again.</font><br><br>";
                        print "</span>";
                        $this->header_bot();
                        die;
                } else {
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $sql = "UPDATE `reserve`.`reseller_agents` SET `waiver` = 'Yes', `ip_address` = '$ip', `timestamp` = NOW() WHERE `reseller_agentID` = '$_SESSION[reseller_agentID]'";
                        $result = $this->new_mysql($sql);
                        print "<br><br>Thank you for accepting the terms and condition of Aggressor Fleet. We will not ask you to agree to the terms again unless they change. You may now continue to your profile.<br><br>";

                }

                print "</span>";
                $this->header_bot();
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

      public function dive_map($width,$height,$bot='yes') {

	    ?>

		<script language="javascript" type="text/javascript">
		<!--
		/****************************************************
		     Author: Eric King
		     Url: http://redrival.com/eak/index.shtml
		     This script is free to use as long as this info is left in
		     Featured on Dynamic Drive script library (http://www.dynamicdrive.com)
		****************************************************/
		var win=null;
		function NewWindow(mypage,myname,w,h,scroll,pos){
		if(pos=="random"){LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}
		if(pos=="center"){LeftPosition=(screen.width)?(screen.width-w)/2:100;TopPosition=(screen.height)?(screen.height-h)/2:100;}
		else if((pos!="center" && pos!="random") || pos==null){LeftPosition=0;TopPosition=20}
		settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no';
		win=window.open(mypage,myname,settings);}
		// -->
		</script>

	    <?php

            print "
            <div id=\"result_wrapper\">
               <div id=\"result_pos1\">
                  <div id=\"result_pos2\">
                     
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
				";
				if ($bot == "yes") {
				print "
                              <table border=\"0\" width=\"950\" cellpadding=\"0\" cellspacing=\"0\" background=\"bt-bck.jpg\" height=\"30\">
                                 <tr>
                                    <td width=\"263\" class=\"details-top\">&nbsp;&nbsp;


					<a class=\"btn\" href=\"map.php\" style=\"text-decoration : none; color : #000000;\" 
					onclick=\"NewWindow(this.href,'My Aggressor','1050','650','no','center');return false\" onfocus=\"this.blur()\">
					<i class=\"fa fa-external-link\"></i>
					</a>


					&nbsp;&nbsp;MyAggressor</td>
                                    <td width=\"303\" class=\"details-top\">&nbsp;</td>
                                    <td width=\"283\" align=\"right\" class=\"details-top\">&nbsp;</td>
                                 </tr>
                              </table>
				";
				}
				print " 
                           </td>
                        </tr>
                     </table>
                    <div style=\"clear:both;\"></div>
            ";


         print "<div id=\"result_pos3\">";
      }


		public function header_bot() {
          print "<br><br></div></div></div></div>";
		}

    public function vip_status($contactID) {
        $today = date("Ymd");

        $sql = "
        SELECT
          `c`.`charterID`

        FROM
          `inventory` i,
          `reservations` r,
          `charters` c

        WHERE
          `i`.`passengerID` = '$contactID'
          AND `i`.`reservationID` = `r`.`reservationID`
          AND `r`.`show_as_suspended` != '1'
          AND `i`.`charterID` = `c`.`charterID`
          AND `c`.`start_date` < '$today'

        GROUP BY `c`.`charterID`
        ";
        $vip = "0";
        $result = $this->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
          $vip++;
        } 

        // based on 100 / 15
        $total = $vip * "6.66666666666667";
        if ($total > "100") {
          $total = "100";
        }
        return $total;

    }

    public function vip_status_plus($contactID) {
        $today = date("Ymd");

        $sql = "
        SELECT
          `c`.`charterID`

        FROM
          `inventory` i,
          `reservations` r,
          `charters` c

        WHERE
          `i`.`passengerID` = '$contactID'
          AND `i`.`reservationID` = `r`.`reservationID`
          AND `r`.`show_as_suspended` != '1'
          AND `i`.`charterID` = `c`.`charterID`
          AND `c`.`start_date` < '$today'

        GROUP BY `c`.`charterID`
        ";
        $vip = "0";
        $result = $this->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
          $vip++;
        }

        // based on 100 / 25
        $total = $vip * "4";
        if ($total > "100") {
          $total = "100";
        }
        return $total;

    }


    public function seven_seas_status($contactID) {

      $linkID2 = new mysqli(HOST, USER, PASS, DB);

      $sql = "SELECT * FROM `seven_seas`.`af_guests` WHERE `contactID` = '$contactID'";
      $result = $linkID2->query($sql);
      while ($row = $result->fetch_assoc()) {

        // andaman sea
        $andaman = 0;
        $sql2 = "SELECT * FROM `seven_seas`.`af_andaman_sea`";
        $result2 = $linkID2->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
                $boatID = $row2['boatID'];
                if ($row[$boatID] > 0) {
                        $andaman++;
                }
        }

        // caribbean
        $caribbean = 0;
        $sql2 = "SELECT * FROM `seven_seas`.`af_caribbean`";
        $result2 = $linkID2->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
                $boatID = $row2['boatID'];
                if ($row[$boatID] > 0) {
                        $caribbean++;
                }
        }

        // eastern pacific
        $eastern_pacific = 0;
        $sql2 = "SELECT * FROM `seven_seas`.`af_eastern_pacific`";
        $result2 = $linkID2->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
                $boatID = $row2['boatID'];
                if ($row[$boatID] > 0) {
                        $eastern_pacific++;
                }
        }

        // indian ocean
        $indian_ocean = 0;
        $sql2 = "SELECT * FROM `seven_seas`.`af_indian_ocean`";
        $result2 = $linkID2->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
                $boatID = $row2['boatID'];
                if ($row[$boatID] > 0) {
                        $indian_ocean++;
                }
        }

        // north atlantic
        $north_atlantic = 0;
        $sql2 = "SELECT * FROM `seven_seas`.`af_north_atlantic`";
        $result2 = $linkID2->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
                $boatID = $row2['boatID'];
                if ($row[$boatID] > 0) {
                        $north_atlantic++;
                }
        }

        // red sea
        $red_sea = 0;
        $sql2 = "SELECT * FROM `seven_seas`.`af_red_sea`";
        $result2 = $linkID2->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
                $boatID = $row2['boatID'];
                if ($row[$boatID] > 0) {
                        $red_sea++;
                }
        }

        // south pacific
        $south_pacific = 0;
        $sql2 = "SELECT * FROM `seven_seas`.`af_south_pacific`";
        $result2 = $linkID2->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
                $boatID = $row2['boatID'];
                if ($row[$boatID] > 0) {
                        $south_pacific++;
                }
        }
      }

      if ($andaman > 0) {
        $total = $total + 1.42857142857143;
      }
      if ($caribbean > 0) {
        $total = $total + 1.42857142857143;
      }
      if ($eastern_pacific > 0) {
        $total = $total + 1.42857142857143;
      }
      if ($indian_ocean > 0) {
        $total = $total + 1.42857142857143;
      }
      if ($north_atlantic > 0) {
        $total = $total + 1.42857142857143;
      }
      if ($red_sea > 0) {
        $total = $total + 1.42857142857143;
      }
      if ($south_pacific > 0) {
        $total = $total + 1.42857142857143;
      }
      return $total;

    }

	public function wishlist() {
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

	if ($_GET['a'] == "d") {
		$sql = "DELETE FROM `wish_list` WHERE `id` = '$_GET[i]' AND `contactID` = '$_SESSION[contactID]'";
		$result = $this->new_mysql($sql);
	}

        $sql = "SELECT `name`,`boatID` FROM `reserve`.`boats` WHERE `status` = 'Active' ORDER BY `name` ASC";
        $result = $this->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
                $boats .= "<option value=\"$row[boatID]\">$row[name]</option>";
        }

        print "<br><span class=\"result-title-text\">Wish List ($_SESSION[first] $_SESSION[last])</span><br><br>
        <span class=\"details-description\">";
        // content

        for ($x=1; $x < 7; $x++) {
                $hours .= "<option>$x</option>";
        }

        for ($x=1; $x < 60; $x++) {
                $minutes .= "<option>$x</option>";
        }

        print "
        <form action=\"adddwishlist.php\" method=\"post\" name=\"myform\" id=\"myform\" enctype=\"multipart/form-data\">
        <input type=\"hidden\" name=\"section\" value=\"save\">
	Please select a <b>destination</b> and a <b>itinerary</b>.<br><br>
        <table class=\"table\">
        <tr><td width=300>Yacht:</td><td><select name=\"boatID\" required onchange=\"get_itinerary(this.form)\" style=\"width:250px;\"><option selected value=\"\">--Select--</option>$boats</select></td></tr>
        <tr id=\"itinerary\" style=\"display:none\"></tr>
        <tr><td colspan=2><input type=\"submit\" value=\"Save\" class=\"btn btn-primary\">&nbsp;&nbsp;<input type=\"button\" class=\"btn btn-warning\" value=\"Cancel\" onclick=\"document.location.href='portal.php'\"></td></tr>
        </table>
        </form>
        ";


	$sql = "
	SELECT
		`d`.`description`,
		`b`.`name`,
		`w`.`id`

	FROM
		`wish_list` w, `boats` b, `destinations` d

	WHERE
		`w`.`contactID` = '$_SESSION[contactID]'
		AND `w`.`boatID` = `b`.`boatID`
		AND `w`.`itinerary` = `d`.`destinationID`
	";

	print "<table class=\"table\">";

	$result = $this->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		print "<tr><td>$row[name]</td><td>$row[description]</td><td><input type=\"button\" class=\"btn btn-danger\" onclick=\"
		if(confirm('You are about to delete $row[name] - $row[description] from your wish list. Click OK to continue.')) {
		document.location.href='wishlist.php?a=d&i=$row[id]'
		};
		\" value=\"Delete\"></td></tr>";
	}

	print "</table>";

        ?>
        <script>
        function get_itinerary(myform) {
                $.get('get_itinerary2.php',
                $(myform).serialize(),
                function(php_msg) {
                       $("#itinerary").html(php_msg);
                });
                document.getElementById('itinerary').style.display='table-row';
        }
        </script>
	<?php

	print "</span></div>";

	}

      public function myaggressor() {

        $sql = "SELECT * FROM `contacts` WHERE `contactID` = '$_SESSION[contactID]'";
        $result = $this->new_mysql($sql);
        $row = $result->fetch_assoc();
        if ($row['total_dives'] == "") {
          $row['total_dives'] = "0";
        }
        $total_dives = $row['total_dives'];

        $age = floor((time() - strtotime($row['date_of_birth'])) / 31556926);

        //print "$_SESSION[contactID]";
        //$linkID2 = new mysqli(HOST, USER, PASS, DB);
        //$sql2 = "SELECT * FROM `af_guests` WHERE `contactID` = '$_SESSION[contactID]'";
        //$result2 = $linkID2->query($sql2);
        //while ($row2 = $result2->fetch_assoc()) {
        //  print_r($row2);
        //}
        $seven_seas = $this->seven_seas_status($_SESSION['contactID']);
        $seven_seas = $seven_seas * 10;

        $vip = $this->vip_status($_SESSION['contactID']);
        $vip = $vip * 10;

	$vip_plus = $this->vip_status_plus($_SESSION['contactID']);
	$vip_plus = $vip_plus * 10;

        ?>




<table width="817" border="0" cellspacing="0" cellpadding="0">
 <tbody>
   <tr>
     <td valign="top"><table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="217">
       <tr>
         <td><br>
         <?php if ($row['avatar'] == "") { ?>
         <img src="avatar/default_avatar.png" width="200"><br>
         <?php } else { ?>
          <img src="avatar/<?=$row['avatar'];?>" width="200"><br>
          <?php } ?>
         <center><span id="myaggressor">
         <?php
         print "$row[first] $row[last]<br>$row[city], $row[state]$row[province]<br>";
         print "$age Years Young<br>";
         print "<br>Total Dives - <font color=\"green\"><b>$row[total_dives]</b></font><br><br>";
         ?>
         </span></center>
         <input type="button" style="width:200px;" value="Update Profile" class="btn btn-primary" onclick="document.location.href='profile.php';"><br><br>
         <input type="button" style="width:200px;" value="My Reservations" class="btn btn-primary" onclick="document.location.href='myreservations.php';"><br><br>
         <input type="button" style="width:200px;" value="Wish List" class="btn btn-primary" onclick="document.location.href='wishlist.php';"><br>
	


         <?php
           switch ($_SESSION['contact_type']) {
               case "reseller_manager":
               case "reseller_agent":
               case "reseller_third_party":
               print "<br><input type=\"button\" value=\"Reseller Portal\" style=\"width:200px\" class=\"btn btn-primary\" onclick=\"document.location.href='resellerportal.php'\"><br>";
               break;
            }
          ?>


        <br><span class="Section-Titles">Certifications/Awards</span><br>
                  <?php
                  if (($total_dives > 99) && ($total_dives < 200)) {
                    $this->trophy(100);
                  }
                  if (($total_dives > 199) && ($total_dives < 300)) {
                    $this->trophy(200);
                  }
                  if (($total_dives > 299) && ($total_dives < 400)) {
                    $this->trophy(300);
                  }
                  if (($total_dives > 399) && ($total_dives < 500)) {
                    $this->trophy(400);
                  }
                  if (($total_dives > 499) && ($total_dives < 600)) {
                    $this->trophy(500);
                  }
                  if (($total_dives > 599) && ($total_dives < 700)) {
                    $this->trophy(600);
                  }
                  if (($total_dives > 699) && ($total_dives < 800)) {
                    $this->trophy(700);
                  }
                  if (($total_dives > 799) && ($total_dives < 900)) {
                    $this->trophy(800);
                  }
                  if (($total_dives > 899) && ($total_dives < 1000)) {
                    $this->trophy(900);
                  }
                  if (($total_dives > 999) && ($total_dives < 1100)) {
                    $this->trophy(1000);
                  }
                  if (($total_dives > 1249) && ($total_dives < 1500)) {
                    $this->trophy(1250);
                  }
                  if (($total_dives > 1499) && ($total_dives < 1750)) {
                    $this->trophy(1500);
                  }
                  if (($total_dives > 1749) && ($total_dives < 2000)) {
                    $this->trophy(1750);
                  }
                  if (($total_dives > 1999) && ($total_dives < 2500)) {
                    $this->trophy(2000);
                  }
                  if (($total_dives > 2499) && ($total_dives < 5000)) {
                    $this->trophy(5000);
                  }

                $this->all_star();

		// test //
		if ($_SESSION['uuname'] == "test2") {
			$vip = "100";
			$vip_plus = "100";
			$seven_seas = "100";
		}


                if ($vip > 99) { $this->vip(); }
                if ($vip_plus > 99) { $this->vip_plus(); }
                if ($seven_seas > 99) { $this->seven_seas(); }


		$this->dive_cert();

                ?>

		<br><a href="viewallawards.php">View All</a>
         </td>
       </tr>


 
     </table></td>
     <td align="center" valign="top">
        <table text-align:="text-align:" center;="center;"" bgcolor="#ffffff" border="0" cellpadding="8" cellspacing="0" width="740">
        <tr>
          <td colspan="2">
          
          <span id="myaggressor">VIP Progress Bar</span><br>

          <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=$vip;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$vip;?>%;">
              <span class="sr-only"><?=$vip;?>% Complete</span>
            </div>
          </div>
          
          </td>
        </tr>


        <tr>
          <td colspan="2">
          
          <span id="myaggressor">VIP<i>plus</i> Progress Bar</span><br>

          <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=$vip_plus;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$vip_plus;?>%;">
              <span class="sr-only"><?=$vip_plus;?>% Complete</span>
            </div>
          </div>
          
          </td>
        </tr>



        <tr>
          <td colspan="2">
          
          <span id="myaggressor">7 Seas Progress Bar</span><br>
          <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=$seven_seas;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$seven_seas;?>%;">
              <span class="sr-only"><?=$seven_seas;?>% Complete</span>
            </div>
          </div>        
          </td>
        </tr>

        <tr><td colspan="3"><hr></td></tr>

        <tr>
          <td colspan="3">
          <table border="0" width="100%">
            <tr>
              <td width="50%">
                <table border="0" width="300">
                  <tr>
                    <td valign=top>
                     &nbsp;&nbsp; 
                    </td>
                    <td valign=top>

                      <span class="Section-Titles">My Dive Logs</span><br>
                      <?php
                      $sql = "SELECT `id`,DATE_FORMAT(`dive_date`,'%m/%d/%Y') AS 'dive_date', `site` FROM `dive_log` WHERE `contactID` = '$_SESSION[contactID]' ORDER BY `dive_date` DESC LIMIT 4";
                      $result = $this->new_mysql($sql);
                      while ($row = $result->fetch_assoc()) {
                        print "<a href=\"adddivelog.php?section=edit&id=$row[id]\"><i class=\"fa fa-file-text-o\" aria-hidden=\"true\"></i> $row[dive_date] - $row[site]</a><br>";
                      }


                      ?>


                      <br><a href="adddivelog.php"><i class="fa fa-plus" aria-hidden="true"></i> Add Log</a>&nbsp;&nbsp;<a href="adddivelog.php?section=viewall"><i class="fa fa-reply-all" aria-hidden="true"></i> View All</a><br>
                    </td>
                  </tr>
                </table>
              
              </td>
              <td width="50%">
                
                <table border="0" width="300">
                  <tr>
                    <td valign=top>
                      &nbsp;&nbsp;
                    </td>
                    <td valign=top>

                      <?php
                      $sql = "SELECT `points` FROM `contacts` WHERE `contactID` = '$_SESSION[contactID]'";
                      $result = $this->new_mysql($sql);
                      while ($row = $result->fetch_assoc()) {
                        $points = $row['points'];
                      }
                      if ($points == "") {
                        $points = "0";
                      }

                      ?>


                      <span class="Section-Titles">Boutique Points</span><br><br>
                      <img src="images/icons/icn_storepoints.png">&nbsp;&nbsp;<span class="storepoints"><?=$points;?></span><br><br><br>
                      <a href="redeem.php"><i class="fa fa-minus" aria-hidden="true"></i> Redeem Now</a>&nbsp;&nbsp;<br>

                    </td>
                  </tr>
                </table>              
              </td>
            </tr>
            <tr>

            <tr><td colspan="2"><hr></td></tr>

              <td valign=top colspan="2">

              <?php
              $this->random_special();
              ?>
              </td>

            </tr>

            <tr><td colspan="2"><hr></td></tr>

            <tr>
              <td valign=top>
                <span class="Section-Titles">Creature Checklist</span><br><br>

                <?php
                $this->creature_list('creature','10','normal');
                ?>
                <br><br>
                <a href="viewallcreatures.php?section=creature">View All</a><br><br>
              </td>

              <td>
                <span class="Section-Titles">Most Wanted</span><br><br>

                <?php
                $this->creature_list('wanted','10','normal');
                ?>
                <br><br>
                <a href="viewallcreatures.php?section=wanted">View All</a><br><br>
              </td>
            </tr>


          </table>

          </td>
        </tr>



         
     </table></td>
   </tr>
 </tbody>
</table>




         <?php


      }

      public function view_all_creature() {
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
        print "<br><span class=\"result-title-text\">Creature ($_SESSION[first] $_SESSION[last])</span><br><br>
        <span class=\"details-description\">";
        print "<h2>Select up to 10</h2>";

        $this->creature_list('creature','999','normal');

        print "</span>";
        $this->header_bot();  
      }

      public function view_all_wanted() {
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
        print "<br><span class=\"result-title-text\">Wanted ($_SESSION[first] $_SESSION[last])</span><br><br>
        <span class=\"details-description\">";
        print "<h2>Select up to 10</h2>";


        $this->creature_list('wanted','999','normal');


        print "</span>";
        $this->header_bot();  
      }

      public function creature_list($type,$max,$order) {
        if ($max == "") {
          $max = "99999";
        }

        if ($type == "creature") {
          $sql = "
          SELECT 
            `c`.`title`,
            `c`.`id`,
            `c`.`description`,
            `ccl`.`contactID`,
            `ccl`.`cid`

          FROM 
            `af_df_unified2`.`creature` c

          LEFT JOIN `reserve`.`creature_check_list` ccl ON `c`.`id` = `ccl`.`cid` AND `ccl`.`contactID` = '$_SESSION[contactID]'

          ORDER BY -`ccl`.`cid` DESC

          LIMIT 0,$max
          ";
        }

        if ($type == "wanted") {
          $sql = "
          SELECT 
            `c`.`title`,
            `c`.`id`,
            `c`.`description`,
            `ccl`.`contactID`,
            `ccl`.`cid`

          FROM 
            `af_df_unified2`.`creature` c

          LEFT JOIN `reserve`.`wanted_check_list` ccl ON `c`.`id` = `ccl`.`cid` AND `ccl`.`contactID` = '$_SESSION[contactID]'

          ORDER BY -`ccl`.`cid` DESC

          LIMIT 0,$max
          ";
        }

        print "<table class=\"table\">
        <form action=\"creature.php\" method=\"post\">
        <input type=\"hidden\" name=\"section\" value=\"$type\">
        ";

        $result = $this->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
          $checked = "";
          if ($row['cid'] == $row['id']) {
            $checked = "checked";
          }
          print "<td><input type=\"checkbox\" name=\"id$row[id]\" value=\"checked\" $checked></td><td>
            <input type=\"hidden\" name=\"id\" value=\"$row[id]\">
            <a href=\"#\" data-toggle=\"modal\" data-target=\"#basicModal$row[id]\">$row[title]</a>
            ";

        print '
    <div class="modal fade" id="basicModal'.$row['id'].'" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">'.$row['title'].'</h4>
          </div>
          <div class="modal-body">
            '.$row['description'].'
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
        ';

            print "</td></tr>";
        }

        print "</table><input type=\"submit\" value=\"Save\" class=\"btn btn-success\"></form>";


      }

      public function random_special() {
        $today = date("Y-m-d");
        $sql = "
        SELECT `af_df_unified2`.`specials`.*
      
        FROM `af_df_unified2`.`specials`

        WHERE
          `af_df_unified2`.`specials`.`start_date` <= '$today'
          AND `af_df_unified2`.`specials`.`end_date` >= '$today'
          AND `af_df_unified2`.`specials`.`type` = 'Specials'
          AND `af_df_unified2`.`specials`.`myaggressor` != ''

        ORDER BY RAND()

        LIMIT 1
        ";
        $result = $this->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
          print "<a href=\"http://www.aggressor.com/specials.php#$row[anchor]\" target=_blank>$row[myaggressor]</a>";
        }
      }

      public function dive_countdown($reservationID) {
        $today = date("Ymd");
        $sql = "
        SELECT
          `b`.`name`,
	  `b`.`logo_url`,
          DATEDIFF(`c`.`start_date`,'$today') AS 'days'

        FROM
          `reservations` r, `charters` c, `inventory` i, `boats` b

        WHERE
          `r`.`reservationID` = '$reservationID'
          AND `r`.`charterID` = `c`.`charterID`
          AND `r`.`reservationID` = `i`.`reservationID`
          AND `i`.`passengerID` = '$_SESSION[contactID]'
          AND `c`.`boatID` = `b`.`boatID`
          AND `c`.`start_date` > '$today'

        LIMIT 1
        ";
        $result = $this->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
          print "<br>
          <table class=\"table-curved\" cellspacing=\"0\" cellpadding=\"0\" width=\"220\" height=\"75\">
            <td valign=middle width=\"72\"><center><img src=\"$row[logo_url]\" width=\"60\" height=\"71\"></center></td>
            <td valign=middle width=\"148\"><b><span class=\"details-prices2\"><center>$row[name]</center></span></b>
            <span class=\"details-prices2-red\"><p><center>$row[days] Days</center></p></span></td>
          </tr>
          </table>
	  ";
        }


      }

      public function save_creature() {
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
        print "<br><span class=\"result-title-text\">Creatures ($_SESSION[first] $_SESSION[last])</span><br><br>
        <span class=\"details-description\">";

        $sql = "DELETE FROM `reserve`.`creature_check_list` WHERE `contactID` = '$_SESSION[contactID]'";
        $result = $this->new_mysql($sql);

        foreach ($_POST as $key=>$value) {

          $cid = substr($key,2);
          $y = "id";
          $y .= $cid;
          if ($_POST[$y] == "checked") {
            $sql2 = "INSERT INTO `reserve`.`creature_check_list` (`contactID`,`cid`) VALUES ('$_SESSION[contactID]','$cid')";
            $result2 = $this->new_mysql($sql2);
          }
        }

        print "<br><br>The creature list was updated. Loading...<br><br>";

	?>
                <script>
                setTimeout(function() { document.location.href='portal.php'},2000);
                </script>
	<?php

        print "</span>";
        $this->header_bot();         
      }

      public function save_wanted() {
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
        print "<br><span class=\"result-title-text\">Wanted ($_SESSION[first] $_SESSION[last])</span><br><br>
        <span class=\"details-description\">";

        $sql = "DELETE FROM `reserve`.`wanted_check_list` WHERE `contactID` = '$_SESSION[contactID]'";
        $result = $this->new_mysql($sql);

        foreach ($_POST as $key=>$value) {

          $cid = substr($key,2);
          $y = "id";
          $y .= $cid;
          if ($_POST[$y] == "checked") {
            $sql2 = "INSERT INTO `reserve`.`wanted_check_list` (`contactID`,`cid`) VALUES ('$_SESSION[contactID]','$cid')";
            $result2 = $this->new_mysql($sql2);
          }
        }

        print "<br><br>The wanted list was updated. Loading...<br><br>";
	?>
                <script>
                setTimeout(function() { document.location.href='portal.php'},2000);
                </script>
	<?php
        print "</span>";
        $this->header_bot();         
      }


      private function all_star() {
        $year = date("Y");
        $today = date("Ymd");

        $sql = "
        SELECT
          `c`.`charterID`

        FROM
          `inventory` i,
          `charters` c

        WHERE
          `i`.`passengerID` = '$_SESSION[contactID]'
          AND `i`.`charterID` = `c`.`charterID`
          AND `c`.`start_date` < '$today'
          AND DATE_FORMAT(`c`.`start_date`, '%Y') = '$year'


        GROUP BY `i`.`charterID`
        ";

        $total = "0";
        $result = $this->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
          $total++;
        }
	if ($_SESSION['uuname'] == "test2") {
		$total = "3";
	}
        if ($total > 2) {
          print '
          <div>
          <img src="images/icons/icn_AllStars.png">&nbsp;<span class="my-text">All Star</span>
          </div>
          ';  
        }
      }

      private function dive_cert() {
	$sql = "SELECT * FROM `dive_certifications` WHERE `contactID` = '$_SESSION[contactID]' ORDER BY `certification` ASC";
	$result = $this->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		print '<div><img src="images/icons/icn_divecerts.png">&nbsp;<span class="my-text">'.$row['certification'].'</span></div>';
	}

      }

      private function trophy($dives) {
        print '
        <div>
        <img src="images/icons/icn_dives.png">&nbsp;<span class="my-text">'.$dives.'</span>
        </div>
        ';  
      }

      private function vip() {
        print '
          <div>
          <img src="images/icons/icn_VIP.png">&nbsp;<span class="my-text">VIP</span>
          </div>
        ';
      }

      private function vip_plus() {
        print '
          <div>
          <img src="images/icons/icn_VIPplus.png">&nbsp;<span class="my-text">VIP<i>plus</i></span>
          </div>
        ';
      }

      private function seven_seas() {
        print '
          <div>
          <img src="images/icons/icn_7seas.png">&nbsp;<span class="my-text">7 Seas</span>
          </div>
        ';
      }

      public function redeem_form() {
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
        print "<br><span class=\"result-title-text\">Points ($_SESSION[first] $_SESSION[last])</span><br><br>
        <span class=\"details-description\">";

        $points = "0";
        $sql = "SELECT `points` FROM `contacts` WHERE `contactID` = '$_SESSION[contactID]'";
        $result = $this->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
          $points = $row['points'];
        }
        if ($points > 0) {
          print "<form action=\"redeem.php\" method=\"post\">
          <input type=\"hidden\" name=\"section\" value=\"redeem\">
          <table class=\"table\">
          <tr><td>How many points would you like to redeem?</td><td><input type=\"text\" name=\"points\" size=\"20\" required></td></tr>
          <tr><td>&nbsp;</td><td><b>Balance: $points</b></td></tr>
          <tr><td colspan=\"2\"><input type=\"submit\" value=\"Redeem Points\" class=\"btn btn-primary\">&nbsp;&nbsp;
          <input type=\"button\" value=\"Cancel\" class=\"btn btn-warning\" onclick=\"document.location.href='portal.php'\"></td></tr>
          </table>
          </form>";
        } else {
          print "<br><font color=red>Sorry, but you do not have any points to redeem.</font><br>";
        }

        print "</span>";
        $this->header_bot();

      }

      public function viewallawards() {
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
        print "<br><span class=\"result-title-text\">Awards ($_SESSION[first] $_SESSION[last])</span><br><br>
        <span class=\"details-description\">";

                      $sql = "SELECT `total_dives` FROM `contacts` WHERE `contactID` = '$_SESSION[contactID]'";
                      $result = $this->new_mysql($sql);
                      while ($row = $result->fetch_assoc()) {
                        $total_dives = $row['total_dives'];
                      }
                      if ($total_dives == "") {
                        $total_dives = "0";
                      }

                      $seven_seas = $this->seven_seas_status($_SESSION['contactID']);
                      $seven_seas = $seven_seas * 10;

                      $vip = $this->vip_status($_SESSION['contactID']);
                      $vip = $vip * 10;

                      $vip_plus = $this->vip_status_plus($_SESSION['contactID']);
                      $vip_plus = $vip_plus * 10;

                  print "<h2>Dive Milestone(s)</h2>";
                  print "<table class=\"table\">
                  <tr>";

                  if ($total_dives < 99) {
                    print "<td>Keep diving. You will earn your first milestone at 100 dives.</td>";
                  }

                  if ($total_dives > 99) {
                    print "<td>";
                    $this->trophy(100);
                    print "</td>";
                  }
                  if ($total_dives > 199) {
                    print "<td>";
                    $this->trophy(200);
                    print "</td>";
                  }
                  if ($total_dives > 299) {
                    print "<td>";
                    $this->trophy(300);
                    print "</td>";
                  }
                  if ($total_dives > 399) {
                    print "<td>";
                    $this->trophy(400);
                    print "</td></tr>";
                  }
                  if ($total_dives > 499) {
                    print "<tr><td>";
                    $this->trophy(500);
                    print "</td>";
                  }
                  if ($total_dives > 599) {
                    print "<td>";
                    $this->trophy(600);
                    print "</td>";
                  }
                  if ($total_dives > 699) {
                    print "<td>";
                    $this->trophy(700);
                    print "</td>";
                  }
                  if ($total_dives > 799) {
                    print "<td>";
                    $this->trophy(800);
                    print "</td></tr>";
                  }
                  if ($total_dives > 899) {
                    print "<tr><td>";
                    $this->trophy(900);
                    print "</td>";
                  }
                  if ($total_dives > 999) {
                    print "<td>";
                    $this->trophy(1000);
                    print "</td>";
                  }
                  if ($total_dives > 1249) {
                    print "<td>";
                    $this->trophy(1250);
                    print "</td>";
                  }
                  if ($total_dives > 1499) {
                    print "<td>";
                    $this->trophy(1500);
                    print "</td></tr>";
                  }
                  if ($total_dives > 1749) {
                    print "<tr><td>";
                    $this->trophy(1750);
                    print "</td>";
                  }
                  if ($total_dives > 1999) {
                    print "<td>";
                    $this->trophy(2000);
                    print "</td>";
                  }
                  if ($total_dives > 2499) {
                    print "<td>";
                    $this->trophy(5000);
                    print "</td>";
                  }
                  print "</tr></table>";

			if ($_GET['part'] == "d") {
				$sql = "DELETE FROM `reserve`.`iron_divers` WHERE `id` = '$_GET[i]' AND `contactID` = '$_SESSION[contactID]'";
				$result = $this->new_mysql($sql);
			}

			if ($_GET['part'] == "d2") {
				$sql = "DELETE FROM `reserve`.`dive_certifications` WHERE `id` = '$_GET[i]' AND `contactID` = '$_SESSION[contactID]'";
                                $result = $this->new_mysql($sql);
                        }


                  print "<h2>Dive Certifications <input type=\"button\" class=\"btn btn-primary\" value=\"Add\" onclick=\"document.location.href='newdivecertification.php'\"></h2>
                  Self-Awarded to every guest who completed the requirements while on an Aggressor Fleet yacht.<br><br>";

			$sql = "SELECT * FROM `dive_certifications` WHERE `contactID` = '$_SESSION[contactID]' ORDER BY `certification` ASC";
                        $result = $this->new_mysql($sql);
                        while ($row = $result->fetch_assoc()) {
                                print "<a href=\"viewallawards.php?part=d2&i=$row[id]\">
                                <i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;&nbsp;$row[certification]<br>";
                                $d2 = "1";
                        }
                        if ($d2 == "") {
                                print "<font color=blue>You do not have any Dive Certifications.</font><br>";
                        }

		  print "<h2>Iron Divers <input type=\"button\" class=\"btn btn-primary\" value=\"Add\" onclick=\"document.location.href='newirondiver.php'\"></h2>
		  Self-Awarded to every guest who completed the requirements while on an Aggressor Fleet yacht.<br><br>";

			$sql = "
			SELECT
				`b`.`name`,
				DATE_FORMAT(`id`.`date`, '%m/%d/%Y') AS 'date',
				`id`.`id`
			FROM
				`reserve`.`iron_divers` id, `reserve`.`boats` b

			WHERE
				`id`.`boatID` = `b`.`boatID`
				AND `id`.`contactID` = '$_SESSION[contactID]'

			ORDER BY `b`.`name` ASC
			";
			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				print "<a href=\"viewallawards.php?part=d&i=$row[id]\">
                                <i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;&nbsp;$row[name] Iron Diver<br>";
				$d = "1";
			}
			if ($d == "") {
				print "<font color=blue>You do not have any Iron Diver awards.</font><br>";
			}

                  print "<h2>All-Star Divers</h2>";
                  print "Awarded to every guest who traveled with Aggressor Fleet for 3 trips or more within the year<br><br>";
                  $this->all_star();

                  print "<h2>VIP</h2>";
                  print "Awarded to every guest who has been on 15 trips with Aggressor Fleet.<br><br>";
                  if ($vip > 99) { $this->vip(); }
                  if ($vip_plus > 99) { $this->vip_plus(); }

                  print "<h2>Seven Seas</h2>";
                  print "Awarded to every guest who has been to at least 7 seas with Aggressor Fleet.<br><br>";
                  if ($seven_seas > 99) { $this->seven_seas(); }



        print "</span>";
        $this->header_bot();
        
      }

	public function newdivecert() {
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
                print "<br><span class=\"result-title-text\">New Dive Certification ($_SESSION[first] $_SESSION[last])</span><br><br>
                <span class=\"details-description\">";

                $options = "<option value=\"\">--Select--</option>i";
		$options .= "
			<option>Non-Diver</option>
			<optin>Junior Open Water</option>
			<option>Open Water</option>
			<option>Advanced Open Water</option>
			<option>Rescue Diver</option>
			<option>Master Suba Diver</option>
			<option>Dive Master</option>
			<option>Assistant Instructor</option>
			<option>Instructor</option>
			<option>Instructor Trainer</option>
			<option>Nitrox</option>
		";


                print "
                <form action=\"newdivecertification.php\" method=\"post\">
                <input type=\"hidden\" name=\"section\" value=\"save\">
                <table class=\"table\">
                <tr><td>Certification Type:</td><td><select name=\"cert\" required>$options</select></td></tr>
                <tr><td colspan=2><input type=\"submit\" value=\"Save\" class=\"btn btn-primary\"></td></tr>
                </table>
                </form>";

                print "</span>";
                $this->header_bot();
	}

	public function savedivecert() {
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
                print "<br><span class=\"result-title-text\">New Dive Certification ($_SESSION[first] $_SESSION[last])</span><br><br>
                <span class=\"details-description\">";

                $today = date("Ymd");
                $sql = "INSERT INTO `reserve`.`dive_certifications` (`contactID`,`certification`,`date`) VALUES ('$_SESSION[contactID]','$_POST[cert]','$today')";
                $result = $this->new_mysql($sql);
                if ($result == "TRUE") {
                        print "<br><br>Your Dive Certification was added. Loading...<br>";
                        ?>
                        <script>
                        setTimeout(function() { document.location.href='portal.php'},2000);
                        </script>
                        <?php
                } else {
                        print "<br><br><font color=red>There was an error saving your certification.</font><br><br>";
                }

                print "</span>";
                $this->header_bot();
	}

	public function newirondiver() {
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
        	print "<br><span class=\"result-title-text\">New Iron Diver ($_SESSION[first] $_SESSION[last])</span><br><br>
	        <span class=\"details-description\">";

		$options = "<option value=\"\">--Select--</option>";
		$sql = "SELECT `name`,`boatID` FROM `reserve`.`boats` WHERE `status` = 'Active' ORDER BY `name` ASC";
		$result = $this->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			$options .= "<option value=\"$row[boatID]\">$row[name] Iron Diver</option>";
		}

		print "
		<form action=\"newirondiver.php\" method=\"post\">
		<input type=\"hidden\" name=\"section\" value=\"save\">
		<table class=\"table\">
		<tr><td>Select Yacht:</td><td><select name=\"boatID\" required>$options</select></td></tr>
		<tr><td colspan=2><input type=\"submit\" value=\"Save\" class=\"btn btn-primary\"></td></tr>
		</table>
		</form>";
		
	        print "</span>";
        	$this->header_bot();
	}

	public function saveirondiver() {
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
	        print "<br><span class=\"result-title-text\">New Iron Diver ($_SESSION[first] $_SESSION[last])</span><br><br>
        	<span class=\"details-description\">";

		$today = date("Ymd");
		$sql = "INSERT INTO `reserve`.`iron_divers` (`boatID`,`contactID`,`date`) VALUES ('$_POST[boatID]','$_SESSION[contactID]','$today')";
		$result = $this->new_mysql($sql);
		if ($result == "TRUE") {
			print "<br><br>Your Iron Diver award was added. Loading...<br>";
			?>
	                <script>
        	        setTimeout(function() { document.location.href='portal.php'},2000);
                	</script>
			<?php
		} else {
			print "<br><br><font color=red>There was an error saving your award.</font><br><br>";
		}

	        print "</span>";
        	$this->header_bot();   
	}

      public function redeem_points() {
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
        print "<br><span class=\"result-title-text\">Points ($_SESSION[first] $_SESSION[last])</span><br><br>
        <span class=\"details-description\">";

        $points = "0";
        $sql = "SELECT `points` FROM `contacts` WHERE `contactID` = '$_SESSION[contactID]'";
        $result = $this->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
          $points = $row['points'];
        }
        if ($_POST['points'] <= $points) {
          $amount = $_POST['points'] * 0.05;
          $this->create_coupon($amount,$_POST['points']);

        } else {
          print "<br><br><font color=red>Sorry, but you have requested more points then you have available.</font><br><br>";
        }

        print "</span>";
        $this->header_bot();

      }

      private function generate_code($length = 10) {
        $code = '';
        $total = 0;

        do
        {
          if (rand(0, 1) == 0)
          {
            $code.= chr(rand(97, 122)); // ASCII code from **a(97)** to **z(122)**
          }
          else
          {
            $code.= rand(0, 9); // Numbers!!
          }
          $total++;
        } while ($total < $length);

        return $code;
      }


      private function create_coupon($amount,$points) {

        if ($_SESSION['points'] == $points) {
          print "<br><font color=red>Sorry, but we have detected the same amount of points was just issued as a coupon code. If you wish to generate another code please use a different value of points.
          <br><br></font>";
          die;
        }

        $linkID2 = new mysqli(HOST2, USER2, PASS2, DB2);

        //$sql = "SELECT * FROM `seven_seas`.`af_guests` WHERE `contactID` = '$contactID'";
        //$result = $linkID2->query($sql);

        $code = $this->generate_code('8');

        $date1 = date("Y-m-d H:i:s");
        $date2 = date("Y-m-d H:i:s", strtotime($date1 . ' +30 day'));
        $date3 = date("Ymd");

        $sql = "
        INSERT INTO `ps_cart_rule` 

        (`id_customer`,`date_from`,`date_to`,`description`,`quantity`,`quantity_per_user`,`priority`,`partial_use`,`code`,`minimum_amount`,`minimum_amount_tax`,`minimum_amount_currency`,
        `minimum_amount_shipping`,`country_restriction`,`carrier_restriction`,`group_restriction`,`cart_rule_restriction`,`product_restriction`,`shop_restriction`,`free_shipping`,
        `reduction_percent`,`reduction_amount`,`reduction_tax`,`reduction_currency`,`reduction_product`,`gift_product`,`gift_product_attribute`,`highlight`,`active`,
        `date_add`,`date_upd`)

        VALUES

        ('0','$date1','$date2','My Aggressor Points Redeemed','1','1','1','1','$code','0.00','0','1','0','0','0','0','0','0','0','0','0.00','$amount','0','1','0','0','0',
        '0','1','$date1','$date1'
        )
        ";

        $result = $linkID2->query($sql);
        if ($result == "TRUE") {
          $id = $linkID2->insert_id;
          $sql2 = "INSERT INTO `ps_cart_rule_lang` (`id_cart_rule`,`id_lang`,`name`) VALUES ('$id','1','My Aggressor Points Redeemed')";
          $result2 = $linkID2->query($sql2);

          // log the details
          $sql3 = "INSERT INTO `points_log` (`contactID`,`points_used`,`code_issued`,`date`) VALUES ('$_SESSION[contactID]','$points','$code','$date3')";
          $result3 = $this->new_mysql($sql3);

          // balance the user
          $sql4 = "SELECT `points` FROM `contacts` WHERE `contactID` = '$_SESSION[contactID]'";

          $result4 = $this->new_mysql($sql4);
          while ($row4 = $result4->fetch_assoc()) {
            $points_balance = $row4['points'];
          }
          $points_balance = $points_balance - $points;
          $sql5 = "UPDATE `contacts` SET `points` = '$points_balance' WHERE `contactID` = '$_SESSION[contactID]'";

          $result5 = $this->new_mysql($sql5);
          $_SESSION['points'] = $points;

          print "<br><br>Thank you, your points have been redeemed in the amount <b>$ $amount</b>. Please use coupon code <b>$code</b> at the Aggressor Fleet Boutique.<br><br>
          Please print this page for your records.<br><br><input type=\"button\" value=\"Back to My Aggressor\" class=\"btn btn-success\" onclick=\"document.location.href='portal.php'\"><br>";
        }



      }

      public function add_divelog() {
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

	$sql = "SELECT `name`,`boatID` FROM `reserve`.`boats` WHERE `status` = 'Active' ORDER BY `name` ASC";
	$result = $this->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		$boats .= "<option value=\"$row[boatID]\">$row[name]</option>";
	}

        print "<br><span class=\"result-title-text\">Dive Log ($_SESSION[first] $_SESSION[last])</span><br><br>
        <span class=\"details-description\">";
        // content

	for ($x=1; $x < 7; $x++) {
		$hours .= "<option>$x</option>";
	}

	for ($x=1; $x < 60; $x++) {
		$minutes .= "<option>$x</option>";
	}

        print "
        <form action=\"adddivelog.php\" method=\"post\" name=\"myform\" id=\"myform\" enctype=\"multipart/form-data\">
        <input type=\"hidden\" name=\"section\" value=\"save\">
        <table class=\"table\">
	<tr><td>Dive Trip:</td><td><select name=\"boatID\" required onchange=\"get_itinerary(this.form)\" style=\"width:250px;\"><option selected value=\"\">--Select--</option>$boats</select></td></tr>
	<tr id=\"itinerary\" style=\"display:none\"></tr>
        <tr><td>Dive Date:</td><td><input type=\"text\" name=\"dive_date\" id=\"dive_date\" size=\"40\" required></td></tr>
        <tr><td>Dive Site:</td><td><input type=\"text\" name=\"site\" size=\"40\" required></td></tr>
        <tr><td>Dive Buddies:</td><td><textarea name=\"dive_buddies\" cols=40 rows=5></textarea></td></tr>
        <tr><td>Max Depth:</td><td><input type=\"text\" name=\"max_depth\" size=\"40\"></td></tr>
        <tr><td>Bottom Time:</td><td><select name=\"bottom_time_hours\">$hours</select> Hour(s)&nbsp;&nbsp;&nbsp; <select name=\"bottom_time_mins\">$minutes</select> Minute(s)</td></tr>
	<tr><td>Water Temp:</td><td><input type=\"text\" name=\"water_temp\" size=\"40\"></td></tr>
	<tr><td>Air Temp:</td><td><input type=\"text\" name=\"air_temp\" size=\"40\"></td></tr>
        <tr><td>Describe Your Dive:</td><td><textarea name=\"description\" cols=40 rows=10></textarea></td></tr>
        <tr><td>Rate This Dive:</td><td><select name=\"rating\">
          <option selected value=\"5\">5 Stars</option>
          <option value=\"4\">4 Stars</option>
          <option value=\"3\">3 Stars</option>
          <option value=\"2\">2 Stars</option>
          <option value=\"1\">1 Stars</option>
          </select></td></tr>
	<tr><td>Upload Image:</td><td><input type=\"file\" name=\"dive_image\"></td></tr>
        <tr><td colspan=2><input type=\"submit\" value=\"Save\" class=\"btn btn-primary\">&nbsp;&nbsp;<input type=\"button\" class=\"btn btn-warning\" value=\"Cancel\" onclick=\"document.location.href='portal.php'\"></td></tr>
        </table>
        </form>
        ";

	?>
	<script>
        function get_itinerary(myform) {
	        $.get('get_itinerary.php',
                $(myform).serialize(),
                function(php_msg) {
         	       $("#itinerary").html(php_msg);
                });
		document.getElementById('itinerary').style.display='table-row';
        }
	</script>
	<?php

        // end content
        print "</span>";
        $this->header_bot();
      }

      public function edit_divelog() {
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
        print "<br><span class=\"result-title-text\">Dive Log ($_SESSION[first] $_SESSION[last])</span><br><br>
        <span class=\"details-description\">";
        // content

        $sql = "
	SELECT 
		`d`.*,
		`b`.`name`

	FROM 
		`dive_log` d

	LEFT JOIN `boats` b ON `d`.`boatID` = `b`.`boatID`

	WHERE 
		`d`.`id` = '$_GET[id]' 
		AND `d`.`contactID` = '$_SESSION[contactID]'

	";
        $result = $this->new_mysql($sql);
        $row = $result->fetch_assoc();

	if ($row['bottom_time_hours'] != "") {
		$hours .= "<option selected>$row[bottom_time_hours]</option>";
	}
        for ($x=1; $x < 7; $x++) {
                $hours .= "<option>$x</option>";
        }

	if ($row['bottom_time_mins'] != "") {
		$minutes .= "<option selected>$row[bottom_time_mins]</option>";
	}
        for ($x=1; $x < 60; $x++) {
                $minutes .= "<option>$x</option>";
        }

        print "
        <form action=\"adddivelog.php\" method=\"post\" enctype=\"multipart/form-data\">
        <input type=\"hidden\" name=\"section\" value=\"update\">
        <input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">
        <table class=\"table\">
	<tr><td>Dive Trip:</td><td>$row[name]</td></tr>
	<tr><td>Itinerary:</td><td>$row[itinerary]</td></tr>
        <tr><td>Dive Date:</td><td><input type=\"text\" name=\"dive_date\" id=\"dive_date\" value=\"$row[dive_date]\" size=\"40\" required></td></tr>
        <tr><td>Dive Site:</td><td><input type=\"text\" name=\"site\" size=\"40\" value=\"$row[site]\" required></td></tr>
        <tr><td>Dive Buddies:</td><td><textarea name=\"dive_buddies\" cols=40 rows=5>$row[dive_buddies]</textarea></td></tr>
        <tr><td>Max Depth:</td><td><input type=\"text\" name=\"max_depth\" value=\"$row[max_depth]\" size=\"40\"></td></tr>

        <tr><td>Bottom Time:</td><td><select name=\"bottom_time_hours\">$hours</select> Hour(s)&nbsp;&nbsp;&nbsp; <select name=\"bottom_time_mins\">$minutes</select> Minute(s)</td></tr>
        <tr><td>Water Temp:</td><td><input type=\"text\" name=\"water_temp\" value=\"$row[water_temp]\" size=\"40\"></td></tr>
        <tr><td>Air Temp:</td><td><input type=\"text\" name=\"air_temp\" value=\"$row[air_temp]\" size=\"40\"></td></tr>

        <tr><td>Describe Your Dive:</td><td><textarea name=\"description\" cols=40 rows=10>$row[description]</textarea></td></tr>
        <tr><td>Rate This Dive:</td><td><select name=\"rating\">
          <option selected value=\"$row[rating]\">$row[rating] Stars</option>
          <option value=\"5\">5 Stars</option>
          <option value=\"4\">4 Stars</option>
          <option value=\"3\">3 Stars</option>
          <option value=\"2\">2 Stars</option>
          <option value=\"1\">1 Stars</option>
          </select></td></tr>

        <tr><td valign=top>Upload Image:</td><td valign=top><input type=\"file\" name=\"dive_image\">
	";
	if ($row['dive_image'] != "") {
		print "<br><img src=\".divelogimages/$row[dive_image]\" width=\"300\">";
	}
	print "
	</td></tr>

        <tr><td colspan=2><input type=\"submit\" value=\"Update\" class=\"btn btn-primary\"> <input type=\"checkbox\" name=\"delete\" value=\"yes\" onclick=\"return confirm('You are about to delete your Dive Log. Click OK to continue.')\"> Delete Log</td></tr>
        </table>
        </form>
        ";

        // end content
        print "</span>";
        $this->header_bot();
      }

      public function delete_divelog() {
        if ($_POST['delete'] == "yes") {
          $sql = "DELETE FROM `dive_log` WHERE `id` = '$_POST[id]' AND `contactID` = '$_SESSION[contactID]'";
        } else {

	        // upload image
        	$fileName = $_FILES['dive_image']['name'];
	        $tmpName  = $_FILES['dive_image']['tmp_name'];
        	$fileSize = $_FILES['dive_image']['size'];
	        $fileType = $_FILES['dive_image']['type'];
        	if ($fileName != "") {
	                $ext = $this->file_types($fileType);
        	        if ($ext == "1") {
                	        print "Supported file types are<br>GIF, PNG, JPG<br>";
	                } else {
	                        $today = date("Ymd");
        	                $new_file = date("U");
                	        $new_file .= rand(50,500);
                        	$new_file .= $ext;
	                        move_uploaded_file("$tmpName", ".divelogimages/$new_file");
        	                chmod(".divelogimages/$new_file", 0644);
				$sql_dive_img = ",`dive_image` = '$new_file'";
	                }
	        }


          $sql = "UPDATE `dive_log` SET `dive_date` = '$_POST[dive_date]', `site` = '$_POST[site]', `dive_buddies` = '$_POST[dive_buddies]', `max_depth` = '$_POST[max_depth]', 
          `description` = '$_POST[description]', `rating` = '$_POST[rating]', `bottom_time_hours` = '$_POST[bottom_time_hours]', `bottom_time_mins` = '$_POST[bottom_time_mins]',
	  `water_temp` = '$_POST[water_temp]', `air_temp` = '$_POST[air_temp]' $sql_dive_img

	  WHERE `id` = '$_POST[id]' AND `contactID` = '$_SESSION[contactID]'
          ";
        }

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

        $result = $this->new_mysql($sql);
        if ($result == "TRUE") {
          print "<br><br>Your dive log was updated. Loading...<br>";
	  ?>
		<script>
		setTimeout(function() { document.location.href='portal.php'},2000);
		</script>
	  <?php

        } else {
          print "<br><br><font color=red>There was an error updating your dive log.</font><br><br>";
        }

        print "</span>";
        $this->header_bot();

      }

	public function save_wishlist() {
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

	$today = date("Ymd");
        $sql = "INSERT INTO `wish_list` (`contactID`,`boatID`,`itinerary`,`date`) VALUES
        ('$_SESSION[contactID]','$_POST[boatID]','$_POST[destination]','$today')
        ";
        $result = $this->new_mysql($sql);
        if ($result == "TRUE") {
          print "<br><br>Your wish list was added. Loading...<br><br>";
                ?>
                <script>
                setTimeout(function() { document.location.href='portal.php'},2000);
                </script>
                <?php
        } else {
          print "<br><br><font color=red>There was an error saving your wish list.</font><br><br>";
        }

        print "</span>";
        $this->header_bot();
      }


      public function save_divelog() {

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


	// upload image
        $fileName = $_FILES['dive_image']['name'];
        $tmpName  = $_FILES['dive_image']['tmp_name'];
        $fileSize = $_FILES['dive_image']['size'];
        $fileType = $_FILES['dive_image']['type'];
        if ($fileName != "") {
        	$ext = $this->file_types($fileType);
		if ($ext == "1") {
			print "Supported file types are<br>GIF, PNG, JPG<br>";
                } else {
			$today = date("Ymd");
	            	$new_file = date("U");
        	        $new_file .= rand(50,500);
	                $new_file .= $ext;
        	        move_uploaded_file("$tmpName", ".divelogimages/$new_file");
	                chmod(".divelogimages/$new_file", 0644);
                }
	}

	$description = $this->linkID->real_escape_string($_POST['description']);
        $dive_buddies = $this->linkID->real_escape_string($_POST['dive_buddies']);


        $sql = "INSERT INTO `dive_log` (`contactID`,`dive_date`,`site`,`dive_buddies`,`max_depth`,`description`,`rating`,`boatID`,`itinerary`,`bottom_time_hours`,`bottom_time_mins`,`water_temp`,`air_temp`,`dive_image`) VALUES
        ('$_SESSION[contactID]','$_POST[dive_date]','$_POST[site]','$dive_buddies','$_POST[max_depth]','$description','$_POST[rating]','$_POST[boatID]','$_POST[itinerary]','$_POST[bottom_time_hours]','$_POST[bottom_time_mins]',
	'$_POST[water_temp]','$_POST[air_temp]','$new_file')
        ";
        $result = $this->new_mysql($sql);
        if ($result == "TRUE") {
          print "<br><br>Your dive log was added. Loading...<br><br>";
		?>
                <script>
                setTimeout(function() { document.location.href='portal.php'},2000);
                </script>
		<?php
        } else {
          print "<br><br><font color=red>There was an error saving your dive log.</font><br><br>";
        }

        print "</span>";
        $this->header_bot();
      }

      public function view_alldivelog() {
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
        print "<h2>Dive Log</h2>";
        $sql = "SELECT `id`,DATE_FORMAT(`dive_date`,'%m/%d/%Y') AS 'dive_date', `site` FROM `dive_log` WHERE `contactID` = '$_SESSION[contactID]' ORDER BY `dive_date` DESC";
        $result = $this->new_mysql($sql);
        while ($row = $result->fetch_assoc()) {
          print "<a href=\"adddivelog.php?section=edit&id=$row[id]\"><i class=\"fa fa-file-text-o\" aria-hidden=\"true\"></i> $row[dive_date] - $row[site]</a><br>";
        }


        print "</span>";
        $this->header_bot();
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
                        print ' 
			<br>
                        <table width="817" border="0" cellspacing="0" cellpadding="0">
                         <tbody>
                           <tr>
                             <td valign="top"><table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="217">
                               <tr>
                                 <td height="47"><img name="ProfilePageMenu01" src="buttons/bt-updateprofile.png" width="185" height="32" id="ProfilePageMenu01" alt="" onclick="document.location.href=\'profile.php\';" /></td>
                               </tr>
                               <tr>
                                 <td height="47"><img name="ProfilePageMenu02" src="buttons/bt-myreservations.png" width="185" height="32" id="ProfilePageMenu02" alt="" onclick="document.location.href=\'myreservations.php\';" /></td>
                               </tr>
                        ';

                        if ($_SESSION['contact_type'] != "reseller_third_party") {
                                print '
                                <tr>
                                <td height="47"><img name="ProfilePageMenu03" src="buttons/bt-allreservations.png" width="185" height="32" id="ProfilePageMenu03" alt="" onclick="document.location.href=\'agentreservations.php\';" /></td>
                                </tr>
                                ';
                        }
                        if ($_SESSION['contact_type'] == "reseller_manager") {
                                print '
                               <tr>
                                 <td height="47"><img name="ProfilePageMenu04" src="buttons/bt-manage-agents.png" width="185" height="32" id="ProfilePageMenu04" alt="" onclick="document.location.href=\'agents.php\';" /></td>
                               </tr>
                                ';
                        }
                        print '
                        
                               <tr>
                                 <td height="47">&nbsp;</td>
                               </tr>
                               <tr>
                                 <td height="47">&nbsp;</td>
                               </tr>
                             </table></td>
                                ';

                        if ($_SESSION['contact_type'] == "reseller_third_party") {
                                $extra_msg = " authorized by $_SESSION[company]";
                        }
                        print '
                             <td valign="top"><strong><br>Welcome '.$_SESSION['first'].' '.$_SESSION['last'].$extra_msg.' to the Reseller Reservation System.</strong> We have provided the following 
                                   Agent Resources to assist you with marketing Aggressor Fleet.
                        ';
                        $sql_r = "SELECT `msg` FROM `reserve`.`reseller_message` WHERE `id` = '1'";
                        $result_r = $this->new_mysql($sql_r);
                        while ($row_r = $result_r->fetch_assoc()) {
                                print "$row_r[msg]";
                        }
                        print '
                          </td>
                           </tr>
                         </tbody>
                        </table>
                        ';
                }



      public function show_reseller_menu() {
               $this->reseller_portal_view();
      }

      public function my_profile() {
            $this->dive_map('950','275');
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
					print "<input type=\"button\" name=\"new_agent\" value=\"Add New Agent\" class=\"btn btn-primary\" onclick=\"document.location.href='add_agent.php'\">&nbsp;
					<input type=\"button\" name=\"company_info\" value=\"Your Company Info\" class=\"btn btn-primary\" onclick=\"document.location.href='company_info.php'\">&nbsp;
					<input type=\"button\" name=\"help\" onclick=\"window.open('reseller_3rd_agents_help.html','_blank','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=800,height=600,left = 600,top = 250')\" 
                                        value=\"How do I add a 3rd Party Reseller?\" class=\"btn btn-primary\">
					<br><hr>";
					$_SESSION['resellerID'] = $row['resellerID'];

					// list agents
					$sql2 = "
					SELECT
						`reseller_agents`.*,
						`c`.`company`,
						`c`.`contact_type`

					FROM
						`reseller_agents`

					LEFT JOIN `contacts` c ON `reseller_agents`.`reseller_agentID` = `c`.`reseller_agentID`

					WHERE
						`reseller_agents`.`resellerID` = '$row[resellerID]'
						
					ORDER BY `reseller_agents`.`status` ASC, `reseller_agents`.`last` ASC, `reseller_agents`.`first` ASC
					";
					print "<table border=0 width=90%>
					<tr>
						<td><b>Name</b></td>
						<td><b>Company</b></td>
						<td><b>Reseller Type</b></td>
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
                  switch ($row2['contact_type']) {
                     case "reseller_manager":
                     $agent = "Manager";
                     break;

                     case "reseller_agent":
                     $agent = "Agent";
                     break;
                  
                     case "reseller_third_party":
                     $agent = "3rd Party";
                     break;

                     default:
                     $agent = "<font color=red>Missing!</font>";
                     break;
                  }
						print "<tr bgcolor=\"$bgcolor\"><td>$row2[first] $row2[last]</td><td>$row2[company]</td><td>$agent</td><td>$row2[email]</td><td>$row2[status]</td><td><a href=\"agent_edit.php?id=$row2[reseller_agentID]\">Edit</a></td></tr>";
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

         if ($_SESSION['contact_type'] == "reseller_third_party") {
            $s1 = "`reservations`.`reseller_agentID` = '$_SESSION[reseller_agentID]'";
         } else {
            $s1 = "`reservations`.`reservation_contactID` = '$_SESSION[contactID]'";
         }

			$sql = "
			SELECT
				`reservations`.`reservationID`,
				`boats`.`name`,
				DATE_FORMAT(`charters`.`start_date`,'%b %e, %Y') AS 'start_date',
				`charters`.`nights`

			FROM
				`reservations`,`charters`,`boats`

			WHERE
				$s1
				AND `reservations`.`charterID` = `charters`.`charterID`
				AND `charters`.`boatID` = `boats`.`boatID`
				AND `reservations`.`show_as_suspended` = '0'

			ORDER BY `charters`.`start_date` DESC
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

            switch ($_SESSION['contact_type']) {
               case "reseller_manager":
               case "reseller_agent":
               //$invoice = "<a href=\"invoicer.php?r=$row[reservationID]\" target=_blank>Reseller Invoice</a>&nbsp;|&nbsp;<a href=\"invoice.php?r=$row[reservationID]\" target=_blank>Client Invoice</a>";
               $invoice = " | <a href=\"invoice.php?r=$row[reservationID]\" target=_blank>Aggressor Invoice</a> | <a href=\"generate_invoice.php?r=$row[reservationID]&rid=$_SESSION[resellerID]\" target=_blank>Generate Invoice</a>";
               break;

               case "reseller_third_party":
               $invoice = " | <a href=\"generate_invoice.php?r=$row[reservationID]&rid=$_SESSION[resellerID]\" target=_blank>Generate Invoice</a>";
               break;

            }

				print "<tr><td>$row[reservationID]</td><td>$row[name]</td><td>$row[start_date]</td><td>$row[nights]</td><td><a href=\"guests.php?res=$row[reservationID]&c=$_SESSION[contactID]\">Assign Guests</a> | 
				<a href=\"gis.php?res=$row[reservationID]\">GIS</a> $invoice</td></tr>";
				$found = "1";
			}
			if ($found != "1") {
					print "<tr><td colspan=5><br><br><center>Sorry, you do not have any reservations to display.</center><br><br></td></tr>";
			}

			print "</table>";


         print "</span>";
         $this->header_bot();

		}

    public function file_types($type) {
      $ext = "1";
      $sql = "SELECT * FROM `file_types` WHERE `meta` = '$type'";
         $result = $this->new_mysql($sql);
                while ($row = $result->fetch_assoc()) {
                        $ext = $row['ext'];
                }
                return $ext;
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

	      // upload image
	      $fileName = $_FILES['avatar']['name'];
   	   $tmpName  = $_FILES['avatar']['tmp_name'];
	      $fileSize = $_FILES['avatar']['size'];
   	   $fileType = $_FILES['avatar']['type'];
      	if ($fileName != "") {
	        $ext = $this->file_types($fileType);
   	     if ($ext == "1") {
      	    print "Supported file types are<br>GIF, PNG, JPG<br>";
	        } else {
   	       $today = date("Ymd");
      	    $new_file = date("U");
         	 $new_file .= rand(50,500);
	          $new_file .= $ext;
   	       move_uploaded_file("$tmpName", "avatar/$new_file");
      	    chmod("avatar/$new_file", 0644);
	          $avatar_sql = " ,`avatar` = '$new_file' ";
   	     }
	      }

         // upload image
         $fileName2 = $_FILES['logo']['name'];
         $tmpName2  = $_FILES['logo']['tmp_name'];
         $fileSize2 = $_FILES['logo']['size'];
         $fileType2 = $_FILES['logo']['type'];
         if ($fileName2 != "") {
           $ext2 = $this->file_types($fileType2);
           if ($ext2 == "1") {
             print "Supported file types are<br>GIF, PNG, JPG<br>";
           } else {
             $today2 = date("Ymd");
             $new_file2 = date("U");
             $new_file2 .= rand(50,500);
             $new_file2 .= $ext2;
             move_uploaded_file("$tmpName2", "logo/$new_file2");
             chmod("logo/$new_file2", 0644);
             $logo_sql = " ,`logo` = '$new_file2' ";
           }
         }

			 $sql = "UPDATE `contacts` SET `address1` = '$_POST[address1]', `address2` = '$_POST[address2]', `city` = '$_POST[city]', `state` = '$_POST[state]', `province` = '$_POST[province]', `countryID` = '$_POST[countryID]',`zip` = '$_POST[zip]',
			`phone1` = '$_POST[phone1]', `phone2` = '$_POST[phone2]', `phone3` = '$_POST[phone3]', `phone4` = '$_POST[phone4]', `uupass` = '$_POST[uupass]' $avatar_sql $logo_sql, `total_dives` = '$_POST[total_dives]'

      WHERE `contactID` = '$_SESSION[contactID]'";
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
				<form name=\"myform\" action=\"profile.php\" method=\"post\" enctype=\"multipart/form-data\">
				<input type=\"hidden\" name=\"section\" value=\"update\">
				<table border=0 width=90% class=\"table\">
				<tr><td>Name:</td><td>$row[first] $row[middle] $row[last]</td></tr>
        <tr><td>Profile Picture:</td><td><input type=\"file\" name=\"avatar\">
        ";

        if ($row['avatar'] != "") {
          print "<img src=\"avatar/$row[avatar]\" height=\"64\">";
        }

			// merged
            if ($row['contact_type'] == "reseller_third_party") {
               print "<tr><td>Company:</td><td>$row[company]</td></tr>";
               print "<tr><td>Logo (300x125)</td><td><input type=\"file\" name=\"logo\"></td></tr>";
               if ($row['logo'] != "") {
                  print "<tr><td colspan=2><img src=\"logo/$row[logo]\" width=\"300\" height=\"125\"></td></tr>";
               }
            }

			// end merge

        print "
        </td></tr>
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

        print "<tr><td>Total Number of Dives:</td><td><input type=\"text\" name=\"total_dives\" value=\"$row[total_dives]\" size=40></td></tr>";
				print "<tr><td>Account Type:</td><td>$type</td></tr>";
				print "<tr><td>&nbsp;</td><td><br><input type=\"submit\" class=\"btn btn-success\" value=\"Update\"></td></tr>";
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
					INSERT INTO `contacts` (`first`,`last`,`address1`,`address2`,`city`,`state`,`province`,`zip`,`countryID`,`phone1`,`phone1_type`,`phone2`,`phone2_type`,`phone3`,`phone3_type`,`email`,`sex`,`date_of_birth`,`reseller_agentID`,`uuname`,`uupass`,`contact_type`,`company`,`commission`,`commission2`)
					VALUES
					(
					'$_POST[first]','$_POST[last]','$_POST[address1]','$_POST[address2]','$_POST[city]','$_POST[state]','$_POST[province]','$_POST[zip]','$_POST[country]','$_POST[phone1]','Home','$_POST[phone2]','Work','$_POST[phone3]','Mobile','$_POST[email]',
					'$_POST[sex]','$dob','$reseller_agentID','$_POST[uuname]','$_POST[uupass]','$_POST[contact_type]','$_POST[company]','$_POST[commission]','$_POST[commission2]'
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

         if ($_SESSION['contact_type'] == "reseller_third_party") {
            print "<br><font color=red>You have requested an un-authorized section!</font><br>";
            die;
         }


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
		    `reseller_agents`.`last`,
	         `c`.`contact_type`,
				`c`.`company`

         FROM
            `reservations`,`charters`,`boats`,`reseller_agents`

         LEFT JOIN `contacts` c ON `reseller_agents`.`reseller_agentID` = `c`.`reseller_agentID`

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
            <td><b>Type</b></td>
				<td><b>Company</b></td>
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
               $invoice = "<a href=\"invoice.php?r=$row[reservationID]\" target=_blank>AF Invoice</a> | <a href=\"generate_invoice.php?r=$row[reservationID]&rid=$_SESSION[resellerID]\" target=_blank>Generate Invoice</a>";
					break;

					default:
               $invoice = "<a href=\"generate_invoice.php?r=$row[reservationID]&rid=$_SESSION[resellerID]\" target=_blank>Generate Invoice</a>";
					break;

				}

            $type = "N/A";
            switch ($row['contact_type']) {
               case "reseller_manager":
               $type = "Manager";
               break;

               case "reseller_agent":
               $type = "Agent";
               break;

               case "reseller_third_party":
               $type = "3rd Party";
               break;
            }

            print "<tr bgcolor=$bgcolor>
					<td><a href=\"guests.php?res=$row[reservationID]&c=$_SESSION[contactID]\">$row[reservationID]</a></td>
					<td>$row[first] $row[last]</td>
					<td>$type</td>
					<td>$row[company]</td>
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

         print "<br><span class=\"result-title-text\">Your Company Information to Appear On 3rd Party Invoice</span><br><br>
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

         $sql2 = "
         SELECT
            `c`.`contactID`,
            `c`.`first`,
            `c`.`last`

         FROM
            `resellers` r,
            `reseller_agents` ra,
            `contacts` c

         WHERE
            `r`.`resellerID` = '$_SESSION[resellerID]'
            AND `r`.`resellerID` = `ra`.`resellerID`
            AND `ra`.`reseller_agentID` = `c`.`reseller_agentID`
            AND `c`.`contact_type` = 'reseller_manager'
         ";
         if ($row['primary_contactID'] == "") {
            $primary_contactID = "<option value=\"\">-- Select Contact --</option>";
         }  
         $result2 = $this->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
            if ($row['primary_contactID'] == $row2['contactID']) {
               $primary_contactID .= "<option selected value=\"$row2[contactID]\">$row2[first] $row2[last]</option>";
            } else {
               $primary_contactID .= "<option value=\"$row2[contactID]\">$row2[first] $row2[last]</option>";
            }  
         }  


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
         <tr><td>Primary Contact:</td><td><select name=\"primary_contactID\" required>$primary_contactID</select></td></tr>
			<tr><td>Email:</td><td><input type=\"text\" name=\"email\" value=\"$row[email]\" size=40></td></tr>
			<tr><td colspan=2>Upload Company Logo Size: 750 px wide by 125 px high in .jpg, .gif, or .png.</td></tr>
			<tr><td valign=top>Logo:</td><td><input type=\"file\" name=\"logo\">";
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
						$logo1 = ",`logo` = '$fileName'";
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
				`zip` = '$_POST[zip]', `phone` = '$_POST[phone]', `email` = '$_POST[email]', `default_commission` = '$_POST[default_commission]', `primary_contactID` = '$_POST[primary_contactID]' $logo1 WHERE `resellerID` = '$_SESSION[resellerID]'";

			} else {
				// insert
				$sql = "INSERT INTO `reseller_3rd_party` (`resellerID`,`address1`,`address2`,`city`,`state`,`country`,`zip`,`phone`,`email`,`default_commission`,`primary_contactID`$logo2a) VALUES
				('$_SESSION[resellerID]','$_POST[address1]','$_POST[address2]','$_POST[city]','$_POST[state]','$_POST[country]','$_POST[zip]','$_POST[phone]','$_POST[email]','$_POST[default_commission]','$_POST[primary_contactID]'$logo2b)";
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
                $t1 = "<font color=red>*</font>";

            print "<form action=\"agent_save.php\" name=\"myform\" method=\"post\">
            <input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">
            <input type=\"hidden\" name=\"section\" value=\"update\">
                                <input type=\"hidden\" name=\"rr\" value=\"1\">
            <table border=\"0\" width=90%>
            <tr><td width=\"200\">First Name:</td><td><input type=\"text\" name=\"first\" value=\"$row[first]\" size=40> $t1</td></tr>
            <tr><td>Last Name:</td><td><input type=\"text\" name=\"last\" value=\"$row[last]\" size=40> $t1</td></tr>
            <tr><td>Address 1:</td><td><input type=\"text\" name=\"address1\" value=\"$row[address1]\" size=40> $t1</td></tr>
            <tr><td>Address 2:</td><td><input type=\"text\" name=\"address2\" value=\"$row[address2]\" size=40></td></tr>
            <tr><td>City:</td><td><input type=\"text\" name=\"city\" value=\"$row[city]\" size=40> $t1</td></tr>
            <tr><td>State: Only for the US</td><td><input type=\"text\" name=\"state\" value=\"$row[state]\" size=40></td></tr>
                                <tr><td>Province:</td><td><input type=\"text\" name=\"province\" size=40></td></tr>
            <tr><td>Zip:</td><td><input type=\"text\" name=\"zip\" value=\"$row[zip]\" size=40></td></tr>
            <tr><td>Country:</td><td><select name=\"country\" required><option selected value=\"\">Select Country</option>$countries</select> $t1</td></tr>
                                <tr><td>Company:</td><td><input type=\"text\" name=\"company\" size=\"40\"></td></tr>
            <tr><td valign=\"top\">Groups Commission:<br>3rd party only</td><td align=\"top\"><input type=\"text\" name=\"commission\" value=\"0\" size=\"40\">%</td></tr>
            <tr><td valign=top>Individual Commission:<br>3rd party only</td><td valign=top><input type=\"text\" name=\"commission2\" value=\"0\" size=40>%</td></tr>

                                <tr><td>Gender:</td><td><input type=\"radio\" name=\"sex\" value=\"male\" checked> Male <input type=\"radio\" name=\"sex\" value=\"female\"> Female $t1</td></tr>
                                <tr><td>Birth Month:</td><td><select name=\"birth_month\">$months</select></td></tr>
                                <tr><td>Birth Day:</td><td><select name=\"birth_day\">$days</select></td></tr>
                                <tr><td>Birth Year:</td><td><select name=\"birth_year\">$years</select> $t1</td></tr>
            ";
            print "<tr><td>Work Phone:</td><td><input type=\"text\" name=\"phone2\" value=\"$row[phone2]\" required size=40> $t1</td></tr>";
            print "<tr><td>Cell Phone:</td><td><input type=\"text\" name=\"phone3\" value=\"$row[phone3]\" required size=40> $t1</td></tr>";
            print "<tr><td>Email:</td><td><input type=\"text\" name=\"email\" value=\"$row[email]\" required size=40> $t1</td></tr>";
                                print "<tr><td>Username:</td><td><input type=\"text\" name=\"uuname\" value=\"$row2[uuname]\" onblur=\"check_uuname(this.form)\" size=40 required>&nbsp;<div id=\"info1\" style=\"display:inline\"></div> $t1</td></tr>";
            print "
            <tr><td>Password:</td><td><input type=\"text\" name=\"uupass\" value=\"$row2[uupass]\" required size=40> $t1</td></tr>
            <tr><td>Reseller Type:</td><td><select name=\"contact_type\">";
            print "<option value=\"reseller_agent\">Reseller Agent</option>
            <option value=\"reseller_third_party\">Reseller Third Party</option>
            <option value=\"reseller_manager\">Reseller Manager</option></select></td></tr>";

            print "<tr><td colspan=2><input type=\"submit\" value=\"Continue\" id=\"Continue\" disabled> (Please complete all fields <font color=red>*</font>)</td></tr>";
            print "</table><br><br>
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
				`ra`.*,
				`c`.`company`,
				`c`.`contactID`,
				`c`.`commission`

			FROM
				`reseller_agents` ra, `contacts` c

			LEFT JOIN countries ON `ra`.`countryID` = `countries`.`countryID`

			WHERE
				`ra`.`reseller_agentID` = '$_GET[id]'
				AND `ra`.`resellerID` = '$_SESSION[resellerID]'
            AND `ra`.`reseller_agentID` = `c`.`reseller_agentID`
			";

			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$found = "1";

            $countries = $this->country_list($row['countryID']);

				print "<form action=\"agent_edit.php\" method=\"post\">
				<input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">
				<input type=\"hidden\" name=\"section\" value=\"update\">
            <input type=\"hidden\" name=\"contactID\" value=\"$row[contactID]\">
				<table border=\"0\" width=90%>
                                <tr><td width=\"200\">Status:</td><td><select name=\"status\"><option selected value=\"$row[status]\">$row[status]<option>Active</option><option>Inactive</option></select></td></tr>
				<tr><td width=\"200\">First Name:</td><td><input type=\"text\" name=\"first\" value=\"$row[first]\" size=40></td></tr>
				<tr><td>Last Name:</td><td><input type=\"text\" name=\"last\" value=\"$row[last]\" size=40></td></tr>
				<tr><td>Address 1:</td><td><input type=\"text\" name=\"address1\" value=\"$row[address1]\" size=40></td></tr>
				<tr><td>Address 2:</td><td><input type=\"text\" name=\"address2\" value=\"$row[address2]\" size=40></td></tr>
				<tr><td>City:</td><td><input type=\"text\" name=\"city\" value=\"$row[city]\" size=40></td></tr>
				<tr><td>State:</td><td><input type=\"text\" name=\"state\" value=\"$row[state]\" size=40></td></tr>
				<tr><td>Zip:</td><td><input type=\"text\" name=\"zip\" value=\"$row[zip]\" size=40></td></tr>
				<tr><td>Country:</td><td><select name=\"country\">$countries</select></td></tr>
            <tr><td>Company:</td><td><input type=\"text\" name=\"company\" size=40 value=\"$row[company]\"></td></tr>
				";
            print "<tr><td valign=top>Groups Commission:<br>3rd party only</td><td valign=top><input type=\"text\" name=\"commission\" value=\"$row[commission]\" size=40>%</td></tr>";
            print "<tr><td valign=top>Individual Commission:<br>3rd party only</td><td valign=top><input type=\"text\" name=\"commission2\" value=\"$row[commission2]\" size=40>%</td></tr>";

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
                                                if ($row2['contact_type'] == "reseller_agent") {
                                                        print "<option selecte value=\"$row2[contact_type]\">Reseller Agent (Default)</option>";
                                                }
                                                if ($row2['contact_type'] == "reseller_manager") {
                                                        print "<option selecte value=\"$row2[contact_type]\">Reseller Manager (Default)</option>";
                                                }
                                                if ($row2['contact_type'] == "reseller_third_party") {
                                                        print "<option selecte value=\"$row2[contact_type]\">Reseller Third Party (Default)</option>";
                                                }
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

				print "<tr><td colspan=2><input type=\"submit\" value=\"Continue\" class=\"btn btn-primary\"></td></tr>";
				print "</table>
				</form>";
			}
         if ($found != "1") {
            print "<br><br><font color=red>There was an error with the agent record. The reseller agent is not linked to a contact record. Please contact your reseller manager to correct the error.</font><br><br>";
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
	                `status` = '$_POST[status]',
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

            if ($_POST['contactID'] != "") {
               $sql2 = "UPDATE `contacts` SET `company` = '$_POST[company]', `commission` = '$_POST[commission]', `commission2` = '$_POST[commission2]' WHERE `contactID` = '$_POST[contactID]'";
               $result2 = $this->new_mysql($sql2);
            }

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

        // update RSS pw
         if (($_POST['contactID'] != "") && ($_POST['uuname'] == "") && ($_POST['uupass'] != "")) {

            $sql = "SELECT `contacts`.`contactID` FROM `contacts` WHERE `contacts`.`reseller_agentID` = '$_POST[id]' LIMIT 1";
            $result = $this->new_mysql($sql);
            while ($row = $result->fetch_assoc()) {
               $sql2 = "UPDATE `contacts` SET `uupass` = '$_POST[uupass]',`contact_type` = '$_POST[contact_type]' WHERE `contactID` = '$row[contactID]'";
               $result2 = $this->new_mysql($sql2);

                if ($_POST['contactID'] == $_SESSION['contactID']) {
                        print "<br><br><font color=green>You updated your own password. You have been logged out. Please log back in.</font><br><br>";
                        session_destroy();
                }

            }
         }



         $this->header_bot();

		}
}
?>
