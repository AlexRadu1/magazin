<?php
include("includes/connect.php");
include('functions/function.php');
session_start();
if (isset($_SESSION['user_logged_in'])) {
  $user_id = $_SESSION['user_id'];
}
if (isset($_GET['id_comanda'])) {
  $id_comanda = $_GET['id_comanda'];
  $result_comanda_q = mysqli_query($con, "SELECT *,cs.denumire AS nume_stat,cs.ID AS status_id,j.denumire AS nume_judet FROM comenzi c
  INNER JOIN clienti cl ON c.cod_client=cl.ID
  INNER JOIN judete j on cl.cod_judet=j.ID
  INNER JOIN comenzi_status cs ON cs.ID=c.cod_status
  WHERE c.ID=$id_comanda");
  $client_info = mysqli_fetch_assoc($result_comanda_q);
}

if (isset($_GET['action']) && $_GET['action'] == 1) {
  $comanda = mysqli_query($con, "SELECT *,c.cantitate AS cantitate_produs FROM comenzi_detalii c 
  INNER JOIN atribute_produs a ON c.cod_atribut_produs=a.ID
  WHERE cod_comanda=$id_comanda");
  while ($row_c = mysqli_fetch_assoc($comanda)) {
    mysqli_query($con, "UPDATE atribute_produs SET cantitate=cantitate+{$row_c['cantitate_produs']} WHERE ID={$row_c['cod_atribut_produs']}");
  }
  mysqli_query($con, "UPDATE comenzi SET cod_status=4 WHERE ID=$id_comanda");
  header("location:order_details.php?id_comanda=$id_comanda");
}
if (isset($_GET['logout'])) {
  session_destroy();
  header('location:login.php');
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
  <?php include("includes/header.php") ?>
  <section class="margin-top">
    <div class="container flex-content">
      <div class="account-menu">
        <ul>
          <li>
            <a href="account.php" class="user-menu-cards">
              <span class="icon">
                <img src="poze/businessman-svgrepo-com.svg" alt="Setari cont" height="30px" width="30px">
              </span>
              <span class="title title-toggle">
                Date personale
              </span>
              <i class="fa-solid fa-caret-right"></i>
            </a>
          </li>
          <li><a href="orders.php" class="user-menu-cards">
              <span class="icon">
                <img src="poze/clipboard-list-svgrepo-com.svg" alt="Lista comenzi" height="30px" width="30px">
              </span>
              <span class="title title-toggle">
                Istoric comenzi
              </span>
              <i class="fa-solid fa-caret-right"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="order-detail">
        <h1 class="order-detail-header">Detalii comanda #<?= $id_comanda ?> </h1>
        <div class="orders-wrapper">
          <table id="table_field">
            <thead>
              <th>Cod produs</th>
              <th>Poza</th>
              <th>Nume produs</th>
              <th>Marime</th>
              <th>Culoare</th>
              <th>Pret unitar</th>
              <th>Cant.</th>
              <th>Pret total</th>
            </thead>
            <tbody>
              <?php
              $result_query = mysqli_query($con, "SELECT *,p.ID AS produs_ID,p.pret AS pret_produs,c.cantitate AS cantitate_produs,c.pret AS pret_row,cl.denumire AS culoare_denumire,m.denumire AS marime_denumire FROM comenzi_detalii c 
            INNER JOIN atribute_produs a ON c.cod_atribut_produs=a.ID
            INNER JOIN marimi m ON a.cod_marime=m.ID
            INNER JOIN culori cl ON a.cod_culoare=cl.ID
            INNER JOIN produse p ON a.cod_produs=p.ID
            WHERE cod_comanda=$id_comanda");
              $total = 0;
              while ($row = mysqli_fetch_assoc($result_query)) {
              ?>
                <tr>
                  <td data-title="Cod produs">
                    <div class="td-wrapper"><?= $row['produs_ID'] ?></div>
                  </td>
                  <td data-title="Poza">
                    <div class="td-wrapper"><img src="admin_area/images/<?= $row['produs_imagine1'] ?>" alt=""></div>
                  </td>
                  <td data-title="Nume produs">
                    <div class="td-wrapper"><a href="product_details.php?product_id=<?= $row['cod_produs'] ?>"><?= $row['denumire'] ?></a></div>
                  </td>
                  <td data-title="Marime">
                    <div class="td-wrapper"><?= $row['marime_denumire'] ?></div>
                  </td>
                  <td data-title="Culoare">
                    <div class="td-wrapper"><?= $row['culoare_denumire'] ?></div>
                  </td>
                  <td data-title="Pret unitar">
                    <div class="td-wrapper"><?= $row['pret_produs'] ?></div>
                  </td>
                  <td data-title="Cant.">
                    <div class="td-wrapper"><?= $row['cantitate_produs'] ?></div>
                  </td>
                  <td data-title="Pret total">
                    <div class="td-wrapper"><?= $row['pret_row'] ?></div>
                  </td>
                </tr>
              <?php
                $total += $row['pret_row'];
              }
              ?>
              <tr class="table-bottom">
                <td colspan="7" class="total align-right">TOTAL: </td>
                <td class="total align-left"><?= $total ?></td>
              </tr>
            </tbody>
          </table>
          <div class="info">
            <div class="shipping-info">
              <h2>Detalii cumparator</h2>
              <p>Nume: <?= $client_info['nume'] . " " . $client_info['prenume'] ?></p>
              <p>Date facturare: <?= $client_info['date_facturare'] ?></p>
            </div>
            <div class="shipping-info">
              <h2>Detalii livrare</h2>
              <p>Adresa Livrare: <?= $client_info['adresa'] . " " . $client_info['localitate'] . ", " . $client_info['nume_judet'] ?></p>
            </div>
            <div class="shipping-info">
              <span><strong>Status comanda:</strong><?= $client_info['nume_stat'] ?> </span>
            </div>
            <?php if (!($client_info['status_id'] == 4 || $client_info['status_id'] == 5)) : ?>
              <a href="order_details?id_comanda=<?= $id_comanda ?>&action=1" class="btn" onclick="return confirm('Are you sure you want to cancel order?')">Anuleaza comanda</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="javascript.js"></script>
</body>

</html>