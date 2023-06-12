<?php
include('includes/connect.php');
session_start();

if (isset($_SESSION['user_logged_in'])) {
  $user_id = $_SESSION['user_id'];
  $select_cart_items_query = mysqli_query($con, "SELECT * FROM cos WHERE user_id = $user_id");
  if (mysqli_num_rows($select_cart_items_query) > 0) {
    $check_cart_quantities_query = mysqli_query($con, "SELECT *,c.ID AS cos_id FROM cos c INNER JOIN atribute_produs a ON c.cod_atribut_produs=a.ID");
    while ($row =  mysqli_fetch_assoc($check_cart_quantities_query)) {
      $remove_id = $row['cos_id'];
      if ($row['cantitate'] == 0) {
        mysqli_query($con, "DELETE FROM cos WHERE ID='$remove_id'");
        $message[] = "Din pacate unele obiecte din cos nu mai sunt in stock, cosul a fost modificat!";
      } elseif ($row['cantitate'] < $row['quantity']) {
        mysqli_query($con, "UPDATE cos SET quantity=" . $row['cantitate'] . " WHERE ID='$remove_id'");
        $message[] = "Din pacate unele obiecte din cos nu mai sunt in stock, cosul a fost modificat!";
      }
    }
  }
  if (isset($_POST['update_cart'])) {
    $update_quantity = $_POST['cart_quantity'];
    $update_id = $_POST['cart_id'];
    $size_id = $_POST['size_id'];
    $culoare_id = $_POST['culoare_id'];
    $prod_id = $_POST['prod_id'];
    $check_query = "SELECT * FROM atribute_produs WHERE cod_produs=$prod_id AND cod_marime=$size_id AND cod_culoare=$culoare_id";
    $reuslt_check_query = mysqli_query($con, $check_query);
    $stock_quantity = 0;
    while ($row = mysqli_fetch_assoc($reuslt_check_query)) {
      $stock_quantity = $row['cantitate'];
      if ($stock_quantity >= $update_quantity) {
        mysqli_query($con, "UPDATE cos SET quantity='$update_quantity' WHERE ID='$update_id'") or die('query failed');
        $message[] = 'Cart updated! ';
      } else $message[] = 'Stock insuficient! ';
    }
  }
  if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($con, "DELETE FROM cos WHERE id='$remove_id'") or die('query failed');
    header('location:cart.php');
  }
  if (isset($_GET['delete_all'])) {
    mysqli_query($con, "DELETE FROM cos WHERE user_id='$user_id'") or die('query failed');
    header('location:cart.php');
  }
} else {
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $cart_item) {
      $check_query = "SELECT * FROM atribute_produs WHERE cod_produs=" . $cart_item['product_id'] . " AND cod_marime=" . $cart_item['size'] . " AND cod_culoare=" . $cart_item['color'] . "";
      $reuslt_check_query = mysqli_query($con, $check_query);
      while ($row = mysqli_fetch_assoc($reuslt_check_query)) {
        if ($row['cantitate'] == 0) {
          unset($_SESSION['cart'][$key]);
          $message[] = "Din pacate unele obiecte din cos nu mai sunt in stock, cosul a fost modificat!";
        } elseif ($row['cantitate'] < $cart_item['quantity']) {
          $_SESSION['cart'][$key]['quantity'] = $row['cantitate'];
          $message[] = "Din pacate unele obiecte din cos nu mai sunt in stock, cosul a fost modificat!";
        }
      }
    }
  }
  if (isset($_GET['delete_all'])) {
    unset($_SESSION['cart']);
    header('location:cart.php');
  }
  if (isset($_GET['remove'])) {
    foreach ($_SESSION['cart'] as $key => $cart_item) {
      if ($key == $_GET['remove']) {
        unset($_SESSION['cart'][$key]);
        header('location:cart.php');
      }
    }
  }
  if (isset($_POST['update_cart'])) {
    foreach ($_SESSION['cart'] as $key => $cart_item) {
      if ($cart_item['cod_atribut_produs'] == $_POST['product_id']) {
        $check_query = "SELECT * FROM atribute_produs WHERE cod_produs=" . $cart_item['product_id'] . " AND cod_marime=" . $cart_item['size'] . " AND cod_culoare=" . $cart_item['color'] . "";
        $reuslt_check_query = mysqli_query($con, $check_query);
        $quantity = 0;
        while ($row = mysqli_fetch_assoc($reuslt_check_query)) {
          $quantity = $row['cantitate'];
          if ($quantity >= $_POST['cart_quantity']) {
            $_SESSION['cart'][$key]['quantity'] = $_POST['cart_quantity'];
            $message[] = 'Cart updated! ';
          } else $message[] = 'Stock insuficient! ';
        }
      }
    }
  }
}
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
  <?php include('includes/header.php') ?>
  <div class="shopping-cart">
    <?php
    if (isset($message)) {
      foreach ($message as $message) {
        echo "<div class='message' onclick='this.remove();'>" . $message . "</div>";
      }
    }
    ?>
    <h1 class="heading">shopping cart</h1>

    <table>
      <thead>
        <th>image</th>
        <th>name</th>
        <th>price</th>
        <th>quantity</th>
        <th>total price</th>
        <th>action</th>
      </thead>
      <tbody>
        <?php if (isset($_SESSION['user_logged_in'])) {
          $grand_total = 0;
          $select_cart_query = "SELECT c.ID,c.user_id,c.quantity AS cantitate_cos, a.cod_produs, a.cod_marime, a.cod_culoare,p.denumire AS `name`,p.pret AS `price`,a.cantitate AS `quantity`,p.produs_imagine1 AS `image`,m.denumire AS denumireMarime,f.denumire AS denumireCuloare 
          FROM cos c 
          INNER JOIN atribute_produs a 
          ON c.cod_atribut_produs = a.ID 
          INNER JOIN produse p 
          ON a.cod_produs = p.ID
          INNER JOIN marimi m
          ON a.cod_marime = m.ID
          INNER JOIN culori f
          ON a.cod_culoare = f.ID
          WHERE c.user_id = $user_id";
          $cart_query = mysqli_query($con, $select_cart_query) or die('query failed');
          if (mysqli_num_rows($cart_query) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
        ?>
              <tr>
                <td>
                  <img src="admin_area/images/<?php echo $fetch_cart['image'] ?>" height="100px" alt="">
                </td>
                <td><?php echo "" . $fetch_cart['name'] . "<br>" . $fetch_cart['denumireCuloare'] . "<br>" . $fetch_cart['denumireMarime'] . "" ?></td>
                <td><?php echo $fetch_cart['price'] ?>lei</td>
                <td>
                  <form action="" method="post">
                    <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['ID'] ?>">
                    <input type="hidden" name="size_id" value="<?php echo $fetch_cart['cod_marime'] ?>">
                    <input type="hidden" name="prod_id" value="<?php echo $fetch_cart['cod_produs'] ?>">
                    <input type="hidden" name="culoare_id" value="<?php echo $fetch_cart['cod_culoare'] ?>">
                    <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['cantitate_cos'] ?>">
                    <input type="submit" name="update_cart" value="update" class="option-btn">
                  </form>
                </td>
                <td><?php echo $sub_total = $fetch_cart['price'] * $fetch_cart['cantitate_cos'] ?>lei</td>
                <td><a href="cart.php?remove=<?php echo $fetch_cart['ID'] ?>" class="delete-btn" onclick="return confirm('Remove item from cart?');">Remove</a>
                </td>
              </tr>
          <?php
              $grand_total += $sub_total;
            };
          } else {
            echo "<tr><td colspan='6' style='padding: 20px; text-transform:capitalize;'>no item added</td></tr>";
          };
          ?>
          <tr class="table-bottom">
            <td colspan="4" class="total">TOTAL: </td>
            <td><?php echo $grand_total; ?>lei</td>
            <td><a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled' ?>" onclick="return confirm('Delete all from cart?');">Delete all</a></td>
          </tr>
      </tbody>
    </table>
    <div class="cart-btn">
      <a href="checkout.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled' ?>">proceed to checkout</a>
    </div>
    <?php
          if (isset($_SESSION['grand_total'])) {
            $_SESSION['grand_total'] = $grand_total;
          } else {
            $_SESSION['grand_total'] = $grand_total;
          }
        } else {
          $grand_total = 0;
          if (isset($_SESSION['cart'])) {
            $select_query = "SELECT * FROM `produse`";
            $result_query = mysqli_query($con, $select_query);
            while ($row = mysqli_fetch_assoc($result_query)) {
              foreach ($_SESSION['cart'] as $key => $cart_item) {
                if ($row['ID'] == $cart_item['product_id']) {

    ?>
            <!-- html table body tr here -->
            <tr>
              <td>
                <img src="admin_area/images/<?php echo $row['produs_imagine1'] ?>" height="100px" alt="">
              </td>
              <td><?php
                  $size_query = "SELECT * FROM marimi WHERE ID=" . $cart_item['size'] . "";
                  $marime_result = mysqli_query($con, $size_query);
                  $culoare = "";
                  while ($row_s = mysqli_fetch_assoc($marime_result)) {
                    $culoare = $row_s['denumire'];
                  }
                  $color_query = "SELECT * FROM culori WHERE ID=" . $cart_item['color'] . "";
                  $color_result = mysqli_query($con, $color_query);
                  $size = "";
                  while ($row_q = mysqli_fetch_assoc($color_result)) {
                    $size = $row_q['denumire'];
                  }
                  echo "" . $row['denumire'] . "<br>" . $culoare . "<br>" . $size . "" ?></td>
              <td><?php echo $row['pret'] ?>lei</td>
              <td>
                <form method="post">

                  <input type="hidden" name="product_id" value="<?php echo $cart_item['cod_atribut_produs'] ?>">
                  <input type="number" min="1" name="cart_quantity" value="<?php echo $cant = $cart_item['quantity'] ?>">
                  <input type="submit" name="update_cart" value="update" class="option-btn">
                </form>
              </td>
              <td><?php echo $sub_total = $row['pret'] * $cart_item['quantity'] ?>lei</td>
              <td>
                <a href="cart.php?remove=<?php echo $key ?>" class="delete-btn" onclick="return confirm('Remove item from cart?');">Remove</a>
              </td>
            </tr>
    <?php
                  $grand_total += $sub_total;
                }
              }
            }
          } else {
            echo "<tr><td colspan='6' style='padding: 20px; text-transform:capitalize;'>no item added</td></tr>";
          }
    ?>
    <tr class="table-bottom">
      <td colspan="4" class="total">TOTAL: </td>
      <td><?php echo $grand_total; ?>lei</td>
      <td><a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled' ?>" onclick="return confirm('Delete all from cart?');">Delete all</a></td>
    </tr>
    </tbody>
    </table>
    <div class="cart-btn">
      <a href="checkout.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled' ?>">proceed to checkout</a>
    </div>

  <?php
          if (isset($_SESSION['grand_total'])) {
            $_SESSION['grand_total'] = $grand_total;
          } else {
            $_SESSION['grand_total'] = $grand_total;
          }
        }
  ?>
  </div>
  <footer>
    <small>&copy; Copyright <?php echo date("Y") ?>, Bella Glam Chic</small>
  </footer>
</body>

</html>