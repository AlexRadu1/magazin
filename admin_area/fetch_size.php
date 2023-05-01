<?php
include("../includes/connect.php");
$select_query = "SELECT * FROM marimi";
$result_query = mysqli_query($con, $select_query);
$options = array();
if ($result_query->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($result_query)) {
    array_push($options, $row);
  }
  echo json_encode($options);
}
