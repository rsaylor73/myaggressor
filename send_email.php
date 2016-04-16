<?php
session_start();
require "settings.php";
	$answer = $_SESSION['num1'] + $_SESSION['num2'];
	if ($answer != $_POST['answer']) {
			$num1 = rand(2,50);
			$num2 = rand(2,50);
			$_SESSION['num1'] = $rand1;
			$_SESSION['num2'] = $rand2;

						print "
                  <form name=\"myform\">
						<input type=\"hidden\" name=\"link\" value=\"$_POST[link]\">
                  <table 	border=0 cellspacing=0 cellpadding=3 width=\"320\">
                  <tr class=\"email_a_friend_text\"><td width=100>Your Name:</td><td><input type=\"text\" name=\"your_name\" value=\"$_POST[your_name]\" size=25></td></tr>
                  <tr class=\"email_a_friend_text\"><td>&nbsp;<td><input type=\"text\" name=\"your_friend_name\" value=\"$_POST[your_friend_name]\" size=25></td></tr>
                  <tr class=\"email_a_friend_text\"><td>Friends Email:</td><td><input type=\"text\" name=\"your_friend_email\" value=\"$_POST[your_friend_email]\" size=25></td></tr>
                  <tr class=\"email_a_friend_text\"><td>Security Question:</td><td>What is $num1 plus $num2? <input type=\"text\" name=\"answer\" id=\"answer\" size=25></td></tr>
                  <tr class=\"email_a_friend_text\"><td><font color=red>Code Invalid</font></td><td><input type=\"button\" value=\"Send Email\" onclick=\"sendemail(this.form)\">&nbsp;&nbsp;<a class=\"email_a_friend_text\" href=\"javascript:void(0)\" onclick=\"document.getElementById('email_a_friend
                  </table>
                  </form>";
        } else {

				//$subj = "$_POST[your_name] sent you a link";
				$subj = "Message from Aggressor Fleet and Dancer Fleet";
				$msg = "$_POST[your_friend_name],<br><br>$_POST[your_name] would like to share a link from Aggressor Fleet and Dancer Fleet.<br><br>
				<a href=\"$_POST[link]\">Click here to see the link</a><br><br>
Best Regards,<br>
Aggressor Fleet & Dancer Fleet<br>
209 Hudson Trace<br>
Augusta, GA 30907<br>
USA<br>
<br>
Reservations:<br>
+1 800.348.2628<br>
+1 706.993.2531 tel<br>
info@liveaboardfleet.com<br>
www.aggressor.com<br>
www.dancerfleet.com<br>
<br>
LiveAboard Vacations Travel Agency:<br>
+1 706.993.2534 tel<br>
info@liveaboardvacations.com<br>

				";
				mail($_POST['your_friend_email'],$subj,$msg,$headers);
				print "<br><br><br><center><span class=\"email_a_friend_text\">Your email has been sent to $_POST[your_friend_name]<br><br>Loading please wait...</center></span>";

				?>

				<script>
                                             setTimeout(function()
                                                {
                                                window.location.replace('<?=$_POST['link']?>')
                                                }
                                             ,2000);
				</script>

				<?php
		}

?>
