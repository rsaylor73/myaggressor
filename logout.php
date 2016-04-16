<?php

include "settings.php";
include "header.php";

if (is_array($_GET['boats'])) {
	foreach ($_GET['boats'] as $boat) {
   	$this_boats .= "&boats[]=$boat";
	}
}

   $bg = "1";
   include "search.php";

   $reservation->logout();
?>

