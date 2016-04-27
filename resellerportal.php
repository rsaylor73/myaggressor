<?php
session_start();
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


include "settings.php";
include "header.php";
   $bg = "1";
   include "search.php";

            switch ($_SESSION['contact_type']) {
               case "reseller_manager":
               case "reseller_agent":
               case "reseller_third_party":
					$common->header_top();
					$common->reseller_portal_view();
					break;

					default:
					$common->login();
					break;
				}


?>
