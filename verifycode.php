<?php
include "settings.php";

if ($_SESSION['sessionID'] != "") {
	$sql = "
	SELECT
		`contacts`.`verification_code`

	FROM
		`contacts`

	WHERE
		`contacts`.`contactID` = '$_GET[contactID]'
		AND `contacts`.`verification_code` = '$_GET[code]'

	";
	$result = $reservation->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		$found = "1";
	}

	if ($found == "1") {

		$sql2 = "
		SELECT
			`contacts`.*,
			`countries`.`countryID` AS 'countryID2',
			`countries`.`country` AS 'countryName'

		FROM
			`contacts`

		LEFT JOIN `countries` ON `contacts`.`countryID` = `countries`.`countryID`

		WHERE
	      `contacts`.`contactID` = '$_GET[contactID]'

		";

		$result2 = $reservation->new_mysql($sql2);
		while ($row2 = $result2->fetch_assoc()) {
			print "<div id=\"verified\">
         <form name=\"MyForm\" id=\"MyForm\">
			<input type=\"hidden\" name=\"contactID\" value=\"$_GET[contactID]\">
         <table border=0 width=700 class=\"details-description\">
			<tr><td width=50>&nbsp;</td><td colspan=2>Great Thanks! You have been verified. Please review your contact profile below and set a password for your online account.</td></tr>
			<tr><td></td><td>Name:</td><td input type=\"text\" name=\"contact_name\" value=\"$row2[first] $row2[last]\" disabled size=40></td></tr>
			<tr><td></td><td>Address:</td><td><input type=\"text\" name=\"address1\" value=\"$row2[address1]\" size=40></td></tr>
			<tr><td></td><td>Address Line 2:</td><td><input type=\"text\" name=\"address2\" value=\"$row2[address2]\" size=40></td></tr>
			<tr><td></td><td>City:</td><td><input type=\"text\" name=\"city\" value=\"$row2[city]\" size=40></td></tr>";

			$sql3 = "SELECT * FROM `countries` ORDER BY `country` ASC";
			$result3 = $reservation->new_mysql($sql3);
			while ($row3 = $result3->fetch_assoc()) {
				if ($row2['countryID'] == $row3['countryID']) {
					$country .= "<option selected value=\"$row3[countryID]\">$row3[country]</option>";
				} else {
               $country .= "<option value=\"$row3[countryID]\">$row3[country]</option>";
				}
			}

			if ($row2['countryID'] == "2") {
				$sql3 = "SELECT * FROM `state` ORDER BY `state_abbr` ASC";
				$result3 = $reservation->new_mysql($sql3);
				while ($row3 = $result3->fetch_assoc()) {
					if ($row2['state'] == $row3['state_abbr']) {
						$state .= "<option selected value=\"$row3[state_abbr]\">$row3[state_abbr]</option>";
					} else {
						$state .= "<option value=\"$row3[state_abbr]\">$row3[state_abbr]</option>";
					}
				}
				print "<tr><td></td><td>State:</td><td><select name=\"state\">$state</select></td></tr>";
			} else {
				print "<tr><td></td><td>Province:</td><td><input type=\"text\" name=\"province\" value=\"$row2[province]\" size=40></td></tr>";
			}
			print "<tr><td></td><td>Postal Code:</td><td><input type=\"text\" name=\"zip\" value=\"$row2[zip]\" size=40></td></tr>
			<tr><td></td><td>Country:</td><td><select name=\"countryID\">$country</select></td></tr>
			<tr><td></td><td>Email:</td><td><input type=\"text\" name=\"email\" value=\"$row2[email]\" size=40></td></tr>
			<tr><td></td><td><select name=\"phone1_type\"><option selected>$row2[phone1_type]</option><option>Home</option><option>Work</option><option>Mobile</option></select></td><td><input type=\"text\" name=\"phone1\" value=\"$row2[phone1]\" size=40></td></tr>
         <tr><td></td><td><select name=\"phone2_type\"><option selected>$row2[phone2_type]</option><option>Home</option><option>Work</option><option>Mobile</option></select></td><td><input type=\"text\" name=\"phone2\" value=\"$row2[phone2]\" size=40></td></tr>
         <tr><td></td><td><select name=\"phone3_type\"><option selected>$row2[phone3_type]</option><option>Home</option><option>Work</option><option>Mobile</option></select></td><td><input type=\"text\" name=\"phone3\" value=\"$row2[phone3]\" size=40></td></tr>
			<tr><td></td><td>Username:</td><td><input type=\"text\" name=\"uuname\" size=40 onchange=\"check_uuname(this.form)\"><div id=\"check_uuname\" style=\"display:inline\"></div> </td></tr>
			<tr><td></td><td>Password:</td><td><input type=\"password\" name=\"uupass1\" size=40></td></tr>
			<tr><td></td><td>Verify Password:</td><td><input type=\"password\" name=\"uupass2\" onchange=\"check_pw(this.form)\" size=40><div id=\"check_pw\" style=\"display:inline\"></div></td></tr>
			</table>

			<div id=\"submit-ok\" style=\"display:none\">
			
         <table border=0 width=700 class=\"details-description\">
			<tr><td width=50></td><td colspan=2><input type=\"image\" src=\"buttons/bt-account.png\" id=\"create\" name=\"create\" onclick=\"create_account(this.form);return false;\"></td></tr>
			</table>
			</div>

			<br><br><br>

			</form>
			</div>
			";
			?>
                                <script>
                                function check_uuname(myform) {
                                        $.get('check_uuname.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#check_uuname").html(php_msg);
                                        });
                                }
                                </script>

                                <script>
                                function check_pw(myform) {
                                        $.get('check_pw.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#check_pw").html(php_msg);
                                        });
                                }
                                </script>
                                <script>
                                function create_account(myform) {
                                        $.get('create_account.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#verified").html(php_msg);
                                        });
                                }
                                </script>



			<?php	


		}


	} else {

            print "
            <div id=\"verifycode\">
            <form name=\"MyForm\" id=\"MyForm\">
            <input type=\"hidden\" name=\"contactID\" value=\"$_GET[contactID]\">
            <table border=0 width=700 class=\"details-description\">
				<tr><td width=50>&nbsp;</td><td><font color=red>Sorry, the code entered was not valid. Please try again. To have a new code sent press F5.</td></tr>
            <tr><td width=50></td><td>Please enter the verification code: <input type=\"text\" name=\"code\" id=\"code\" size=40> <input type=\"image\" src=\"buttons/bt-go.png\" id=\"verfiy\" onclick=\"verifycode(this.form);return false;\"></td></tr>
            <tr><td width=50>&nbsp;</td><td><br><br>If you are unable to hear or type in the verification code please press F5 to refresh your screen and request a new verification code. If you ar
e still unable to be verified please call us at 1-800-348-2628 to speak with an Aggressor Fleet and Dancer Fleet agent. Provide the following code <b>$_GET[contactID]</b> and a agent will provide 
the verification code. During your call please do not close this screen or the verification code will be in-active.</td></tr>
            </table>
            </form>
            </div>
            ";

            ?>
                                <script>
                                function verifycode(myform) {
                                        $.get('verifycode.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#verifycode").html(php_msg);
                                        });
                                }
                                </script>


            <?php

	}

}
?>
