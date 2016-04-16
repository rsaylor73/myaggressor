<?php
session_start();
if(!isset($_SESSION['sessionID'])) {
	$_SESSION['sessionID'] = session_id();
}
$DB_NAME = 'reserve';
$DB_HOST = 'mysql';
$DB_USER = 'root';
$DB_PASS = 'F7m9dSz0';

$linkID = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
}

// Start the class
include "class/consumer.class.php";
include "class/resellers.class.php";
include "class/common.class.php";

$reservation = new Reservation($linkID); // the old class name will stay the same so mass code does not need to be changed
$resellers = new Resellers($linkID);
$common = new common($linkID);

// email headers - This is fine tuned, please do not modify
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= "From: Aggressor Fleet <info@liveaboardfleet.com>\r\n";
$headers .= "Reply-To: Aggressor Fleet <info@liveaboardfleet.com>\r\n";
$headers .= "X-Priority: 3\r\n";
$headers .= "X-Mailer: PHP/" . phpversion()."\r\n";

$site_url = "https://".$_SERVER['HTTP_HOST']."/reservations";
?>
