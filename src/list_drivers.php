<?php
require_once '../lib/conn_db.php';
$sortColumn = $_GET['sort'];
$sortOrder = $_GET['order'];
$limit = $_GET['limit'];
$offset = $_GET['offset'] ? $_GET['offset'] : '0';
$search = $_GET['search'];


$sql = "SELECT  (SELECT COUNT(*) FROM drivers) TOTAL \n".
",  D.DRIVER_ID \n".
", D.DRIVER_NAME\n".
" FROM drivers D WHERE 1 = 1 ";
if ($search){
    $sql .= " AND  upper(D.DRIVER_NAME) LIKE UPPER('%$search%')";
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