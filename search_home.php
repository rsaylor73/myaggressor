	<div id="reservation-box-outline"></div>
   <form name="MyForm" id="MyForm" method="get" action="checkreservations.php" onsubmit="return validateForm()">
   <div id="reservation-box-inline-wrapper">

      <div id="reservation-box-inline">
			<table width=240 border=0 cellpadding=2 cellspacing=0>

			<tr><td colspan=2><span class="details-title-text">SEARCH</span> <br>
			  <br>
	</td></tr>

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

      </div>
      <div id="reservation-box-inline">
         <table width=240 border=0 cellpadding=2 cellspacing=0>
         <tr>
         <td width=220 colspan=2>
				<select name="datepicker" style="width:202px;"><?=$date_picker;?></select><br>
			</td>
			</tr>
			</table>
         <br>
      </div>
      <div id="reservation-box-inline">
         <table width=240 border=0 cellpadding=2 cellspacing=0>
         <tr>
         <td width=111>
	         <span class="reservation-start-text"><strong>Passengers</strong></span>:
			</td>
			<td width=121 align=left>
	         <select name="passengers" id="passengers" style="width:85px">
            <?php
            if ($_GET['passengers'] != "") {
               print "<option selected>$_GET[passengers]</option>";
            }
            if ($_GET['passengers'] == "") {
               print "<option selected value=\"1\">--Select--</option>";
            }
            ?>

				<?php
            switch ($_SESSION['contact_type']) {
               case "consumer":
					$options = '
	            <option value="1">1</option>
   	         <option value="2">2</option>
      	      <option value="3">3</option>
         	   <option value="4">4</option>
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

					/*
               $options = '
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>
               <option value="4">4</option>
               <option value="5">5</option>
               <option value="6">6</option>
               <option value="7">7</option>
               <option value="8">8</option>
               <option value="9">9</option>
               <option value="10">10</option>
               <option value="11">11</option>
               <option value="12">12</option>
               <option value="13">13</option>
               <option value="14">14</option>
               <option value="15">15</option>
               <option value="16">16</option>
               <option value="17">17</option>
               <option value="18">18</option>
               ';
					*/
					$options .= "<option value=\"6\">Wholeboat</option>";
               break;

					default:
               $options = '
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>
               <option value="4">4</option>
               ';
					break;
            }
				?>
				<?php print "$options"; ?>
				</select>
			</td>
			</tr>
			</table>
         <br>
      </div>

      <div id="reservation-box-inline">
         <span class="reservation-start-text">
         <table width=240 border=0 cellpadding=2 cellspacing=0>
			<tr>
			  <td colspan=2>
				<strong>Destination:</strong> &nbsp;&nbsp;<label for="multiple" title="You may select multiple destinations by holding the command key on a Mac or the Ctrl key on Windows">(Multiple)</label>
			</td></tr>		

		</table></span>
           
      </div>
      
      <div id="reservation-start-input-box">
		

		<?php
		   $boat_list = "<select name=\"boats[]\" id=\"boats\" multiple size=8 style=\"width:235px;\" >";
		?>

			<?php
			print "$boat_list";

         //<select name="boats[]" id="boats" multiple size=16>
			?>

      <script>
		var screenwidth = screen.width;
		var screenheight = screen.height;

		if (screenheight < 769){
         document.getElementById('boats').size = '20';
      //alert(screenheight);
		}
		if (screenheight >= 769) {
         document.getElementById('boats').size = '20';
      //alert(screenheight);
		}

      </script>


         <?php
            $options = $reservation->get_boats();
            print $options;
         ?>
         </select><br><br>
      </div>

            <script>
				function validateRadio (radios)
				{
				    for (i = 0; i < radios.length; ++ i)
				    {
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
            </script>


<script>
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
      <div id="reservation-start-input-box-submit">
        <input type="submit" class="btn btn-success btn_custom" value="Search"> 
		</form><br>
		<br>
		<form name="MyForm" id="MyForm" method="get" action="portal.php">
		

		<?php
			if (($_SESSION['uuname'] == "") && ($_SESSION['uupass'] == "")) {
			?>
	<input type="submit" class="btn btn-primary" value="Log In" id="submit">
			<?php
			} else {
			print "<input type=\"image\" src=\"buttons/bt-hm-myprofile.png\" id=\"submit\">&nbsp;&nbsp;<input type=\"image\" src=\"buttons/bt-logout.png\" onclick=\"document.location.href='logout.php';return false;\">";
			}
		?>
		</form>
      </div>
<p>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="img-creditcards.png" width="194" height="62" alt=""/></p>
   </div>
   <!-- end -->
