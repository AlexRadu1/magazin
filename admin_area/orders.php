<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin'] == true) {
  header("location:../index.php");
}

$start = 0;
$rows_per_page = 25;
$records = mysqli_query($con, "SELECT *,s.ID AS cod_status,c.ID AS cod_comanda,CONCAT(nume,' ',prenume) AS 'fullName' 
FROM comenzi c 
INNER JOIN clienti ON c.cod_client=clienti.ID 
INNER JOIN comenzi_status s ON c.cod_status=s.ID");
$nr_of_rows = mysqli_num_rows($records);
$pages = ceil($nr_of_rows / $rows_per_page);

if (isset($_GET['page-nr'])) {
  $page_limit = $_GET['page-nr'] - 1;
  $start = $page_limit * $rows_per_page;
}

$orders_query = mysqli_query($con, "SELECT *,s.ID AS cod_status,c.ID AS cod_comanda,CONCAT(nume,' ',prenume) AS 'fullName' 
FROM comenzi c 
INNER JOIN clienti ON c.cod_client=clienti.ID 
INNER JOIN comenzi_status s ON c.cod_status=s.ID 
ORDER BY c.ID DESC LIMIT $start,$rows_per_page");

if (isset($_GET['submit_search'])) {
  $select_criteria = '';
  $condition = '';
  $status = '';
  $start_date = '1998-04-10 14:15:55';
  $end_date = date("Y-m-d H:i:s");
  $order = ' ORDER BY c.data ';
  $asc = 'DESC';
  $order = $_GET['order'];
  switch ($order) {
    case "1":
      $order = ' ORDER BY c.data ';
      break;
    case "2":
      $order = " ORDER BY CONCAT(nume, ' ', prenume)";
      break;
    case "3":
      $order = " ORDER BY clienti.adresa";
      break;
    case "4":
      $order = " ORDER BY s.denumire";
      break;
    case "5":
      $order = " ORDER BY c.metoda_plata";
      break;
    default:
      $order = ' ORDER BY c.data ';
      break;
  }


  $select_criteria = $_GET['cat'];
  switch ($select_criteria) {
    case "1":
      $select_criteria = "CONCAT(nume, ' ', prenume)";
      break;
    case "2":
      $select_criteria = "adresa";
      break;
    default:
      " ";
      break;
  }
  if (isset($_GET['input_text'])) {
    $input_text = $_GET['input_text'];
    $search = explode(" ", $input_text);
    foreach ($search as $text) {
      $condition .= "$select_criteria LIKE '%$text%' OR ";
    }
  }
  $condition = substr($condition, 0, -4);
  if (isset($_GET['asc'])) {
    $asc = ' ASC';
  }
  if (isset($_GET['stat']) && $_GET['stat'] != 0) {
    $status = "AND c.cod_status={$_GET['stat']} ";
  }
  if (isset($_GET['start_date_search']) && $_GET['start_date_search'] != '') {
    $start_date = date('Y-m-d', strtotime($_GET['start_date_search']));
  }
  if (isset($_GET['end_date_search']) && $_GET['start_date_search'] != '') {
    $end_date = date('Y-m-d', strtotime($_GET['end_date_search']));
  }
  $q_date = "AND (`data` BETWEEN '$start_date' AND '$end_date')";
  $string_query = "SELECT *,
  s.ID AS cod_status,
  c.ID AS cod_comanda
  FROM comenzi c 
  INNER JOIN clienti ON c.cod_client=clienti.ID 
  INNER JOIN comenzi_status s ON c.cod_status=s.ID
  WHERE ($condition) $status $q_date $order $asc LIMIT $start,$rows_per_page";
  $records = mysqli_query($con, "SELECT *,
  s.ID AS cod_status,
  c.ID AS cod_comanda
  FROM comenzi c 
  INNER JOIN clienti ON c.cod_client=clienti.ID 
  INNER JOIN comenzi_status s ON c.cod_status=s.ID
  WHERE ($condition) $status $q_date $order $asc ");
  $nr_of_rows = mysqli_num_rows($records);
  $pages = ceil($nr_of_rows / $rows_per_page);
  $orders_query = mysqli_query($con, $string_query);
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/0ec3550c52.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<html>

<body>
  <?php include("includes/header.php") ?>
  <div class="wrapper">
    <?php include("includes/sidebar.php") ?>
    <div class="container">
      <div class="order-detail-header">Comenzi</div>
      <div class="orders-wrapper">
        <div class="search-wrapper">
          <form method="get" class="search_form">
            <input type="text" name='input_text' id="orders_search_bar" placeholder="Cauta" value="<?php if (isset($_GET['input_text']))  echo $_GET['input_text']; ?>">
            <label for="categorii">In: </label>
            <select name="cat" id="categorii">
              <option value="1" <?php if (isset($_GET['cat']) && $_GET['cat'] == 1) echo "selected" ?>>nume</option>
              <option value="2" <?php if (isset($_GET['cat']) && $_GET['cat'] == 2) echo "selected" ?>>adresa</option>
              <!-- TODO: <option value="3">toate</option> -->
            </select>
            <select name="stat">
              <option value="0">toate</option>
              <option value="1" <?php if (isset($_GET['stat']) && $_GET['stat'] == 1) echo "selected" ?>>Pending</option>
              <option value="2" <?php if (isset($_GET['stat']) && $_GET['stat'] == 2) echo "selected" ?>>Processing</option>
              <option value="3" <?php if (isset($_GET['stat']) && $_GET['stat'] == 3) echo "selected" ?>>Shipped</option>
              <option value="4" <?php if (isset($_GET['stat']) && $_GET['stat'] == 4) echo "selected" ?>>Canceled</option>
              <option value="5" <?php if (isset($_GET['stat']) && $_GET['stat'] == 5) echo "selected" ?>>Complete</option>
            </select>
            <label for="start_date_search">De la</label>
            <input type="date" name="start_date_search" id="start_date_search" value="<?php if (isset(($_GET['start_date_search']))) echo $_GET['start_date_search'] ?>">
            <label for="start_date_search">pana la</label>
            <input type="date" name="end_date_search" id="end_date_search" value="<?php if (isset(($_GET['end_date_search']))) echo $_GET['end_date_search'] ?>">
            <label for="orderSelect">Order</label>
            <select name="order" id="orderSelect">
              <option value="1" <?php if (isset($_GET['order']) && $_GET['order'] == 1) echo "selected" ?>>Data</option>
              <option value="2" <?php if (isset($_GET['order']) && $_GET['order'] == 2) echo "selected" ?>>Nume client</option>
              <option value="3" <?php if (isset($_GET['order']) && $_GET['order'] == 3) echo "selected" ?>>Adresa</option>
              <option value="4" <?php if (isset($_GET['order']) && $_GET['order'] == 4) echo "selected" ?>>Status</option>
              <option value="5" <?php if (isset($_GET['order']) && $_GET['order'] == 5) echo "selected" ?>>Metoda plata</option>
            </select>
            <div class="checkbox-container">
              <label for="checkbox-search">ASC:</label>
              <input type="checkbox" name='asc' id="checkbox-search" <?php if (isset(($_GET['asc']))) echo "checked" ?>>
            </div>
            <button type="submit" name="submit_search"><i class="fa fa-search" aria-hidden="true"></i></button>
          </form>
        </div>
        <?php
        if (mysqli_num_rows($orders_query) ==  0) {
        ?>
          <div class='message' onclick='this.remove();'>No results .</div>
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
          <table id="table_field">
            <thead>
              <th>ID#</th>
              <th>Data</th>
              <th>Adresa</th>
              <th>Metoda plata</th>
              <th>Status Comanda</th>
              <th>Detalii comanda</th>
            </thead>
            <tbody>
              <?php
              while ($row = mysqli_fetch_assoc($orders_query)) {
              ?>
                <tr>
                  <td data-title="ID#">
                    <div class="td-wrapper">
                      <a href="order_details.php?id_comanda=<?= $row['cod_comanda'] ?>" class="buttons">
                        <span class="padding-right"><?= $row['cod_comanda'] ?></span><span>&#10095;</span>
                      </a>
                    </div>
                  </td>
                  <td data-title="Data">
                    <div class="td-wrapper"><?php echo "" . $row['data'] . " " ?></div>
                  </td>
                  <td data-title="Adresa">
                    <div class="td-wrapper"><?php
                                            $jud = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM judete WHERE ID=" . $row['cod_judet'] . ""));
                                            $judet = $jud['denumire'];
                                            echo "{$row['adresa']} {$row['localitate']} $judet <br> {$row['zipcode']}" ?></div>
                  </td>
                  <td data-title="Metoda plata">
                    <div class="td-wrapper"><?= $row['metoda_plata'] ?></div>
                  </td>
                  <td data-title="Status comanda">
                    <div class="td-wrapper"><?= $row['denumire'] ?></div>
                  </td>
                  <td data-title="Detalii comanda">
                    <div class="td-wrapper"><button class="btn buttons" data-modal-target="#modal<?= $row['cod_comanda'] ?>">Quick view</button></div>
                  </td>
                </tr>
                <div class="modal" id="modal<?= $row['cod_comanda'] ?>">
                  <div class="modal-header">
                    <div class="title">Comanda nr. <?= $row['cod_comanda'] ?></div>
                    <button data-close-button class="close-button">&times;</button>
                  </div>
                  <div class="modal-body">
                    <section>
                      <h3>Produse in comanda</h3>
                      <div class="table">
                        <div class="table-row">
                          <div class="table-cell">
                            Poza
                          </div>
                          <div class="table-cell">
                            Nume Produs
                          </div>
                          <div class="table-cell">
                            Pret unitar
                          </div>
                          <div class="table-cell">
                            Cantitate
                          </div>
                          <div class="table-cell">
                            Pret total
                          </div>
                        </div>
                        <?php
                        $comenzi_detalii_q = mysqli_query($con, "SELECT *,p.pret AS pret_produs,c.cantitate AS cantitate_produs,c.pret AS pret_row FROM comenzi_detalii c 
              INNER JOIN atribute_produs a ON c.cod_atribut_produs=a.ID
              INNER JOIN produse p ON a.cod_produs=p.ID
              WHERE cod_comanda=" . $row['cod_comanda'] . "");

                        while ($table_row = mysqli_fetch_assoc($comenzi_detalii_q)) {
                        ?>
                          <div class="table-row">
                            <div class="table-cell">
                              <img src="images/<?= $table_row['produs_imagine1'] ?>" height="80px" width="80px" style="object-fit: contain;">
                            </div>
                            <div class="table-cell">
                              <a href="../product_details.php?product_id=<?= $table_row['cod_produs'] ?>"><?= $table_row['denumire'] ?> </a>
                            </div>
                            <div class="table-cell">
                              <?= $table_row['pret_produs'] ?>
                            </div>
                            <div class="table-cell">
                              <?= $table_row['cantitate_produs'] ?>
                            </div>
                            <div class="table-cell">
                              <?= $table_row['pret_row'] ?>
                            </div>
                          </div>

                        <?php
                        }
                        ?>
                      </div>
                    </section>
                    <section>
                      <h3>Detalii cumparator</h3>
                      <div class="detalii-order">
                        <p>Nume: <?php echo "" . $row['nume'] . " " . $row['prenume'] . "" ?></p>
                        <p>Adresa facturare: <?php
                                              $arr = explode(",", $row['date_facturare'], 2);
                                              $first = $arr[1];
                                              echo $first
                                              ?></p>
                        <p>Nr.telefon: <?= $row['telefon'] ?></p>
                      </div>
                    </section>
                    <section>
                      <h3>Detalii livrare</h3>
                      <div class="detalii-order">
                        <p>Adresa livrare: <?php
                                            $jud = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM judete WHERE ID=" . $row['cod_judet'] . ""));
                                            $judet = $jud['denumire'];
                                            echo "" . $row['adresa'] . " " . $row['localitate'] . "$judet" ?></p>
                      </div>
                    </section>
                  </div>
              <?php }
            } ?>
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
  <div id="overlay"></div>
  <script src="javascript.js" defer></script>
</body>

</html>