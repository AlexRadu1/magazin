<?php include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}


if (isset($_POST['save'])) {
  $serie_fact = mysqli_real_escape_string($con, $_POST['serie_fact']);
  $nr_fact = mysqli_real_escape_string($con, $_POST['nr_fact']);
  $furnizor_fact = mysqli_real_escape_string($con, $_POST['furnizor_fact']);
  $data = mysqli_real_escape_string($con, $_POST['data']);
  $tva_fact = mysqli_real_escape_string($con, $_POST['tva_fact']);
  $aviz_fact = mysqli_real_escape_string($con, $_POST['aviz_fact']);
  $pret_total = 0;
  $query_fact = "INSERT INTO intrarifacturi(serie,nr,cod_furnizor,`data`,TVA,nr_aviz,pret_total) VALUES ('$serie_fact','$nr_fact',$furnizor_fact,'$data','$tva_fact','$aviz_fact','0')";
  $result_query_fact = mysqli_query($con, $query_fact);

  $fact_table_id = mysqli_insert_id($con);

  $txtProdus = $_POST['txt_Produs'];
  $txt_Cant = $_POST['txt_Cant'];
  $txt_Marime = $_POST['txt_Marime'];
  $txt_culoare = $_POST['txt_culoare'];
  $txt_Pret = $_POST['txt_Pret'];

  foreach ($txtProdus as $key => $value) {
    $pret_row = $txt_Cant[$key] * $txt_Pret[$key];
    $pret_total += $pret_row;
    $query_produse = "INSERT INTO intrariproduse(cod_factura,cod_produs,cantitate,cod_marime,cod_culoare,pret_unitar) VALUES ('$fact_table_id','" . $value . "','" . $txt_Cant[$key] . "','" . $txt_Marime[$key] . "','" . $txt_culoare[$key] . "','" . $txt_Pret[$key] . "')";
    $result_query_produse = mysqli_query($con, $query_produse);
  }
  mysqli_query($con, "UPDATE intrarifacturi SET pret_total=$pret_total WHERE ID=$fact_table_id");
  foreach ($txtProdus as $key => $value) {
    //attr_produs
    $attr_query = "SELECT * FROM `atribute_produs` WHERE cod_produs=$value AND cod_marime=" . $txt_Marime[$key] . " AND cod_culoare=" . $txt_culoare[$key] . "";
    $attr_result_query = mysqli_query($con, $attr_query);
    if ($attr_rows = mysqli_num_rows($attr_result_query) > 0) {
      while ($rez = mysqli_fetch_assoc($attr_result_query)) {
        $cantitate = $rez['cantitate'] + $txt_Cant[$key];
        $update_query = "UPDATE atribute_produs SET cantitate=$cantitate WHERE cod_produs=$value AND cod_marime=" . $txt_Marime[$key] . " AND cod_culoare=" . $txt_culoare[$key] . "";
        mysqli_query($con, $update_query);
      }
    } else {
      //insert
      $attr_insert_prod = "INSERT INTO atribute_produs(cod_produs,cod_marime,cod_culoare,cantitate) VALUES ('" . $value . "','" . $txt_Marime[$key] . "','" . $txt_culoare[$key] . "','" . $txt_Cant[$key] . "')";
      mysqli_query($con, $attr_insert_prod);
    }
  }
  header("location:view_bills.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="admin_area/css/style.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <title>Document</title>
</head>

<body>
  <?php include("includes/header.php") ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <form method="post" id='factura_box'>
        <h1>Introdu date facutra</h1>
        <div class="date-fact">
          <div class="form-box">
            <label for="product_title" for="serie_fact">Serie factura</label>
            <input type="text" placeholder="Serie..." name="serie_fact" required="required">
          </div>
          <div class="form-box">
            <label for="nr_fact">Numar factura</label>
            <input type="text" placeholder="Numar..." name="nr_fact" required="required">
          </div>
          <div class="form-box">
            <label for="furnizor_fact">Furnizor</label>
            <select name="furnizor_fact" id="">
              <option value="">Select furnizor</option>
              <?php
              $select_query = "SELECT * FROM furnizori";
              $result_query = mysqli_query($con, $select_query);
              while ($row = mysqli_fetch_assoc($result_query)) {
                $category_title = $row['denumire'];
                $category_id = $row['ID'];
                echo "<option value=$category_id>$category_title</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-box">
            <label for="data">Data factura</label>
            <input type="date" name="data" required="required">
          </div>
          <div class="form-box">
            <label for="tva_fact">TVA factura</label>
            <input type="number" placeholder="TVA..." name="tva_fact" required="required">
          </div>
          <div class="form-box">
            <label for="aviz_fact">Numar aviz</label>
            <input type="number" placeholder="Nr aviz..." name="aviz_fact" required="required">
          </div>
        </div>
        <h1>Introdu produse de pe facutra</h1>
        <table id="table_field">
          <thead>
            <th>produs</th>
            <th>cantitate</th>
            <th>marime</th>
            <th>culoare</th>
            <th>pret_unitar</th>
            <th>Add or Remove</th>
          </thead>
          <tbody id="table-body">
            <tr class="table-row">
              <td>
                <select name="txt_Produs[]">
                  <option value="">Select a category</option>
                  <?php
                  $select_query = "SELECT * FROM produse";
                  $result_query = mysqli_query($con, $select_query);
                  while ($row = mysqli_fetch_assoc($result_query)) {
                    $category_title = $row['denumire'];
                    $category_id = $row['ID'];
                    echo "<option value=$category_id>$category_title</option>";
                  }
                  ?>
                </select>
              </td>
              <td><input type="text" name="txt_Cant[]" required="required"></td>
              <td>
                <select name="txt_Marime[]">
                  <option value="">Select a category</option>
                  <?php
                  $select_query = "SELECT * FROM marimi";
                  $result_query = mysqli_query($con, $select_query);
                  while ($row = mysqli_fetch_assoc($result_query)) {
                    $category_title = $row['denumire'];
                    $category_id = $row['ID'];
                    echo "<option value=$category_id>$category_title</option>";
                  }
                  ?>
                </select>
              </td>
              <td>
                <select name="txt_culoare[]">
                  <option value="">Select a category</option>
                  <?php
                  $select_query = "SELECT * FROM culori";
                  $result_query = mysqli_query($con, $select_query);
                  while ($row = mysqli_fetch_assoc($result_query)) {
                    $category_title = $row['denumire'];
                    $category_id = $row['ID'];
                    echo "<option value=$category_id>$category_title</option>";
                  }
                  ?>
                </select>
              </td>
              <td><input type="text" name="txt_Pret[]" required="required"></td>
              <td><input type="button" name="add" id="add" value="Add"></td>
            </tr>
          </tbody>
        </table>
        <div class="form-box">
          <input type="submit" name="save" class="buttons" id="save" value="Save Data">
        </div>
      </form>
    </div>



    <script>
      function loadRow() {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_products.php', true);
        xhr.onload = function() {
          if (this.status == 200) {
            let options = JSON.parse(this.responseText);
            let output = '';
            for (let i in options['prod']) {
              output += '<option value="' + options['prod'][i].ID + '">' + options['prod'][i].denumire + '</option>';
            }
            let output2 = '';
            for (let i in options['marimi']) {
              output2 += '<option value="' + options['marimi'][i].ID + '">' + options['marimi'][i].denumire + '</option>';
            }
            let output3 = '';
            for (let i in options['culori']) {
              output3 += '<option value="' + options['culori'][i].ID + '">' + options['culori'][i].denumire + '</option>';
            }
            let html = ``;
            html += '<tr>' +
              '<td><select name="txt_Produs[]" id="produs_input">' +
              '<option value="">Select a category</option>' + output +
              '</select></td>' +
              '<td><input type="text" name="txt_Cant[]" required="required"></td>' +
              '<td>' +
              '<select name="txt_Marime[]">' +
              '<option value="">Select a category</option>' + output2 +
              '</select>' +
              '</td>' +
              '<td>' +
              '<select name="txt_culoare[]">' +
              '<option value="">Select a category</option>' + output3 +
              '</select>' +
              '</td>' +
              '<td><input type="text" name="txt_Pret[]" required="required"></td>' +
              '<td><input type="button" name="remove" id="remove" value="remove"></td>' +
              '</tr>';
            $('#table-body').append(html);
          }
        }
        xhr.send();
      }

      function getSize() {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_size.php', true);
        xhr.onload = function() {
          if (this.status == 200) {
            var options = JSON.parse(this.responseText);
            console.log(options);
            var output = '';
            // for (let i in options) {
            //   output += '<option value="' + options[i].ID + '">' + options[i].denumire + '</option>';
            // }
          }
        }
        xhr.send();
      }
      $(document).ready(function() {
        $('#add').click(function() {
          loadRow();
        });
        $("#table_field").on('click', '#remove', function() {
          $(this).closest('tr').remove();
        });
      });
    </script>
</body>

</html>