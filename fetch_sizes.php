<?php
require("includes/connect.php");

$sql = "SELECT * FROM atribute_produs INNER JOIN marimi ON atribute_produs.cod_marime=marimi.ID
  WHERE cod_culoare=" . $_GET['id'] . " AND cod_produs=" . $_GET['prod_id'] . " ";

$result = mysqli_query($con, $sql);

$json = [];
while ($row = mysqli_fetch_assoc($result)) {
  $json[$row['cod_marime']] = [
    $row['denumire'],
    $row['cantitate']
  ];
}

echo json_encode($json);
