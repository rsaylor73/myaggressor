<?php
require "settings.php";

	if ($_SESSION['uuname'] != "") {

		$sql = "
		SELECT 
			`contacts`.*,
			`countries`.`country`

		FROM 
			`contacts`,`countries`

		WHERE `contacts`.`uuname` = '$_SESSION[uuname]'
		AND `contacts`.`countryID` = `countries`.`countryID`
		";

		$result = $reservation->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			$authnet_login = "5ZuC46WbX";
			$authnet_key = "53mr9jJwEL4689qn";

			require_once('authorizenet.class.php');
			$cc_num_v = preg_replace("/[^0-9]/","", $_POST['cc_num']);
			$_POST['amount'] = str_replace(",","",$_POST['amount']);

         $a = new authorizenet_class;
         $a->add_field('x_login', $authnet_login);
         $a->add_field('x_tran_key', $authnet_key);
         $a->add_field('x_version', '3.1');
         $a->add_field('x_type', 'AUTH_CAPTURE');

         //$a->add_field('x_test_request', 'TRUE');    // Just a test transaction

         $a->add_field('x_relay_response', 'FALSE');
         $a->add_field('x_delim_data', 'TRUE');
         $a->add_field('x_delim_char', '|');
         $a->add_field('x_encap_char', '');
         $a->add_field('x_email_customer', 'FALSE');
         $a->add_field('x_ship_to_first_name', $row['first']);
         $a->add_field('x_ship_to_last_name', $row['last']);
         $a->add_field('x_ship_to_address', $row['address1']);
         $a->add_field('x_ship_to_city', $row['city']);

			if ($row['countryID'] == "2") {
 	        $a->add_field('x_ship_to_state', $row['state']);
			} else {
           $a->add_field('x_ship_to_state', $row['province']);
			}
         $a->add_field('x_ship_to_zip', $row['zip']);
         $a->add_field('x_ship_to_country', $row['country']);
         $a->add_field('x_first_name', $row['first']);
         $a->add_field('x_last_name', $row['last']);
         $a->add_field('x_address', $row['address1']);
         $a->add_field('x_city', $row['city']);
         if ($row['countryID'] == "2") {
 	        $a->add_field('x_state', $row['state']);
			} else {
           $a->add_field('x_state', $row['province']);
			}
         $a->add_field('x_zip', $row['zip']);
         $a->add_field('x_country', $row['country']);
         $a->add_field('x_email', $row['email']);
         $a->add_field('x_phone', $row['phone1']);
         $a->add_field('x_description', "Reservation $_POST[reservationID]");
         $a->add_field('x_method', 'CC');
         $a->add_field('x_card_num', $cc_num_v);   // test successful visa
         $a->add_field('x_amount', $_POST['amount']);
			$exp_date = $_POST['exp_month'] . $_POST['exp_year'];
         $a->add_field('x_exp_date', $exp_date);    // march of 2008
         $a->add_field('x_card_code', $_POST['cvv']);    // Card CAVV Security code

			switch ($a->process()) {
				case 1: // Accepted
      	   	//echo $a->get_response_reason_text();
					// Make Reservation and record the payment

					// Record Payment
					if ($_SESSION['contact_type'] == "consumer") {
						$cc_type = "Online - CC";
						$pay_note = "Payment made via CRS by $_POST[cc_name]";
					} else {
						$cc_type = "Credit Card";
                  $pay_note = "Payment made via RRS by $_POST[cc_name]";
					}

					$today = date("Ymd");
					$sql2 = "INSERT INTO `reservation_payments` (`reservationID`,`payment_amount`,`payment_date`,`payment_type`,`comment`) VALUES
					('$_POST[reservationID]','$_POST[amount]','$today','$cc_type','$pay_note')";
					$result2 = $reservation->new_mysql($sql2);
					echo "A";
				break;

				case 2:  // Declined
					echo "D";
	         	//echo $a->get_response_reason_text();
					// send back to form
   	      break;

				case 3:  // Error
					echo "E";
         		//echo $a->get_response_reason_text();
					//send back to form
					//$_GET['charter'] = $_POST['charter'];
					//include "reservenow.php";
	         break;
			}
		}
	} else {
		$msg = "Your session has expired. Please re-start your search to log back in.";
		$reservation->general_error($msg);
	}

?>
