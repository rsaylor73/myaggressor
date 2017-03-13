<?php
include "settings.php";

print "<span class=\"details-description\">";

if (($_GET['tk'] == $_SESSION['reservation_token']) && ($_GET['tk'] != "")) {


	// Make Reservation

	if ($_SESSION['uuname'] == "") {
		$reservation->general_error('Your session has been lost. Please start your search again to login.');
		die;
	}

	// Step 1 get contact ID
	$sql = "SELECT `contactID`,`first`,`last` FROM `contacts` WHERE `uuname` = '$_SESSION[uuname]' LIMIT 1";
	$result = $reservation->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		$contactID = $row['contactID'];
		$contact_name = "$row[first] $row[last]";
	}

	// If contact ID not found
	if ($contactID == "") {
		$reservation->general_error('There was a problem loading your contact profile. Please contact us and provide your username.');
		die;
	}

	// Get total cost
   $sql = "
   SELECT
 		`inventory`.`inventoryID`,
      `inventory`.`bunk`,
      `inventory`.`bunk_price` + `charters`.`add_on_price_commissionable` + `charters`.`add_on_price` AS 'bunk_price',
      `inventory`.`bunk_description`

	FROM
   	`inventory`,`charters`


	WHERE
		`inventory`.`charterID` = '$_GET[charter]'
		AND `charters`.`charterID` = '$_GET[charter]'
      AND `inventory`.`sessionID` = '$_SESSION[sessionID]'
	";

	$pax = 0;
	$result = $reservation->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {

		$i = "passenger_";
		$i .= $row['inventoryID'];
		$options .= "<input type=\"hidden\" name=\"passenger_$row[inventoryID]\" value=\"$_GET[$i]\">";
		$found = "1";
		$pax++;
                     $temp_d = "";
                     $discount = $reservation->find_discount($_GET['charter'],$row['bunk_price']);
                     if (is_array($discount)) {
                        foreach ($discount as $value) {
                           if ($value > $temp_d) {
                              if(!is_array($value)) {
	                              $temp_d = $value;
										}
                           }
                        }
                     }

		// ----------------

                        $sql_b = "
                        SELECT
                                `sb`.`bunkID`,
                                `sb`.`value`,
                                `bk`.`cabin`,
                                `bk`.`bunk`,
                                `b`.`abbreviation`,
                                CONCAT(`b`.`abbreviation`,'-',`bk`.`cabin`,`bk`.`bunk`) AS 'location'
                        FROM
                                `af_df_unified2`.`specials_bunks` sb,
                                `reserve`.`bunks` bk,
                                `reserve`.`boats` b

                        WHERE
                                `sb`.`discountID` = '".$discount[$temp_d][1]."'
                                AND `sb`.`bunkID` = `bk`.`bunkID`
                                AND `bk`.`boatID` = `b`.`boatID`

                        ";
                        $check_discount = $temp_d; // this is here incase the query is empty
                        $result_b = $reservation->new_mysql($sql_b);
                        $num_rows = $result_b->num_rows;
                        if ($num_rows > 0) {
                                $check_discount = "";
                                while ($row_b = $result_b->fetch_assoc()) {
                                        if ($row_b['location'] == $row['bunk']) {
                                                $check_discount = $temp_d;

                                        }
                                }
                        }

		// ---------------

		if ($check_discount > 0) {
			$new_price = $row['bunk_price'] - $check_discount;
			$total = $total + $new_price;
		} else {
			$total = $total + $row['bunk_price'];
		}
	}
	$total2 = round($total);
	//$total2 = number_format($total,2);

	// If the inventory is timmed our stop
	if ($found != "1") {
		$reservation->general_error('The inventory you selected is no longer available or your session has expired.');
		die;
	}

	// Determin charter date for payment policy
	$sql = "SELECT UNIX_TIMESTAMP(`start_date`) AS 'start_date' FROM `charters` WHERE `charterID` = '$_GET[charter]'";
	$result = $reservation->new_mysql($sql);
	while ($row = $result->fetch_assoc()) {
		$start_date_epoch = $row['start_date'];
	}
	$today_date = date("U");
	$diff = $start_date_epoch - $today_date;

	if ($diff > 7776000) { // 90 days
		$policy = "3"; // only deposit required
	} else {
		$policy = "5"; // full amount required
	}

	switch ($policy) {
		case "3":
			$deposit = $total * .40;
			//$deposit = number_format($deposit,2);
			$msg = "$contact_name, please complete the payment information below to reserve your trip.<br><br>";

		break;
		
		case "5":
			$deposit = $total;
			$msg = "$contact_name, please complete the payment information below to reserve your trip.<br><br>";

		break;

	}

	$start_year = date("Y");
	$end_year = $start_year + 10;

	for ($i = $start_year; $i < $end_year; $i++) {
		$year .= "<option>$i</option>";
	}

	print "
	<div id=\"checkout\">
	<form name=\"myform\" id=\"myform\">
	$options
	<input type=\"hidden\" name=\"charter\" value=\"$_GET[charter]\">
	<input type=\"hidden\" name=\"primary\" value=\"$_GET[primary]\">
	<table border=0 width=\"700\" cellpadding=\"4\" cellspacing=\"0\">
	<tr><td width=20></td><td colspan=2><br>

