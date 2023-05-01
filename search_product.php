<?php
include('includes/connect.php');
include('functions/function.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online shop</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
</head>

<body>
  <?php include('includes/header.php') ?>
  <section id="main">
    <nav class="sidebar">
      <div class="text">
        Categorii
      </div>
      <ul>
        <?php
        get_categories();
        ?>
      </ul>
    </nav>
    <div class="products">
      <div class="cards-container">
        <?php
        search_product();
        get_unique_category_products();
        get_unique_subcategory();
        ?>
      </div>
    </div>
  </section>
  <footer>
    <?php
    $year = date('Y');
    echo "<small>&copy; Copyright $year, Online Shop</small>"
    ?>
  </footer>
  <script>
    // 
  </script>
</body>

</html>