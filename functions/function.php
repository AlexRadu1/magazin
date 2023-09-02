<?php
include('./includes/connect.php');


function popup_card_product()
{
  global $con;
  if (!isset($_GET['category'])) {
    if (!isset($_GET['subcategory'])) {
      $select_query = "SELECT * FROM `produse`";
      $result_query = mysqli_query($con, $select_query);
      while ($row = mysqli_fetch_assoc($result_query)) {
        $product_id = $row['ID'];
        $product_title = $row['denumire'];
        $product_image1 = $row['produs_imagine1'];
        $product_price  = $row['pret'];
        echo "
        <form method='post' class='form-card'>
          <div class='product'>
            <div class='product-card'>
              <h2 class='product-name'>$product_title</h5>
              <span class='product-price'>$product_price lei</span>
              <a class='popup-btn disabled'>Add to cart</a>
              <a href='product_details.php?product_id=$product_id'><img src='./admin_area/images/$product_image1' alt='$product_title' class='product-img'></a>
            </div>
            <div class='popup-view'>
              <div class='popup-card'>
                <a href=''><i class='fas fa-times close-btn'></i></a>
                <div class='product-img'>
                  <img src='./admin_area/images/$product_image1' alt='$product_title'>
                </div>
                <div class='info'>
                  <h2>$product_title</h2>
                  <div class='color-section'>
                    <label for='color-select'>Culoare:</label>
                    <br>
                    <input type='hidden' name='product_id' value='$product_id' class='product-id'>
                    <select name='txt_color' class='color-select' id='color-select'><option value='' disabled selected>Select culoare</option>";
        $select_attr_query = "SELECT * FROM atribute_produs INNER JOIN culori ON atribute_produs.cod_culoare=culori.ID WHERE cod_produs=$product_id GROUP BY(cod_culoare)";
        $result_attr_query = mysqli_query($con, $select_attr_query);
        while ($row = mysqli_fetch_assoc($result_attr_query)) {
          $color_title = $row['denumire'];
          $color_id = $row['ID'];
          echo "<option value='$color_id'>$color_title</option>";
        }
        echo "      </select>
                  </div>
                  <div class='size-section'>
                    <label for='size-select$product_id'>Size:</label><br>
                    <select name='txt_size' class='size-select' id='size-select$product_id' required><option value=''>Select size</option>
                    </select>
                  </div>
                  <span class='price'>$product_price<small>lei</small></span>
                  <input type='hidden' name='product_quantity' value='1'>
                  <input type='submit' class='add-cart-btn' name='add_to_cart' value='Add to cart'>
                </div>
              </div>
            </div>
          </div>
        </form>
        ";
      }
    }
  }
}

function get_unique_category_products()
{
  global $con;
  if (isset($_GET['category'])) {
    $cat_id = $_GET['category'];
    $select_query = "SELECT * FROM `produse` WHERE cod_categorie=$cat_id";
    $result_query = mysqli_query($con, $select_query);
    $number_of_rows = mysqli_num_rows($result_query);
    if ($number_of_rows == 0) {
      echo "<h1>No products available for this category</h1>";
    }
    while ($row = mysqli_fetch_assoc($result_query)) {
      $product_id = $row['ID'];
      $product_title = $row['denumire'];
      $product_description = $row['descriere'];
      $product_image1 = $row['produs_imagine1'];
      $product_price  = $row['pret'];
      $product_brand = $row['cod_brand'];
      $product_category = $row['cod_categorie'];
      echo "
      <form method='post'>
        <div class='product'>
          <div class='product-card'>
            <h2 class='product-name'>$product_title</h5>
            <span class='product-price'>$product_price lei</span>
            <a class='popup-btn'>Add to cart</a>
            <a href='product_details.php?product_id=$product_id'><img src='./admin_area/images/$product_image1' alt='$product_title' class='product-img'></a>
          </div>
          <div class='popup-view'>
            <div class='popup-card'>
              <a href=''><i class='fas fa-times close-btn'></i></a>
              <div class='product-img'>
                <img src='./admin_area/images/$product_image1' alt='$product_title'>
              </div>
              <div class='info'>
                <h2>$product_title</h2>
                <div class='color-section'>
                  <label for='color-select'>Culoare:</label>
                  <br>
                  <input type='hidden' name='product_id' value='$product_id' class='product-id'>
                  <select name='txt_color' class='color-select' id='color-select'><option value='' disabled selected>Select culoare</option>";
      $select_attr_query = "SELECT * FROM atribute_produs INNER JOIN culori ON atribute_produs.cod_culoare=culori.ID WHERE cod_produs=$product_id GROUP BY(cod_culoare)";
      $result_attr_query = mysqli_query($con, $select_attr_query);
      while ($row = mysqli_fetch_assoc($result_attr_query)) {
        $color_title = $row['denumire'];
        $color_id = $row['ID'];
        echo "<option value='$color_id'>$color_title</option>";
      }
      echo "      </select>
                </div>
                <div class='size-section'>
                  <label for='size-select$product_id'>Size:</label><br>
                  <select name='txt_size' class='size-select' id='size-select$product_id' required><option value=''>Select size</option>
                  </select>
                </div>
                <span class='price'>$product_price<small>lei</small></span>
                <input type='hidden' name='product_quantity' value='1'>
                <input type='submit' class='add-cart-btn' name='add_to_cart' value='Add to cart'>
              </div>
            </div>
          </div>
        </div>
      </form>
      ";
    }
  }
}

