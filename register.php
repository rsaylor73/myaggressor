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

	if ($_GET['c'] == "") {
	   $reservation->register();
	}

	if ($_GET['c'] != "") {
		// use existing contact
		$reservation->register_part2();
	}
?>

