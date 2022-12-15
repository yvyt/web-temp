<?php
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $t = date('y-m-d h:i:s');
    include_once("./config.php");
    $connect=connect();
    if(isset($_GET['id'])){
        $id=$_GET['id'];
        $sql= "UPDATE file SET priority='1',modify='$t' WHERE id='$id'";
        $run=mysqli_query($connect,$sql);
        if($run){
            header("Location:priority.php");
        }
        else{
            echo mysqli_errno($connect);
        }
    }
    
    if(isset($_GET['huy'])){
        $id = $_GET['huy'];
        $sql = "UPDATE file SET priority='0',modify='$t' WHERE id='$id'";
        $run = mysqli_query($connect, $sql);
        if ($run) {
            header("Location:priority.php");
        } else {
            echo mysqli_errno($connect);
        }
    }


?>