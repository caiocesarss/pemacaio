<?php
require_once '../lib/conn_db.php';
$sortColumn = $_GET['sort'];
$sortOrder = $_GET['order'];
$limit = $_GET['limit'];
$offset = $_GET['offset'] ? $_GET['offset'] : '0';
$search = $_GET['search'];


$sql = "SELECT  (SELECT COUNT(*) FROM CUSTOMER_LOADS) TOTAL \n".
",  CL.LOAD_ID AS id \n".
", CL.CUSTOMER_NAME\n".
", CL.ORIGIN\n".
", CL.DESTINATION\n".
", CL.DISTANCE\n".
", CL.WEIGTH\n".
" FROM CUSTOMER_LOADS CL WHERE 1 = 1 ";
if ($search){
    $sql .= " AND  upper(T.CUSOMER_NAME) LIKE UPPER('%$search%')";
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