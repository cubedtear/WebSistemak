<?php

function get_location()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/" . $_SERVER["REMOTE_ADDR"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($output, true);

    if ($data["status"] === "success") {
        return $data;
    } else {
        return null;
    }
}

$location = get_location();
if ($location != null) {
    $lat = $location["lat"];
    $lon = $location["lon"];
}

?>

<!DOCTYPE html>
<html>
<head>
    <?php
    require "parts/head.php";

    if ($location != null) { //
        ?>
        <script language="JavaScript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANlADlRBP4NIN17RNrs0ptXdTtz5e11sI"></script>
        <script language="JavaScript">
            function init_map() {
                // Maparen konfigurazioa
                var myOptions = {
                    zoom: 16,
                    center: new google.maps.LatLng(<?= $lat ?>, <?= $lon ?>),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    disableDefaultUI: true
                };
                map = new google.maps.Map(document.getElementById('map'), myOptions);

                marker = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(<?= $lat ?>, <?= $lon ?>)
                });

            }

            google.maps.event.addDomListener(window, 'load', init_map);
        </script>
        <?php
    }
    ?>
<body>
<?php
require "parts/header.php";
?>

<main>
    <div class="container">
        <div class="row">
            <div class="col s6">
                <div class="row">
                    <div class="col s12 center-align">
                        <h1>Credits</h1>
                    </div>
                    <div class="col s12 center-align">
                        <h2>Authors</h2>
                    </div>

                    <div class="col s12 center-align">
                        <ul style="list-style: none">
                            <li style="display: inline-block; padding: 16px; text-align: center; border-right: 1px solid gray">
                                <img src="/img/elena.png"><br>
                                Elena Hernandez <br>
                                <i class="material-icons">phone</i> 666-666-666 <br>
                                <i class="material-icons">email</i> ehernandez035@ikasle.ehu.eus
                            </li>
                            <li style="display: inline-block; padding: 16px; text-align: center">
                                <img src="/img/aritz.png"><br>
                                Aritz Lopez <br>
                                <i class="material-icons">phone</i> 666-666-666 <br>
                                <i class="material-icons">email</i> alopez306@ikasle.ehu.eus
                            </li>
                        </ul>
                    </div>
                    <div class="col s12 center-align">
                        <h2>Speciality</h2>
                        Computation
                    </div>
                </div>
            </div>
            <div class="col s6 center-align">
                <div class="row">
                    <div id="map" style="height: 500px; margin-top: 60px"></div>
                </div>
            </div>
        </div>
    </div>
</main>


<?php
require_once "parts/footer.php";
?>
</body>
</html>
