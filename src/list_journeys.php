<?php
require_once '../lib/conn_db.php';
$sortColumn = $_GET['sort'];
$sortOrder = $_GET['order'];
$limit = $_GET['limit'];
$offset = $_GET['offset'] ? $_GET['offset'] : '0';
$search = $_GET['search'];


$sql = "SELECT * FROM (SELECT  (SELECT COUNT(*) FROM journeys ) TOTAL \n".
", J.JOURNEY_ID AS id \n".
", T.TRAILER_NAME \n".
", D.DRIVER_NAME \n".
", SUM(CL.DISTANCE) DISTANCE \n".
", SUM(CL.WEIGTH) WEIGTH\n".
" FROM journeys J, journey_loads JL, customer_loads CL, drivers D, trailers T WHERE 1 = 1 
 AND J.JOURNEY_ID = JL.JOURNEY_ID AND JL.LOAD_ID = CL.LOAD_ID and J.DRIVER_ID = D.DRIVER_ID 
 AND J.TRAILER_ID = T.TRAILER_ID GROUP BY J.JOURNEY_ID, T.TRAILER_NAME, D.DRIVER_NAME) T WHERE 1=1 
  ";
if ($search){
    $sql .= " AND  (upper(T.TRAILER_NAME) LIKE UPPER('%$search%') or upper(T.DRIVER_NAME)  LIKE UPPER('%$search%') ) ";
}
if ($sortColumn) {
    $sql .= "order by lpad($sortColumn, 20, ' ') $sortOrder "; 
}
$sql .= " limit $offset , $limit "; 

$result = mysqli_query($conMy, $sql);
while ($row = mysqli_fetch_assoc($result)){
    $rows[] = $row;
    $total = $row['TOTAL'];
}
$result = array();
$result['total'] = $total;
$result['rows']=$rows;

print json_encode($result);
mysqli_close($conMy);
?>