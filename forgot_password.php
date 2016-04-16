<?php
session_start();
require "settings.php";


	if ($_GET['action'] == "") {
		$s1 = rand(1,10);
		$s2 = rand(1,10);
		$_SESSION['s1'] = $s1;
		$_SESSION['s2'] = $s2;

		print "
               <div id=\"forgot-pw\" align=\"center\">
               <form name=\"myform\" id=\"myform\">
					<input type=\"hidden\" name=\"action\" value=\"send_pw\">
               <table border=0 width=\"800\" cellpadding=0 cellspacing=3>
                  <tr>
                     <td valign=top width=\"300\"><img src=\"30Year-Blue-300px.png\" width=\"250\"></td>
                     <td valign=top width=\"450\">
                        <table border=\"0\" width=\"500\" cellpadding=\"5\" class=\"details-description\">
						
									<tr><td colspan=2><b>Please type in your user name and answer the simple math problem and we will email you your login details to the email address on file.</b></td></tr>
                           <tr><td width=200>User Name:</td><td><input type=\"text\" name=\"uuname\" size=40></td></tr>
                           <tr><td>What is $s1 plus $s2?</td><td><input type=\"text\" name=\"answer\" size=40></td></tr>
                           <tr><td>&nbsp;</td><td><input type=\"image\" src=\"buttons/bt-send-password.png\" onclick=\"send_pw(this.form);return false;\"></td></tr>
                        </table>
                     </td>
                  </tr>
               </table>
               </form>
               </div>
		";
		?>
                                <script>
                                 function send_pw(myform) {
                                        $.get('forgot_password.php',
                                        $(myform).serialize(),

                                        function(php_msg) {
                                          if (php_msg.substring(0,5) == "https") {
                                             $("#login-scr").html('<span class="details-description"><br><font color=green>Please check your email for your login details. Loading please wait...</font><br></span>');
                                             setTimeout(function()
                                                {
                                                window.location.replace(php_msg)
                                                }
                                             ,4000);
                                          } else {
                                             $("#forgot-pw").html(php_msg);
                                          }
                                        });
                                 }
											</script>
		<?php
	}

	if ($_GET['action'] == "send_pw") {
		$answer = $_SESSION['s1'] + $_SESSION['s2'];

		if ($_GET['answer'] != $answer) {
			$err = "1";
		} else {

			$sql = "
			SELECT
				`contacts`.`first`,
				`contacts`.`last`,
				`contacts`.`email`,
				`contacts`.`uuname`,
				`contacts`.`uupass`

			FROM
				`contacts`

			WHERE
				`contacts`.`uuname` = '$_GET[uuname]'

			";
			$result = $reservation->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				if (($row['uuname'] != '') && ($row['uupass'] != '')) {
					$subj = "Forgot password for Aggressor/Dancer Fleet";
					$msg = "Dear $row[first] $row[last]:<br><br>
					A \"forgot password\" request has been received by the Aggressor Fleet / Dancer Fleet consumer reservation system. The Username entered corresponds to your email address.
					<br><br>
					The login for this user ID is:
					<br><br>
					Username: $row[uuname]<br>
					Password: $row[uupass]<br><br>
					To access the <b>Online Reservation System</b> please visit www.aggressor.com<br><br>";
					mail($row['email'],$subj,$msg,$headers);
					$found = "1";
					print "$_SESSION[uri]";
				}
			}
			if ($found != "1") {
				$err = "1";
			}
		}
		if ($err == "1") {
      $s1 = rand(1,10);
      $s2 = rand(1,10);
      $_SESSION['s1'] = $s1;
      $_SESSION['s2'] = $s2;

      print "
               <div id=\"forgot-pw\" align=\"center\">
               <form name=\"myform\" id=\"myform\">
               <input type=\"hidden\" name=\"action\" value=\"send_pw\">
               <table border=0 width=\"800\" cellpadding=0 cellspacing=3>
                  <tr>
                     <td valign=top width=\"300\"><img src=\"30Year-Blue-300px.png\" width=\"250\"></td>
                     <td valign=top width=\"450\">
                        <table border=\"0\" width=\"500\" cellpadding=\"5\" class=\"details-description\">
                  
                           <tr><td colspan=2><b>Please type in your user name and answer the simple math problem and we will email you your login details to the email address on file.</b></td></tr>
									<tr><td colspan=2><font color=red>Either the username was not valid or you did not solve the math problem correctly.</font></td></tr>
                           <tr><td width=200>User Name:</td><td><input type=\"text\" name=\"uuname\" size=40></td></tr>
                           <tr><td>What is $s1 plus $s2?</td><td><input type=\"text\" name=\"answer\" size=40></td></tr>
                           <tr><td>&nbsp;</td><td><input type=\"image\" src=\"buttons/bt-send-password.png\" onclick=\"send_pw(this.form);return false;\"></td></tr>
                        </table>
                     </td>
                  </tr>
               </table>
               </form>
               </div>
      ";

		}
	}
?>
