<?php
require_once ('./lib/conn_db.php');

$loadIds = $_GET['loadids'];
$created = false;
$trailerId = $_GET['trailerid'];
$driverId = $_GET['driver'];
if (!$trailerId || !$driverId) {
    $created = false;
} else {
    $sql = "INSERT INTO journeys (TRAILER_ID, DRIVER_ID) VALUES (?,?)";
    $stmt = mysqli_prepare($conMy, $sql);
    mysqli_stmt_bind_param($stmt, "dd", $trailerId, $driverId);
    mysqli_stmt_execute($stmt) or die(mysqli_error($conMy));
    $journeyId = mysqli_insert_id($conMy);

    $loadIds = explode(',', $loadIds);
    foreach ($loadIds as $loadId) {
      
        $sql = "INSERT INTO journey_loads (JOURNEY_ID, LOAD_ID) VALUES (?,?)";
        $stmt = mysqli_prepare($conMy, $sql);
        mysqli_stmt_bind_param($stmt, "dd", $journeyId, $loadId);
        mysqli_stmt_execute($stmt) or die(mysqli_error($conMy));
    }
    $created = true;
}

if ($created) {
    $sql = "SELECT j.journey_id, t.trailer_name, d.driver_name, sum(cl.distance) distance, sum(cl.weigth) weigth
            FROM journeys j, journey_loads jl, customer_loads cl, drivers d, trailers t 
            where j.journey_id = jl.journey_id and jl.load_id = cl.load_id
            and j.driver_id = d.driver_id
            and j.trailer_id = t.trailer_id
            and j.journey_id =  ?
            group by j.journey_id, t.trailer_name, d.driver_name";

    $stmt = mysqli_prepare($conMy, $sql);
    mysqli_stmt_bind_param($stmt, "s", $journeyId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)){
        $driverName = $row['driver_name'];
        $trailerName = $row['trailer_name'];
        $distance = $row['distance'];
        $totalWeigth = $row['weigth'];
    }


    $txtResult = "Successfull! Joruney Created.";
    $class = "";
}
?>
<div class="col-md-12">
    <h3><?echo $txtResult?></h3>
    <h4>Details:</h4>
    <p><b>Driver Name:</b> <?echo $driverName?></p>
    <p><b>Trailer Name:</b> <?echo $trailerName?></p>
    <p><b>Distance:</b> <?echo $distance?> Km</p>
    <p><b>Total Weigth:</b> <?echo $totalWeigth?> Kg</p>
</div>

