<?php
    include_once("./config.php");
    $connect=connect();
    session_start();
    $folder_name=$_SESSION['user'];

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $t = date('y-m-d h:i:s');
    if(isset($_GET['xoa']) && $_GET['xoa']==1){
        $dir="files/".$folder_name.'/';
        $id_delete=$_POST['id'];
        $file_name='';
        $file_size=0;
        $use_size=0;
        $sql_us="SELECT * FROM users WHERE username='$folder_name'";
        $run_qr=mysqli_query($connect,$sql_us);
        if($run_qr){
            $d=mysqli_fetch_assoc($run_qr);
            $use_size=$d['use_size'];
        }
        $sql_sele="SELECT * FROM file WHERE id='$id_delete' LIMIT 1";
        $r=mysqli_query($connect,$sql_sele);
        if($r){
            if($num=mysqli_num_rows($r)>0){
                $data=mysqli_fetch_assoc($r);
                $file_name=$data['file_name'];
                $file_size=$data['size'];
            }
        }
        unlink($dir.$file_name);
        $sql_dele="DELETE FROM file WHERE id='$id_delete'";
        $query_dele=mysqli_query($connect,$sql_dele);
        if($query_dele){
            $new_size=$use_size-$file_size;
            echo $new_size;
            $update="UPDATE users SET use_size='$new_size' WHERE username='$folder_name'";
            $res=mysqli_query($connect,$update);
            if($res){
                echo 'Xóa thành công';
            }
        }
        else{
            echo 'Đã xảy ra sự cố trong quá trình xóa. Vui lòng thử lại';
        }
    }
    else if(isset($_GET['khoiphuc']) && $_GET['khoiphuc']==1){
        $id = $_POST['id'];
        $sql = "UPDATE file SET deleted='0',modify='$t' WHERE id='$id'";
        $query = mysqli_query($connect, $sql);
        if ($query) {
            echo 'Khôi phục thành công';
        } else {
            echo 'Xảy ra lỗi.Vui lòng thử lại';
        }
    }
    else {
        $id = $_POST['id'];
        $sql = "UPDATE file SET deleted='1' ,modify='$t' WHERE id='$id'";
        $query = mysqli_query($connect, $sql);
        if ($query) {
            echo 'Xóa thành công';
        } else {
            echo 'Xảy ra lỗi.Vui lòng thử lại';
        }
    }

?>