<?php
include "settings.php";

if ($_SESSION['sessionID'] != "") {

            require "Services/Twilio.php";
            // Set our AccountSid and AuthToken from twilio.com/user/account
            $AccountSid = "AC5e0c7d285c36517ca1cf3c6be5aeb0d9";
            $AuthToken = "a19b6047d540f41451f8452faed7df7c";
            // Instantiate a new Twilio Rest Client
            $client = new Services_Twilio($AccountSid, $AuthToken);
            /* Your Twilio Number or Outgoing Caller ID */
            $from = '+17064054083';
            // make an associative array of server admins. Feel free to change/add your
            // own phone number and name here.

            $verification_code = rand(4000,4);

            $sql = "UPDATE `contacts` SET `verification_code` = '$verification_code',`captured_mobile` = '$_GET[cell]'  WHERE `contactID` = '$_GET[contactID]'";
            $result = $reservation->new_mysql($sql);

            $to = $_GET['cell'];
            $body = "Greeting from AF please enter this verification code $verification_code";
            $client->account->sms_messages->create($from, $to, $body);

            print "
            <div id=\"verifycode\">
            <form name=\"MyForm\" id=\"MyForm\">
            <input type=\"hidden\" name=\"contactID\" value=\"$_GET[contactID]\">
            <table border=0 width=700 class=\"details-description\">
            <tr><td width=50></td><td>Please enter the verification code: <input type=\"text\" name=\"code\" id=\"code\" size=4> <input type=\"image\" name=\"verify\" id=\"verfiy\" src=\"buttons/bt-go.png\" onclick=\"verifycode(this.form);return false;\"></td></tr>
            <tr><td width=50>&nbsp;</td><td><br><br>

Unable to hear or type in verfication code?<br><br>

<li>Press F5 to refresh your screen and request new code.</li>
<li>Call 1-800-348-2628 or +1-706-993-2531.</li>
<li>Give the agent #$_GET[contactID] to receive your verification.</li>
<li>Do not close screen or verification code will become inactive.</li>
</td></tr>
            </table>
            </form>
            </div>
            ";

            ?>
                                <script>
                                function verifycode(myform) {
                                        $.get('verifycode.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                                $("#verifycode").html(php_msg);
                                        });
                                }
                                </script>


            <?php

}
?>

