<?php
include('includes/connect.php');
include('functions/function.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=0">
  <title>Online shop</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script>
  </script>
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
      <?php
      view_more();
      get_unique_category_products();
      get_unique_subcategory();
      ?>
    </div>
    </div>
  </section>
  <footer>
    <small>&copy; Copyright 2023, Online Shop</small>
  </footer>
  <script>
    const allImages = document.querySelectorAll('.option img');
    const mainImageContainer = document.querySelector('.main_image');

    window.addEventListener('DOMContentLoaded', () => {
      allImages[0].classList.add('active');
    });
    allImages.forEach((image) => {
      image.addEventListener('mouseover', () => {
        mainImageContainer.querySelector('img').src = image.src;
        resetActiveImg();
        image.classList.add('active');
      });
    });

    function resetActiveImg() {
      allHoverImages.forEach((img) => {
        img.classList.remove('active');
      });
    }
  </script>
</body>

</html>