<?php
require_once '../lib/conn_db.php';
$sortColumn = $_GET['sort'];
$sortOrder = $_GET['order'];
$limit = $_GET['limit'] ? $_GET['limit'] : '25';
$offset = $_GET['offset'] ? $_GET['offset'] : '0';
$search = $_GET['search'];
$weightCapacity = $_GET['weigth_capacity'];
if ($weightCapacity){
    $sqlW .= " AND T.WEIGTH_CAPACITY >= $weightCapacity ";
}

$sql = "SELECT  (SELECT COUNT(*) FROM trailers T WHERE 1=1 $sqlW) TOTAL \n".
",  T.TRAILER_ID as id \n".
", T.TRAILER_NAME \n".
", T.WEIGTH_CAPACITY \n".
" FROM trailers T WHERE 1 = 1 ";
$sql .= $sqlW;
if ($search){
    $sql .= " AND  upper(T.TRAILER_NAME) LIKE UPPER('%$search%')";
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