function get_unique_subcategory()
{
  global $con;
  if (isset($_GET['subcategory'])) {
    $brand_id = $_GET['subcategory'];
    $select_query = "SELECT * FROM `produse` WHERE cod_subcategorie=$brand_id";
    $result_query = mysqli_query($con, $select_query);
    $number_of_rows = mysqli_num_rows($result_query);
    if ($number_of_rows == 0) {
      echo "<h1>No products available for this category</h1>";
    }
    while ($row = mysqli_fetch_assoc($result_query)) {
      $product_id = $row['ID'];
      $product_title = $row['denumire'];
      $product_description = $row['descriere'];
      $product_image1 = $row['produs_imagine1'];
      $product_price  = $row['pret'];
      $product_brand = $row['cod_brand'];
      $product_category = $row['cod_categorie'];
      echo "
      <form method='post'>
        <div class='product'>
          <div class='product-card'>
            <h2 class='product-name'>$product_title</h5>
            <span class='product-price'>$product_price lei</span>
            <a class='popup-btn'>Add to cart</a>
            <a href='product_details.php?product_id=$product_id'><img src='./admin_area/images/$product_image1' alt='$product_title' class='product-img'></a>
          </div>
          <div class='popup-view'>
            <div class='popup-card'>
              <a href=''><i class='fas fa-times close-btn'></i></a>
              <div class='product-img'>
                <img src='./admin_area/images/$product_image1' alt='$product_title'>
              </div>
              <div class='info'>
                
                <h2>$product_title</h2>
                <div class='color-section'>
                  <label for='color-select'>Culoare:</label>
                  <br>
                  <input type='hidden' name='product_id' value='$product_id' class='product-id'>
                  <select name='txt_color' class='color-select' id='color-select'><option value='' disabled selected>Select culoare</option>";
      $select_attr_query = "SELECT * FROM atribute_produs INNER JOIN culori ON atribute_produs.cod_culoare=culori.ID WHERE cod_produs=$product_id GROUP BY(cod_culoare)";
      $result_attr_query = mysqli_query($con, $select_attr_query);
      while ($row = mysqli_fetch_assoc($result_attr_query)) {
        $color_title = $row['denumire'];
        $color_id = $row['ID'];
        echo "<option value='$color_id'>$color_title</option>";
      }
      echo "      </select>
                </div>
                <div class='size-section'>
                  <label for='size-select$product_id'>Size:</label><br>
                  <select name='txt_size' class='size-select' id='size-select$product_id' required><option value=''>Select size</option>
                  </select>
                </div>
                <span class='price'>$product_price<small>lei</small></span>
                <input type='hidden' name='product_quantity' value='1'>
                <input type='submit' class='add-cart-btn' name='add_to_cart' value='Add to cart'>
              </div>
            </div>
          </div>
        </div>
      </form>
      ";
    }
  }
}

