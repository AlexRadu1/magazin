<?php
include("includes/connect.php");
include('functions/function.php');
session_start();
if (isset($_SESSION['user_logged_in'])) {
  $user_id = $_SESSION['user_id'];
}
$orders_query = mysqli_query($con, "SELECT *,s.ID AS cod_status,c.ID AS cod_comanda,CONCAT(nume,' ',prenume) AS 'fullName' 
FROM comenzi c 
INNER JOIN clienti ON c.cod_client=clienti.ID 
INNER JOIN comenzi_status s ON c.cod_status=s.ID
WHERE cod_utilizator=$user_id
ORDER BY c.ID DESC");
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
  <title>Istoric comenzi</title>
  <link rel="icon" type="image/x-icon" href="poze/logo.png">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<html>

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
              <span class="title">
                Date personale
              </span>
              <i class="fa-solid fa-caret-right"></i>
            </a>
          </li>
          <li><a href="orders.php" class="user-menu-cards">
              <span class="icon">
                <img src="poze/clipboard-list-svgrepo-com.svg" alt="Lista comenzi" height="30px" width="30px">
              </span>
              <span class="title">
                Istoric comenzi
              </span>
              <i class="fa-solid fa-caret-right"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="order-detail">
        <h1 class="order-detail-header">Comenzi</h1>
        <div class="orders-wrapper">
          <table id="orders_table">
            <thead>
              <th>Comanda</th>
              <th>Data</th>
              <th>Valoare</th>
              <th class="desktop-show">Adresa</th>
              <th class="desktop-show">Metoda plata</th>
              <th class="desktop-show md-screen">Status Comanda</th>
              <th class="desktop-show">Detalii comanda</th>
            </thead>
            <tbody>
              <?php
              if (mysqli_num_rows($orders_query) ==  0) {
              ?>
                <div class='message' onclick='this.remove();'>No results .</div>
                <?php
              } else {
                while ($row = mysqli_fetch_assoc($orders_query)) {
                ?>
                  <tr>
                    <td><a href="order_details.php?id_comanda=<?= $row['cod_comanda'] ?>" class="btn"><?php echo "" . $row['cod_comanda'] . " " ?></a> </td>
                    <td><?php
                        $sqlDate = $row['data'];
                        $ftm = new IntlDateFormatter('ro_RO', IntlDateFormatter::GREGORIAN, IntlDateFormatter::GREGORIAN);
                        $timestamp = strtotime($sqlDate);
                        $formattedDate = $ftm->format($timestamp);
                        echo substr($formattedDate, 0, -7);
                        ?></td>
                    <td><?= $row['pret_total'] . "RON" ?></td>
                    <td class="desktop-show"><?php
                                              $jud = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM judete WHERE ID=" . $row['cod_judet'] . ""));
                                              $judet = $jud['denumire'];
                                              echo "{$row['adresa']} {$row['localitate']} $judet <br> {$row['zipcode']}" ?></td>
                    <td class="desktop-show"><?= $row['metoda_plata'] ?></td>
                    <td class="desktop-show md-screen"><span class="<?= $row['denumire'] ?>"><?= $row['denumire'] ?></span></td>
                    <td class="desktop-show"><button class="btn" data-modal-target="#modal<?= $row['cod_comanda'] ?>">Quick view</button></td>
                  </tr>
                  <div class="modal" id="modal<?= $row['cod_comanda'] ?>">
                    <div class="modal-header">
                      <div class="title">Comanda nr. <?= $row['cod_comanda'] ?></div>
                      <button data-close-button class="close-button">&times;</button>
                    </div>
                    <div class="modal-body">
                      <section>
                        <h3>Produse in comanda</h3>
                        <div class="table">
                          <div class="table-row">
                            <div class="table-cell">
                              Poza
                            </div>
                            <div class="table-cell">
                              Nume Produs
                            </div>
                            <div class="table-cell">
                              Pret unitar
                            </div>
                            <div class="table-cell">
                              Cantitate
                            </div>
                            <div class="table-cell">
                              Pret total
                            </div>
                          </div>
                          <?php
                          $comenzi_detalii_q = mysqli_query($con, "SELECT *,p.pret AS pret_produs,c.cantitate AS cantitate_produs,c.pret AS pret_row FROM comenzi_detalii c 
              INNER JOIN atribute_produs a ON c.cod_atribut_produs=a.ID
              INNER JOIN produse p ON a.cod_produs=p.ID
              WHERE cod_comanda=" . $row['cod_comanda'] . "");

                          while ($table_row = mysqli_fetch_assoc($comenzi_detalii_q)) {
                          ?>
                            <div class="table-row">
                              <div class="table-cell">
                                <img src="admin_area/images/<?= $table_row['produs_imagine1'] ?>" height="80px" width="80px">
                              </div>
                              <div class="table-cell">
                                <a href="product_details.php?product_id=<?= $table_row['cod_produs'] ?>"><?= $table_row['denumire'] ?> </a>
                              </div>
                              <div class="table-cell">
                                <?= $table_row['pret_produs'] ?>
                              </div>
                              <div class="table-cell">
                                <?= $table_row['cantitate_produs'] ?>
                              </div>
                              <div class="table-cell">
                                <?= $table_row['pret_row'] ?>
                              </div>
                            </div>

                          <?php
                          }
                          ?>
                        </div>
                      </section>
                      <section>
                        <h3>Detalii cumparator</h3>
                        <div class="detalii-order">
                          <p>Nume: <?php echo "" . $row['nume'] . " " . $row['prenume'] . "" ?></p>
                          <p>Adresa facturare: <?php
                                                $arr = explode(",", $row['date_facturare'], 2);
                                                $first = $arr[1];
                                                echo $first
                                                ?></p>
                          <p>Nr.telefon: <?= $row['telefon'] ?></p>
                        </div>
                      </section>
                      <section>
                        <h3>Detalii livrare</h3>
                        <div class="detalii-order">
                          <p>Adresa livrare: <?php
                                              $jud = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM judete WHERE ID=" . $row['cod_judet'] . ""));
                                              $judet = $jud['denumire'];
                                              echo "" . $row['adresa'] . " " . $row['localitate'] . "$judet" ?></p>
                        </div>
                      </section>
                    </div>
                <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
  <div id="overlay"></div>
  <script src="javascript.js"></script>
</body>

</html>