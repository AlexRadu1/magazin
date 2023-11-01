<?php
include("../includes/connect.php");
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin'] == true) {
  header("location:../index.php");
}

if (isset($_GET['action'])) {
  // 0 - user / 1 - admin
  $new_tip = ($_GET['action'] == 0) ? 1 : 0;
  $user_id = $_GET['user_id'];
  mysqli_query($con, "UPDATE utilizatori SET tip='$new_tip' WHERE ID='$user_id'");
  header("location:view_users.php");
}

$start = 0;
$rows_per_page = 25;

$records = mysqli_query($con, "SELECT *,utilizatori.ID AS id_user,judete.ID AS id_judet FROM utilizatori INNER JOIN judete ON utilizatori.cod_judet=judete.ID");
$nr_of_rows = mysqli_num_rows($records);
$pages = ceil($nr_of_rows / $rows_per_page);
if (isset($_GET['page-nr'])) {
  $page_limit = $_GET['page-nr'] - 1;
  $start = $page_limit * $rows_per_page;
}
$string_query = "SELECT *,utilizatori.ID AS id_user,judete.ID AS id_judet FROM utilizatori INNER JOIN judete ON utilizatori.cod_judet=judete.ID LIMIT $start,$rows_per_page";
$user_query = mysqli_query($con, $string_query);

if (isset($_GET['input_text'])) {
  $param = $_GET['input_text'];
  $select_criteria = $_GET['cat'];
  $order_criteria = $_GET['order'];
  $asc = " DESC";
  switch ($select_criteria) {
    case "1":
      $select_criteria = "username";
      break;
    case "2":
      $select_criteria = "CONCAT(nume, ' ', prenume)";
      break;
    case "3":
      $select_criteria = "telefon";
      break;
    case "4":
      $select_criteria = "email";
      break;
    default:
      " ";
      break;
  }
  switch ($order_criteria) {
    case "1":
      $order_criteria = "username";
      break;
    case "2":
      $order_criteria = "CONCAT(nume, ' ', prenume)";
      break;
    case "3":
      $order_criteria = "telefon";
      break;
    case "4":
      $order_criteria = "email";
      break;
    case "4":
      $order_criteria = "CONCAT(adresa, ' ', oras)";
      break;
    default:
      " ";
      break;
  }
  if (isset($_GET['asc'])) {
    $asc = ' ASC';
  }

  $string_query = "SELECT *,utilizatori.ID AS id_user,judete.ID AS id_judet FROM utilizatori INNER JOIN judete ON utilizatori.cod_judet=judete.ID";
  $string_query .= " WHERE $select_criteria LIKE '%$param%'";
  $string_query .= " ORDER BY $order_criteria";
  $string_query .= " $asc";
  $records = mysqli_query($con, $string_query);
  $string_query .= " LIMIT $start,$rows_per_page";
  $nr_of_rows = mysqli_num_rows($records);
  $pages = ceil($nr_of_rows / $rows_per_page);
  $user_query = mysqli_query($con, $string_query);
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
          <div class="order-detail-header">Useri</div>
          <form method="get" class="search_form">
            <input type="text" name='input_text' id="orders_search_bar" placeholder="Cauta" value="<?php if (isset($_GET['input_text']))  echo $_GET['input_text']; ?>">
            <label for="categorii">In: </label>
            <select name="cat" id="categorii">
              <option value="1" <?php if (isset($_GET['cat']) && $_GET['cat'] == 1) echo "selected" ?>>usernume</option>
              <option value="2" <?php if (isset($_GET['cat']) && $_GET['cat'] == 2) echo "selected" ?>>nume</option>
              <option value="3" <?php if (isset($_GET['cat']) && $_GET['cat'] == 3) echo "selected" ?>>telefon</option>
              <option value="4" <?php if (isset($_GET['cat']) && $_GET['cat'] == 4) echo "selected" ?>>email</option>
            </select>
            <label for="orderSelect">Order:</label>
            <select name="order" id="orderSelect">
              <option value="1" <?php if (isset($_GET['order']) && $_GET['order'] == 1) echo "selected" ?>>Username</option>
              <option value="2" <?php if (isset($_GET['order']) && $_GET['order'] == 2) echo "selected" ?>>Nume</option>
              <option value="3" <?php if (isset($_GET['order']) && $_GET['order'] == 3) echo "selected" ?>>telefon</option>
              <option value="4" <?php if (isset($_GET['order']) && $_GET['order'] == 4) echo "selected" ?>>Email</option>
            </select>
            <div class="checkbox-container">
              <label for="checkbox-search">ASC:</label>
              <input type="checkbox" name='asc' id="checkbox-search" <?php if (isset(($_GET['asc']))) echo "checked" ?>>
            </div>
            <button type="submit" name="submit_search"><i class="fa fa-search" aria-hidden="true"></i></button>
          </form>
          <?php
          if (mysqli_num_rows($user_query) ==  0) {
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
            <table id="table_field">
              <thead>
                <th>ID</th>
                <th>Username</th>
                <th>Nume Prenume</th>
                <th>Telefon</th>
                <th>Email</th>
                <th>Adresa</th>
                <th>Actiuni</th>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($user_query)) {
                ?>
                  <tr>
                    <td data-title="ID">
                      <div class="td-wrapper"><?= $row['id_user'] ?></div>
                    </td>
                    <td data-title="Username">
                      <div class="td-wrapper"><?= $row['username'] ?></div>
                    </td>
                    <td data-title="Nume Prenunme">
                      <div class="td-wrapper"><?= $row['nume'] . " " . $row['prenume']  ?></div>
                    </td>
                    <td data-title="Telefon">
                      <div class="td-wrapper"><?= $row['telefon'] ?></div>
                    </td>
                    <td data-title="Email">
                      <div class="td-wrapper"><?= $row['email'] ?></div>
                    </td>
                    <td data-title="Adresa">
                      <div class="td-wrapper"><?php echo "{$row['adresa']},{$row['Oras']}" ?></div>
                    </td>
                    <td data-title="Actiuni">
                      <div class="td-wrapper"><a href="view_user_orders.php?user_id=<?= $row['id_user'] ?>" class="buttons">Vezi comenzi</a>
                        <a href="view_users.php?user_id=<?= $row['id_user'] ?>&action=<?= $row['tip'] ?>" class="buttons" onclick="confirm('Are u sure ?')"><?php echo ($row['tip'] == 0) ? "Turn into admin" : "Turn into user" ?></a>
                      </div>
                    </td>
                  </tr>
              <?php
                }
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
  <script src="javascript.js" defer></script>
</body>

</html>