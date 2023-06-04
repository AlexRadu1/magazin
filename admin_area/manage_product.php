<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
  header("location:../index.php");
}
$prod_info = array();
$image_required = 'required';
$image = '';
$msg = '';
$attrProd = array();

if (isset($_GET['pi']) && $_GET['pi'] > 0) {
  $pi = mysqli_real_escape_string($con, $_GET['pi']);
  $delete_sql = "delete from imagini where id='$pi'";
  mysqli_query($con, $delete_sql);
}

if (isset($_GET['id']) && $_GET['id'] != '') {
  $select_prod_info = mysqli_query($con, "SELECT * FROM produse WHERE ID={$_GET['id']}");
  $prod_info = mysqli_fetch_assoc($select_prod_info);
  $image_required = '';
  $image = $prod_info['produs_imagine1'];
  $id = mysqli_real_escape_string($con, $_GET['id']);
}

if (isset($_POST['submit'])) {
  $category_id = mysqli_real_escape_string($con, $_POST['category']);
  $subcategory_id = mysqli_real_escape_string($con, $_POST['subcategory']);
  $title = mysqli_real_escape_string($con, $_POST['title']);
  $brand_id = mysqli_real_escape_string($con, $_POST['brand']);
  $price = mysqli_real_escape_string($con, $_POST['price']);
  $keywords = mysqli_real_escape_string($con, $_POST['keywords']);
  $description = mysqli_real_escape_string($con, $_POST['description']);

  $res = mysqli_query($con, "SELECT * FROM produse WHERE denumire='$title'");
  $check = mysqli_num_rows($res);
  if ($check > 0) {
    if (isset($_GET['id']) && $_GET['id'] != '') {
      $getData = mysqli_fetch_assoc($res);
      if ($id == $getData['ID']) {
      } else {
        $msg = "Product already exist";
        die();
      }
    } else {
      $msg = "Product already exist";
      die();
    }
  }

  if ($msg == '') {
    if (isset($_GET['id']) && $_GET['id'] != '') {
      if ($_FILES['image']['name'] != '') {
        $product_image = rand(111111111, 999999999) . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "images/$product_image");
        $update_sql = "UPDATE produse SET denumire='$title',cod_brand='$brand_id',cod_categorie='$category_id',pret='$price',descriere='$description',keywords='$keywords',cod_subcategorie='$subcategory_id',produs_imagine1=$product_image WHERE ID=$id";
      } else {
        $update_sql = "UPDATE produse SET denumire='$title',cod_brand='$brand_id',cod_categorie='$category_id',pret='$price',descriere='$description',keywords='$keywords',cod_subcategorie='$subcategory_id' WHERE ID='$id'";
      }
      mysqli_query($con, $update_sql);
    } else {
      $product_image = rand(111111111, 999999999) . '_' . $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], "images/$product_image");
      mysqli_query($con, "INSERT INTO `produse` (denumire,cod_brand,cod_categorie,pret,descriere,keywords,cod_subcategorie,produs_imagine1)
      VALUES ('$title','$brand_id','$category_id','$price','$description','$keywords','$subcategory_id','$product_image')");
      $id = mysqli_insert_id($con);
    }
    // Multiple images
    if (isset($_GET['id']) && $_GET['id'] != '') {
      if (isset($_FILES['product_images']['name'])) {
        foreach ($_FILES['product_images']['name'] as $key => $val) {
          if ($_FILES['product_images']['name'][$key] != '') {
            if (isset($_POST['product_images_id'][$key])) {
              $image = rand(111111111, 999999999) . '_' . $_FILES['product_images']['name'][$key];
              move_uploaded_file($_FILES['product_images']['tmp_name'][$key], "images/$image");
              mysqli_query($con, "UPDATE imagini SET `path`='$image' where ID='" . $_POST['product_images_id'][$key] . "'");
            } else {
              $image = rand(111111111, 999999999) . '_' . $_FILES['product_images']['name'][$key];
              move_uploaded_file($_FILES['product_images']['tmp_name'][$key], "images/$image");
              mysqli_query($con, "INSERT INTO  imagini(cod_produs,`path`) values('$id','$image')");
            }
          }
        }
      }
    } else {
      if (isset($_FILES['product_images']['name'])) {
        foreach ($_FILES['product_images']['name'] as $key => $val) {
          if ($_FILES['product_images']['name'][$key] != '') {
            $image = rand(111111111, 999999999) . '_' . $_FILES['product_images']['name'][$key];
            move_uploaded_file($_FILES['product_images']['tmp_name'][$key], "images/$image");
            mysqli_query($con, "INSERT INTO  imagini(cod_produs,`path`) values('$id','$image')");
          }
        }
      }
    }
  }

  if (isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $key => $val) {
      $quantity = mysqli_real_escape_string($con, $_POST['quantity'][$key]);
      $size_id = mysqli_real_escape_string($con, $_POST['size'][$key]);
      $color_id = mysqli_real_escape_string($con, $_POST['color'][$key]);
      $attr_id = mysqli_real_escape_string($con, $_POST['attr_id'][$key]);
      if ($attr_id > 0) {
        mysqli_query($con, "UPDATE atribute_produs SET cod_produs='$id',cod_marime='$size_id',cod_culoare='$color_id',cantitate='$quantity' WHERE ID='$attr_id'");
      } else {
        mysqli_query($con, "INSERT INTO atribute_produs(cod_produs,cod_marime,cod_culoare,cantitate) VALUES('$id','$size_id','$color_id','$quantity')");
      }
    }
  }
  header("location:view_products.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalii produs</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
  <?php include("includes/header.php") ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="order-detail-header">Detalii produs<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "#{$prod_info['ID']}" : "" ?> </div>
      <form method="post" enctype="multipart/form-data" class="width">
        <div class="prod-form-wrapper">
          <div class="product-form">
            <h2>Text produs</h2>
            <div class="form-group">
              <div class="group-row">
                <label for="product-category">Categorie</label>
                <select name="category" id="product-category" onchange="get_sub_cat('')">
                  <option>Select Category</option>
                  <?php
                  $select_cat = mysqli_query($con, "SELECT * FROM categorii");
                  while ($row_categorie = mysqli_fetch_assoc($select_cat)) {
                  ?>
                    <option value="<?= $row_categorie['ID'] ?>" <?php if (isset($_GET['id']) && $prod_info['cod_categorie'] == $row_categorie['ID']) echo "selected" ?>><?= $row_categorie['denumire'] ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
              <div class="group-row">
                <label for="product-subcategory">Subcategorie</label>
                <select name="subcategory" id="product-subcategory">
                  <option>Select Category</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="group-row">
                <label for="product-title">Titlu produs</label>
                <input type="text" name="title" id="product-title" value="<?php if (isset($_GET['id']) && $_GET['id'] != '') echo $prod_info['denumire'] ?>">
              </div>
            </div>
            <div class="form-group">
              <div class="group-row">
                <label for="product-brand">Brand produs</label>
                <select name="brand" id="product-brand">
                  <?php
                  $select_brand = mysqli_query($con, "SELECT * FROM branduri");
                  while ($row_brand = mysqli_fetch_assoc($select_brand)) {
                  ?>
                    <option value="<?= $row_brand['ID'] ?>" <?php if (isset($_GET['id']) && $prod_info['cod_brand'] == $row_brand['ID']) echo "selected" ?>><?= $row_brand['denumire'] ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="group-row">
                <label for="product-price">Pret produs</label>
                <input type="number" name="price" id="product-price" value="<?php if (isset($_GET['id']) && $_GET['id'] != '') echo $prod_info['pret'] ?>">
              </div>
            </div>
            <div class="form-group">
              <div class="group-row">
                <label for="product-keywords">Keywords produs</label>
                <textarea type="text" name="keywords" rows="5" cols="10" wrap="soft" id="product-keywords"><?php if (isset($_GET['id']) && $_GET['id'] != '') echo $prod_info['keywords'] ?></textarea>
              </div>
            </div>
            <div class="form-group">
              <div class="group-row">
                <label for="product-description">Descriere produs</label>
                <textarea type="text" name="description" rows="5" cols="10" wrap="soft" id="product-description"><?php if (isset($_GET['id']) && $_GET['id'] != '') echo $prod_info['descriere'] ?></textarea>
              </div>
            </div>
          </div>
          <div class="product-form image-box" id="image_box">
            <h2>Imagini produs</h2>
            <div class="form-group">
              <div class="group-row">
                <label for="product-image">Imagine produs</label>
                <input type="file" name="image" id="product-image" accept="image/png, image/jpg, image/jpeg" <?php echo  $image_required ?>>
                <?php
                if ($image != '') {
                  echo "<a target='_blank' href='images/{$prod_info['produs_imagine1']}'><img width='150px' src='images/{$prod_info['produs_imagine1']}'/></a>";
                }
                ?>
              </div>
              <div class="group-row add-image">
                <button type="button" class="buttons" onclick="add_more_images()">Add image</button>
              </div>
            </div>
            <?php
            if (isset($_GET['id']) && $_GET['id'] != '') {
              $select_img = mysqli_query($con, "SELECT * FROM imagini WHERE cod_produs={$_GET['id']}");
              if (mysqli_num_rows($select_img) > 0) {
                while ($row_img = mysqli_fetch_assoc($select_img)) {
            ?>
                  <div class="form-group" id="add_image_box_<?= $row_img['ID'] ?>">
                    <div class="group-row">
                      <label for="product-image">Imagine produs</label>
                      <input type="file" name="product_images[]" id="product-images" accept="image/png, image/jpg, image/jpeg">
                      <a target='_blank' href="images/<?= $row_img['path'] ?>"><img width='150px' src="images/<?= $row_img['path'] ?>" /></a>
                      <input type="hidden" name="product_images_id[]" value="<?= $row_img['ID'] ?>" />
                    </div>
                    <div class="group-row add-image">
                      <a href="manage_product?id=<?= $_GET['id'] ?>&pi=<?= $row_img['ID'] ?>" class="buttons danger" onclick="return confirm('Are you sure you want to delete photo?')">Remove img</a>
                    </div>
                  </div>
            <?php
                }
              }
            }
            ?>

          </div>
          <?php
          if (isset($_GET['id']) && $_GET['id'] != '') {
            $select_atributes = mysqli_query($con, "SELECT * FROM atribute_produs WHERE cod_produs={$_GET['id']}");
            if (mysqli_num_rows($select_atributes) > 0) {
          ?>
              <div class="product-form">
                <div class="add-more-stoc">
                  <h2>Stocuri produs</h2>
                  <button type="button" class="buttons" onclick="add_more_attr()">Add more</button>
                </div>
                <div class="form-group" id="product_attr_box">
                  <?php
                  $attrProductLoop = 1;
                  while ($row_atribut = mysqli_fetch_assoc($select_atributes)) {
                  ?>
                    <div class="group-col" id="attr_<?php echo $attrProductLoop ?>">
                      <div class="group-row-atr">
                        <div class="col">
                          <label for="quantity<?= $attrProductLoop ?>">Cantitate</label>
                          <input type="text" name="quantity[]" placeholder="Cantitate" id="quantity<?= $attrProductLoop ?>" class="" required value="<?= $row_atribut['cantitate'] ?>">
                        </div>
                        <div class="col">
                          <label for="color<?= $attrProductLoop ?>">Culoare</label>
                          <select name="color[]" id="color<?= $attrProductLoop ?>">
                            <option>Culoare</option>
                            <?php
                            $color_q = mysqli_query($con, "SELECT * FROM culori");
                            while ($row_color = mysqli_fetch_assoc($color_q)) {
                              if ($row_atribut['cod_culoare'] == $row_color['ID']) {
                                echo "<option value=" . $row_color['ID'] . " selected>" . $row_color['denumire'] . "</option>";
                              } else {
                                echo "<option value=" . $row_color['ID'] . " >" . $row_color['denumire'] . "</option>";
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <div class="col">
                          <label for="size<?= $attrProductLoop ?>">Marime</label>
                          <select name="size[]" id="size<?= $attrProductLoop ?>">
                            <option>Marime</option>
                            <?php
                            $size_q = mysqli_query($con, "SELECT * FROM marimi");
                            while ($row_size = mysqli_fetch_assoc($size_q)) {
                              if ($row_atribut['cod_marime'] == $row_size['ID']) {
                                echo "<option value=" . $row_size['ID'] . " selected>" . $row_size['denumire'] . "</option>";
                              } else {
                                echo "<option value=" . $row_size['ID'] . " >" . $row_size['denumire'] . "</option>";
                              }
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="group-row-atr">
                        <button type="button" class="buttons danger" onclick="if(confirm('Are you sure?')){remove_attr('<?php echo $attrProductLoop ?>','<?php echo $row_atribut['ID'] ?>')}">
                          Remove
                        </button>
                        <input type="hidden" name="attr_id[]" value='<?php echo $row_atribut['ID'] ?>' />
                      </div>
                    </div>
                  <?php
                    $attrProductLoop++;
                  }
                  ?>
                </div>
              </div>
          <?php
            }
          }
          ?>
        </div>
        <div class="submit-group">
          <input type="submit" name="submit" value="<?php echo (isset($_GET['id']) && $_GET['id'] != '') ? "Actualizeaza" : "Adauga" ?>" class="buttons">
        </div>
      </form>
      <div class="field_error"><?php echo $msg ?></div>
    </div>
  </div>






  <script>
    function get_sub_cat(sub_cat_id) {
      let category_id = $('#product-category').val()
      $.ajax({
        url: "get_sub_cat.php",
        type: 'post',
        data: 'category_id=' + category_id + '&sub_cat_id=' + sub_cat_id,
        success: function(result) {
          $('#product-subcategory').html(result)
        }
      })
    }

    <?php
    if (isset($_GET['id'])) {
    ?>
      get_sub_cat('<?php echo $prod_info['cod_subcategorie'] ?>')
    <?php } ?>


    let total_image = 1
    if ($('#image_box :last-child').attr('id')) {
      total_image = $('#image_box').children().last().attr('id').slice(14)
    }

    function add_more_images() {
      total_image++;
      let html = '<div class="form-group" id="add_image_box_' + total_image + '"><div class="group-row"><label for="categories" class="">Image</label><input type="file" name="product_images[]" accept="image/png, image/jpg, image/jpeg" required></div><div class="group-row add-image"><button type="button" class="buttons danger" onclick=remove_image("' + total_image + '")>Remove</button></div></div>'
      $('#image_box').append(html)
    }

    function remove_image(id) {
      $('#add_image_box_' + id).remove()
    }

    let last_attr_id = 1
    if ($('#product_attr_box :last-child').attr('id')) {
      last_attr_id = $('#product_attr_box').children().last().attr('id').slice(5)
    }

    function add_more_attr() {
      last_attr_id++

      let size_html = $('#attr_1 #size1').html()
      size_html = size_html.replace('selected', '')

      let color_html = $('#attr_1 #color1').html()
      color_html = color_html.replace('selected', '')

      let html = '<div class="group-col" id="attr_' + last_attr_id + '"><div class="group-row-atr"><div class="col"><label for="quantity' + last_attr_id + '">Cantitate</label><input type="text" name="quantity[]" placeholder="Cantitate" id="quantity' + last_attr_id + '" class="" required"></div><div class="col"><label for="color' + last_attr_id + '">Culoare</label><select name="color[]" id="color' + last_attr_id + '">' + color_html + '</select></div><div class="col"><label for="size' + last_attr_id + '">Marime</label><select name="size[]" id="size' + last_attr_id + '">' + size_html + '</select></div></div><div class="group-row-atr"><button type="button" class="buttons danger" onclick=remove_attr("' + last_attr_id + '")>Remove</button><input type="hidden" name="attr_id[]" value="" /></div></div>'
      $('#product_attr_box').append(html)
    }

    function remove_attr(attr_count, id) {
      $.ajax({
        url: 'remove_product_attr.php',
        data: 'id=' + id,
        type: 'post',
        success: function(result) {
          $('#attr_' + attr_count).remove();
        }
      })
    }
  </script>
</body>

</html>