<span class=\"details-title-text\">Payment (Step 3 of 3)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <div id=\"timeleft\" style=\"display:inline\"></div>

	</td></tr>
	<tr><td width=20>&nbsp;</td><td colspan=2>
		<a href=\"https://gis.liveaboardfleet.com/gis/_POLICY.html\" target=_blank><img src=\"buttons/bt-policy.png\" border=0></a><br><br>

	<tr><td></td><td>Total Reservation:</td><td>$".number_format($total2, 2, '.', '')."</td></tr>
	<tr><td></td><td width=\"200\">Total amount due today:</td><td>$".number_format($deposit, 2, '.', '')."</td></tr>
	<input type=\"hidden\" name=\"total\" id=\"total\" value=\"".number_format($total, 2, '.', '')."\">
	<input type=\"hidden\" name=\"details\" value=\"$_GET[details]\">
	<input type=\"hidden\" name=\"min\" id=\"min\" value=\"".number_format($deposit, 2, '.', '')."\">
	<tr><td></td><td colspan=2>Please select your payment amount:</td></tr>
	<tr><td></td><td>
		<input type=\"radio\" name=\"howmuch\" checked value=\"min\" onclick=\"document.getElementById('payment_amount').value=document.getElementById('min').value\">Minimum Due 
		<input type=\"radio\" name=\"howmuch\" value=\"total\" onclick=\"document.getElementById('payment_amount').value=document.getElementById('total').value\">Total Due</td>
		<td>$<input type=\"text\" name=\"payment_amount\" id=\"payment_amount\" size=20 value=\"".number_format($deposit, 2, '.', '')."\" readonly> USD</td></tr>


	<tr><td></td><td>Name On Card:</td><td><input type=\"text\" name=\"cc_name\" value=\"$contact_name\" size=30></td></tr>
	<tr><td></td><td>Credit Card Number:</td><td>
		
      <div id=\"cc\">
         <table border=0 cellspacing=0 cellpadding=0 align=right>
            <tr>
               <td><img src=\"CC-Visa.jpg\" alt=\"Visa Accepted\" title=\"Visa Accepted\"></td>
               <td><img src=\"CC-MCard.jpg\" alt=\"Master Card Accepted\" title=\"Master Card Accepted\"></td>
					<td width=\"140\">&nbsp;</td>
            </tr>
         </table>
      </div>


	<input type=\"text\" name=\"cc_num\" size=30 onchange=\"cctype(this.form)\" onkeyup=\"if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')\">



</td>


