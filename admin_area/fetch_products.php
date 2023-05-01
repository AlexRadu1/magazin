<?php
include("../includes/connect.php");

$select_query = "SELECT * FROM produse";
$result_query = mysqli_query($con, $select_query);
$options['prod'] = array();
$options['marimi'] = array();
$options['culori'] = array();
if ($result_query->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($result_query)) {
    array_push($options['prod'], $row);
  }
}
$select_query = "SELECT * FROM marimi";
$result_query = mysqli_query($con, $select_query);
if ($result_query->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($result_query)) {
    array_push($options['marimi'], $row);
  }
}
$select_query = "SELECT * FROM culori";
$result_query = mysqli_query($con, $select_query);
if ($result_query->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($result_query)) {
    array_push($options['culori'], $row);
  }
}
echo json_encode($options);
