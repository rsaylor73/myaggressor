<?php
include "settings.php";

if ($_SESSION['sessionID'] != "") {

	if ($_POST['action'] == "") {
		// look up contacts

		$sql = "
			SELECT
				`contacts`.`contactID`,
				`contacts`.`first`,
				`contacts`.`last`,
				`contacts`.`city`,
				`contacts`.`state`,
				`contacts`.`zip`,
				`contacts`.`donotbook`

			FROM
				`contacts`

			WHERE
				SUBSTRING(`contacts`.`date_of_birth`, 1,6) = '$_GET[birth_year]$_GET[birth_month]'
				AND `contacts`.`first` = '$_GET[fname]'
				AND `contacts`.`last` = '$_GET[lname]'

		";
		$result = $reservation->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			if ($s == "") {
				print "<div id=\"ornewbefore\">";
				print "<table border=0 width=700 class=\"details-description\">
				<tr><td width=50>&nbsp;</td><td colspan=2><br>Select the contact below that matches the first 3 letters of the city you reside in. If none is found, select <b>Register New Contact</b>.<br></td></tr>";
				$s = "1";
			}
			$varU = $_GET['varU'];
			$length = strlen($row['city']) / 2;
			$city = $reservation->mask($row['city'],'3');
			if ($row['donotbook'] == "") {
				print "<tr><td width=50></td><td width=325><span class=\"view_details-description\">City:</span> $city</td><td width=325><input type=\"image\" src=\"buttons/bt-select.png\" onclick=\"location.href='register.php?c=$row[contactID]&$varU';return false;\"></td></tr>";
			} else {
				print "<tr><td width=50></td><td width=325><span class=\"view_details-description\">City:</span> $city</td><td width=325><font color=red>Please contact Aggressor Fleet - unable to select contact</font></td></tr>";
			}
			$found = "1";
		}

		
		if ($found == "1") {
			print "<tr><td width=50></td><td width=325></td><td width=325 align=\"center\"><br><br><br><input type=\"image\" src=\"buttons/bt-newcontact.png\" onclick=\"
			document.getElementById('ornew').style.display='inline';
			document.getElementById('ornewbefore').style.display='none';
			return false;
			\"></td></tr>
			</table>";
			print "</div>";
		}

			$countryID = "<option value=\"\">--Select--</option>";
			$countryID .= "<option value=\"2\">USA</option>";
			$sql = "SELECT * FROM `countries` ORDER BY `country` ASC";
			$result = $reservation->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$countryID .= "<option value=\"$row[countryID]\">$row[country]</option>";
			}

			$state = "<option value=\"\">--Select--</option>";
			$sql = "SELECT * FROM `state` ORDER BY `state_abbr` ASC";
			$result = $reservation->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$state .= "<option>$row[state_abbr]</option>";
			}

			$dob_day = "<option value=\"\">--Select--</option>";
			for ($t=1; $t < 32; $t++) {
				if ($t < 10) {
					$dob_day .= "<option value=\"0$t\">$t</option>";
				} else {
					$dob_day .= "<option>$t</option>";
				}
			}

			$dob_year = "<option value=\"\">--Select--</option>";
			$year = date("Y");
			$end_year = $year - 99;
			for ($t=$end_year; $t < $year; $t++) {
				$dob_year .= "<option>$t</option>";
			}

			?>
			<script>
				document.getElementById('fname').readOnly = 'true';
            document.getElementById('lname').readOnly = 'true';
            document.getElementById('birth_year').readOnly = 'true';
			</script>
			<input type="hidden" name="birth_month2" id="birth_month2" value="">
			<script>
				document.getElementById('birth_month2').value=document.getElementById('birth_month').value;
				document.getElementById('birth_month').disabled='true';
			</script>
			<?php

         $_SESSION['temp_data'] = rand(500,50000);
         print "<input type=\"hidden\" name=\"temp_data\" value=\"$_SESSION[temp_data]\">";

			if ($found == "1") {
				$style = "display:none";
			} else {
				$style = "display:inline";
			}

			print "<div id=\"ornew\" style=\"$style\">";

			?>
            <script>
            function validateForm2() {
                 var x=document.forms["myform"]["address1"].value;
                 if (x==null || x=="") {
                         alert("Address is required.");
                         return false;
                 }
                 var x=document.forms["myform"]["email"].value;
                 if (x==null || x=="") {
                         alert("Email is required.");
                         return false;
                 }

		 var emailID = document.forms["myform"]["email"].value;
	         atpos = emailID.indexOf("@");
	         dotpos = emailID.lastIndexOf(".");
         
	         if (atpos < 1 || ( dotpos - atpos < 2 )) {
	            alert("Please enter a valid email address")
	            document.forms["myform"]["email"].focus() ;
	            return false;
	         }

                 var x=document.forms["myform"]["email2"].value;
                 if (x==null || x=="") {
                         alert("Please enter your email again.");
                         return false;
                 }

                 var x=document.forms["myform"]["phone1"].value;
                 if (x==null || x=="") {
                         alert("The first phone field is required.");
                         return false;
                 }

                 var email1=document.forms["myform"]["email"].value;
                 var email2=document.forms["myform"]["email2"].value;
						if (email1 != email2) {
							alert("The email address does not match.");
							return false;
						}



                 var x=document.forms["myform"]["dob_day"].value;
                 if (x==null || x=="") {
                         alert("Birth Day is required.");
                         return false;
                 }

                  return true;
            }
            </script>


			<?php

			print "<table border=0 width=700 class=\"details-description\">
			<tr><td width=50>&nbsp;</td><td>Address:</td><td><input type=\"text\" name=\"address1\" size=40></td></tr>
			<tr><td width=50>&nbsp;</td><td>Address Line 2:</td><td><input type=\"text\" name=\"address2\" size=40></td></tr>
			<tr><td width=50>&nbsp;</td><td>City:</td><td><input type=\"text\" name=\"city\" sizee=\"40\"></td></tr>
			<tr><td width=50>&nbsp;</td><td>Country:</td><td><select name=\"countryID\" onchange=\"state_or_province(this.form)\">$countryID</select></td></tr>
			<tr id=\"state\" style=\"display:none\"><td width=50>&nbsp;</td><td>State:</td><td><select name=\"state\">$state</select></td></tr>
			<tr id=\"province\" style=\"display:none\"><td width=50>&nbsp;</td><td>Province:</td><td><input type=\"text\" name=\"province\" size=40></td></tr>
			<tr><td width=50>&nbsp;</td><td>Zip:</td><td><input type=\"text\" name=\"zip\" size=40></td></tr>
			<tr><td width=50>&nbsp;</td><td>Email Address:</td><td><input type=\"text\" name=\"email\" size=40 required></td></tr>
         <tr><td width=50>&nbsp;</td><td>Verify Email Address:</td><td><input type=\"text\" name=\"email2\" size=40 required></td></tr>
			<tr><td width=50>&nbsp;</td><td>Phone <select name=\"phone1_type\" required><option>Mobile</option><option>Home</option><option>Work</option></select></td><td><input type=\"text\" name=\"phone1\" size=40></td></tr>
         <tr><td width=50>&nbsp;</td><td>Phone <select name=\"phone2_type\"><option>Home</option><option>Work</option><option>Mobile</option></select></td><td><input type=\"text\" name=\"phone2\" size=40></td></tr>
         <tr><td width=50>&nbsp;</td><td>Phone <select name=\"phone3_type\"><option>Work</option><option>Home</option><option>Mobile</option></select></td><td><input type=\"text\" name=\"phone3\" size=40></td></tr>
			<tr><td width=50>&nbsp;</td><td>Birth Day:</td><td><select name=\"dob_day\">$dob_day</select></td></tr>
			<tr><td width=50>&nbsp;</td><td>Gender:</td><td><input type=\"radio\" name=\"sex\" value=\"male\" checked> Male <input type=\"radio\" name=\"sex\" value=\"female\">Female</td></tr>
			<tr><td width=50>&nbsp;</td><td>Username:</td><td><input type=\"text\" name=\"uuname\" id=\"uuname\" onchange=\"check_uuname(this.form)\" size=40> <div id=\"check_uuname\" style=\"display:inline\"></div></td></tr>
         <tr><td width=50></td><td>Password:</td><td><input type=\"password\" name=\"uupass1\" size=40></td></tr>
         <tr><td width=50></td><td>Verify Password:</td><td><input type=\"password\" name=\"uupass2\" onchange=\"check_pw(this.form)\" size=40><div id=\"check_pw\" style=\"display:inline\"></div></td></tr>
			</table>";

			print "
         <div id=\"submit-ok\" style=\"display:none\">
         
         <table border=0 width=700 class=\"details-description\">
         <tr><td width=50></td><td colspan=2><input type=\"image\" src=\"buttons/bt-account.png\" id=\"create\" name=\"create\" onclick=\"if(validateForm2() ) { create_account(this.form);}return false;\"></td></tr>
         </table>
         </div>
			</div>

			</div>
			";

			?>
                                <script>
                                function state_or_province(myform) {
                                        $.get('state_or_province.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
														if (php_msg == "2") {
															document.getElementById('state').style.display='table-row';
															document.getElementById('province').style.display='none';
														} else {
															document.getElementById('province').style.display='table-row';
															document.getElementById('state').style.display='none';
														}
                                        });
                                }
                                </script>

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
                                        $.get('create_account2.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#create_new_account").html(php_msg);
                                        });
                                }
                                </script>


			<?php

/*
		}
*/

	}

}
?>
