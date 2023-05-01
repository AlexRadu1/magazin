<?php
include("includes/connect.php");
session_start();
if (isset($_SESSION['grand_total'])) {
  $grand_total = $_SESSION['grand_total'];
}

if (isset($_SESSION['user_logged_in'])) {
  $user_id = $_SESSION['user_id'];
  if (isset($_POST['submit'])) {
    $nume = mysqli_real_escape_string($con, $_POST['name']);
    $prenume = mysqli_real_escape_string($con, $_POST['prenume']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $judet = mysqli_real_escape_string($con, $_POST['judet']);
    $localitate = mysqli_real_escape_string($con, $_POST['localitate']);
    $zip = mysqli_real_escape_string($con, $_POST['zip']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $metoda_plata = $_POST['payment_method'];
    $date_fact = "";
    if ($_POST['date_facturare'] == 1) {
      $row_fact = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM judete WHERE ID='$judet'"));
      $date_fact = "$nume $prenume, $address, " . $row_fact['denumire'] . " , $localitate , $zip";
    } else {
      $nume_fact = mysqli_real_escape_string($con, $_POST['name_fact']);
      $prenume_fact = mysqli_real_escape_string($con, $_POST['prenume_fact']);
      $address_fact = mysqli_real_escape_string($con, $_POST['address_fact']);
      $judet_fact = mysqli_real_escape_string($con, $_POST['judet_fact']);
      $localitate_fact = mysqli_real_escape_string($con, $_POST['localitate_fact']);
      $zip_fact = mysqli_real_escape_string($con, $_POST['zip_fact']);
      $row_fact = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM judete WHERE ID='$judet_fact'"));
      $date_fact = "$nume_fact $prenume_fact, $address_fact," . $row_fact['denumire'] . " ,$localitate_fact ,$zip_fact";
    }
    //insert into clienti
    $insert_clienti = mysqli_query($con, "INSERT INTO `clienti`(nume,prenume,adresa,cod_judet,localitate,zipcode,telefon,date_facturare,cod_utilizator) VALUES ('$nume','$prenume','$address',$judet,'$localitate','$zip','$phone','$date_fact',$user_id)");
    $id_client = mysqli_insert_id($con);
    //insert into comenzi
    $insert_comanda_query = mysqli_query($con, "INSERT INTO comenzi (cod_client,pret_total,cod_status,`data`,metoda_plata) VALUES ('$id_client','$grand_total','1','" . date('Y-m-d H:i:s') . "','$metoda_plata')") or die('query failed');
    $id_comanda = mysqli_insert_id($con);
    //insert into comenzi detalii
    $cart_query = mysqli_query($con, "SELECT * FROM cos INNER JOIN atribute_produs ON cos.cod_atribut_produs=atribute_produs.ID INNER JOIN produse ON atribute_produs.cod_produs=produse.ID WHERE user_id=$user_id") or die('query failed');
    while ($row = mysqli_fetch_assoc($cart_query)) {
      $prod_id = $row['cod_produs'];
      $marime_id = $row['cod_marime'];
      $color_id = $row['cod_culoare'];
      $cantitate  = $row['quantity'];
      $pret_unitar  = $row['pret'];
      $pret =  $cantitate * $pret_unitar;
      $attr_prod = mysqli_query($con, "SELECT * FROM atribute_produs WHERE cod_produs=$prod_id AND cod_marime=$marime_id AND cod_culoare=$color_id") or die('query failed');
      while ($row_attr = mysqli_fetch_assoc($attr_prod)) {
        $cod_atribut_produs = $row_attr['ID'];
        mysqli_query($con, "INSERT INTO comenzi_detalii (cod_comanda,cod_atribut_produs,cantitate,pret) VALUES('$id_comanda','$cod_atribut_produs','$cantitate','$pret')") or die('query failed');
        $cantitate_stoc_actuala = $row_attr['cantitate'];
        $new_cant_stoc = $cantitate_stoc_actuala - $cantitate;
        mysqli_query($con, "UPDATE atribute_produs SET cantitate=$new_cant_stoc WHERE ID=$cod_atribut_produs");
      }
    }
    mysqli_query($con, "DELETE FROM cos WHERE user_id=$user_id");
  }
} else {
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
  <?php include("includes/header.php"); ?>
  <div class="checkout-container">
    <form method="post" class="checkout-form">
      <div class="shipping-details">
        <h2>Detalii livrare</h2>
        <div class="nume-prenume">
          <div class="nume">
            <label for="name">Nume:</label>
            <input type="text" id="name" name="name" required>
          </div>
          <div class="nume">
            <label for="prenume">Prenume:</label>
            <input type="text" id="prenume" name="prenume" required>
          </div>
        </div>
        <label for="phone">Enter your phone number:</label>
        <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>
        <label for="Judet">Judet:</label>
        <select id="Judet" name="judet" required>
          <option value="">Alege un judet</option>
          <?php
          $query = mysqli_query($con, "SELECT * FROM judete");
          while ($row = mysqli_fetch_assoc($query)) {
            echo "<option value=" . $row['ID'] . ">" . $row['denumire'] . "</option>";
          }
          ?>
        </select>
        <div class="nume-prenume">
          <div class="nume">
            <label for="Localitate">Localitate:</label>
            <input type="text" id="Localitate" name="localitate" required>
          </div>
          <div class="nume">
            <label for="zip">Cod poștal:</label>
            <input type="text" id="zip" name="zip" required>
          </div>
        </div>
      </div>
      <div class="billing-details">
        <h2>Date facturare</h2>
        <label for="fact1"><input type="radio" id="fact1" name="date_facturare" value="1" checked>Aceeași ca adresa de livrare</label>
        <label for="fact2"><input type="radio" id="fact2" name="date_facturare" value="2">Adresă de facturare diferită</label>
        <div class="showDiv" style="display: none;">
          <div class="nume-prenume">
            <div class="nume">
              <label for="name_fact">Nume:</label>
              <input type="text" id="name_fact" name="name_fact">
            </div>
            <div class="nume">
              <label for="prenume_fact">Prenume:</label>
              <input type="text" id="prenume_fact" name="prenume_fact">
            </div>
          </div>
          <br>
          <label for="address_fact">Address:</label>
          <input type="text" id="address_fact" name="address_fact"> <br>
          <label for="Judet_fact">Judet:</label>
          <select id="Judet_fact" name="Judet_fact">
            <option value="">Alege un judet</option>
            <?php
            $query = mysqli_query($con, "SELECT * FROM judete");
            while ($row = mysqli_fetch_assoc($query)) {
              echo "<option value=" . $row['ID'] . ">" . $row['denumire'] . "</option>";
            }
            ?>
          </select>
          <br>
          <div class="nume-prenume">
            <div class="nume">
              <label for="Localitate_fact">Localitate:</label>
              <input type="text" id="Localitate_fact" name="Localitate_fact">
            </div>
            <div class="nume">
              <label for="zip_fact">Cod poștal:</label>
              <input type="text" id="zip_fact" name="zip_fact">
            </div>
          </div>
        </div>
      </div>
      <h2>Payment Information</h2>
      <label for="ramburs"><input type="radio" id="ramburs" name="payment_method" value="1" checked>Plata ramburs</label>
      <label for="card"><input type="radio" id="card" name="payment_method" value="2">Plata card</label>
      <div class="showDiv1" style="display: none;">
        <label for="cardnumber">Card Number:</label>
        <input type="text" id="cardnumber" name="cardnumber">
        <br>
        <label for="expirydate">Expiry Date:</label>
        <input type="text" id="expirydate" name="expirydate">
        <br>
        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv">
      </div>
      <input type="submit" name="submit" value="submit">
  </div>
  </form>
  <script>
    $(document).ready(function() {
      $("input[name='date_facturare']").change(function() {
        if ($(this).attr('id') == 'fact2') {
          $('.showDiv').show();
        } else {
          $('.showDiv').hide();
        }
      });
      $("input[name='payment_method']").change(function() {
        if ($(this).attr('id') == 'card') {
          $('.showDiv1').show();
        } else {
          $('.showDiv1').hide();
        }
      })
    });
  </script>
</body>

</html>