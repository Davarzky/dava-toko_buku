<?php
include '../../layout/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../config/database.php';

    $database = new db();
    $database->koneksi();

    $tanggal = $_POST['tanggal'];

    foreach ($_POST['id_buku'] as $key => $id_buku) {
        $jumlah = $_POST['jumlah'][$key];

        if (!empty($id_buku) && !empty($jumlah)) {
            $sql = "SELECT harga_jual FROM buku WHERE id_buku = $id_buku";
            $harga_jual = $database->ambil_data($sql)[0]['harga_jual'];

            $total_harga = $jumlah * $harga_jual;

            $sql = "INSERT INTO penjualan (id_buku, jumlah, harga, tanggal) VALUES ('$id_buku', '$jumlah', '$total_harga', '$tanggal')";
            $berhasil = $database->modifikasi($sql);

            if ($berhasil) {
                echo '<div class="alert alert-success" role="alert">Penjualan berhasil disimpan.</div>';
                header('Location: index.php');
            } else {
                echo '<div class="alert alert-danger" role="alert">Penjualan gagal disimpan. Silakan coba lagi.</div>';
            }
        }
    }
}
?>

<div class="container">
    <h1>Form Penjualan</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-3">
            <label for="id_buku" class="form-label">Pilih Buku</label>
            <select class="form-select" id="id_buku" name="id_buku[]" multiple>
                <?php
                include '../../config/database.php';
                $database = new db();
                $database->koneksi();
                $sql = "SELECT * FROM buku";
                $hasil = $database->ambil_data($sql);
                foreach ($hasil as $row) :
                    ?>
                    <option value="<?= $row['id_buku'] ?>" data-harga="<?= $row['harga_jual'] ?>"><?= $row['judul'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="jumlah_input">
        </div>
        <button type="button" class="btn btn-secondary" onclick="tambahPembelian()">Tambah Pembelian</button>
        <button type="button" class="btn btn-primary" onclick="hitungTotal()">Hitung Total</button>
        <div class="mb-3">
            <label for="total" class="form-label">Total Harga</label>
            <input type="text" class="form-control" id="total" name="total" readonly>
        </div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script>
    function tambahInputJumlah() {
        var selectedOptions = document.querySelectorAll('#id_buku option:checked');
        var jumlahInputHTML = '';
        selectedOptions.forEach(function(option) {
            var judulBuku = option.textContent;
            var idBuku = option.value;
            jumlahInputHTML += '<div class="mb-3">' +
                '<label for="jumlah_' + idBuku + '" class="form-label">Jumlah ' + judulBuku + '</label>' +
                '<input type="number" class="form-control" id="jumlah_' + idBuku + '" name="jumlah[]" min="1">' +
                '</div>';
        });
        document.getElementById('jumlah_input').innerHTML = jumlahInputHTML;
    }

    document.getElementById('id_buku').addEventListener('change', tambahInputJumlah);

    function tambahPembelian() {
        var tambahPembelianButton = document.createElement('button');
        tambahPembelianButton.type = 'button';
        tambahPembelianButton.className = 'btn btn-secondary';
        tambahPembelianButton.textContent = 'Hapus Pembelian';
        tambahPembelianButton.onclick = function() {
            this.parentNode.remove();
        };

        var div = document.createElement('div');
        div.className = 'mb-3';
        div.appendChild(tambahPembelianButton);

        var selectBuku = document.getElementById('id_buku').cloneNode(true);
        div.appendChild(selectBuku);

        var jumlahInput = document.createElement('input');
        jumlahInput.type = 'number';
        jumlahInput.className = 'form-control';
        jumlahInput.name = 'jumlah[]';
        jumlahInput.min = '1';
        div.appendChild(jumlahInput);

        document.getElementById('jumlah_input').appendChild(div);
    }

    function hitungTotal() {
        var total = 0;
        var jumlahInputs = document.getElementsByName('jumlah[]');
        var hargaInputs = document.querySelectorAll('#id_buku option:checked');

        for (var i = 0; i < jumlahInputs.length; i++) {
            var jumlah = parseInt(jumlahInputs[i].value);
            var harga = parseInt(hargaInputs[i].getAttribute('data-harga'));
            total += jumlah * harga;
        }

        document.getElementById('total').value = total;
    }
</script>
<?php

include '../../layout/footer.php';

?>