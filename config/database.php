<?php

class db{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'toko_buku_2';
    private $connection;

    public function koneksi(){
        $this->connection = new mysqli($this->host,$this->username,$this->password,$this->database,$this->connection);
    }

    public function ambil_data($query) {
        $result = $this->connection->query($query);
        $data = [];
        if ($result->num_rows > 0){
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
    public function execute_query($query, $params) {
        $stmt = $this->connection->prepare($query);
        $types = str_repeat('s', count($params)); 
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();
    }
}

?>
