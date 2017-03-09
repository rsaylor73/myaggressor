<?php
session_start();
?>

<script type="text/javascript">
    $(document).ready(function () {
      var windowHeight = $(window).height();

        $("iframe").width=(document.body.offsetWidth);
        $("iframe").height=(document.body.offsetHeight);
    });
</script>
<?php
if ($bg != "1") {
?>
<div id="toparea">
   <iframe id="iframe" src="ResSys/hm-ressys.html" style="width:1950px;height:1400px;max-width:100%;overflow:hidden;border:0;padding:0;margin:0 auto;displ
ay:block;scrolling=No;" marginheight="0" marginwidth="0"></iframe>
   <!-- Sets a transparent box -->
</div>
<?php
include "search_home.php";
}
if ($bg == "1") {

?>

<!-- resp -->
<div class="row">
	<div class="col-md-2 hidden-sm hidden-xs">
	<table border=0 width=100%><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>


		<form name="MyForm" id="MyForm" method="get" action="checkreservations.php" onsubmit="return validateForm()">
		<table width=240 border=0 cellpadding=2 cellspacing=0>
	                <tr><td colspan=2><span class="details-title-text">SEARCH</span> <br><br></td></tr>
                        <tr>
	                        <td width=220 colspan=2>
	                        <?php
        	                $month[1] = "Jan";
                	        $month[2] = "Feb";
	                        $month[3] = "Mar";
        	                $month[4] = "Apr";
                	        $month[5] = "May";
                        	$month[6] = "Jun";
	                        $month[7] = "Jul";
        	                $month[8] = "Aug";
                	        $month[9] = "Sep";
                        	$month[10] = "Oct";
	                        $month[11] = "Nov";
        	                $month[12] = "Dec";

                	        $x = date("n");
                        	$year_start = date("Y");
	                        $year_end = $year_start + 6;

        	                for ($year = $year_start; $year < $year_end; $year++) {
                		        if ($year == $year_start) {
                                	        $x = date("n");
	                                } else {
        	                                $x = "1";
                	                }
                        	        for ($y = $x; $y < 13; $y++) {
                                	        if ($y < 10) {
	                                                $selected = "";
        	                                        if ($_GET['datepicker'] == "0$y$year") {
                	                                        $selected = "selected";
                        	                        }
                                	                $date_picker .= "<option $selected value=\"0$y$year\">$month[$y] $year</option>";
	                                        } else {
        	                                        $selected = "";
                	                                if ($_GET['datepicker'] == "$y$year") {
                        	                                $selected = "selected";
                                	                }
					                $date_picker .= "<option $selected value=\"$y$year\">$month[$y] $year</option>";
	                                        }
        	                        }
                	        }
                        	?>
				<span class="reservation-start-text"><strong>Departure Month:</strong></span>
				</td>
			</tr>
		</table>
                <!-- seperator -->
		<table width=240 border=0 cellpadding=2 cellspacing=0>
			<tr><td width=220 colspan=2><select name="datepicker" style="width:202px;"><?=$date_picker;?></select><br></td></tr>
		</table>
		<!-- seperator -->
		<table width=240 border=0 cellpadding=2 cellspacing=0>
			<tr>
				<td width=111><span class="reservation-start-text"><strong>Passengers</strong></span>:</td>
				<td width=121 align=left><select name="passengers" id="passengers" style="width:95px">
            			<?php
				if ($_GET['passengers'] != "") {
		        		print "<option selected>$_GET[passengers]</option>";
				}
				if ($_GET['passengers'] == "") {
					print "<option selected value=\"1\">--Select--</option>";
				}
				switch ($_SESSION['contact_type']) {
					case "consumer":
					$options = '
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        ';
					break;

					case "reseller_manager":
					case "reseller_agent":
					case "reseller_third_party":

					$sql = "SELECT `cap` FROM `boats` ORDER BY `cap` DESC LIMIT 1";
					$result2 = $reservation->new_mysql($sql);
					while ($row2 = $result2->fetch_assoc()) {
						$cap = $row2['cap'];
                                                $cap++;
					}

                                        for ($x=1; $x < $cap; $x++) {
                                                $options .= "<option value=\"$x\">$x</option>";
                                        }
                                        $options .= "<option value=\"6\">Wholeboat</option>";
					break;

                                        default:
					$options = '
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
					';
                                        break;
				}
                                ?>
                                <?php print "$options"; ?>
                                </select>
                        	</td>
                        </tr>
		</table>
                <!-- seperator -->
		<span class="reservation-start-text">
		<table width=240 border=0 cellpadding=2 cellspacing=0>
			<tr>
				<td colspan=2><strong>Destination:</strong> &nbsp;&nbsp;<span data-toggle="modal" data-target="#myModal"><b>(Click for Help)</b></span></td>
			</tr>
		</table>
		</span>
                <!-- seperator -->

		<?php
		$boat_list = "<select name=\"boats[]\" id=\"boats\" multiple size=20 style=\"width:220px;\" class=\"form-control\">";
		print "$boat_list";
		$options = $reservation->get_boats();
		print $options;
		print "</select>";
		?>

		<!-- scripts -->
		<script>
		function validateRadio (radios) {
			for (i = 0; i < radios.length; ++ i) {
				if (radios [i].checked) return true;
			}
			return false;
		}

		function validateForm() {
			var pass = document.getElementById('passengers').value;
			if (pass==null || pass=="") {
				alert("You did not select a number of passenger(s)");
				return false;
			}       
			var x=document.forms["MyForm"]["boats"].value;
			if (x==null || x=="") {
				alert("You must select at least one destination.");
				return false;
			}
			return true;
		}

		function selectAll(selectBox,selectAll) { 
			// have we been passed an ID 
			if (typeof selectBox == "string") { 
				selectBox = document.getElementById(selectBox);
			} 
			// is the select box a multiple select box? 
			if (selectBox.type == "select-multiple") { 
				for (var i = 0; i < selectBox.options.length; i++) { 
					selectBox.options[i].selected = selectAll; 
				} 
			}
		}
		</script>
		<!-- end scripts -->

                <!-- seperator -->
		<br>
		<input style="width:200px;" type="submit" class="btn btn-primary btn_custom" value="Search"><br><br>
		</form>

                <input type="button" class="btn btn-success btn_custom" value="Specials" onclick="document.location.href='specials.php'">
                <br><br>

                <!-- seperator -->
		<form name="MyForm" id="MyForm" method="get" action="portal.php">

		<?php
		if (($_SESSION['uuname'] == "") && ($_SESSION['uupass'] == "")) {
			print '<input  style="width:200px;" type="submit" class="btn btn-primary" value="Log In" id="submit"><br><br>';
		} else {
			print "<input style=\"width:200px;\" type=\"submit\" class=\"btn btn-primary\" value=\"My Aggressor\" id=\"submit\"><br><br>
			<input style=\"width:200px;\" type=\"button\" class=\"btn btn-primary\" value=\"Log Out\" onclick=\"document.location.href='logout.php';return false;\"><br><br>";
		}
		?>
		</form>
		<p>&nbsp;<img src="img-creditcards.png" width="194" height="62" alt=""/></p>


	<?php
	if ($bypass != "1") {
		$check_login2 = $common->check_login();
        	if ($check_login2 == "TRUE") {
			$today = date("Ymd");
			$sql = "
			SELECT
				`r`.`reservationID`
			FROM
				`inventory` i, `charters` c, `reservations` r
			WHERE
				`i`.`passengerID` = '$_SESSION[contactID]'
				AND `i`.`passengerID` != ''
				AND `i`.`charterID` = `c`.`charterID`
				AND `c`.`start_date` > '$today'
				AND `i`.`reservationID` = `r`.`reservationID`
				AND `r`.`show_as_suspended` != '1'
			ORDER BY `c`.`start_date` ASC
			";
			$result = $common->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$common->dive_countdown($row['reservationID']);
			}
		}
	}
	?>



	</td></tr></table>

	<!-- end col -->
	</div>


	<!-- modal helper windows -->
	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Destinations</h4>
				</div>
				<div class="modal-body">
					<p>You may select multiple destinations by holding the command key on a Mac or the Ctrl key on Windows</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<?php
	}
	?>

   <!-- resp -->
	<div class="col-md-8">

<!-- e resp -->
