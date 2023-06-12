<?php
include('includes/connect.php');
include('functions/function.php');
session_start();

if (isset($_SESSION['user_logged_in'])) {
  $user_id = $_SESSION['user_id'];
  if (isset($_POST['add_to_cart'])) {
    $product_quantity = $_POST['product_quantity'];
    $product_id = $_POST['product_id'];
    $product_size = $_POST['txt_size'];
    $product_color = $_POST['txt_color'];
    $select_add_cart = "SELECT c.user_id,a.ID, a.cod_produs, a.cod_marime, a.cod_culoare
    FROM cos c 
    INNER JOIN atribute_produs a 
    ON c.cod_atribut_produs = a.ID 
    WHERE c.user_id = $user_id AND a.cod_produs=$product_id AND a.cod_marime=$product_size AND a.cod_culoare=$product_color ";
    $query_add_cart = mysqli_query($con, $select_add_cart);
    if (mysqli_num_rows($query_add_cart) > 0) {
      $select_atribut = mysqli_query($con, "SELECT ID FROM atribute_produs WHERE cod_produs=$product_id AND cod_marime=$product_size AND cod_culoare=$product_color");
      $row = mysqli_fetch_assoc($select_atribut);
      $cod_atribut_prod = $row['ID'];
      $id = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM cos WHERE user_id=$user_id AND cod_atribut_produs=$cod_atribut_prod"));
      mysqli_query($con, "UPDATE cos SET quantity=quantity+$product_quantity WHERE ID={$id['ID']}") or die('query failed');
      $message[] = "product already added to cart! Increased quantity by $product_quantity";
    } else {
      $select_atribut = mysqli_query($con, "SELECT ID FROM atribute_produs WHERE cod_produs=$product_id AND cod_marime=$product_size AND cod_culoare=$product_color");
      $row = mysqli_fetch_assoc($select_atribut);
      $cod_atribut_prod = $row['ID'];
      mysqli_query($con, "INSERT INTO `cos`(user_id,cod_atribut_produs,quantity) VALUES ('$user_id',$cod_atribut_prod,'$product_quantity')") or die('query failed');
      $message[] = 'product added to cart !';
    }
  }
} else {
  if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_size = $_POST['txt_size'];
    $product_color = $_POST['txt_color'];
    $product_quantity = $_POST['product_quantity'];
    $select_atribut = mysqli_query($con, "SELECT ID FROM atribute_produs WHERE cod_produs=$product_id AND cod_marime=$product_size AND cod_culoare=$product_color");
    $row = mysqli_fetch_assoc($select_atribut);
    $cod_atribut_prod = $row['ID'];
    if (isset($_SESSION['cart'])) {
      $item_array_atr = array_column($_SESSION['cart'], "cod_atribut_produs");
      if (in_array($cod_atribut_prod, $item_array_atr)) {
        foreach ($_SESSION['cart'] as $key => $cart_item) {
          if ($cart_item['cod_atribut_produs'] == $cod_atribut_prod) {
            $_SESSION['cart'][$key]['quantity'] += $product_quantity;
          }
        }
        $message[] = "product already added to cart! Increased quantity by $product_quantity";
      } else {
        $item_array = array(
          'cod_atribut_produs' => $cod_atribut_prod,
          'product_id' => $_POST['product_id'],
          'quantity' => 1,
          'size' => $_POST['txt_size'],
          'color' => $_POST['txt_color']
        );
        array_push($_SESSION['cart'], $item_array);
        $message[] = 'product added to cart !';
      }
    } else {
      $_SESSION['cart'] = [];
      $item_array = array(
        'cod_atribut_produs' => $cod_atribut_prod,
        'product_id' => $_POST['product_id'],
        'quantity' => 1,
        'size' => $_POST['txt_size'],
        'color' => $_POST['txt_color']
      );
      array_push($_SESSION['cart'], $item_array);
      $message[] = 'product added to cart !';
    }
  }
};
if (isset($_GET['logout'])) {
  session_destroy();
  header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=0">
  <title>Online shop</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
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
      if (isset($message)) {
        foreach ($message as $message) {
          echo "<div class='message' onclick='this.remove();'>" . $message . "</div>";
        }
      }
      view_more();
      get_unique_category_products();
      get_unique_subcategory();
      ?>
    </div>
    </div>
  </section>
  <footer>
    <small>&copy; Copyright <?php echo date("Y") ?>, Bella Glam Chic</small>
  </footer>
  <script>
    const allImages = document.querySelectorAll('.option img');
    const mainImageContainer = document.querySelector('.main_image');

    window.addEventListener('DOMContentLoaded', () => {
      allImages[0].classList.add('active');
    });
    allImages.forEach((image) => {
      image.addEventListener('click', (event) => {
        event.preventDefault()
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

    $(".color-select").change(function() {
      let colorID = $(this).val();
      let prodID = $(this).siblings('.product-id').val();
      if (colorID) {
        $.ajax({
          url: "fetch_sizes.php",
          dataType: 'Json',
          data: {
            'id': colorID,
            'prod_id': prodID
          },
          success: function(data) {
            $('.size-select').empty();
            $.each(data, function(key, value) {
              if (value[1] > 0) {
                $('.size-select').append('<option value="' + key + '">' + value[0] + '</option>');
              } else {
                $('.size-select').append('<option disabled value="' + key + '">' + value[0] + '- Out of stock</option>');
              }
            })
          }
        });
      }
    });

    const imgs = document.querySelectorAll('.img-select a');
    const imgBtns = [...imgs];
    let imgId = 1;

    imgBtns.forEach((imgItem) => {
      imgItem.addEventListener('click', (event) => {
        event.preventDefault();
        imgId = imgItem.dataset.id;
        slideImage();
      });
    });

    function slideImage() {
      const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;

      document.querySelector('.img-showcase').style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
    }

    window.addEventListener('resize', slideImage);
  </script>
</body>

</html>