function get_categories()
{
  global $con;
  $select_category = "SELECT * FROM categorii";
  $result_categories = mysqli_query($con, $select_category);
  while ($category_row_data = mysqli_fetch_assoc($result_categories)) {
    $category_title = $category_row_data['denumire'];
    $category_id = $category_row_data['ID'];
    echo "<li><a href='index.php?category=$category_id' class='$category_title-btn'><span>$category_title</span>";
    if (isset($_GET['category'])) {
      if ($_GET['category'] === $category_id) {
        echo "<i class='fas fa-caret-down'></i>";
      } else {
        echo "<i class='fa-solid fa-caret-left'></i>";
      }
    } elseif (isset($_GET['subcategory'])) {
      $query = mysqli_query($con, "SELECT * FROM subcategorii WHERE ID={$_GET['subcategory']}");
      $row = mysqli_fetch_assoc($query);
      if ($category_id === $row['cod_categorie']) {
        echo "<i class='fas fa-caret-down'></i>";
      } else {
        echo "<i class='fa-solid fa-caret-left'></i>";
      }
    } else {
      echo "<i class='fa-solid fa-caret-left'></i>";
    }
    echo "</a>";
    get_subcategories($category_id);
    echo "</li>";
  }
}
function get_subcategories($id)
{
  global $con;
  $select_subcategory = "SELECT * FROM subcategorii WHERE cod_categorie=$id";
  $result_subcategories = mysqli_query($con, $select_subcategory);
  echo "<ul ";
  if (isset($_GET['category'])) {
    if ($_GET['category'] === $id) {
      echo "style='display: flex; flex-direction:column;'";
    }
  }
  if (isset($_GET['subcategory'])) {
    $query = mysqli_query($con, "SELECT * FROM subcategorii WHERE ID={$_GET['subcategory']}");
    $row = mysqli_fetch_assoc($query);
    if ($id === $row['cod_categorie']) {
      echo "style='display: flex; flex-direction:column;'";
    }
  }
  echo ">";
  while ($subcategory_row_data = mysqli_fetch_assoc($result_subcategories)) {
    $subcategory_title = $subcategory_row_data['denumire'];
    $subcategory_id = $subcategory_row_data['ID'];
    echo "<li><a href=index.php?subcategory=$subcategory_id>$subcategory_title</a></li>";
  }
  echo "</ul>";
}

function search_product()
{
  global $con;
  if (isset($_GET['search_data'])) {
    $search_data_value = $_GET['search_data'];
    $search_query = "SELECT * FROM `produse` WHERE keywords LIKE '%$search_data_value%'";
    $result_query = mysqli_query($con, $search_query);
    $number_of_rows = mysqli_num_rows($result_query);
    if ($number_of_rows == 0) {
      echo "<h1>No results found !</h1>";
    }
    while ($row = mysqli_fetch_assoc($result_query)) {
      $product_id = $row['ID'];
      $product_title = $row['denumire'];
      $product_description = $row['descriere'];
      $product_image1 = $row['produs_imagine1'];
      $product_price  = $row['pret'];
      $product_brand = $row['cod_brand'];
      $product_category = $row['cod_categorie'];
      echo "
      <form method='post'>
      <div class='product'>
        <div class='product-card'>
          <h2 class='product-name'>$product_title</h5>
          <span class='product-price'>$product_price lei</span>
          <a class='popup-btn'>Add to cart</a>
          <a href='product_details.php?product_id=$product_id'><img src='./admin_area/images/$product_image1' alt='$product_title' class='product-img'></a>
        </div>
        <div class='popup-view'>
          <div class='popup-card'>
            <a href=''><i class='fas fa-times close-btn'></i></a>
            <div class='product-img'>
              <img src='./admin_area/images/$product_image1' alt='$product_title'>
            </div>
            <div class='info'>
              <h2>$product_title</h2>
              <div class='color-section'>
                <label for='color-select'>Culoare:</label>
                <br>
                <input type='hidden' name='product_id' value='$product_id' class='product-id'>
                <select name='txt_color' class='color-select' id='color-select'><option value='' disabled selected>Select culoare</option>";
      $select_attr_query = "SELECT * FROM atribute_produs INNER JOIN culori ON atribute_produs.cod_culoare=culori.ID WHERE cod_produs=$product_id GROUP BY(cod_culoare)";
      $result_attr_query = mysqli_query($con, $select_attr_query);
      while ($row = mysqli_fetch_assoc($result_attr_query)) {
        $color_title = $row['denumire'];
        $color_id = $row['ID'];
        echo "<option value='$color_id'>$color_title</option>";
      }
      echo "      </select>
              </div>
              <div class='size-section'>
                <label for='size-select$product_id'>Size:</label><br>
                <select name='txt_size' class='size-select' id='size-select$product_id' required><option value=''>Select size</option>
                </select>
              </div>
              <span class='price'>$product_price<small>lei</small></span>
              <input type='hidden' name='product_quantity' value='1'>
              <input type='submit' class='add-cart-btn' name='add_to_cart' value='Add to cart'>
            </div>
          </div>
        </div>
      </div>
    </form>
    ";
    }
  }
}

