<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


include "settings.php";
include "header.php";
   $bg = "1";
   include "search.php";
   if (($_GET['section'] == "") && ($_POST['section'] == "")) {
      $common->agentreservations();
   }


?>
