<?php

function CreditCardCompany($ccNum) {
        /*
            * mastercard: Must have a prefix of 51 to 55, and must be 16 digits in length.
            * Visa: Must have a prefix of 4, and must be either 13 or 16 digits in length.
            * American Express: Must have a prefix of 34 or 37, and must be 15 digits in length.
            * Diners Club: Must have a prefix of 300 to 305, 36, or 38, and must be 14 digits in length.
            * Discover: Must have a prefix of 6011, and must be 16 digits in length.
            * JCB: Must have a prefix of 3, 1800, or 2131, and must be either 15 or 16 digits in length.
        */
 
        if (ereg("^5[1-5][0-9]{14}$", $ccNum))
                return "Mastercard";
 
        if (ereg("^4[0-9]{12}([0-9]{3})?$", $ccNum))
                return "Visa";
 
        if (ereg("^3[47][0-9]{13}$", $ccNum))
                return "American Express";
 
        if (ereg("^3(0[0-5]|[68][0-9])[0-9]{11}$", $ccNum))
                return "Diners Club";
 
        if (ereg("^6011[0-9]{12}$", $ccNum))
                return "Discover";
 
        if (ereg("^(3[0-9]{4}|2131|1800)[0-9]{11}$", $ccNum))
                return "JCB";
}

$whatcard = CreditCardCompany($_GET['cc_num']);

print "<table border=0><tr><td colspan=2>";

switch ($whatcard) {
	case "Mastercard":
		print "
               <img src=\"CC-Visa-off.jpg\" alt=\"Visa Accepted\" title=\"Visa Accepted\"><img src=\"CC-MCard.jpg\" alt=\"Master Card Accepted\" title=\"Master Card Accepted\">
		";
	break;

	case "Visa":
		print "
               <img src=\"CC-Visa.jpg\" alt=\"Visa Accepted\" title=\"Visa Accepted\"><img src=\"CC-MCard-off.jpg\" alt=\"Master Card Accepted\" title=\"Master Card Accepted\">
		";
	break;

	default:
		print "
               <font color=red><b>The credit card number provided is not a Visa or Mastercard.</b></font>
		";
	break;

print "</td></tr></table>";

}

?>
