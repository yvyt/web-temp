<?php
$action = 1;
if (isset($_GET['action']) && $_GET['action'] == 2) {
    $action = 2;
}
session_start();
include_once("./config.php");
$connect = connect();
$username = $_SESSION['user'];
$sql = "SELECT * FROM users WHERE username='" . $username . "' LIMIT 1";
$query = mysqli_query($connect, $sql);
$name = "";
$use_size = 0;
$password = "";
$er = "";
if ($num = mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_array($query)) {
        $name = $row['name'];
        $use_size = $row['use_size'];
        $password = $row['password'];
    }
}
if (isset($_POST['submit']) && $_POST['submit'] == 'change_pass') {
    $old = md5($_POST['old_pass']);
    $new = $_POST['new_pass'];
    $new_again = $_POST['new_pass_again'];

    if (empty($old) || empty($new) || empty($new_again)) {
        $er = "Vui lòng nhập đầy đủ thông tin";
    } else if (strlen($new) < 6 || strlen($new_again) < 6) {
        $er = "Mật khẩu phải từ 6 chữ ký tự trở lên.";
    } else {
        if ($password !== $old) {
            $er = "Mật khẩu cũ không chính xác. Vui lòng nhập lại";
        }else if($password===md5($new)){
            $er = "Mật khẩu cũ và mật khẩu mới trùng nhau. Vui lòng nhập lại";
        } 
        else {
            if ($new == $new_again) {
                $hashed = md5($new);

                $query = "UPDATE users SET password = '$hashed' WHERE username = '$username'";

                $res = mysqli_query($connect, $query);
                if($res){
                    $er = "Mật khẩu đã cập nhật thành công";
                }
                
            } else {
                $er = "Mật khẩu không khớp";
            }
        }
    }
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cài đặt tài khoản</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container" style="margin-top:24px">
        <div class="row">
            <div class="list-group col-2">
                <a href="account_setting.php?action=1" class="list-group-item list-group-item-action <?php if($_GET['action'] == 1) echo "bg-info"; else echo "bg-white";?>" aria-current="true">
                    Thông tin tài khoản
                </a>
                <a href="account_setting.php?action=2" class="list-group-item list-group-item-action <?php if($_GET['action'] == 2) echo "bg-info"; else echo "bg-white";?>">Đổi mật khẩu</a>

            </div>
            <?php
            if ($action == 1) {
            ?>
                <div class="col-10">
                    <div class="card">
                        <div class="card-header"> Thông tin cá nhân</div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Họ tên</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="name_us" aria-describedby="emailHelp" value="<?php echo $name ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="exampleInputPassword1" name="email_us" value="<?php echo $username ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Dung lượng đã dùng</label>
                                    <input type="text" class="form-control" id="exampleInputPassword1" name="email_us" value="<?php echo $use_size ?>">
                                </div>
                            </form>
                            <a class="btn btn-primary" name="backBtn" href="index.php">Trở về</a>
                            <button type="submit" class="btn btn-primary" name="submit">Lưu</button>
                        </div>
                    </div>
                </div>
            <?php

            } else if ($action == 2) {
            ?>
                <div class="col-10">
                    <div class="card">
                        <div class="card-header">Đổi mật khẩu</div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Mật khẩu cũ</label>
                                    <input type="password" class="form-control" id="exampleInputEmail1" name="old_pass" aria-describedby="emailHelp">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" name="new_pass">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Nhập lại mật khẩu</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" name="new_pass_again">
                                </div>
                                <p class="text text-danger"><?php
                                                                if (isset($er)) {
                                                                    if ($er == 'Mật khẩu đã cập nhật thành công') {
                                                                        echo $er;
                                                                        
                                                                    }
                                                                    else {
                                                                        echo $er;
                                                                    }
                                                                }
                                                                
                                                                ?>
                                </p>
                                <a type="submit" class="btn btn-primary" value="change_pass" name="submit" href="index.php">Đổi mật khẩu</a>
                            </form>

                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

</body>

</html>