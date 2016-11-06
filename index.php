<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $www .  $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}



include "settings.php";

if ($_GET['s'] == "1") {
	$_SESSION['found_before'] = "";
	$ses = session_id();
	if ($ses != "") {
		$sql = "UPDATE `inventory` SET `timestamp` = '', `donotmove_passenger` = '', `status` = 'avail', `passengerID` = '0', `sessionID` = '' WHERE `sessionID` = '$ses'";
		$result = $reservation->new_mysql($sql);
	}
}

include "header_landing.php";
include "search.php";
?>




<!-- Google Code for Remarketing Tag -->
<!--------------------------------------------------
Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
--------------------------------------------------->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1068114335;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="0" width="0" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1068114335/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>


</body>
</html>
