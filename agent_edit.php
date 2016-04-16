<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


include "settings.php";
include "header.php";
   $bg = "1";

	if (($_GET['section'] == "") && ($_POST['section'] == "")) {
	   include "search.php";
   	$common->edit_agents();
	}

	if ($_POST['section'] == "update") {
      include "search.php";
		$common->update_agent();
	}

?>

