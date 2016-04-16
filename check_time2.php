<?php
include "settings.php";

function ensure2Digit($number) {
    if($number < 10) {
        $number = '0' . $number;
    }
    return $number;
}

// Convert seconds into months, days, hours, minutes, and seconds.
function secondsToTime($ss) {
    $s = ensure2Digit($ss%60);
    $m = ensure2Digit(floor(($ss%3600)/60));
    $h = ensure2Digit(floor(($ss%86400)/3600));
    $d = ensure2Digit(floor(($ss%2592000)/86400));
    $M = ensure2Digit(floor($ss/2592000));

    //return "$M:$d:$h:$m:$s";
	 return "$m Minutes $s Seconds";
}


if(isset($_SESSION['sessionID'])) {

         $sql = "
         SELECT 
            `charterID`,`sessionID`,`userID`,`timestamp`

         FROM 
            `inventory` 

         WHERE 
            `inventory`.`sessionID` = '$_SESSION[sessionID]'
            AND `inventory`.`charterID` = '$_GET[charter]'
			LIMIT 1
         ";
         $result = $reservation->new_mysql($sql);
         while ($row = $result->fetch_assoc()) {
				$timestamp = $row['timestamp'];
				$_SESSION['found_before'] = "1";
			}


	$now = date("U");
	$time_left = $timestamp - $now;
	$time_left2 = secondsToTime($time_left);

	if ($time_left < 0) {
		if ($_GET['t'] == "1") {
			?>
			<script>
				document.getElementById('button1').disabled=true;
            document.getElementById('button1').value='Session Expired';

			</script>
			<?
		}		


		if ($_SESSION['found_before'] == "1") {
			//print "<font color=red>Your selection has timed out click <a href=\"index.php?s=1\">here</a> to start over.</font>";
         $link = $_SESSION['uri'];
			print "<font color=red>The inventory you selected has timed out. Click <a href=\"index.php?s=1\">here</a> to re-load inventory then make your selection.</font>";
		}
	}

	if ($time_left > 0) {
		print " Time Left: $time_left2";
	}
}
?>
