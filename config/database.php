<?php

class db{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'toko_buku_dava';
    private $connection;

    public function koneksi(){
        $this->connection = new mysqli($this->host,$this->username,$this->password,$this->database);
    }

    public function ambil_data($query) {
        $result = $this->connection->query($query);
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function modifikasi($query) {
        $result = $this->connection->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}

?>
