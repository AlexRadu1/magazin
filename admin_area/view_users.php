<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin'] == true) {
  header("location:../index.php");
}

if (isset($_GET['action'])) {
  // 0 - user / 1 - admin
  $new_tip = ($_GET['action'] == 0) ? 1 : 0;
  $user_id = $_GET['user_id'];
  mysqli_query($con, "UPDATE utilizatori SET tip='$new_tip' WHERE ID='$user_id'");
  header("location:view_users.php");
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
      <div class="order-detail-header">Useri</div>
      <table id="table_field">
        <thead>
          <th>ID</th>
          <th>Username</th>
          <th>Nume Prenume</th>
          <th>Telefon</th>
          <th>Email</th>
          <th>Adresa</th>
          <th>Actiuni</th>
        </thead>
        <tbody>
          <?php
          $user_query = mysqli_query($con, "SELECT *,utilizatori.ID AS id_user,judete.ID AS id_judet FROM utilizatori INNER JOIN judete ON utilizatori.cod_judet=judete.ID");
          while ($row = mysqli_fetch_assoc($user_query)) {
          ?>
            <tr>
              <td><?= $row['id_user'] ?></td>
              <td><?= $row['username'] ?></td>
              <td><?= $row['nume'] . " " . $row['prenume']  ?></td>
              <td><?= $row['telefon'] ?></td>
              <td><?= $row['email'] ?></td>
              <td><?php echo "{$row['adresa']},{$row['Oras']}" ?></td>
              <td><a href="view_user_orders.php?user_id=<?= $row['id_user'] ?>" class="buttons">Vezi comenzi</a>
                <a href="view_users.php?user_id=<?= $row['id_user'] ?>&action=<?= $row['tip'] ?>" class="buttons" onclick="confirm('Are u sure ?')"><?php echo ($row['tip'] == 0) ? "Turn into admin" : "Turn into user" ?></a>
              </td>
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