<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('vendor/autoload.php');

$username = $_SESSION['user'];
$role = $_SESSION['role'];
include_once("./config.php");
$connect = connect();
$prefix = getPrefix();
$er = "";
if (isset($_POST['submit']) && $_POST['submit'] == 'Đăng kí') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $pass = $_POST['password'];
  $pass_again = $_POST['pass_again'];
  $gt=$_POST['gender'];
  if (empty($name) && empty($email) && empty($phone) && empty($pass) && empty($pass_again) && empty($gender)) {
    $er = "Vui lòng nhập đầy đủ thông tin";
  }
  else {
    if(check_exist_email($connect,$email)){
      $err = "Email này đã tồn tại.Vui lòng thử lại";
    }
    else{
      if (strlen($pass) < 6) {
        $er = "Mật khẩu phải có độ dài từ 6 ký tự trở lên.";
    } else if ($pass !== $pass_again) {
        $er = "Mật khẩu và nhập lại mật khẩu không trùng khớp. Vui lòng thử lại.";
    }
    else{
        $password = md5($_POST['password']);
        $token = md5(random_int(100000, 999999));
        if (sendToken($name, $email, $token)) {
          $sql_add = "INSERT INTO users(username,password,role,size_page,use_size,name,gender,phone,token) VALUES('" . $email . "','" . $password . "','0','0 ','0','" . $name . "','".$gt."','".$phone."','" . $token . "')";
          $query = mysqli_query($connect, $sql_add);
          // header("Location:login.php");
          if ($query) {
            $er = "Đăng ký thành công.Vui lòng kiểm tra lại email để xác nhận danh tính.";
          } else {
            echo "Error: " . mysqli_error($connect);
          }
        }
      }

    }
  }
  
    
}
function sendToken($name, $email, $token)
    {
      $prefix = getPrefix();
      $mail = new PHPMailer(true);
      try {
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8'; //Send using SMTP
        $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = 'vytuong2903@gmail.com'; //SMTP username
        $mail->Password = 'dylbprsjmoxllict'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 587; //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('vytuong2903@gmail.com', 'File Manager');
        $mail->addAddress($email, 'Receiver'); //Add a recipient

        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = 'Xác nhận danh tính';
        $mail->Body = "<p>Xin chào, '" . $name . "'! Tôi là quản trị viên trang web File Manager,</p>
        <p>Bạn đã được thêm vào đội ngũ quản trị viên của trang web!</p>
        <p>Click vào<a href ='$prefix/verify_user.php?email=$email&token=$token'> đây</a> để đăng nhập!</p>";
        $mail->send();
        return true;
      } catch (Exception $e) {
        echo $e;
        return false;
      }
    }

  function check_exist_email($connect, $email_register)
  {
    $sql_email = "SELECT * FROM users WHERE username='" . $email_register . "'";
    $query_email = mysqli_query($connect, $sql_email);
    $num_row = mysqli_num_rows($query_email);
    if ($num_row > 0) {
      return true;
    } else {
      return false;
    }
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/addUser.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  <title>Thêm Quản Trị Viên</title>
</head>

<body>

  <body>
    <div class="container">
      <div>

        <a href="indexAdmin.php"><i class="material-icons">arrow_back</i></a>
      </div>
      <div class="title">Thông Tin Người Dùng</div>
      <div class="content">
        <form action="#" method="POST">
          <div class="user-details">
            <div class="input-box">
              <span class="details">Họ và tên</span>
              <input type="text" placeholder="Vui lòng nhập họ và tên" name="name" required>
            </div>

            <div class="input-box">
              <span class="details">Email</span>
              <input type="email" placeholder="Vui lòng nhập email" name="email" required>
            </div>
            <div class="input-box">
              <span class="details">Số điện thoại</span>
              <input type="text" placeholder="Vui lòng nhập số điện thoại" name="phone" required>
            </div>
            <div class="input-box">
              <span class="details">Mật khẩu</span>
              <input type="password" placeholder="Vui lòng nhập mật khẩu" name="password" required>
            </div>
            <div class="input-box">
              <span class="details">Xác nhận mật khẩu</span>
              <input type="password" placeholder="Vui lòng xác nhận mật khẩu" name="pass_again" required>
            </div>
          </div>
          <div class="gender-details">
            
              <input type="radio" name="gender" id="dot-1" value="1" checked>
              <input type="radio" name="gender" id="dot-2" value="0">
            <span class="gender-title">Giới tính</span>
            <div class="category" style="padding-right:45%;padding-left:5%;">
              <label for="dot-1">
                <span class="dot one"></span>
                <span class="gender">Nam</span>
              </label>
              <label for="dot-2">
                <span class="dot two"></span>
                <span class="gender">Nữ</span>
              </label>
            </div>
          </div>
          <p style="color:red"><?php if (isset($er)) echo $er ?></p>
          <div class="button">
            <input type="submit" value="Đăng kí" name="submit">
          </div>
        </form>
      </div>
    </div>

  </body>
</body>

</html>