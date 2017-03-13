<?php
session_start();
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


include "settings.php";
include "header.php";
if(is_array($_GET['boats'])) {
	foreach ($_GET['boats'] as $boat) {
        	$this_boats .= "&boats[]=$boat";
	}
}
$bg = "1";
include "search.php";






                                        print "
                                        <br><br>
                                        <table width=800>


                                        <tr><td width=50>&nbsp;</td><td><br>$_SESSION[contact_name],
                                        <br><br>
                                        Your confirmation number is <b>$_SESSION[reservationID]</b>.<br><br>
                                        </td></tr>

               <tr><td width=50>&nbsp;</td><td bgcolor=\"#E3F6CE\">
                  <b>Thank you for making your payment through our Online Reservation System.</b><br>
                  An email confirmation has been sent to $_SESSION[email] with a link to your reservation where you can add guests, make payments and review your invoice. You may also click the button below to view your reservation details.
                  <br><br><b><font color=red>Please do not use your browser \"Back\" button. It could result in multiple payments being submitted.</font></b><br>
               </td></tr>


                                        <tr><td>&nbsp;</td><td><br>
                                        <input button value=\"View Reservation Details\" class=\"btn btn-primary\" 
					onclick=\"location.href='guests.php?res=$_SESSION[reservationID]&c=$_SESSION[contactID]';return false;\">&nbsp;&nbsp;&nbsp;&nbsp;

					<input type=\"button\" value=\"Make Another Reservation\" class=\"btn btn-primary\" onclick=\"window.open('index.php?s=1')\">
					<br>";

