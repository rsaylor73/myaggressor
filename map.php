<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


include "settings.php";
$bg = "1";


$common->dive_map('1040','600','no');
print "<center><a href=\"javascript:void(0)\" onclick=\"window.close()\">Close</a></center>";



?>
