<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin'] == true) {
  header("location:../index.php");
}
$start = 0;
$rows_per_page = 25;
$string_query = "SELECT *,p.ID AS id_produs,p.denumire AS nume_produs,b.denumire AS nume_brand,c.denumire AS nume_categorie,s.denumire AS nume_subcategorie FROM produse p
INNER JOIN branduri b ON b.ID=p.cod_brand
INNER JOIN categorii c ON c.ID=p.cod_categorie
INNER JOIN subcategorii s ON s.ID=cod_subcategorie LIMIT $start,$rows_per_page";
$records = mysqli_query($con, "SELECT *,p.ID AS id_produs,p.denumire AS nume_produs,b.denumire AS nume_brand,c.denumire AS nume_categorie,s.denumire AS nume_subcategorie FROM produse p
INNER JOIN branduri b ON b.ID=p.cod_brand
INNER JOIN categorii c ON c.ID=p.cod_categorie
INNER JOIN subcategorii s ON s.ID=cod_subcategorie");
$nr_of_rows = mysqli_num_rows($records);
$pages = ceil($nr_of_rows / $rows_per_page);
if (isset($_GET['page-nr'])) {
  $page_limit = $_GET['page-nr'] - 1;
  $start = $page_limit * $rows_per_page;
}

$prod_query = mysqli_query($con, $string_query);

if (isset($_GET['input_text'])) {
  $param = $_GET['input_text'];
  $select_criteria = $_GET['cat'];
  $order_criteria = $_GET['order'];
  $asc = " DESC";
  switch ($select_criteria) {
    case "1":
      $select_criteria = "p.denumire";
      break;
    case "2":
      $select_criteria = "b.denumire";
      break;
    case "3":
      $select_criteria = "c.denumire";
      break;
    case "4":
      $select_criteria = "s.denumire";
      break;
    default:
      " ";
      break;
  }
  switch ($order_criteria) {
    case "1":
      $order_criteria = "p.denumire";
      break;
    case "2":
      $order_criteria = "b.denumire";
      break;
    case "3":
      $order_criteria = "c.denumire";
      break;
    case "4":
      $order_criteria = "s.denumire";
      break;
    case "5":
      $order_criteria = "p.pret";
      break;
    default:
      " ";
      break;
  }
  if (isset($_GET['asc'])) {
    $asc = ' ASC';
  }

  $string_query = "SELECT *,p.ID AS id_produs,p.denumire AS nume_produs,b.denumire AS nume_brand,c.denumire AS nume_categorie,s.denumire AS nume_subcategorie FROM produse p
  INNER JOIN branduri b ON b.ID=p.cod_brand
  INNER JOIN categorii c ON c.ID=p.cod_categorie
  INNER JOIN subcategorii s ON s.ID=cod_subcategorie";
  $string_query .= " WHERE $select_criteria LIKE '%$param%'";
  $string_query .= " ORDER BY $order_criteria";
  $string_query .= " $asc";
  $records = mysqli_query($con, $string_query);
  $string_query .= " LIMIT $start,$rows_per_page";
  $nr_of_rows = mysqli_num_rows($records);
  $pages = ceil($nr_of_rows / $rows_per_page);
  $prod_query = mysqli_query($con, $string_query);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
  <?php include('includes/header.php') ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="orders-wrapper">
        <div class="search-wrapper">
          <div class="order-detail-header"><a href="manage_product.php">Adauga produs nou</a></div>
          <form method="get" class="search_form">
            <input type="text" name='input_text' id="orders_search_bar" placeholder="Cauta" value="<?php if (isset($_GET['input_text']))  echo $_GET['input_text']; ?>">
            <label for="categorii">In: </label>
            <select name="cat" id="categorii">

              <option value="1" <?php if (isset($_GET['cat']) && $_GET['cat'] == 1) echo "selected" ?>>nume</option>
              <option value="2" <?php if (isset($_GET['cat']) && $_GET['cat'] == 2) echo "selected" ?>>brand</option>
              <option value="3" <?php if (isset($_GET['cat']) && $_GET['cat'] == 3) echo "selected" ?>>categorie</option>
              <option value="4" <?php if (isset($_GET['cat']) && $_GET['cat'] == 4) echo "selected" ?>>subcategorie</option>
            </select>
            <label for="orderSelect">Order:</label>
            <select name="order" id="orderSelect">
              <option value="1" <?php if (isset($_GET['order']) && $_GET['order'] == 1) echo "selected" ?>>Nume</option>
              <option value="2" <?php if (isset($_GET['order']) && $_GET['order'] == 2) echo "selected" ?>>Brand</option>
              <option value="3" <?php if (isset($_GET['order']) && $_GET['order'] == 3) echo "selected" ?>>Categorie</option>
              <option value="4" <?php if (isset($_GET['order']) && $_GET['order'] == 4) echo "selected" ?>>Subcategorie</option>
              <option value="5" <?php if (isset($_GET['order']) && $_GET['order'] == 5) echo "selected" ?>>Pret</option>
            </select>
            <div class="checkbox-container">
              <label for="checkbox-search">ASC:</label>
              <input type="checkbox" name='asc' id="checkbox-search" <?php if (isset(($_GET['asc']))) echo "checked" ?>>
            </div>
            <button type="submit" name="submit_search"><i class="fa fa-search" aria-hidden="true"></i></button>
          </form>
          <?php
          if (mysqli_num_rows($prod_query) ==  0) {
          ?>
            <div class='message' onclick="this.remove()">No results .</div>
            <?php
          } else {
            if ($pages != 1) :
            ?>
              <div class="pager">
                <div class="pager__info">
                  <?php
                  if (!isset($_GET['page-nr'])) {
                    $page = 1;
                  } else {
                    $page = $_GET['page-nr'];
                  }
                  ?>
                  Showing <?php echo $page ?> of <?php echo $pages ?>
                </div>
                <div class="pagination">
                  <div class="backwards pager-buttons">
                    <a class="page" href=<?php
                                          if (isset($_GET['submit_search'])) {
                                            //check if page query if present in uri
                                            $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                            if (str_contains($link, "page-nr=")) {
                                              //if it is trim till "=" and replace with last page number
                                              $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                              echo "?" . $trimmed_link . "1";
                                            } else {
                                              //if its not add ?page-nr=$pages
                                              echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=1";
                                            }
                                          } else {
                                            echo "?page-nr=1";
                                          }
                                          ?>>First</a>
                    <?php
                    if (isset($_GET['page-nr']) && $_GET['page-nr'] > 1) {
                    ?>
                      <a class="page" href=<?php
                                            if (isset($_GET['submit_search'])) {
                                              //check if page query if present in uri
                                              $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                              if (str_contains($link, "page-nr=")) {
                                                //if it is trim till "=" and replace with last page number
                                                $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                                echo "?" . $trimmed_link . $_GET['page-nr'] - 1;
                                              } else {
                                                //if its not add ?page-nr=$pages
                                                echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=" . $_GET['page-nr'] - 1;
                                              }
                                            } else {
                                              echo "?page-nr=" . $_GET['page-nr'] - 1;
                                            }
                                            ?>>Previous</a>
                    <?php
                    } else {
                    ?>
                      <a class="page inactive">Previous</a>
                    <?php
                    }
                    ?>
                  </div>
                  <div class="page-numbers">
                    <?php
                    for ($i = $page - 3; $i < $page; $i++) {
                      if ($i > 0) {
                    ?>
                        <a class="page" href=<?php
                                              if (isset($_GET['submit_search'])) {
                                                //check if page query if present in uri
                                                $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                                if (str_contains($link, "page-nr=")) {
                                                  //if it is trim till "=" and replace with last page number
                                                  $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                                  echo "?" . $trimmed_link . $i;
                                                } else {
                                                  //if its not add ?page-nr=$pages
                                                  echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=$i";
                                                }
                                              } else {
                                                echo "?page-nr=$i";
                                              }
                                              ?>><?= $i ?>
                        </a>
                    <?php
                      }
                    }
                    ?>
                    <div class="page active"><?php echo $page ?></div>
                    <?php
                    for ($i = $page + 1; $i <= $pages; $i++) {
                    ?>
                      <a class="page" href=<?php
                                            if (isset($_GET['submit_search'])) {
                                              //check if page query if present in uri
                                              $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                              if (str_contains($link, "page-nr=")) {
                                                //if it is trim till "=" and replace with last page number
                                                $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                                echo "?" . $trimmed_link . $i;
                                              } else {
                                                //if its not add ?page-nr=$pages
                                                echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=$i";
                                              }
                                            } else {
                                              echo "?page-nr=$i";
                                            }
                                            ?>><?= $i ?>
                      </a>
                    <?php
                      if ($i >= $page + 3) {
                        break;
                      }
                    }
                    ?>
                  </div>
                  <div class="forwards pager-buttons">
                    <?php
                    if (!isset($_GET['page-nr'])) {
                    ?>
                      <a class="page" href=<?php
                                            if (isset($_GET['submit_search'])) {
                                              //check if page query if present in uri
                                              $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                              if (str_contains($link, "page-nr=")) {
                                                //if it is trim till "=" and replace with last page number
                                                $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                                echo "?" . $trimmed_link . "2";
                                              } else {
                                                //if its not add ?page-nr=$pages
                                                echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=2";
                                              }
                                            } else {
                                              echo "?page-nr=2";
                                            }
                                            ?>>Next</a>
                      <?php
                    } else {
                      if ($_GET['page-nr'] >= $pages) {
                      ?>
                        <a class="page inactive">Next</a>
                      <?php
                      } else {
                      ?>
                        <a class="page" href=<?php
                                              if (isset($_GET['submit_search'])) {
                                                //check if page query if present in uri
                                                $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                                if (str_contains($link, "page-nr=")) {
                                                  //if it is trim till "=" and replace with last page number
                                                  $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                                  echo "?" . $trimmed_link . $_GET['page-nr'] + 1;
                                                } else {
                                                  //if its not add ?page-nr=$pages
                                                  echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=" . $_GET['page-nr'] + 1;
                                                }
                                              } else {
                                                echo "?page-nr=" . $_GET['page-nr'] + 1;
                                              }
                                              ?>>Next</a>
                    <?php
                      }
                    }
                    ?>
                    <a class="page" href=<?php
                                          if (isset($_GET['submit_search'])) {
                                            //check if page query if present in uri
                                            $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                            if (str_contains($link, "page-nr=")) {
                                              //if it is trim till "=" and replace with last page number
                                              $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                              echo "?" . $trimmed_link . $pages;
                                            } else {
                                              //if its not add ?page-nr=$pages
                                              echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=" . $pages;
                                            }
                                          } else {
                                            echo "?page-nr=$pages";
                                          }
                                          ?>>Last</a>
                  </div>
                </div>
              </div>
            <?php
            endif;
            ?>
            <table id="table_field--wide">
              <thead>
                <th>ID</th>
                <th>Poza</th>
                <th>Nume</th>
                <th>Brand</th>
                <th>Categorie</th>
                <th>Subcategorie</th>
                <th>Culori</th>
                <th>marimi</th>
                <th>Produse in stoc</th>
                <th>Pret produs</th>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($prod_query)) {
                ?>
                  <tr>
                    <td data-title="ID">
                      <div class="td-wrapper">
                        <a href="manage_product.php?id=<?= $row['id_produs'] ?>" class="buttons ">
                          <span class="padding-right"><?= $row['id_produs'] ?></span><span>&#10095;</span>
                        </a>
                      </div>
                    </td>
                    <td data-title="Poza">
                      <div class="td-wrapper"><img src="images/<?= $row['produs_imagine1'] ?>" alt=""></div>
                    </td>
                    <td data-title="Nume">
                      <div class="td-wrapper"><?= $row['nume_produs'] ?></div>
                    </td>
                    <td data-title="Brand">
                      <div class="td-wrapper"><?= $row['nume_brand'] ?></div>
                    </td>
                    <td data-title="Categorie">
                      <div class="td-wrapper"><?= $row['nume_categorie'] ?></div>
                    </td>
                    <td data-title="Subcategorie">
                      <div class="td-wrapper"><?= $row['nume_subcategorie'] ?></div>
                    </td>
                    <td data-title="Culori">
                      <div class="td-wrapper"><?php
                                              $select_color = mysqli_query($con, "SELECT * FROM atribute_produs 
                  INNER JOIN culori ON atribute_produs.cod_culoare=culori.ID
                  WHERE cod_produs={$row['id_produs']} GROUP BY cod_culoare");
                                              $culori = '';
                                              $i = 1;
                                              while ($row_color = mysqli_fetch_assoc($select_color)) {
                                                $culori .= "$i. {$row_color['denumire']}<br>";
                                                $i++;
                                              }
                                              echo $culori;
                                              ?></div>
                    </td>
                    <td data-title="Marimi">
                      <div class="td-wrapper"><?php
                                              $select_size = mysqli_query($con, "SELECT * FROM atribute_produs 
                  INNER JOIN marimi ON atribute_produs.cod_marime=marimi.ID
                  WHERE cod_produs={$row['id_produs']} AND cantitate != 0 GROUP BY cod_marime");
                                              $marimi = '';
                                              while ($row_size = mysqli_fetch_assoc($select_size)) {
                                                $marimi .= "{$row_size['denumire']}/";
                                              }
                                              echo rtrim($marimi, "/");
                                              ?></div>
                    </td>
                    <td data-title="Produse in stoc">
                      <div class="td-wrapper">
                        <?php
                        $select_stoc = mysqli_query($con, "SELECT * FROM atribute_produs WHERE cod_produs={$row['id_produs']}");
                        $stoc = 0;
                        while ($row_stoc = mysqli_fetch_assoc($select_stoc)) {
                          $stoc += $row_stoc['cantitate'];
                        }
                        echo $stoc;
                        ?></div>
                    </td>
                    <td data-title="Pret produse">
                      <div class="td-wrapper"><?= $row['pret'] ?></div>
                    </td>
                  </tr>
              <?php
                }
              }
              ?>
              </tbody>
            </table>

            <?php
            if ($pages > 1) :
            ?>
              <div class="pager">
                <div class="pager__info">
                  <?php
                  if (!isset($_GET['page-nr'])) {
                    $page = 1;
                  } else {
                    $page = $_GET['page-nr'];
                  }
                  ?>
                  Showing <?php echo $page ?> of <?php echo $pages ?>
                </div>
                <div class="pagination">
                  <div class="backwards pager-buttons">
                    <a class="page" href=<?php
                                          if (isset($_GET['submit_search'])) {
                                            //check if page query if present in uri
                                            $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                            if (str_contains($link, "page-nr=")) {
                                              //if it is trim till "=" and replace with last page number
                                              $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                              echo "?" . $trimmed_link . "1";
                                            } else {
                                              //if its not add ?page-nr=$pages
                                              echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=1";
                                            }
                                          } else {
                                            echo "?page-nr=1";
                                          }
                                          ?>>First</a>
                    <?php
                    if (isset($_GET['page-nr']) && $_GET['page-nr'] > 1) {
                    ?>
                      <a class="page" href=<?php
                                            if (isset($_GET['submit_search'])) {
                                              //check if page query if present in uri
                                              $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                              if (str_contains($link, "page-nr=")) {
                                                //if it is trim till "=" and replace with last page number
                                                $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                                echo "?" . $trimmed_link . $_GET['page-nr'] - 1;
                                              } else {
                                                //if its not add ?page-nr=$pages
                                                echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=" . $_GET['page-nr'] - 1;
                                              }
                                            } else {
                                              echo "?page-nr=" . $_GET['page-nr'] - 1;
                                            }
                                            ?>>Previous</a>
                    <?php
                    } else {
                    ?>
                      <a class="page inactive">Previous</a>
                    <?php
                    }
                    ?>
                  </div>
                  <div class="page-numbers">
                    <?php
                    for ($i = $page - 3; $i < $page; $i++) {
                      if ($i > 0) {
                    ?>
                        <a class="page" href=<?php
                                              if (isset($_GET['submit_search'])) {
                                                //check if page query if present in uri
                                                $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                                if (str_contains($link, "page-nr=")) {
                                                  //if it is trim till "=" and replace with last page number
                                                  $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                                  echo "?" . $trimmed_link . $i;
                                                } else {
                                                  //if its not add ?page-nr=$pages
                                                  echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=$i";
                                                }
                                              } else {
                                                echo "?page-nr=$i";
                                              }
                                              ?>><?= $i ?>
                        </a>
                    <?php
                      }
                    }
                    ?>
                    <div class="page active"><?php echo $page ?></div>
                    <?php
                    for ($i = $page + 1; $i <= $pages; $i++) {
                    ?>
                      <a class="page" href=<?php
                                            if (isset($_GET['submit_search'])) {
                                              //check if page query if present in uri
                                              $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                              if (str_contains($link, "page-nr=")) {
                                                //if it is trim till "=" and replace with last page number
                                                $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                                echo "?" . $trimmed_link . $i;
                                              } else {
                                                //if its not add ?page-nr=$pages
                                                echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=$i";
                                              }
                                            } else {
                                              echo "?page-nr=$i";
                                            }
                                            ?>><?= $i ?>
                      </a>
                    <?php
                      if ($i >= $page + 3) {
                        break;
                      }
                    }
                    ?>
                  </div>
                  <div class="forwards pager-buttons">
                    <?php
                    if (!isset($_GET['page-nr'])) {
                    ?>
                      <a class="page" href=<?php
                                            if (isset($_GET['submit_search'])) {
                                              //check if page query if present in uri
                                              $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                              if (str_contains($link, "page-nr=")) {
                                                //if it is trim till "=" and replace with last page number
                                                $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                                echo "?" . $trimmed_link . "2";
                                              } else {
                                                //if its not add ?page-nr=$pages
                                                echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=2";
                                              }
                                            } else {
                                              echo "?page-nr=2";
                                            }
                                            ?>>Next</a>
                      <?php
                    } else {
                      if ($_GET['page-nr'] >= $pages) {
                      ?>
                        <a class="page inactive">Next</a>
                      <?php
                      } else {
                      ?>
                        <a class="page" href=<?php
                                              if (isset($_GET['submit_search'])) {
                                                //check if page query if present in uri
                                                $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                                if (str_contains($link, "page-nr=")) {
                                                  //if it is trim till "=" and replace with last page number
                                                  $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                                  echo "?" . $trimmed_link . $_GET['page-nr'] + 1;
                                                } else {
                                                  //if its not add ?page-nr=$pages
                                                  echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=" . $_GET['page-nr'] + 1;
                                                }
                                              } else {
                                                echo "?page-nr=" . $_GET['page-nr'] + 1;
                                              }
                                              ?>>Next</a>
                    <?php
                      }
                    }
                    ?>
                    <a class="page" href=<?php
                                          if (isset($_GET['submit_search'])) {
                                            //check if page query if present in uri
                                            $link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                                            if (str_contains($link, "page-nr=")) {
                                              //if it is trim till "=" and replace with last page number
                                              $trimmed_link = substr($link, 0, strrpos($link, '=') + 1);
                                              echo "?" . $trimmed_link . $pages;
                                            } else {
                                              //if its not add ?page-nr=$pages
                                              echo "?" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) . "&page-nr=" . $pages;
                                            }
                                          } else {
                                            echo "?page-nr=$pages";
                                          }
                                          ?>>Last</a>
                  </div>
                </div>
              </div>
            <?php
            endif;
            ?>
        </div>
      </div>
    </div>
  </div>
  <script src="javascript.js" defer></script>
</body>

</html>