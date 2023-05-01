<?php
include("../includes/connect.php");
session_start();
if (isset($_SESSION['admin']) && $_SESSION['admin'] != '') {
} else {
  header('location:login.php');
  die();
}

$category_id = $_POST['category_id'];
$sub_cat_id = $_POST['sub_cat_id'];
$q = mysqli_query($con, "SELECT * FROM subcategorii WHERE cod_categorie=$category_id");
if (mysqli_num_rows($q) > 0) {
  $html = '';
  while ($row = mysqli_fetch_assoc($q)) {
    if ($sub_cat_id == $row['ID']) {
      $html .= "<option value=" . $row['ID'] . " selected>" . $row['denumire'] . "</option>";
    } else {
      $html .= "<option value=" . $row['ID'] . ">" . $row['denumire'] . "</option>";
    }
  }
  echo $html;
} else {
  echo "<option value=''>No sub categories found</option>";
}
