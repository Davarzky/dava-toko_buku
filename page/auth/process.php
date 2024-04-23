<?php
session_start();

require "../../config/database.php";

if (isset($_POST['login'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $_SESSION['error'] = "Username dan password harus diisi";
        header("Location: login.php");
        exit();
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        print_r($_POST);
        $db = new db();
        $db->koneksi();

        $query = "SELECT * FROM kasir WHERE username='$username'";
        $result = $db->ambil_data($query);

        if (count($result) > 0) {
            $p_password = $result[0]['password'];
            $password = $_POST['password'];
        
            if ($p_password === $password) {
                $_SESSION['username'] = $username;
                header("Location: ../../layout/index.php");
                exit();
            } else {
                $_SESSION['error'] = "Password tidak sesuai";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Username tidak terdaftar";
            header("Location: login.php");
            exit();
        }
        
    }
} else {
    header("Location: login.php");
    exit();
}
?>
