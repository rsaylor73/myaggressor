<?php
session_start();
?>

<html>
<head>
<title>Aggressor Fleet and Dancer Fleet Online Reservation System</title>

<script>
window.location.hash="no-back-button";
window.location.hash="Again-No-back-button";//again because google chrome don't insert first hash into history
window.onhashchange=function(){window.location.hash="no-back-button";}
</script> 
<meta http-equiv="X-UA-Compatible" content="IE=8">
<script type="text/JavaScript">
var screenwidth = screen.width;
var screenheight = screen.height;

//alert("Width: "+screenwidth + " Height: "+screenheight);

if (screenwidth < 1281) {
        document.write('<link rel="stylesheet" href="css/style2.css" type="text/css" media="screen" />');
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


   <link rel="stylesheet" href="js/jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
   <script src="js/jquery-ui-1.10.3/jquery-1.9.1.js"></script>
   <script src="js/jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
   <script src="js/jquery-ui-1.10.3/ui/jquery.ui.widget.js"></script>
   <script src="js/jquery-ui-1.10.3/ui/jquery.ui.datepicker.js"></script>
   <link rel="stylesheet" href="js/jquery-ui-1.10.3/demos/demos.css">


	<script type="text/javascript">
		var RecaptchaOptions = {
			theme : 'custom',
			custom_theme_widget: 'recaptcha_widget'
		};
	</script>
<script>

   $(function() {
	   $( "#start_date" ).datepicker({ 
			dateFormat: "dd-M-yy",
			minDate: 0, 
			maxDate: "+36M",
				onSelect: function (date) {
            	var date2 = $('#start_date').datepicker('getDate');
	            date2.setDate(date2.getDate() + 7);
   	         $('#end_date').datepicker('setDate', date2);
      	      //sets minDate to dt1 date + 1
         	   $('#end_date').datepicker('option', 'minDate', date2);
				} 
		});
      $( "#end_date" ).datepicker({ 
			dateFormat: "dd-M-yy",
			minDate: "+10D", 
			maxDate: "+36M",
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

	});


</script>

</head>

<body>
