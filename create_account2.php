<?php

require "settings.php";

if (($_GET['temp_data'] == $_SESSION['temp_data']) && ($_GET['temp_data'] != "")) {

	$d = array();
	foreach ($_GET as $key=>$value) {
		$d[$key] = preg_replace('/[^A-Za-z0-9\-@. ]/', '', $value);
	}

	$date_created = date("Ymd");
	$dob = $_GET['birth_year'] . $_GET['birth_month2'] . $_GET['dob_day'];

	$sql = "
	INSERT INTO `contacts`
	(`first`,`last`,`address1`,`address2`,`city`,`state`,`province`,`zip`,`countryID`,`email`,`phone1`,`phone1_type`,`phone2`,`phone2_type`,`phone3`,`phone3_type`,`date_created`,`date_of_birth`,`sex`,`uuname`,`uupass`)
	VALUES
	('$d[fname]','$d[lname]','$d[address1]','$d[address2]','$d[city]','$d[state]','$d[province]','$d[zip]','$d[countryID]','$d[email]','$d[phone1]','$d[phone1_type]',
	'$d[phone2]','$d[phone2_type]','$d[phone3]','$d[phone3_type]','$date_created','$dob','$d[sex]','$d[uuname]','$d[uupass1]'
	)
	";

	print "<span class=\"details-description\">";
	$result = $reservation->new_mysql($sql);
	if ($result == "TRUE") {
		print "<br><br>Your account has been created. You can now login by clicking <a href=\"$_SESSION[uri]\">here</a><br><br>\n";
	} else {
		print "<br><br><font color=red>There was an error creating your account. An email with the error has been sent to the IT department.<br><br>";
		mail('robert@wayneworks.com','AF/DF Registration Error',$sql);
	}
	print "</span>";
} else {
	print "<br><br><span class=\"details-description\"><font color=red>Your session has timed out. Please start your search again to register.</font><br><br></span>";
}

?>
