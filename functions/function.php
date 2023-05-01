<?php
include('./includes/connect.php');

function get_products()
{
  global $con;
  if (!isset($_GET['category'])) {
    if (!isset($_GET['subcategory'])) {
      $select_query = "SELECT * FROM `produse` ORDER BY ID DESC";
      $result_query = mysqli_query($con, $select_query);
      while ($row = mysqli_fetch_assoc($result_query)) {
        $product_id = $row['ID'];
        $product_title = $row['denumire'];
        $product_description = $row['descriere'];
        $product_image1 = $row['produs_imagine1'];
        $product_price  = $row['pret'];
        $product_brand = $row['cod_brand'];
        $product_category = $row['cod_categorie'];
        $product_in_query = "SELECT * FROM `intrariproduse` WHERE cod_produs=$product_id";
        echo "
        <form method='post' class='card'>
          <div class='product'>
          <img src='./admin_area/images/$product_image1' alt='$product_title' class='card-img'>
          <div class='card-body'>
            <h5 class='card-title'>$product_title</h5>
            <p class='card-text'>$product_price lei</p>
            <input type='hidden' name='product_quantity' value='1'>
            <input type='hidden' name='product_image' value='$product_image1'> 
            <input type='hidden' name='product_name' value='$product_title'>
            <input type='hidden' name='product_id' value='$product_id'>
            <input type='hidden' name='product_price' value='$product_price'>";
        echo "   <label for='product_size'>Choose a color:</label>
            <select name='product_size' required>";

        $result_product_query = mysqli_query($con, $product_in_query);
        while ($product_row = mysqli_fetch_assoc($result_product_query)) {
          $product_size = $product_row['culoare'];
          echo "<option value='$product_size'>$product_size</option>";
        }
        echo "</select><br>
            <label for='product_size'>Choose a size:</label>
            <select name='product_size' required>";
        $result_product_query = mysqli_query($con, $product_in_query);
        while ($product_row = mysqli_fetch_assoc($result_product_query)) {
          $product_size = $product_row['marime'];
          echo "<option value='$product_size'>$product_size</option>";
        }
        echo "</select>
        <br>
            <input type='submit' class='card-button' name='add_to_cart' value='Add to cart'>
            <br>
            <a href='product_details.php?product_id=$product_id' class='card-button'>View more</a>
          </div>
          </div>
        </form>";
      }
    }
  }
}

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
            <img src='./admin_area/images/$product_image1' alt='$product_title' class='product-img'>
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
            <img src='./admin_area/images/$product_image1' alt='$product_title' class='product-img'>
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
    echo "<li><a href='index.php?category=$category_id' class='$category_title-btn'>$category_title<span class='fas fa-caret-down'></span></a>";
    get_subcategories($category_id);
    echo "</li>";
  }
}
function get_subcategories($id)
{
  global $con;
  $select_subcategory = "SELECT * FROM subcategorii WHERE cod_categorie=$id";
  $result_subcategories = mysqli_query($con, $select_subcategory);
  echo "<ul>";
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
  if (isset($_GET['search_data_product'])) {
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
  if (isset($_GET['product_id']))
    if (!isset($_GET['category'])) {
      if (!isset($_GET['subcategory'])) {
        $product_id = $_GET['product_id'];
        $select_query = "SELECT * FROM `produse` WHERE ID=$product_id";
        $result_query = mysqli_query($con, $select_query);
        while ($row = mysqli_fetch_assoc($result_query)) {
          $product_id = $row['ID'];
          $product_title = $row['denumire'];
          $product_description = $row['descriere'];
          $product_image1 = $row['produs_imagine1'];
          $product_image3 = $row['produs_imagine2'];
          $product_image2 = $row['produs_imagine3'];
          $product_price  = $row['pret'];
          $product_brand = $row['cod_brand'];
          $product_category = $row['cod_categorie'];
          echo "
          <div class='product-container product-flex'>
          <div class='left'>
            <div class='main_image'>
              <img src='admin_area/images/$product_image1' class='slide'>
            </div>
            <div class='option product-flex'>
              <img src='./admin_area/images/$product_image1' onclick='img('./admin_area/images/$product_image2')'>
              <img src='./admin_area/images/$product_image2' onclick='img('./admin_area/images/$product_image3')'>
              <img src='./admin_area/images/$product_image3' onclick='img('./admin_area/images/$product_image1)'>
            </div>
          </div>
          <div class='right'>
            <h3>$product_title</h3>
            <h4> $product_price <small>lei</small></h4>
            <p>$product_description</p>
            <h5>Quantity</h5>
            <div class='add flex1'>
              <span>-</span>
              <label>1</label>
              <span>+</span>
            </div>
            <button><a href='index.php?add_to_cart=$product_id' class='card-button'>Add to cart</a></button>
          </div>
        </div>";
        }
      }
    }
}
