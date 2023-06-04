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
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
  <?php include('includes/header.php') ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="order-detail-header"><a href="manage_product.php">Adauga produs nou</a></div>
      <table id="table_field">
        <thead>
          <th>ID</th>
          <th>Poza</th>
          <th>Nume</th>
          <th>Brand</th>
          <th>Categorie</th>
          <th>Subcategorie</th>
          <th>Culori</th>
          <th>marimi</th>
          <th>Produse in stoc</th>
          <th>Pret produs</th>
        </thead>
        <tbody>
          <?php
          $prod_query = mysqli_query($con, "SELECT *,p.ID AS id_produs,p.denumire AS nume_produs,b.denumire AS nume_brand,c.denumire AS nume_categorie,s.denumire AS nume_subcategorie FROM produse p
          INNER JOIN branduri b ON b.ID=p.cod_brand
          INNER JOIN categorii c ON c.ID=p.cod_categorie
          INNER JOIN subcategorii s ON s.ID=cod_subcategorie
          ");
          while ($row = mysqli_fetch_assoc($prod_query)) {
          ?>
            <tr>
              <td><a href="manage_product.php?id=<?= $row['id_produs'] ?>" class="buttons"><?= $row['id_produs'] ?></a></td>
              <td><img src="images/<?= $row['produs_imagine1'] ?>" alt=""></td>
              <td><?= $row['nume_produs'] ?></td>
              <td><?= $row['nume_brand'] ?></td>
              <td><?= $row['nume_categorie'] ?></td>
              <td><?= $row['nume_subcategorie'] ?></td>
              <td><?php
                  $select_color = mysqli_query($con, "SELECT * FROM atribute_produs 
                  INNER JOIN culori ON atribute_produs.cod_culoare=culori.ID
                  WHERE cod_produs={$row['id_produs']} GROUP BY cod_culoare");
                  $culori = '';
                  $i = 1;
                  while ($row_color = mysqli_fetch_assoc($select_color)) {
                    $culori .= "$i. {$row_color['denumire']}<br>";
                    $i++;
                  }
                  echo $culori;
                  ?></td>
              <td><?php
                  $select_size = mysqli_query($con, "SELECT * FROM atribute_produs 
                  INNER JOIN marimi ON atribute_produs.cod_marime=marimi.ID
                  WHERE cod_produs={$row['id_produs']} AND cantitate != 0 GROUP BY cod_marime");
                  $marimi = '';
                  while ($row_size = mysqli_fetch_assoc($select_size)) {
                    $marimi .= "{$row_size['denumire']}/";
                  }
                  echo rtrim($marimi, "/");
                  ?></td>
              <td>
                <?php
                $select_stoc = mysqli_query($con, "SELECT * FROM atribute_produs WHERE cod_produs={$row['id_produs']}");
                $stoc = 0;
                while ($row_stoc = mysqli_fetch_assoc($select_stoc)) {
                  $stoc += $row_stoc['cantitate'];
                }
                echo $stoc;
                ?>
              </td>
              <td><?= $row['pret'] ?></td>
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