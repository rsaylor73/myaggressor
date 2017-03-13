<?php
include "settings.php";

$err = "1";

if (($_GET['uuname'] != "") && ($_GET['uupass'] != "")) {
	$sql = "SELECT `first`,`last`,`uuname`,`uupass`,`contactID`,`email`,`sex`, `contact_type`,`reseller_agentID`,`donotbook` FROM `contacts` WHERE `uuname` = '$_GET[uuname]' AND `uupass` = '$_GET[uupass]'";

	$result = $reservation->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
                $donotbook = $row['donotbook'];
                if ($donotbook == "Y") {
                        print "
                        <table border=\"0\" width=\"500\" cellpadding=\"3\" class=\"details-description\">
                        <tr><td><font color=red>
                        <b>Your account has been disabled. <br>Please contact a reservation agent directly at 1-800-348-2628</b></font>
                        </td></tr>
                        </table>
                        ";
                        die;
                }

		// log in log
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$sql2 = "INSERT INTO `crs_rrs_login_log` (`date`,`time`,`contactID`,`ip_address`) VALUES ('$date','$time','$row[contactID]','$ip_address')";
		$result2 = $reservation->new_mysql($sql2);
		// end login log

		$err = "0";
		$_SESSION['uuname'] = $row['uuname'];
		$_SESSION['uupass'] = $row['uupass'];
		$_SESSION['contactID'] = $row['contactID'];
		$_SESSION['first'] = $row['first'];
		$_SESSION['last'] = $row['last'];
		$_SESSION['email'] = $row['email'];
		$_SESSION['sex'] = $row['sex'];
		$_SESSION['contact_type'] = $row['contact_type'];
		// look up reseller if reseller

		if ($row['reseller_agentID'] != "") {
			$sql2 = "SELECT `resellerID`,`reseller_agentID`,`status` FROM `reseller_agents` WHERE `reseller_agentID` = '$row[reseller_agentID]'";
			$result2 = $reservation->new_mysql($sql2);
			while ($row2 = $result2->fetch_assoc()) {
				$_SESSION['resellerID'] = $row2['resellerID'];
				$_SESSION['reseller_agentID'] = $row2['reseller_agentID'];
				if ($row2['status'] == "Inactive") {
					$err2 = "1";
					$err = "1";
				}
			}
		}

		// look up reseller company
      if ($row['reseller_agentID'] != "") {
			$sql2 = "SELECT `company` FROM `resellers` WHERE `resellerID` = '$_SESSION[resellerID]'";
         $result2 = $reservation->new_mysql($sql2);
         while ($row2 = $result2->fetch_assoc()) {
				$_SESSION['company'] = $row2['company'];
			}
		}

		if ($err2 == "") {
			print $_SESSION['uri'];
		} else {
			print "<br><center><font color=red>You do not have access.</font></center><br>";
			die;
		}
	}
}

// try reseller DB
if ($err == "1") {
	$sql = "SELECT * FROM `reseller_users` WHERE `uuname` = '$_GET[uuname]' AND `uupass` = '$_GET[uupass]'";
	$result = $reservation->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		// convert user
		$err = 0;

		// check if username is avail
		$sql2 = "SELECT `uuname` FROM `contacts` WHERE `uuname` = '$_GET[uuname]'";
		$result2 = $reservation->new_mysql($sql2);
		while ($row2 = $result2->fetch_assoc()) {
			print "<br>Sorry, but your reseller username from the old reseller system is already in use with another user. Please contact your reseller manager and request a new account or email Aggressor Fleet for assistance.<br><br>\n";
			die;
		}

		// convert the user
		switch ($row['account_type']) {
			case "Manager":
			$type = "reseller_manager";
			break;

			case "User":
			$type = "reseller_agent";
			break;
		}

		$sql2 = "UPDATE `contacts` SET `uuname` = '$row[uuname]', `uupass` = '$row[uupass]',`contact_type` = '$type' WHERE `contactID` = '$row[contact_id]' AND `reseller_agentID` = '$row[reseller_agentid]'";
		$result2 = $reservation->new_mysql($sql2);

		print "<br>Your profile was converted. Please click <a href=\"portal.php\">here</a> to log back in.<br>";
		die;

	}
}

if ($err2 == "1") {
	$err = "1";
}

if ($err == "1") {
	$varU = $_GET['varU'];
	print "
		<center><span class=\"details-description\">

					<form name=\"myform\" id=\"myform\">
               <table border=0 width=\"700\" cellpadding=0 cellspacing=3>
                  <tr>
                     <td valign=top width=\"250\"><img src=\"30Year-Blue-300px.png\" width=\"250\"></td>
                     <td valign=top width=\"450\">
                        <table border=\"0\" width=\"500\" class=\"details-description\">
									<input type=\"hidden\" name=\"varU\" value=\"$_GET[varU]\">
									<tr><td colspan=2><font color=red>Sorry, the username or password was incorrect.</font></td></tr>
                           <tr><td>Username:</td><td><input type=\"text\" name=\"uuname\" size=40></td></tr>
                           <tr><td>Password:</td><td><input type=\"password\" name=\"uupass\" size=40></td></tr>
                           <tr><td>&nbsp;</td><td><input type=\"image\" src=\"buttons/bt-login.png\" onclick=\"login(this.form);return false;\">&nbsp;&nbsp;<input type=\"image\" src=\"buttons/bt-register.png\" onclick=\"location.href='register.php?$varU';return false;\"></td></tr>
                           <tr><td>&nbsp;</td><td><a href=\"javascript:void(0)\" onclick=\"forgot_password(this.form)\">Forgot Password</a></td></tr>
                        </table>
                     </td>
                  </tr>
               </table>
					</form>
			</center></span>
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
	</script>

	<?php

}
?>
