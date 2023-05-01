<header>
  <div class="navbar-main">
    <div class="inner-left">
      <a href="index.php" class="back-home">
        <img class="logo" src="./poze/logo.png" alt="shop-logo">
      </a>
      <ul class="nav_links">
        <li><a href="cart.php">Cart<sup>
              <?php
              if (isset($_SESSION['user_logged_in'])) {
                $cart_query = mysqli_query($con, "SELECT * FROM `cos` WHERE user_id = $user_id") or die('query failed');
                if (mysqli_num_rows($cart_query) > 0) {
                  $row_cnt = mysqli_num_rows($cart_query);
                  echo $row_cnt;
                }
              } else {
                if (isset($_SESSION['cart'])) {
                  echo count($_SESSION['cart']);
                }
              }
              ?></sup></a></li>
      </ul>
    </div>
    <div class="inner-right">
      <div class="wrapper-search">
        <form action="search_product.php" method="get">
          <input class="search" placeholder="Cauta" type="search" aria-label="Search" name="search_data">
          <input type="submit" value="Search" class="search-button" name="search_data_product">
        </form>
        <?php if (isset($_SESSION['user_logged_in'])) : ?>

          <a href="index.php?logout" class="delete-btn" onclick="return confirm('are you sure you want to logout?')">logout</a>
          <?php if (isset($_SESSION['admin'])) echo "<a href='admin_area/index.php' class='l-button'>Admin Area</a>"; ?>

        <?php else : ?>
          <a href="register.php" class="l-button">Register</a>
          <a href="login.php" class="l-button">Log in</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>