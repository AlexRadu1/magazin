<header class="header">
  <div class="container">
    <button class="nav-toggle" aria-label="open navigation">
      <span class="hamburger"></span>
    </button>
    <nav class="navbar-main">
      <ul class="nav_links">
        <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a><sup>
            <?php
            if (isset($_SESSION['user_logged_in'])) {
              $cart_query = mysqli_query($con, "SELECT * FROM `cos` WHERE user_id = $user_id") or die('query failed');
              if (mysqli_num_rows($cart_query) > 0) {
                $prod_cnt = 0;
                while ($row_cnt = mysqli_fetch_assoc($cart_query)) {
                  $prod_cnt += $row_cnt['quantity'];
                }
                echo $prod_cnt;
              }
            } else {
              if (isset($_SESSION['cart'])) {
                $prod_cnt = 0;
                foreach ($_SESSION['cart'] as  $cart_item) {
                  $prod_cnt += $cart_item['quantity'];
                }
                echo $prod_cnt;
              }
            }
            ?></sup></li>
        <?php if (isset($_SESSION['user_logged_in'])) : ?>
          <?php if (isset($_SESSION['admin'])) echo  "<li><a href='admin_area/index.php' class='l-button'>Admin Area</a></li>" ?>
          <li><a href="account.php"><i class="fa-solid fa-user"></i></a></li>
          <li><a href="?logout" class="" onclick="return confirm('are you sure you want to logout?')"><i class="fa-solid fa-right-from-bracket"></i></a></li>
        <?php else : ?>
          <li><a href="register.php" class="l-button"><i class="fa-solid fa-user-plus"></i></a></li>
          <li><a href="login.php" class="l-button"><i class="fa-solid fa-right-to-bracket"></i></a></li>
        <?php endif; ?>
      </ul>
      <ul class="nav_categories">
        <?php
        get_categories();
        ?>
      </ul>
    </nav>
    <a href="index.php" class="logo">
      <img src="./poze/logo.png" alt="clothing hanger">
    </a>
    <button name="search_data_product" class="search-trigger"><i class="fa fa-search" aria-hidden="true"></i>
    </button>
  </div>
  <form action="search_product.php" method="get" style="display: none;" class="search-bar">
    <input class="search" placeholder="Cauta" type="search" aria-label="Search" name="search_data">
    <!-- <button type="submit" name="search_data_product" class="search-button"><i class="fa fa-search" aria-hidden="true"></i></button> -->
  </form>
</header>
<header class="header-large">
  <div class="container">
    <nav class="nav-large d-flex">
      <ul class="d-flex">
        <li>
          <a href="index.php" class="logo">
            <img src="./poze/logo.png" alt="clothing hanger">
          </a>
        </li>
        <li><a href="cart.php" class="nav-button">Cart<sup>
              <?php
              if (isset($_SESSION['user_logged_in'])) {
                $cart_query = mysqli_query($con, "SELECT * FROM `cos` WHERE user_id = $user_id") or die('query failed');
                if (mysqli_num_rows($cart_query) > 0) {
                  $prod_cnt = 0;
                  while ($row_cnt = mysqli_fetch_assoc($cart_query)) {
                    $prod_cnt += $row_cnt['quantity'];
                  }
                  echo $prod_cnt;
                }
              } else {
                if (isset($_SESSION['cart'])) {
                  $prod_cnt = 0;
                  foreach ($_SESSION['cart'] as  $cart_item) {
                    $prod_cnt += $cart_item['quantity'];
                  }
                  echo $prod_cnt;
                }
              }
              ?></sup></a></li>
      </ul>
      <form action="search_product.php" method="get" class="header-large--search d-flex">
        <input class="search" placeholder="Cauta" type="search" aria-label="Search" name="search_data">
      </form>
      <ul class="d-flex">
        <?php if (isset($_SESSION['user_logged_in'])) : ?>
          <?php if (isset($_SESSION['admin'])) echo  "<li><a href='admin_area/index.php' class='nav-button'>Admin Area</a></li>" ?>
          <li><a href="account.php" class="nav-button">Contul meu</a></li>
          <li><a href="?logout" class="nav-button danger" onclick="return confirm('are you sure you want to logout?')">logout</a></li>
        <?php else : ?>
          <li><a href="register.php" class="nav-button">Register</a></li>
          <li><a href="login.php" class="nav-button">Log in</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>