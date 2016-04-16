<?php
include "settings.php";

if ($_SESSION['sessionID'] != "") {

	if ($_GET['uupass1'] != $_GET['uupass2']) {
		print "<font color=red>Password does not match.</font>";
		die;
	}
	if ($_GET['uupass1'] == "") {
		print "<font color=red>Password can not be blank.</font>";
		die;
	}

	$sql = "SELECT `uuname` FROM `contacts` WHERE `uuname` = '$_GET[uuname]'";
	$result = $reservation->new_mysql($sql);
	while($row = $result->fetch_assoc()) {
		$found = "1";
	}

	if ($found == "1") {
		print "<font color=red>Re-enter your username</font>";
	} else {
		$random = rand(700,9000);
		$_SESSION['random'] = $random;
		?>
		<script>
			document.getElementById('submit-ok').style.display = 'inline';
			document.getElementById( 'submit-ok' ).scrollIntoView();
		</script>
		<?php
	}

}
?>