function view_more()
{
  global $con;
  if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $result_query = mysqli_query($con, "SELECT *,p.denumire AS nume_produs,b.denumire AS nume_brand, c.denumire AS nume_categorie, sc.denumire AS nume_subcategorie FROM produse p
    INNER JOIN branduri b ON b.ID=p.cod_brand
    INNER JOIN categorii c ON c.ID=p.cod_categorie
    INNER JOIN subcategorii sc ON sc.ID=p.cod_subcategorie
    WHERE p.ID=$product_id");
    $img_query = mysqli_query($con, "SELECT * FROM imagini WHERE cod_produs=$product_id");
    $img_array = [];
    while ($row = mysqli_fetch_assoc($img_query)) {
      array_push($img_array, $row['path']);
    }
    $row = mysqli_fetch_assoc($result_query);
    $product_title = $row['nume_produs'];
    $product_description = $row['descriere'];
    $product_image1 = $row['produs_imagine1'];
    $product_price  = $row['pret'];
    $product_brand = $row['nume_brand'];
    $product_category = $row['nume_categorie'];
    $product_subcategory = $row['nume_subcategorie'];
    $data_id = 1;
    echo "
    <div class = 'card-wrapper'>
          <div class='card'>
            <div class='product-imgs'>
              <div class='img-display'>
                <div class='img-showcase'>
                  <img src = 'admin_area/images/$product_image1'>";
    foreach ($img_array as $image) {
      echo "<img src = 'admin_area/images/$image'>";
    }
    echo "
                </div>
              </div>
              <div class='img-select'>
                <div class = 'img-item'>
                  <a href = '#' data-id = '$data_id'>
                    <img src = 'admin_area/images/$product_image1' >
                  </a>
                </div>";
    foreach ($img_array as $image) {
      $data_id += 1;
      echo "
      <div class = 'img-item'>
      <a href = '#' data-id = '$data_id'>
                    <img src = 'admin_area/images/$image' >
                  </a></div>";
    }
    echo "
                
              </div>
            </div>
            <form class='product-content' method='post'>
              <h2>$product_title</h2>
              <a href = '#' class = 'product-link'>Visit $product_brand store</a>
              <div class = 'product-price'>
                <p class = 'new-price'>Price: <span>$product_price <small>lei</small></span></p>
              </div>
              <div class = 'product-detail'>
              <h2>about this item: </h2>
              <p>Category: $product_category($product_subcategory)</p>
              <p>$product_description</p>
              <ul class='ulist'>
                <li>
                  <div class='color-section'>
                    <label for='color-select'>Culoare:</label>
                    <input type='hidden' name='product_id' value='$product_id' class='product-id'>
                    <select name='txt_color' class='color-select' id='color-select' required><option value='' disabled selected>Select culoare</option>";
    $select_attr_query = "SELECT * FROM atribute_produs INNER JOIN culori ON atribute_produs.cod_culoare=culori.ID WHERE cod_produs=$product_id GROUP BY(cod_culoare)";
    $result_attr_query = mysqli_query($con, $select_attr_query);
    while ($row_1 = mysqli_fetch_assoc($result_attr_query)) {
      $color_title = $row_1['denumire'];
      $color_id = $row_1['ID'];
      echo "<option value='$color_id'>$color_title</option>";
    }
    echo "      
                    </select>
                  </div>
                </li>
                <li>
                  <div class='size-section'>
                    <label for='size-select$product_id'>Size:</label>
                    <select name='txt_size' class='size-select' id='size-select$product_id' required>
                      <option value=''>Select size</option>
                    </select>
                  </div>
                  </li>
                </ul>
            </div>
            <div class = 'purchase-info'>
              <input type = 'number' name='product_quantity'  min = '1' value = '1'>
              <input type='submit' class='add-cart-btn' name='add_to_cart' value='Add to cart'>
            </div> 

          </form>
        </div>
        </div>";
  }
}
