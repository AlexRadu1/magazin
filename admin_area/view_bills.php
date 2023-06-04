<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin'] == true) {
  header("location:../index.php");
}

$bills_query = mysqli_query($con, "SELECT *,c.ID AS cod_fact
FROM intrarifacturi c
INNER JOIN furnizori f ON c.cod_furnizor=f.ID 
");


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<html>

<body>
  <?php include("includes/header.php") ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="order-detail-header"><a href="insert_bills.php">Adauga factura noua</a></div>
      <div class="orders-wrapper">
        <table id="table_field">
          <thead>
            <th>ID#</th>
            <th>Data</th>
            <th>Serie / Nr</th>
            <th>Furnizor</th>
            <th>Pret total factura</th>
            <th>Produse</th>
          </thead>
          <tbody>
            <?php
            if (mysqli_num_rows($bills_query) ==  0) {
            ?>
              <!-- TODO: move this in the table -->
              <div class='message' onclick='this.remove();'>No results .</div>
              <?php
            } else {
              while ($row = mysqli_fetch_assoc($bills_query)) {
              ?>
                <tr>
                  <td><?= $row['cod_fact'] ?></td>
                  <td><?php echo "" . $row['data'] . " " ?></td>
                  <td><?= $row['serie'] . $row['nr'] ?> </td>
                  <td><?= $row['denumire'] ?></td>
                  <td><?= $row['pret_total'] ?></td>
                  <td><a href="bills_details.php?id_factura=<?= $row['cod_fact'] ?>" class="buttons">Detalii</a></td>
                </tr>
            <?php }
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>