<!DOCTYPE html>
<?php include("../includes/connect.php");

if (isset($_POST['save'])) {
  $serie_fact = mysqli_real_escape_string($con, $_POST['serie_fact']);
  $nr_fact = mysqli_real_escape_string($con, $_POST['nr_fact']);
  $furnizor_fact = mysqli_real_escape_string($con, $_POST['furnizor_fact']);
  $data = mysqli_real_escape_string($con, $_POST['data']);
  $tva_fact = mysqli_real_escape_string($con, $_POST['tva_fact']);
  $aviz_fact = mysqli_real_escape_string($con, $_POST['aviz_fact']);

  $query_fact = "INSERT INTO intrarifacturi(serie,nr,cod_furnizor,`data`,TVA,nr_aviz) VALUES ('$serie_fact','$nr_fact',$furnizor_fact,'$data','$tva_fact','$aviz_fact')";
  $result_query_fact = mysqli_query($con, $query_fact);

  $fact_table_id = mysqli_insert_id($con);
  // https://stackoverflow.com/questions/45646866/mysqli-real-escape-string-with-array-in-php
  $txtProdus = $_POST['txt_Produs'];
  $txt_Cant = $_POST['txt_Cant'];
  $txt_Marime = $_POST['txt_Marime'];
  $txt_culoare = $_POST['txt_culoare'];
  $txt_Pret = $_POST['txt_Pret'];

  foreach ($txtProdus as $key => $value) {
    $query_produse = "INSERT INTO intrariproduse(cod_factura,cod_produs,cantitate,cod_marime,cod_culoare,pret_unitar) VALUES ('$fact_table_id','" . $value . "','" . $txt_Cant[$key] . "','" . $txt_Marime[$key] . "','" . $txt_culoare[$key] . "','" . $txt_Pret[$key] . "')";
    $result_query_produse = mysqli_query($con, $query_produse);
  }

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
}

?>
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