<?php
require_once './lib/conn_db.php';
$journeyId = $_POST['journeyid'];

$sql = "SELECT  (SELECT COUNT(*) FROM JOURNEYS ) TOTAL \n".
", J.JOURNEY_ID AS id \n".
", T.TRAILER_NAME \n".
", D.DRIVER_NAME \n".
", CL.CUSTOMER_NAME \n".
", CL.ORIGIN \n".
", CL.DESTINATION \n".
", CL.DISTANCE \n".
", CL.WEIGTH \n".
" FROM JOURNEYS J, JOURNEY_LOADS JL, CUSTOMER_LOADS CL, DRIVERS D, TRAILERS T WHERE 1 = 1 
 AND J.JOURNEY_ID = JL.JOURNEY_ID AND JL.LOAD_ID = CL.LOAD_ID and J.DRIVER_ID = D.DRIVER_ID 
 AND J.TRAILER_ID = T.TRAILER_ID  AND J.JOURNEY_ID = ?
  ";

  $stmt = mysqli_prepare($conMy, $sql);
  mysqli_stmt_bind_param($stmt, "s", $journeyId);
   mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
    $seq = 0;
    $jsonData= array();
  while ($row = mysqli_fetch_assoc($result)){
      $seq += 1;
      $data['seq'] = $seq;
      $data['customer_name']= $row['CUSTOMER_NAME'];
      $data['origin'] = $row['ORIGIN'];
      $data['destination'] = $row['DESTINATION'];
      $data['distance'] = $row['DISTANCE'];
      $data['weigth'] = $row['WEIGTH'];
      $jsonData[] = $data;
  }
  print json_encode($jsonData);
?>