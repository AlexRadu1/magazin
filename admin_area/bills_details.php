<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}

if (isset($_GET['id_factura'])) {
  $id_comanda = $_GET['id_factura'];
  $f_query = mysqli_query($con, "SELECT *,f.denumire AS nume_furnizor,j.denumire AS nume_judet FROM intrarifacturi i
  INNER JOIN furnizori f ON i.cod_furnizor=f.ID
  INNER JOIN judete j ON f.cod_judet=j.ID
  WHERE i.ID=$id_comanda
  ");
  $furnizor_info = mysqli_fetch_assoc($f_query);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="admin_area/css/style.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
  <?php include("includes/header.php") ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="order-detail-header">Produse factura #<?= $id_comanda ?> </div>
      <div class="info">
        <div class="shipping-info">
          <p>Furnizor: <?= $furnizor_info['nume_furnizor'] ?></p>
          <p>C.I.F.: <?= $furnizor_info['CIF'] ?> </p>
          <p>Sediul: <?= $furnizor_info['adresa'] ?></p>
          <p>Judet: <?= $furnizor_info['nume_judet'] ?></p>
          <p>Cont: <?= $furnizor_info['IBAN'] ?></p>
          <p>Banca: <?= $furnizor_info['Banca'] ?></p>
        </div>
        <div class="shipping-info">
          <h2>Factura fiscala</h2>
          <p>SERIA: <?= $furnizor_info['serie'] ?></p>
          <p>NR. FACTURII: <?= $furnizor_info['nr'] ?></p>
          <p>Data: <?= $furnizor_info['data'] ?></p>
          <p>TVA: <?= $furnizor_info['TVA'] ?></p>
        </div>
      </div>
      <div class="table-container">
        <table id="table_field">
          <thead>
            <th>ID</th>
            <th>Poza</th>
            <th>Nume</th>
            <th>Size</th>
            <th>Color</th>
            <th>Pret unitar</th>
            <th>Cantitate</th>
            <th>Pret total</th>
          </thead>
          <tbody>
            <?php
            $result_query = mysqli_query($con, "SELECT *,p.ID AS produs_ID,p.denumire AS nume_produs,m.denumire AS nume_marime,cl.denumire AS nume_culoare FROM intrariproduse i
            INNER JOIN produse p ON i.cod_produs=p.ID
            INNER JOIN marimi m ON i.cod_marime=m.ID
            INNER JOIN culori cl ON i.cod_culoare=cl.ID
            WHERE cod_factura=$id_comanda");
            $total = 0;
            while ($row = mysqli_fetch_assoc($result_query)) {
            ?>
              <tr>
                <td><?= $row['produs_ID'] ?></td>
                <td><img src="images/<?= $row['produs_imagine1'] ?>" alt=""></td>
                <td><?= $row['nume_produs'] ?></td>
                <td><?= $row['nume_marime'] ?></td>
                <td><?= $row['nume_culoare'] ?></td>
                <td><?= $row['pret_unitar'] ?></td>
                <td><?= $row['cantitate'] ?></td>
                <td><?= $row['pret_unitar'] * $row['cantitate'] ?></td>
              </tr>
            <?php
              $total += $row['pret_unitar'] * $row['cantitate'];
            }
            ?>
            <tr class="table-bottom">
              <td colspan="7" class="total">TOTAL: </td>
              <td><?= $total ?></td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</body>

</html>