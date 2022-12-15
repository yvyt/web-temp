<?php
session_start();
include_once('./config.php');
$connect = connect();
$login = false;
$username = "";
$email;
$name = "";
$role = $_SESSION['role'];

if (isset($_SESSION['assign_path'])) {
    $_SESSION['path'] = $_SESSION['assign_path'];
} else {
    $_SESSION['path'] = array();
}
if (isset($_SESSION['assign_folder'])) {
    $_SESSION['cur_folder'] = $_SESSION['assign_folder'];
} else {
    $_SESSION['cur_folder'] = 'NULL';
}
$cur_path = '';

if ($role == 0) {
    header('Location: indexAdmin.php');
    exit();
}
if (!isset($_SESSION['user'])) {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}
$login = true;
$email = $_SESSION['user'];
$is_use = 0;
$max = 0;
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

$sql_select;
if ($_SESSION['cur_folder'] == 'NULL') {
    $sql_select = "SELECT * FROM file WHERE username='" . $email . "' and deleted='0' and folder is NULL";
} else {
    $sql_select = "SELECT * FROM file WHERE username='" . $email . "' and deleted='0' and folder ='" . $_SESSION['cur_folder'] . "'";
}
$run = mysqli_query($connect, $sql_select);

$num = mysqli_num_rows($run);
if (isset($_POST['submit']) && $_POST['submit'] = "submit-search") {
    $search = strtok($_POST['search'], ".");
    $search = mysqli_escape_string($connect, $search);
    $sql_search = "SELECT * FROM file WHERE username='" . $email . "' and deleted='0' and SUBSTRING_INDEX(file_name,'.',1) LIKE '%$search%'";
    $run = mysqli_query($connect, $sql_search);
    $num = mysqli_num_rows($run);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style_index.css?v=<?php echo time(); ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <title>Trang chủ</title>
</head>

<body>
    <header>
        <h>File Manager</h>
    </header>
    <div class="container-">
        <nav class="navbar navbar-expand-lg" id="navbar1">
            <div class="container-fluid">
                <img src="./CSS/images/logo.jpg" height="50px" width="50px" style="border-radius: 50px;">
                <a class="navbar-brand" href="index.php" style="padding-left: 50px;color: rgb(66, 72, 116);">Trang
                    Chủ</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <form action="index.php" method="POST" class="d-flex" role="search" style="width: 60%; padding-left:10%;">
                        <input class="form-control me-2" name="search" value="<?php echo (isset($search)) ? $search : ''; ?>" type="search" placeholder="Tìm kiếm" aria-label="Search">
                        <button class="btn btn-outline-success" name="submit" value="submit-search" type="submit">Tìm</button>
                    </form>
                    <ul>
                        <li class="nav-item dropdown" id="login">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php
                                if ($name != "") {
                                    if ($_SESSION['cur_folder'] == "NULL") {
                                        echo $name . ' - ' . 'Root';
                                    } else {
                                        echo $name . ' - ' . $_SESSION['cur_folder'];
                                    }
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
                    <img src="./CSS/images/folder3.png" width="15%" height="15%">
                    <button class="btn btn-secondary dropdown-toggle" id="dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Thư mục của tôi
                    </button>
                    <ul class="dropdown-menu" id="dropdownUL">
                        <li><a class="dropdown-item" href="index.php">Thư mục gốc</a></li>
                        <li><a class="dropdown-item" href="#" onclick="openPopupFolder()">Thêm thư mục</a></li>
                        <li><a class="dropdown-item" href="#">Quản lý thư mục</a></li>
                    </ul>
                </div>
                <div class="AddFile">
                    <img src="./CSS/images/Add.png" width="15%" height="15%">
                    <button type="button" class="btn btn-light" id="btnAdd" onclick="openPopup()">Thêm tập tin</button>
                    <div class="popup" id="popup">
                        <form style=" background: linear-gradient(135deg, #71b7e6, #9b59b6); border-radius:10px; padding:20px">
                            <h style=" color: black; font-size: 25px; font-family: 'Times New Roman', Times, serif; margin-left: 35%;">
                                Chọn tập tin </h>
                            <!-- <input class="form-control" type="text" id="nameFile" placeholder="Tên tệp tin"> -->
                            <input class="form-control" type="file" id="formFile">
                            <p id="error" style="text-align:center;color:red"></p>
                            <div class="formAdd" style="display: flex;">
                                <button type="button" id="btnAddFile" onclick="uploadFile()"> Thêm </button>
                                <button type="button" id="btnCancel" onclick="closePopup()"> Hủy </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div>
                    <div class="popup" id="popupFolder">
                        <form style=" background: linear-gradient(135deg, #71b7e6, #9b59b6); border-radius:10px; padding:20px">
                            <h style=" color: black; font-size: 25px; font-family: 'Times New Roman', Times, serif; margin: 25%">
                                Nhập tên thư mục </h>
                            <!-- <input class="form-control" type="text" id="nameFile" placeholder="Tên tệp tin"> -->
                            <input class="form-control" type="text" id="newFolderName">
                            <p id="error" style="text-align:center;color:red"></p>
                            <div class="formAdd" style="display: flex;">
                                <button type="button" id="btnAddFile" onclick="createFolder()"> Tạo </button>
                                <button type="button" id="btnCancel" onclick="closePopupFolder()"> Hủy </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="priority">
                    <img src="./CSS/images/priority5.png" width="15%" height="15%">
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
                    <img src="./CSS/images/recent1.png" width="15%" height="15%">
                    <a class="btn" id="btnRecent" href="recent.php">Gần đây</a>
                    <!-- <button type="button" class="btn btn-light" id = "btnShare">Đã chia sẻ</button> -->
                </div>
                <div class="trash">
                    <img src="./CSS/images/trash1.png" width="15%" height="15%">
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

            </nav>

            <div style="display: none;">Here</div>
            <article id="art2">
                <div class="row">
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" onclick="changePath('NULL')">Thư mục gốc</a></li>
                            <!-- other folders -->
                            <?php
                            $variable = $_SESSION['path'];
                            foreach ($variable as $key) {
                                if ($key != 'NULL') {
                            ?>
                                    <li class="breadcrumb-item"><a href="#" onclick="changePath('<?= $key ?>')"><?= $key ?></a></li>
                            <?php
                                }
                            }
                            ?>
                            <li class="breadcrumb-item"><a href="#"></a></li>
                        </ol>
                    </nav>
                    <?php
                    $select_folder;
                    if ($_SESSION['cur_folder'] == 'NULL') {
                        $select_folder = "SELECT * FROM folder WHERE username='" . $email . "' and deleted='0' and parent is NULL";
                    } else {
                        $select_folder = "SELECT * FROM folder WHERE username='" . $email . "' and deleted='0' and parent ='" . $_SESSION['cur_folder'] . "'";
                    }
                    $exec_folder = mysqli_query($connect, $select_folder);
                    if (mysqli_num_rows($exec_folder) != 0) {
                        while ($row = mysqli_fetch_array($exec_folder)) {
                    ?>
                            <div class="col-lg-3 col-md-3">
                                <div class="card" style="width: 85%; background-color: rgb(247, 251, 252);border: 0px;">
                                    <img src="./CSS/images/folder.webp" class="card-img-top">
                                    <div class="card-body">
                                        <p class="card-text" id="folder_name"><?php echo $row['name'] ?></p>
                                        <div class="dropdown" id="dropdownThuMuc" style=" background-color: rgb(247, 251, 252);color: rgb(0, 74, 124);font-family: 'Times New Roman', Times, serif;">
                                            <button id="dropDownOfFile" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="./CSS/images/3dot.png" width="15%" height="15%"> </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Tải về</a></li>
                                                <li><a class="dropdown-item" href="#">Đổi tên thư mục</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="changePath('<?php echo $row['name'] ?>')">Xem chi tiết </a></li>
                                                <li><a class="dropdown-item" href="#">Chia sẻ</a></li>
                                                <li><a class="dropdown-item" href="#">Thêm vào quan trọng</a></li>
                                                <li><a class="dropdown-item" href="#">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>


                    <?php
                    if ($num == 0) {
                        echo "<h2 style=\"text-align:center\">Chưa có dữ liệu lưu trữ</h2>";
                    } else {
                        while ($row = mysqli_fetch_array($run)) {
                    ?>
                            <div class="col-lg-3 col-md-3">
                                <div class="card" style="width: 85%; background-color: rgb(247, 251, 252);border: 0px;">
                                    <img src="./<?php echo $row['image'] ?>" class="card-img-top" height="256px" height="256px">
                                    <div class="card-body">
                                        <p class="card-text" id="file_name">
                                            <?php
                                            if (strlen($row['file_name']) > 20) {
                                                echo substr($row['file_name'], 0, 19) . '...';
                                            } else {
                                                echo $row['file_name'];
                                            }
                                            ?>
                                        </p>
                                        <div class="dropdown" id="dropdownThuMuc" style=" background-color: rgb(247, 251, 252);color: rgb(0, 74, 124);font-family: 'Times New Roman', Times, serif;">
                                            <button id="dropDownOfFile" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="./CSS/images/3dot.png" width="15%" height="15%"> </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Tải về</a></li>
                                                <li><a class="dropdown-item" href="#">Đổi tên tập tin</a></li>
                                                <li><a class="dropdown-item" href="#">Xem chi tiết </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="openShare(<?php echo $row['id'] ?>)">Chia sẻ</a></li>
                                                <li><a class="dropdown-item" href="set_starred.php?id=<?php echo $row['id'] ?>">
                                                        Thêm vào quan trọng</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deleted(<?php echo $row['id'] ?>)">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>

                </div>
                <div class="shareFile">

                    <div class="popup" id="share">
                        <form style=" background: linear-gradient(135deg, #71b7e6, #9b59b6); border-radius:10px; padding:20px">
                            <h style=" color: black; font-size: 25px; font-family: 'Times New Roman', Times, serif; margin-left: 35%;">
                                Chia sẻ </h>
                            <input class="form-control" type="text" id="users">
                            <input class="form-control" type="hidden" id="id_file">
                            <p id="error" style="text-align:center;color:red"></p>
                            <div class="form" style="display: flex;">
                                <button type="button" id="btnAddFile" onclick="shareFile()"> Thêm </button>
                                <button type="button" id="btnCancel" onclick="closeShare()"> Hủy </button>
                            </div>
                        </form>
                    </div>
                </div>

            </article>
        </section>
    </div>
    <footer>
        <p>Footer</p>
    </footer>
    <script>
        let popup = document.getElementById("popup");
        let popupFolder = document.getElementById("popupFolder");

        function openPopup() {
            popup.classList.add("open-popup");
        }

        function openShare(id) {

            document.getElementById("share").classList.add("open-popup");
            document.getElementById("id_file").value = id;
        }

        function closePopup() {
            popup.classList.remove("open-popup");
        }

        function closeShare() {
            document.getElementById("share").classList.remove("open-popup");
        }

        function uploadFile() {
            var file_name = $("#formFile").val().split('\\').pop();
            var file_data = $('#formFile').prop('files')[0];

            var er = document.getElementById("error");

            if ($('#formFile')[0].files.length === 0) {
                er.innerHTML = ("Vui lòng chọn file");
            } else {
                var form_data = new FormData();
                form_data.append("file", file_data);
                form_data.append("filename", file_name);
                form_data.append("folder", '<?= $_SESSION['cur_folder'] ?>')
                $.ajax({
                    url: "upload.php",
                    type: "POST",
                    dataType: 'script',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function(dat2) {
                        window.alert(dat2);
                        $("#nameFile").val("");
                        $('#formFile').val($('#formFile')[0].files.defaultValue);
                        location.reload();
                    }
                });
            }
        }

        function createFolder() {

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
            } else {

            }
            return del;
        }

        function shareFile() {
            users = document.getElementById("users").value;
            id_file = document.getElementById("id_file").value;
            var is_all = 0; //false
            if (users === '') {
                is_all = 1;
            }
            var user_arr = [];
            if (users !== '') {
                user_arr = users.split(",");

            }

            user_share = JSON.stringify(user_arr);
            console.log(user_share);
            var form_data = new FormData();
            form_data.append("id_file", id_file);
            form_data.append("users", user_share);
            form_data.append("isAll", is_all);
            $.ajax({
                url: "shareFile.php",
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

        function openPopupFolder() {
            popupFolder.classList.add("open-popup");
        }

        function closePopupFolder() {
            popupFolder.classList.remove("open-popup");
        }

        function changePath(cur) {
            console.log(cur)
            $.ajax({
                url: "folder_service.php",
                type: "POST",
                dataType: 'json',
                data: {
                    change_path: cur
                },
                success: function(dataResponse) {
                    console.log("change path ok")
                },
                error: function(dataResponse) {
                    console.log("change path khok")
                }
            });
            location.href = 'index.php';
        }
    </script>
</body>

</html>