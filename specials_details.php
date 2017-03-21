<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

include "settings.php";
include "header.php";

if (is_array($_GET['boats'])) {
        foreach ($_GET['boats'] as $boat) {
        $this_boats .= "&boats[]=$boat";
        }
}


   $bg = "1";
   include "search.php";

   $reservation->specials_details();
?>
