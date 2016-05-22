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
	   $common->add_divelog();
	}

	if ($_POST['section'] == "save") {
		$common->save_divelog();
	}

	if ($_GET['section'] == "edit") {
		$common->edit_divelog();
	}

	if ($_POST['section'] == "update") {
		
	}

?>