<?php
    // include_once("./config.php");
    // $connect = connect();
    session_start();
    $_SESSION['assign_path'] = array();
    if(isset($_POST['change_path'])) {
        $_SESSION['assign_folder'] = $_POST['change_path'];
        array_push($_SESSION['assign_path'], $_POST['change_path']);
        foreach ($_SESSION['assign_path'] as $key) {
            echo $key;
            echo '<br>';
        }
        echo '<br>';
        echo $_SESSION['assign_folder'];
    }
?>