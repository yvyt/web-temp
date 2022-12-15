<?php
session_start();
include_once('./config.php');
$connect = connect();
$login = false;
$username = "";
$email;
$name = "";
$role = $_SESSION['role'];
if ($role == 0) {
    header('Location: indexAdmin.php');
    exit();
}
if (!isset($_SESSION['user'])) {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}
$is_use = 0;
$max = 0;
$login = true;
$email = $_SESSION['user'];
$sql = "SELECT * FROM users WHERE username='" . $email . "' LIMIT 1";
$query = mysqli_query($connect, $sql);
$num_row = mysqli_num_rows($query);
if ($num_row > 0) {
    $data = mysqli_fetch_assoc($query);
    $name = $data['name'];
    $is_use = $data['use_size'];
    $max = $data['size_page'];
}
if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
    unlink($_SESSION['url']);
    unset($_SESSION['user']);
    header('Location: login.php');
    exit();
}
$sql_sl = "SELECT * FROM file WHERE priority='1' and deleted=0";
$run_sql = mysqli_query($connect, $sql_sl);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styleAdmin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <title>Document</title>
</head>

<body>
    <header>
        <h>Quan trọng</h>
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
                        <input class="form-control me-2" type="search" placeholder="Tìm kiếm" aria-label="Search">
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

                                <li><a class="dropdown-item" href="index.php?dangxuat=1">Đăng xuất</a></li>
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
                    <img src="css/images/folder3.png" width="15%" height="15%">
                    <button class="btn btn-secondary dropdown-toggle" id="dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Thư mục của tôi
                    </button>
                    <ul class="dropdown-menu" id="dropdownUL">
                        <li><a class="dropdown-item" href="index.php">Thư mục gốc</a></li>
                        <li><a class="dropdown-item" href="#">Thêm thư mục</a></li>
                        <li><a class="dropdown-item" href="#">Quản lý thư mục</a></li>
                    </ul>
                </div>

                <div class="priority">
                    <img src="css/images/priority5.png" width="15%" height="15%">
                    <a class="btn" id="btnPriority" href="priority.php">Quan trọng</a>
                    <!-- <button type="button" class="btn btn-light" id = "btnShare">Đã chia sẻ</button> -->
                </div>

                <div class="dropdown">
                    <img src="./CSS/images/share7.png" width="15%" height="15%">
                    <button class="btn btn-secondary dropdown-toggle" id="dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Chia sẻ
                    </button>
                    <ul class="dropdown-menu" id="dropdownUL">
                        <li><a class="dropdown-item" href="share.php">Đã chia sẻ</a></li>
                        <li><a class="dropdown-item" href="share_with_me.php">Chia sẻ với tôi</a></li>

                    </ul>
                </div>
                <div class="recent">
                    <img src="css/images/recent1.png" width="15%" height="15%">
                    <a class="btn" id="btnRecent" href="recent.php">Gần đây</a>
                    <!-- <button type="button" class="btn btn-light" id = "btnShare">Đã chia sẻ</button> -->
                </div>
                <div class="trash">
                    <img src="css/images/trash1.png" width="15%" height="15%">
                    <a class="btn" id="btnTrash" href="trash.php">Thùng rác</a>
                    <!-- <button type="button" class="btn btn-light" id = "btnShare">Đã chia sẻ</button> -->
                </div>
                <div class="share">
                    <img src="./CSS/images/priority2.png" width="15%" height="15%">
                    <a class="btn" id="btnTrash" href="#">Dung lượng</a>
                    <div class="progress">
                        <?php
                        $now_us = ($is_use / $max) * 100;
                        ?>
                        <div class="progress-bar w-<?php echo $now_us ?>" role="progressbar" aria-valuenow="<?php echo $is_use ?>" aria-valuemin="0" aria-valuemax="<?php echo $max ?>"></div>
                    </div>
                </div>
                <div class="priority">

                    <a class="btn" id="btnPriority" href="priority.php"></a>
                    <!-- <button type="button" class="btn btn-light" id = "btnShare">Đã chia sẻ</button> -->
                </div>

            </nav>

            <article id="art2">
                <div class="row">
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Quan trọng</li>
                        </ol>
                    </nav>
                    <?php
                    if ($num = mysqli_num_rows($run_sql) > 0) {
                        while ($row = mysqli_fetch_array($run_sql)) {
                    ?>
                            <div class="col-lg-3 col-md-3">
                                <div class="card" style="width: 85%; background-color: rgb(247, 251, 252);border: 0px;">
                                    <img src="./<?php echo $row['image'] ?>" class="card-img-top">
                                    <div class="card-body">
                                        <p class="card-text"><?php
                                                                if (strlen($row['file_name']) > 20) {
                                                                    echo substr($row['file_name'], 0, 19) . '...';
                                                                } else {
                                                                    echo $row['file_name'];
                                                                }
                                                                ?></p>
                                        <div class="dropdown" id="dropdownThuMuc" style=" background-color: rgb(247, 251, 252);color: rgb(0, 74, 124);font-family: 'Times New Roman', Times, serif;">
                                            <button id="dropDownOfFile" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="css/images/3dot.png" width="15%" height="15%"> </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Tải về</a></li>
                                                <li><a class="dropdown-item" href="#">Đổi tên tập tin</a></li>
                                                <li><a class="dropdown-item" href="#">Xem chi tiết</a></li>
                                                <li><a class="dropdown-item" href="#">Chia sẻ</a></li>
                                                <li><a class="dropdown-item" href="set_starred.php?huy=<?php echo $row['id'] ?>">Bỏ khỏi mục quan trọng</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deleted(<?php echo $row['id'] ?>)">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<h2 style=\"text-align:center\">Chưa có dữ liệu quan trọng nào!</h2>";
                    }

                    ?>
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

        function deleted(id) {
            var del = confirm("Bạn có chắc chắn xóa file này không? File sẽ được chuyển vào thùng rác và tự động xóa sau 30 ngày.");
            var form_data = new FormData();
            form_data.append("id", id);
            if (del == true) {
                console.log(id);
                $.ajax({
                    url: "deleted.php",
                    type: "POST",
                    dataType: 'script',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function(dat2) {
                        alert(dat2);
                        location.reload();
                    }
                });
            }
            return del;
        }
    </script>
</body>

</html>