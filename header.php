<?php
session_start();
?>

<html>
<head>
<title>Aggressor Fleet Online Reservation System</title>

<script type="text/JavaScript">
var screenwidth = screen.width;
var screenheight = screen.height;

//alert("Width: "+screenwidth + " Height: "+screenheight);

if (screenwidth < 1281) {
        document.write('<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />'); <?php // was style2 ?>
		//alert(screenheight);
}
//if ((screenheight > 769) && (screenwidth < 1025)) {
//        document.write('<link rel="stylesheet" href="style2.css" type="text/css" media="screen" />');
//}
if (screenwidth > 1280) {
        document.write('<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />');
		//alert(screenheight);
}

</script>




  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>



	<script type="text/javascript">
		var RecaptchaOptions = {
			theme : 'custom',
			custom_theme_widget: 'recaptcha_widget'
		};
	</script>

  <script>
  $(function() {
    $( document ).tooltip();
  });
  </script>
  <style>
  label {
    display: inline-block;
    width: 5em;
  }
  </style>


<script>


<?php
            switch ($_SESSION['contact_type']) {
               case "consumer":
					$max1 = "72M";
               break;

               case "reseller_manager":
               case "reseller_agent":
               case "reseller_third_party":
					$max1 = "6M";
					$max2 = "12M";
					break;

					default:
					$max1 = "72M";
					$max2 = "72M";
					break;
				}
?>

   $(function() {
	   $( "#start_date" ).datepicker({ 
			dateFormat: "dd-M-yy",
			changeMonth: true,
			changeYear: true,
			minDate: 0, 
			maxDate: "+<?=$max1;?>",
				onSelect: function (date) {
					var date1 = $('#start_date').datepicker('getDate');
					date1.setDate(date1.getDate() + 10);
            	var date2 = $('#start_date').datepicker('getDate');
	            date2.setDate(date2.getDate() + 182);
   	         $('#end_date').datepicker('setDate', date2);
      	      //sets minDate to dt1 date + 1
         	   $('#end_date').datepicker('option', 'minDate', date1);
				} 
		});
      $( "#end_date" ).datepicker({ 
			dateFormat: "dd-M-yy",
         changeMonth: true,
         changeYear: true,
			minDate: "+10D", 
			maxDate: "+<?=$max2;?>",
			onClose: function () {
         	var dt1 = $('#start_date').datepicker('getDate');
            var dt2 = $('#end_date').datepicker('getDate');
            //check to prevent a user from entering a date below date of dt1
            if (dt2 <= dt1) {
                var minDate = $('#end_date').datepicker('option', 'minDate');
                $('#end_date').datepicker('setDate', minDate);
            }
        } 
		});


      $( "#flight_date" ).datepicker({ 
         dateFormat: "dd-M-yy",
         changeMonth: true,
         changeYear: true,
         minDate: 0, 
         maxDate: "+99M"
      });


	});


</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-2377054-1', 'auto');
  ga('send', 'pageview');

</script>


</head>

<body>






<div id="page-header">

  <div id="logo_start">
	<img src="af-df_hdr_logo.png" align="left" alt=""/>
	  <br>
    <span class="details-title-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+1-706-993-2531</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:info@aggressor.com"><span class="details-title-text">info@aggressor.com</span></a>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(USA &amp; CAN) <span class="details-title-text">1-800-348-2628</span>
  </div>
</div>
