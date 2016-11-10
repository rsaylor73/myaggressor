<?php

	$invoice_url = "https://www.aggressor.com/myaggressor/invoices.php?r=".$_GET['r']."&rid=".$_GET['rid'];

        $filename = date("U") . "_" . rand(40,500) . ".html";
        $filename2 = date("U") . "_" . rand(40,500) . ".pdf";
        //file_put_contents($filename,$pdf_content);

         $dir = "$_SERVER[DOCUMENT_ROOT]/myaggressor/invoice/";
         $cmd = "/bin/wkhtmltopdf-i386 \"$invoice_url\" $dir$filename2";
         system($cmd);


		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="'.$_GET['r'].'.pdf"');
		$file_to_read = $dir . $filename2;
		readfile($file_to_read);
		@unlink($file_to_read);
?>