</tr>
	<tr><td></td><td>Expiration Date:</td><td>
		<select name=\"exp_month\">
		<option value=\"01\">Jan (01)</option>
		<option value=\"02\">Feb (02)</option>
		<option value=\"03\">Mar (03)</option>
		<option value=\"04\">Apr (04)</option>
		<option value=\"05\">May (05)</option>
		<option value=\"06\">Jun (06)</option>
		<option value=\"07\">Jul (07)</option>
		<option value=\"08\">Aug (08)</option>
		<option value=\"09\">Sep (09)</option>
		<option value=\"10\">Oct (10)</option>
		<option value=\"11\">Nov (11)</option>
		<option value=\"12\">Dec (12)</option>
		</select>
		&nbsp;
		<select name=\"exp_year\">
			$year
		</select>
		</td></tr>
	<tr><td></td><td>CVV Number:</td><td><input type=\"text\" name=\"cc_cvv\" size=10> <a href=\"javascript:void(0)\" name=\"cvvQ\" onclick=\"document.getElementById('cvv').style.display='inline'\">?</a> </td></tr>
   <tr><td></td><td colspan=2 height=\"34\">
	<input type=\"checkbox\" name=\"policy\" id=\"policy\" value=\"checked\" onclick=\"document.getElementById('checkout_img').style.display='inline';set_policy(this.form);\"> I agree to the WayneWorks Marine, LLC. <a href=\"https://gis.liveaboardfleet.com/gis/_POLICY.html\" target=_blank>Payment Policy</a>  &nbsp;&nbsp;&nbsp;&nbsp;

	<input type=\"image\" src=\"buttons/bt-pay.png\" name=\"checkout\" id=\"checkout_img\" onclick=\"checkout2(this.form);return false;\" style=\"display:none\"> 
	<div id=\"loading\" style=\"display:none\">Please wait ...</div>
	</td></tr>

	<tr><td></td><td colspan=2>
	<center><b>Please click only once and do not hit the back button on your web browser, <br>this could result in duplicate charges to your credit card.</b></center>
	</td></tr>

   <tr><td></td><td></td><td><br>

	</td></tr>
	</table>
	</form>
	</div>";

	print "<div id=\"cvv\" style=\"display:none\">
	<img src=\"cvv-visa.gif\" width=\"200\"><br><a href=\"javascript:void(0)\" onclick=\"document.getElementById('cvv').style.display='none'\">Close</a>
	</div>";

	?>
	<script>

		function refreshDiv() {
		    $('#timeleft').load('check_time.php?charter=<?=$_GET['charter'];?>', function(){ /* callback code here */ });

		}
		setInterval(refreshDiv, 1000);

											function set_policy(myform) {
												$(function(){
									   	     $('#policy').click(function(){
									      	  $('input[type=checkbox]').attr('disabled','true');
                                      $('input[type=checkbox]').prop('checked','true');

										        });
											   });
											}

                                function cctype(myform) {
                                        $.get('cctype.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#cc").html(php_msg);
                                        });
                                }

                                function checkout2(myform) {
													document.getElementById('cvv').style.display='none';
											      document.getElementById('checkout_img').style.display='none';
											      document.getElementById('loading').style.display='inline';
                                        $.post('checkout.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
														if (php_msg == "E") {
															alert('Your credit card could not be processed due to an error. Please check your information and if the problem continues, contact your bank.');
		                                       //header('Location: http://www.aggressor.com');
							document.getElementById('checkout_img').style.display='inline';
		                                       document.getElementById('loading').style.display='none';
														}
														if (php_msg == "D") {
															alert('Your credit card was declined. Please contact your bank to find out why it will not process.');
                                             document.getElementById('checkout_img').style.display='inline';
                                             document.getElementById('loading').style.display='none';
														}
														if ((php_msg != "E") && (php_msg != "D")) {
                                                $("#checkout").html(php_msg);
						window.location.replace("order_processed.php");
														}
                                        });
                                }

	</script>
	<?php
} else {
	print "<br><br><font color=red>Sorry but your session has expired.</font><br><br>\n";
}

print "</span>";

?>
