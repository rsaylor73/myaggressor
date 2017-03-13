<?php
include "settings.php";
if(isset($_SESSION['sessionID'])) {

         // Get charterID
         $sql = "
         SELECT 
            `charterID`,`sessionID`,`userID` 

         FROM 
            `inventory` 

         WHERE 
				`inventory`.`sessionID` = '$_SESSION[sessionID]'
				AND `inventory`.`charterID` = '$_GET[charter]'
         ";
         $result = $reservation->new_mysql($sql);
         while ($row = $result->fetch_assoc()) {
				$found = "1";
			}
		if ($found == "1") {
	if (is_array($_GET['boats'])) {
	        foreach ($_GET['boats'] as $boat2) {
         	       $this_boats .= "&boats[]=$boat2";
	        }  
	}

				print "<a href=\"bookit.php?charter=$_GET[charter]&name=$_GET[name]&start_date=$_GET[start_date]&end_date=$_GET[end_date]&passengers=$_GET[passengers]$this_boats\"><img src=\"buttons/bt-continue.png\" border=0></a>";
			} else {
				//
			}

}
?>
