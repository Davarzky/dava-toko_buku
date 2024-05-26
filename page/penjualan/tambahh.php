<?php
include '../../layout/header.php';
include '../../config/database.php';

$database = new db();
$database->koneksi();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $total = $_POST['total'];
    $kode_buku = $_POST['kode_buku'];
    $judul = $_POST['judul'];
    $penerbit = $_POST['penerbit'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];
    $diskon = $_POST['diskon'];
    $sub_total = $_POST['sub_total'];

    foreach ($kode_buku as $index => $id_buku) {
        $jumlah_buku = $jumlah[$index];
        $diskon_buku = $diskon[$index];
        $sub_total_buku = $sub_total[$index];

        $sql = "INSERT INTO penjualan (id_buku, jumlah, diskon, tanggal, sub_total) VALUES (?, ?, ?, ?, ?)";
        $params = [$id_buku, $jumlah_buku, $diskon_buku, $tanggal, $sub_total_buku];
        $database->execute_query($sql, $params);
    }

    header('Location: index.php');
    exit;
}

$query = "SELECT * FROM buku";
$buku = $database->ambil_data($query);

$buku_json = json_encode($buku);
?>

<div class="container mt-5">
    <form action="" method="post">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= date("Y-m-d") ?>" readonly>
            </div>
            <div class="col-md-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" id="total" class="form-control" name="total" readonly>
            </div>
        </div>
        <table class="table table-bordered" id="table-pembelian">
            <thead class="table-dark">
                <tr>
                    <th>Kode Buku</th>
                    <th>Judul</th>
                    <th>Penerbit</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Diskon</th>
                    <th>Sub Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tbody-pembelian">
                <tr>
                    <td><input type="text" class="form-control kode_buku" name="kode_buku[]" style="width: 100%;"></td>
                    <td><input type="text" class="form-control judul" name="judul[]" style="width: 100%;" readonly></td>
                    <td><input type="text" class="form-control penerbit" name="penerbit[]" style="width: 100%;" readonly></td>
                    <td><input type="text" class="form-control harga" name="harga[]" style="width: 100%;" readonly></td>
                    <td><input type="text" class="form-control jumlah" name="jumlah[]" style="width: 100%;"></td>
                    <td><input type="text" class="form-control diskon" name="diskon[]" style="width: 100%;"></td>
                    <td><input type="text" class="form-control sub_total" name="sub_total[]" readonly style="width: 100%;"></td>
                    <td><button type="button" class="btn btn-danger remove">Hapus</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary mt-3" id="tambah-baris">Tambah Pembelian</button>
        <button type="submit" class="btn btn-success mt-3">Simpan Penjualan</button>
    </form>
</div>

<script>
    let data_buku = <?= $buku_json ?>;
    console.log('Data Buku:', data_buku); 

    document.addEventListener('input', function(event) {
        if (event.target.classList.contains('kode_buku')) {
            let id_buku = event.target.value;
            let row = event.target.closest('tr');
            let buku = data_buku.find(b => b.id_buku == id_buku);
            console.log('Kode Buku:', id_buku); 
            console.log('Buku yang Ditemukan:', buku); 

            if (buku) {
                row.querySelector('.judul').value = buku.judul;
                row.querySelector('.penerbit').value = buku.penerbit;
                row.querySelector('.harga').value = buku.harga_jual;
                row.querySelector('.diskon').value = buku.diskon || 0;
                calculateSubTotal(row);
            } else {
                row.querySelector('.judul').value = '';
                row.querySelector('.penerbit').value = '';
                row.querySelector('.harga').value = '';
                row.querySelector('.diskon').value = '';
                row.querySelector('.sub_total').value = '';
            }
            calculateTotal();
        }
    });

    document.addEventListener('input', function(event) {
        if (event.target.classList.contains('jumlah') || event.target.classList.contains('diskon')) {
            let row = event.target.closest('tr');
            calculateSubTotal(row);
            calculateTotal();
        }
    });

    document.getElementById('tambah-baris').addEventListener('click', function() {
        let newRow = document.querySelector('#tbody-pembelian tr').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        document.getElementById('tbody-pembelian').appendChild(newRow);
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove')) {
            let row = event.target.closest('tr');
            row.remove();
            calculateTotal();
        }
    });

    function calculateSubTotal(row) {
        let harga = parseFloat(row.querySelector('.harga').value) || 0;
        let jumlah = parseFloat(row.querySelector('.jumlah').value) || 0;
        let diskon = parseFloat(row.querySelector('.diskon').value) || 0;

        let sub_total = (harga * jumlah) - ((harga * jumlah) * (diskon / 100));
        row.querySelector('.sub_total').value = sub_total.toFixed(2);
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.sub_total').forEach(function(sub_total) {
            total += parseFloat(sub_total.value) || 0;
        });
        document.getElementById('total').value = total.toFixed(2);
    }
</script>

<?php 
include '../../layout/footer.php';
?>
