<?php
include("../includes/connect.php");


if (isset($_POST['id'])) {
  $id = $_POST['id'];
  mysqli_query($con, "delete from atribute_produs where ID='$id'");
}
