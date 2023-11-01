<?php
include("includes/connect.php");
include('functions/function.php');
session_start();
if (isset($_SESSION['user_logged_in'])) {
  $user_id = $_SESSION['user_id'];
}
$user_data_query = mysqli_query($con, "SELECT * FROM utilizatori WHERE ID=$user_id");
$user_data = mysqli_fetch_assoc($user_data_query);

if (isset($_GET['logout'])) {
  session_destroy();
  header('location:login.php');
}

if (isset($_POST['submit'])) {
  $nume = mysqli_real_escape_string($con, $_POST['nume']);
  $prenume = mysqli_real_escape_string($con, $_POST['prenume']);
  $adresa = mysqli_real_escape_string($con, $_POST['adresa']);
  $judet = mysqli_real_escape_string($con, $_POST['judet']);
  $oras = mysqli_real_escape_string($con, $_POST['oras']);
  $zipcode = mysqli_real_escape_string($con, $_POST['zipcode']);
  $telefon = mysqli_real_escape_string($con, $_POST['telefon']);

  mysqli_query($con, "UPDATE utilizatori SET nume='$nume',prenume='$prenume',adresa='$adresa',cod_judet='$judet',`Oras`='$oras',zipcode='$zipcode',telefon=$telefon WHERE ID=$user_id");

  header("location:account.php");
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
      <div class="account-detail">
        <h1>Welcome <?= $user_data['username'] ?>!</h1>
        <div class="large-columns">
          <div class="fact-info">
            <h2>Date facturare</h2>
            <p>Datele completate mai jos vor fii folosite pentru a finaliza comenzi! </p>
          </div>
          <form action="" method="post">
            <div class="form-row">
              <div class="form-col half">
                <label for="nume">Nume</label>
                <input type="text" value="<?= $user_data['nume'] ?>" name="nume" id="nume">
              </div>
              <div class="form-col half">
                <label for="prenume">Prenume</label>
                <input type="text" value="<?= $user_data['prenume'] ?>" name="prenume" id="prenume">
              </div>
            </div>
            <div class="form-row">
              <div class="form-col">
                <label for="adresa">Adresa</label>
                <textarea name="adresa" id="adresa"><?= $user_data['adresa'] ?></textarea>
              </div>
            </div>
            <div class="form-row">
              <div class="form-col half">
                <label for="judet">Judet</label>
                <select name="judet" id="judet">
                  <?php
                  $cod_judet = $user_data['cod_judet'];
                  $jud_q = mysqli_query($con, "SELECT * FROM judete");
                  while ($row_j = mysqli_fetch_assoc($jud_q)) {
                  ?>
                    <option value="<?= $row_j['ID'] ?>" <?php echo ($cod_judet == $row_j['ID']) ? "selected" : "" ?>><?= $row_j['denumire'] ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
              <div class="form-col half">
                <label for="oras">Oras</label>
                <input type="text" value="<?= $user_data['Oras'] ?>" name="oras" id="oras">
              </div>
            </div>
            <div class="form-row">
              <div class="form-col">
                <label for="zipcode">Cod postal(vezi <a href="https://www.posta-romana.ro/cauta-cod-postal.html" target="_blank" rel="noopener noreferrer">Posta roamana</a> )</label>
                <input type="text" value="<?= $user_data['zipcode'] ?>" name="zipcode" id="zipcode">
              </div>
            </div>
            <div class="form-row">
              <div class="form-col">
                <label for="telefon">Telefon</label>
                <input type="text" value="<?= $user_data['telefon'] ?>" name="telefon" id="telefon">
              </div>
            </div>
            <input type="submit" name="submit" value="Update" class="submit-button">
          </form>
        </div>
      </div>
    </div>
  </section>
  <script src="javascript.js"></script>
</body>

</html>