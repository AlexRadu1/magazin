<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin'] == true) {
  header("location:../index.php");
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
      <div class="order-detail-header">Useri</div>
      <table id="table_field">
        <thead>
          <th>ID</th>
          <th>Nume</th>
          <th>Prenume</th>
          <th>Telefon</th>
          <th>Adresa</th>
          <th>Lista comenzi</th>
        </thead>
        <tbody>
          <?php
          $user_query = mysqli_query($con, "SELECT *,utilizatori.ID AS id_user,judete.ID AS id_judet FROM utilizatori INNER JOIN judete ON utilizatori.cod_judet=judete.ID");
          while ($row = mysqli_fetch_assoc($user_query)) {
          ?>
            <tr>
              <td><a href="manage_user.php" class="buttons"><?= $row['id_user'] ?></td>
              <td><?= $row['nume'] ?></td>
              <td><?= $row['prenume'] ?></td>
              <td><?= $row['telefon'] ?></td>
              <td><?php echo "{$row['adresa']},{$row['adresa']}" ?></td>
              <td><a href="view_user_orders.php?user_id=<?= $row['id_user'] ?>" class="buttons">Vezi comenzi</a></td>
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