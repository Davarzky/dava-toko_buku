<?php

session_start();

include '../../config/database.php';

if (isset($_GET['id_buku'])) {
    $id = $_GET['id_buku'];
    
    $database = new db();
    $database->koneksi();

    
    $query = "DELETE FROM buku WHERE id_buku = $id";
    $result = $database->modifikasi($query);
    
    if ($result) {
        
        $_SESSION['message'] = "Data berhasil dihapus.";
        
        header("Location: index.php");
        exit(); 
    } else {
        echo "Gagal menghapus buku dengan id $id.";
    }
} else {
    echo "Parameter id_buku tidak ditemukan.";
}
?>
