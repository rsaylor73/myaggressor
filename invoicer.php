<?php
session_start();
switch ($_SESSION['contact_type']) {
	case "reseller_manager":
	case "reseller_agent":
	$ok = "1";
	break;
}
if ($ok == "1") {
	$invoice_url = "https://reservations.aggressor.com/reservation_review_invoice_rrs.php?mode=view&reservationID=".$_GET['r'];

   $filename = date("U") . "_" . rand(40,500) . ".html";
   $filename2 = date("U") . "_" . rand(40,500) . ".pdf";
   $dir = "$_SERVER[DOCUMENT_ROOT]/reservations/invoice/";
   $cmd = "/bin/wkhtmltopdf-i386 \"$invoice_url\" $dir$filename2";
   system($cmd);
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="'.$_GET['r'].'.pdf"');
	$file_to_read = $dir . $filename2;
	readfile($file_to_read);
	@unlink($file_to_read);
} else {
	print "<br><font color=red>Either you have been logged out or you do not have access to this. Please close this window then log back in.</font><br>";
}
?>
