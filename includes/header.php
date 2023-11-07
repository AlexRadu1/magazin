<header class="header">
  <div class="container">
    <button class="nav-toggle" aria-label="open navigation">
      <i class="fa-solid fa-bars hamburger"></i>
    </button>
    <nav class="navbar-main">
      <div class="container">
        <ul class="nav_links">
          <li>
            <div class="label-picture-container">
              <a href="cart.php">
                <div class="icon-padding">
                  <i class="fa-solid fa-cart-shopping"></i>
                  <sup>
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
                    ?>
                  </sup>
                </div>
                <span class="nav-links-label">cart</span>
              </a>
            </div>
          </li>
          <?php if (isset($_SESSION['user_logged_in'])) : ?>
            <?php if (isset($_SESSION['admin'])) echo  "
            <li>
              <div class='label-picture-container'>
                <a href='admin_area/index.php'>
                  <div class='icon-padding'><i class='fa-solid fa-desktop'></i></div>
                  <span class='nav-links-label'>Admin</span>
                </a>
              </div>
            </li>" ?>
            <li>
              <div class="label-picture-container">
                <a href="account.php">
                  <div class="icon-padding"><i class="fa-solid fa-user"></i></div>
                  <span class="nav-links-label">cont</span>
                </a>
              </div>
            </li>
            <li>
              <div class="label-picture-container">
                <a href="?logout" class="" onclick="return confirm('are you sure you want to logout?')">
                  <div class="icon-padding"><i class="fa-solid fa-right-from-bracket"></i></div>
                  <span class="nav-links-label">Logout</span>
                </a>
              </div>
            </li>
          <?php else : ?>
            <li>
              <div class="label-picture-container">
                <a href="register.php">
                  <div class="icon-padding"><i class="fa-solid fa-user-plus"></i></div>
                  <span class="nav-links-label">Register</span>
                </a>
              </div>
            </li>
            <li>
              <a href="login.php">
                <div class="icon-padding"><i class="fa-solid fa-right-to-bracket"></i></div>
                <span class="nav-links-label">login</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
        <ul class="nav_categories">
          <?php
          get_categories();
          ?>
        </ul>
      </div>
    </nav>
    <a href="index.php" class="logo">
      <img src="./poze/logo.png" alt="clothing hanger">
    </a>
    <button name="search_data_product" class="search-trigger"><i class="fa fa-search" aria-hidden="true"></i>
    </button>
  </div>
  <form action="search_product.php" method="get" style="display: none;" class="search-bar">
    <input class="search" placeholder="Cauta" type="search" aria-label="Search" name="search_data">
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