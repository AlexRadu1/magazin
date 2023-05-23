<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}
if (isset($_GET['remove']) && $_GET['remove'] != '') {
  $remove_id = $_GET['remove'];
  mysqli_query($con, "DELETE FROM culori WHERE ID=$remove_id");
  header("location:view_colors.php");
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
      <div class="order-detail-header"><a href="manage_colors.php">Adauga culoare noua</a></div>
      <table id="table_field">
        <thead>
          <th>ID</th>
          <th>Nume</th>
          <th>Actiuni</th>
        </thead>
        <tbody>
          <?php
          $q = mysqli_query($con, "SELECT * FROM culori");
          while ($row = mysqli_fetch_assoc($q)) {
          ?>
            <tr>
              <td><?= $row['ID'] ?></td>
              <td><?= $row['denumire'] ?></td>
              <td>
                <a href="manage_colors.php?id=<?= $row['ID'] ?>" class="buttons">Edit</a>
                <a href="?remove=<?= $row['ID'] ?>" class="buttons danger" onclick="return confirm('Are you sure you want to delete <?= $row['denumire'] ?>')">Remove</a>
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