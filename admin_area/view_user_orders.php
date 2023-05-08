<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin'] == true) {
  header("location:../index.php");
}
if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
  $user_id = $_GET['user_id'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
  <?php include('includes/header.php') ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="order-detail-header">Comenzi user #<?= $_GET['user_id'] ?></div>
      <table id="table_field">
        <thead>
          <th>ID comanda</th>
          <th>Data</th>
          <th>Nume client</th>
          <th>Telefon</th>
          <th>Adresa</th>
          <th>Total comanda</th>
          <th>Status comanda</th>
          <th>Detalii comanda</th>
        </thead>
        <tbody>
          <?php
          $user_query = mysqli_query($con, "SELECT *,co.ID AS id_coamanda FROM clienti cl
          INNER JOIN comenzi co ON cl.ID=co.cod_client
          INNER JOIN comenzi_status cs ON co.cod_status=cs.ID 
          WHERE cod_utilizator=$user_id");
          while ($row = mysqli_fetch_assoc($user_query)) {
          ?>
            <tr>
              <td><?= $row['id_coamanda'] ?></td>
              <td><?= $row['data'] ?></td>
              <td><?= $row['nume'] . " " . $row['prenume'] ?></td>
              <td><?= $row['telefon'] ?></td>
              <td><?= $row['adresa'] . "," . $row['localitate'] /* TODO: si judete */ ?></td>
              <td><?= $row['pret_total'] ?> RON</td>
              <td><?= $row['denumire'] ?></td>
              <td><a href="order_details.php?id_comanda=<?= $row['id_coamanda'] ?>" class="buttons">Detalii</a></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>


    </div>
  </div>
</body>

</html>