<?php
session_start();

include '../../config/database.php';
include '../../layout/header.php';



if (isset($_GET['id_buku'])) {
    $id = $_GET['id_buku'];

    $database = new db();
    $database->koneksi();

    $query = "SELECT * FROM buku WHERE id_buku = $id";
    $data = $database->ambil_data($query);

    if ($data) {
        $buku = $data[0];
    } else {
        $_SESSION['message'] = "Data buku tidak ditemukan.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Parameter id_buku tidak ditemukan.";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $stok = $_POST['stok'];
    $harga_jual = $_POST['harga_jual'];

    $query = "UPDATE buku SET judul = '$judul', penulis = '$penulis', penerbit = '$penerbit', stok = $stok, harga_jual = $harga_jual WHERE id_buku = $id";
    $result = $database->modifikasi($query);

    if ($result) {
        $_SESSION['message'] = "Data berhasil diupdate.";
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal mengupdate data buku.";
    }
}
?>

<main id="main" class="main">

<section class="section">
<form method="POST" action="">
    <div class="form-group">
        <label for="judul">Judul</label>
        <input type="text" class="form-control" id="judul" name="judul" value="<?= $buku['judul'] ?>">
    </div>
    <div class="form-group">
        <label for="penulis">Penulis</label>
        <input type="text" class="form-control" id="penulis" name="penulis" value="<?= $buku['penulis'] ?>">
    </div>
    <div class="form-group">
        <label for="penerbit">Penerbit</label>
        <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= $buku['penerbit'] ?>">
    </div>
    <div class="form-group">
        <label for="stok">Stok</label>
        <input type="number" class="form-control" id="stok" name="stok" value="<?= $buku['stok'] ?>">
    </div>
    <div class="form-group">
        <label for="harga_jual">Harga Jual</label>
        <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="<?= $buku['harga_jual'] ?>">
    </div>
    <a href="index.php" class="btn btn-danger">Cancel</a>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

</section>
</main>
<?php
include '../../layout/footer.php';
  
  ?>