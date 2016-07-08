<?php
if ($map == "ok") {

//print "<pre>";
//print_r($_SESSION);
//print "</pre>";

// find future reservations
$today = date("Ymd");
$sql4 = "
SELECT
	`inventory`.`reservationID`,
	DATE_FORMAT(`charters`.`start_date`, '%m/%d/%Y') AS 'start_date',
	`destinations`.`latitude`,
	`destinations`.`longitude`,
	`boats`.`name`

FROM
	`inventory`,`charters`,`boats`,`destinations`
WHERE
	`inventory`.`passengerID` = '$_SESSION[contactID]'
	AND `inventory`.`charterID` = `charters`.`charterID`
	AND `charters`.`start_date` > '$today'
	AND `charters`.`boatID` = `boats`.`boatID`
	AND `charters`.`destinationID` = `destinations`.`destinationID`
";
$result4 = $this->new_mysql($sql4);
while ($row4 = $result4->fetch_assoc()) {
	$markers .= "
    {
        \"title\": '$row4[name]',
        \"lat\": '$row4[latitude]',
        \"lng\": '$row4[longitude]',
        \"description\": '$row4[name] Confirmation #$row4[reservationID] starting $row4[start_date]',
        \"icon\": \"FlagGreen.png\"
    },
	";

}

// find past
$sql4 = "
SELECT
    `inventory`.`reservationID`,
    DATE_FORMAT(`charters`.`start_date`, '%m/%d/%Y') AS 'start_date',
    `destinations`.`latitude`,
    `destinations`.`longitude`,
    `boats`.`name`

FROM
    `inventory`,`charters`,`boats`,`destinations`
WHERE
    `inventory`.`passengerID` = '$_SESSION[contactID]'
    AND `inventory`.`charterID` = `charters`.`charterID`
    AND `charters`.`start_date` < '$today'
    AND `charters`.`boatID` = `boats`.`boatID`
    AND `charters`.`destinationID` = `destinations`.`destinationID`
";
$result4 = $this->new_mysql($sql4);
while ($row4 = $result4->fetch_assoc()) {
    $markers .= "
    {
        \"title\": '$row4[name]',
        \"lat\": '$row4[latitude]',
        \"lng\": '$row4[longitude]',
        \"description\": '$row4[name] Past Trip Confirmation #$row4[reservationID] starting $row4[start_date]',
        \"icon\": \"FlagRed.png\"
    },
    ";

}

?>

<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    var markers = [
	 <?php print "$markers"; ?>

<?php
/*
    {
        "title": 'Bahamas',
        "lat": '25.022566',
        "lng": '-77.365723',
        "description": 'Bahamas Aggressor',
        "icon": "red-circle.png"
    },
    {
        "title": 'Sri Lanka',
        "lat": '6.903705',
        "lng": '79.870605',
        "description": 'Sri Lanka Aggressor',
        "icon": "blu-circle.png"
    },
    {
        "title": 'Galapagos',
        "lat": '-0.544730',
        "lng": '-90.862427',
        "description": 'Galapagos Aggressor',
        "icon": "red-circle.png"
    },
    {
        "title": 'Kona',
        "lat": '19.631486',
        "lng": '-155.994186',
        "description": 'Kona Aggressor',
        "icon": "red-circle.png"
    },
*/
?>
    ];
    window.onload = function () {
        LoadMap();
    }
    function LoadMap() {
        var mapOptions = {
            center: new google.maps.LatLng(0,40),
            zoom: 2,
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
 
        //Create and open InfoWindow.
        var infoWindow = new google.maps.InfoWindow();
 
        for (var i = 0; i < markers.length; i++) {
            var data = markers[i];
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
            var iconBase = 'https://www.liveaboardfleet.net/admin/google/markers/';
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: data.title,
                icon: iconBase + data.icon
            });
 
            //Attach click event to the marker.
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
                    infoWindow.setContent("<div style = 'width:200px;min-height:20px'>" + data.description + "</div>");
                    infoWindow.open(map, marker);
                });
            })(marker, data);
        }
    }
</script>

<div id="dvMap" style="width: <?=$width;?>px; height: <?=$height;?>px"></div>


<?php
}
