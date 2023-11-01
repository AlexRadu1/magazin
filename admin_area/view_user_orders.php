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
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
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
              <td data-title="ID comanda">
                <div class="td-wrapper"><?= $row['id_coamanda'] ?></div>
              </td>
              <td data-title="Data">
                <div class="td-wrapper"><?= $row['data'] ?></div>
              </td>
              <td data-title="Nume client">
                <div class="td-wrapper"><?= $row['nume'] . " " . $row['prenume'] ?></div>
              </td>
              <td data-title="Telefon">
                <div class="td-wrapper"><?= $row['telefon'] ?></div>
              </td>
              <td data-title="Adresa">
                <div class="td-wrapper"><?= $row['adresa'] . "," . $row['localitate'] /* TODO: si judete */ ?></div>
              </td>
              <td data-title="Total comanda">
                <div class="td-wrapper"><?= $row['pret_total'] ?> RON</div>
              </td>
              <td data-title="Status comanda">
                <div class="td-wrapper"><?= $row['denumire'] ?></div>
              </td>
              <td data-title="Detalii comanda">
                <div class="td-wrapper"><a href="order_details.php?id_comanda=<?= $row['id_coamanda'] ?>" class="buttons">Detalii</a></div>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>


    </div>
  </div>
  <script src="javascript.js" defer></script>
</body>

</html>