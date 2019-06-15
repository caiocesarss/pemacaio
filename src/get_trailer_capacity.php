<?

require_once '../lib/conn_db.php';
$trailerId = $_POST['trailer_id'];
$sql = "SELECT  WEIGTH_CAPACITY FROM TRAILERS WHERE TRAILER_ID = ?";

$stmt = mysqli_prepare($conMy, $sql);
mysqli_stmt_bind_param($stmt, "s", $trailerId);
 mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$weigthCapacity = '0';
while ($row = mysqli_fetch_assoc($result)){
    $weigthCapacity = $row['WEIGTH_CAPACITY'];
}
echo $weigthCapacity;

?>