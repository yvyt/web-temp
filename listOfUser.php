<?php
session_start();
include_once('./config.php');
$connect = connect();
$login = false;
$username = "";
$email;
$name = "";
if (!isset($_SESSION['user'])) {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  header('Location: login.php');
  exit();
}
$login = true;
$email = $_SESSION['user'];
$sql = "SELECT * FROM users WHERE username='" . $email . "' LIMIT 1";
$query = mysqli_query($connect, $sql);
$num_row = mysqli_num_rows($query);
if ($num_row > 0) {
  $data = mysqli_fetch_assoc($query);
  $name = $data['name'];
}
if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
  unset($_SESSION['user']);
  header('Location: login.php');
  exit();
}
$sql_select = "SELECT * FROM users";
$run = mysqli_query($connect, $sql_select);
$num = mysqli_num_rows($run);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link rel="stylesheet" href="css/styleAdmin.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  <title>Document</title>
</head>

<body>
  <header>
    <h>Danh Sách Người Dùng</h>
  </header>
  <div class="container-">
    <nav class="navbar navbar-expand-lg" id="navbar1">
      <div class="container-fluid">
        <img src="./CSS/images/logo.jpg" height="50px" width="50px" style="border-radius: 50px;">
        <a class="navbar-brand" href="index.php" style="padding-left: 50px;color: rgb(66, 72, 116);">Trang Chủ</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <form class="d-flex" role="search" style="width: 60%; padding-left:10%;">
            <input class="form-control me-2" type="search" placeholder="Tìm kiếm" id="searchCharacter" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Tìm</button>
          </form>
          <ul>
            <li class="nav-item dropdown" id="login">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php
                if ($name != "") {
                  echo $name;
                } else {
                  echo "User";
                }
                ?>
              </a>
              <ul class="dropdown-menu" id="dropdownLogin">
                <li><a class="dropdown-item" href="./editInfor.php">Hồ sơ của tôi</a></li>
                <li><a class="dropdown-item" href="./changePassword.php">Đổi mật khẩu</a></li>

                <li><a class="dropdown-item" href="indexAdmin.php?dangxuat=1">Đăng xuất</a></li>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
  <div class="row">
    <section>
      <nav id="navbar2">
        <div class="dropdown">
          <img src="./CSS/images/user.png" width="15%" height="15%">
          <button class="btn btn-secondary dropdown-toggle" id="dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Quản lý người dùng
          </button>
          <ul class="dropdown-menu" id="dropdownUL">
            <li><a class="dropdown-item" href="addUser.php">Thêm quản trị viên</a></li>
            <li><a class="dropdown-item" href="listOfUser.php">Danh sách người dùng</a></li>
          </ul>
        </div>


        <!-- <div class="share">
          <img src="./CSS/images/share7.png" width="15%" height="15%">
          <a class="btn" id="btnShare" href="shareUser.php">Đã chia sẻ</a>
        </div> -->
        <div class="recent">
          <img src="./CSS/images/settings.png" width="15%" height="15%">
          <a class="btn" id="btnRecent" href="recentUser.php">Cài đặt</a>
        </div>

        <div class="trash">
          <img src="./CSS/images/trash1.png" width="15%" height="15%">
          <a class="btn" id="btnTrash" href="trashUser.php">Đã xóa</a>
        </div>
        <div class="trash">
          <a class="btn" id="btnTrash" href="#"></a>
        </div>
        <div class="trash">
          <a class="btn" id="btnTrash" href="#"></a>
        </div>
        <div class="priority">

          <a class="btn" id="btnPriority" href="#"></a>
        </div>
        <div class="priority">

          <a class="btn" id="btnPriority" href="#"></a>
        </div>
        <div class="priority">

          <a class="btn" id="btnPriority" href="#"></a>
        </div>
      </nav>

      <article id="art2">
        <div class="row" id="display_us">
          <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Danh sách người dùng</li>
            </ol>
          </nav>
          <?php
          if ($num > 0) {
            while ($row = mysqli_fetch_array($run)) {
          ?>
              <div class="col-lg-3 col-md-4" style="margin-bottom:20px">
                <div class="card" style="width: 95%;height: 100%; background-color: rgb(247, 251, 252);border: 2px solid ; z-index: 2;">
                  <input type="hidden" name="gender[]" value="<?php echo $row['gender'] ?>">
                  <?php
                  if ($row['gender'] == 0) {
                  ?>
                    <img src="css/images/girl.png" class="card-img-top">
                  <?php
                  } else {
                  ?>
                    <img src="css/images/user3.png" class="card-img-top">
                  <?php
                  }
                  ?>
                  <div class="card-body">
                    <span class="text text-primary">Tên:</span>
                    <span class="card-text text text-primary" name='name_us[]'> <?php echo $row['name'] ?></span>
                    <p></p>
                    <span class="text text-primary">Email: </span>

                    <span class="card-text text text-primary" name='email_us[]'>
                      <?php
                      if (strlen($row['username']) > 20) {
                        echo substr($row['username'], 0, 19) . '...';
                      } else {
                        echo $row['username'];
                      }
                      ?>
                    </span>
                    <p></p>
                    <span class="text text-primary">
                      Vai trò:
                    </span>
                    <span class="card-text text text-primary" name='role_us[]'> <?php
                                                                                if ($row['role'] == 0) {
                                                                                  echo 'Admin';
                                                                                } else {
                                                                                  echo 'Người dùng';
                                                                                }
                                                                                ?></span>
                    <p></p>
                    <a href="#" class="btn btn-primary" style="background-color:  rgb(118, 159, 205); border:none;">Xem</a>
                    <a href="#" class="btn btn-primary" style="background-color:  rgb(235, 29, 54); border:none;">Xóa</a>
                    <a href="#" class="btn btn-primary" style="background-color:  green; border:none;">Nâng cấp</a>
                  </div>
                </div>
              </div>
          <?php
            }
          }

          ?>
          <!-- 
          <div class="col-lg-3 col-md-4">
            <div class="card" style="width: 85%; background-color: rgb(247, 251, 252);border: 0px; z-index: 2">
              <img src="css/images/user3.png" class="card-img-top">
              <div class="card-body">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <a href="#" class="btn btn-primary" style="background-color:  rgb(118, 159, 205); border:none;">Xem</a>
                <a href="#" class="btn btn-primary" style="background-color:  rgb(235, 29, 54); border:none;">Xóa</a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-4">
            <div class="card" style="width: 85%; background-color: rgb(247, 251, 252);border: 0px; z-index: 2">
              <img src="css/images/user3.png" class="card-img-top">
              <div class="card-body">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <a href="#" class="btn btn-primary" style="background-color:  rgb(118, 159, 205); border:none;">Xem</a>
                <a href="#" class="btn btn-primary" style="background-color:  rgb(235, 29, 54); border:none;">Xóa</a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-4">
            <div class="card" style="width: 85%; background-color: rgb(247, 251, 252);border: 0px; z-index: 2">
              <img src="css/images/user3.png" class="card-img-top">
              <div class="card-body">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <a href="#" class="btn btn-primary" style="background-color:  rgb(118, 159, 205); border:none;">Xem</a>
                <a href="#" class="btn btn-primary" style="background-color:  rgb(235, 29, 54); border:none;">Xóa</a>
              </div>
            </div>
          </div> -->
        </div>
      </article>
    </section>
  </div>
  <footer>
    <p>Footer</p>
  </footer>
  <script>
    let popup = document.getElementById("popup");

    function openPopup() {
      popup.classList.add("open-popup");
    }

    function closePopup() {
      popup.classList.remove("open-popup");
    }
    $(document).ready(function() {
      $("#searchCharacter").on('input', function() {
        var char = $("#searchCharacter").val();
        var email_us = document.getElementsByName("email_us[]");
        var name_us = document.getElementsByName('name_us[]');
        var role_us = document.getElementsByName('role_us[]');
        var gender = document.getElementsByName("gender[]");
        var arr = [];
        for (i = 0; i < email_us.length; i++) {
          $str = email_us[i].textContent.replace(/\s/g, '');
          arr.push($str);
        }
        var result = [];
        for (i = 0; i < arr.length; i++) {
          if (arr[i].includes(char)) {
            result.push(i);
          }
        }
        console.log(result);
        var result_html = "";
        for (j = 0; j < result.length; j++) {
          result_html = result_html + "<div class=\"col-lg-3 col-md-4\" style=\"margin-bottom:20px\">" +
            "<div class=\"card\" style=\"width: 95%;height: 100%; background-color: rgb(247, 251, 252);border: 2px solid ; z-index: 2;\">" +
            "<input type=\"hidden\" name=\"gender[]\" value=\"" + gender[result[j]].value + ">" +
            (gender[result[j]].value == 0) ? "<img src=\"css/images/girl.png\" class=\"card-img-top\">" :
            "<img src=\"css/images/user3.png\" class=\"card-img-top\">" +
            "<div class=\"card-body\">" +
            "<span class=\"text text-primary\">Tên:</span>" +
            "<span class=\"card-text text text-primary\" name='name_us[]'>" + name_us[result[j]].textContent + "</span>";
          "<p></p>" +
          "<span class=\"text text-primary\">Email: </span>" +

          "<span class=\"card-text text text-primary\" name='email_us[]'>" +
            email_us[result[j]].textContent.substring(0, 19) + '...' +
            "</span>" +
            "<p></p>" +
            "<span class=\"text text-primary\">" +
            "Vai trò:" +
            "</span>" +
            "<span class=\"card-text text text-primary\" name='role_us[]'>"+
            (role_us[result[j]].textContent == 0) ? "Admin>" : "Người dùng" +"</span>"+
            "<p> </p>" +
            "<a href = \"#\"class = \"btn btn-primary\" style = \"background-color:  rgb(118, 159, 205); border:none;\" > Xem </a> " +
            "<a href = \"#\" class = \"btn btn-primary\" style = \"background-color:  rgb(235, 29, 54); border:none;\" > Xóa </a>" +
            "<a href = \"#\" class = \"btn btn-primary\" style = \"background-color:  green; border:none;\"> Nâng cấp </a> " +
            "</div>" +
            "</div> " +
            "</div>";

        }
        document.getElementById("display_us").innerHTML = result_html;
      })
    })
  </script>
</body>

</html>