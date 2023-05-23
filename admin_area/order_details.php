<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}
if (isset($_GET['id_comanda'])) {
  $id_comanda = $_GET['id_comanda'];
  $result_comanda_q = mysqli_query($con, "SELECT * FROM comenzi c
  INNER JOIN clienti cl ON c.cod_client=cl.ID
  INNER JOIN judete j on cl.cod_judet=j.ID
  WHERE c.ID=$id_comanda");
  $client_info = mysqli_fetch_assoc($result_comanda_q);
  $result_query = mysqli_query($con, "SELECT *,p.ID AS produs_ID,c.cantitate AS cantitate_produs,c.pret AS pret_row,cl.denumire AS culoare_denumire,m.denumire AS marime_denumire FROM comenzi_detalii c 
  INNER JOIN atribute_produs a ON c.cod_atribut_produs=a.ID
  INNER JOIN marimi m ON a.cod_marime=m.ID
  INNER JOIN culori cl ON a.cod_culoare=cl.ID
  INNER JOIN produse p ON a.cod_produs=p.ID
  WHERE cod_comanda=$id_comanda");
}

if (isset($_POST['submit'])) {
  $update_id = $_POST['status_id'];
  $new_comanda_status_id = $_POST['comanda_id'];
  if ($update_id == 4 && $client_info['cod_status'] != 4) {
    while ($row = mysqli_fetch_assoc($result_query)) {
      mysqli_query($con, "UPDATE atribute_produs SET cantitate=cantitate+{$row['cantitate_produs']} WHERE ID={$row['cod_atribut_produs']}");
    }
  }
  if ($client_info['cod_status'] == 4 && $update_id != 4) {
    while ($row = mysqli_fetch_assoc($result_query)) {
      mysqli_query($con, "UPDATE atribute_produs SET cantitate=cantitate-{$row['cantitate_produs']} WHERE ID={$row['cod_atribut_produs']}");
    }
  }
  mysqli_query($con, "UPDATE comenzi SET cod_status=$update_id WHERE ID=$new_comanda_status_id ");
  header("location:order_details.php?id_comanda=$id_comanda");
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
  <?php include("includes/header.php") ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="order-detail-header">Detalii comanda #<?= $id_comanda ?> </div>
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
            $total = 0;
            while ($row = mysqli_fetch_assoc($result_query)) {
            ?>
              <tr>
                <td><?= $row['produs_ID'] ?></td>
                <td><img src="images/<?= $row['produs_imagine1'] ?>" alt=""></td>
                <td><?= $row['denumire'] ?></td>
                <td><?= $row['marime_denumire'] ?></td>
                <td><?= $row['culoare_denumire'] ?></td>
                <td><?= $row['pret_unitar'] ?></td>
                <td><?= $row['cantitate_produs'] ?></td>
                <td><?= $row['pret_row'] ?></td>
              </tr>
            <?php
              $total += $row['pret_row'];
            }
            ?>
            <tr class="table-bottom">
              <td colspan="7" class="total">TOTAL: </td>
              <td><?= $total ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="info">
        <div class="shipping-info">
          <h2>Detalii cumparator</h2>
          <p>Nume: <?= $client_info['nume'] . " " . $client_info['prenume'] ?></p>
          <p>Date facturare: <?= $client_info['date_facturare'] ?></p>
        </div>
        <div class="shipping-info">
          <h2>Detalii livrare</h2>
          <p>Adresa Livrare: <?= $client_info['adresa'] . " " . $client_info['localitate'] . ", " . $client_info['denumire'] ?></p>
        </div>
        <div class="shipping-info">
          <h2>Status comanda</h2>
          <form method="post">
            <select name="status_id">
              <?php
              $select_options = mysqli_query($con, "SELECT * FROM `comenzi_status`");
              while ($select_row = mysqli_fetch_assoc($select_options)) {
              ?>
                <option value="<?= $select_row['ID'] ?>" <?php echo ($client_info['cod_status'] == $select_row['ID']) ? "selected" : "" ?>><?= $select_row['denumire'] ?></option>
              <?php
              }
              ?>
            </select>
            <input type="hidden" value="<?= $id_comanda ?>" name="comanda_id">
            <input type="submit" class="buttons" name="submit" value="Update">
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>