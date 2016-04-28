<?php
session_start();
include "settings.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>Aggressor Safari Lodge</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Custom styles for this template -->
    <link href="assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/af.css">

    <!-- jQuery -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

   <script src="jquery-ui-1.10.3/jquery-1.9.1.js"></script>
   <script src="js/bootstrap.js"></script>
   <link rel="stylesheet" href="jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
   <script src="jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
   <script src="jquery-ui-1.10.3/ui/jquery.ui.widget.js"></script>
   <script src="jquery-ui-1.10.3/ui/jquery.ui.datepicker.js"></script>
   <script src="jquery-ui-1.10.3/ui/jquery.ui.menu.js"></script>
   <script src="jquery-ui-1.10.3/ui/jquery.ui.autocomplete.js"></script>
   <script src="jquery-ui-1.10.3/ui/jquery.ui.dialog.js"></script>
  <script src="js/script.js"></script>


  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>


  
</head>
<body>


  <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
 
        <img src="af-df_hdr_logo.png">

        </div><!--/.nav-collapse -->
      </div>
    </nav>





<div id="dg">
  <div class="container">
    <div class="row">
      <div id="main_element">
        <div class="col-md-4">

			<?php
			$bg = "1";
			include "search.php";
			?>

        </div>
        <div class="col-md-8">
          Ok
        </div>


      </div>
    </div>
  </div>
</div>


 

  </body>
</html>
