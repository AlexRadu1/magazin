<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
  <?php include("includes/header.php") ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <?php
      if (isset($_GET['insert_bills'])) {
        include("insert_bills.php");
      }
      if (isset($_GET['add_size'])) {
        include("add_size.php");
      }
      if (isset($_GET['add_colors'])) {
        include("add_colors.php");
      }
      ?>
    </div>
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