<?php
class Conectar {
    protected $dbh;

    protected function conexion() {
        try {
            $conectar = $this->dbh = new PDO("mysql:host=localhost;dbname=sistema_de_ventas", "apiuser", "1234");
            return $conectar;
        } catch (Exception $e) {
            die("¡Error DB!: " . $e->getMessage());
        }
    }

    public function set_name() {
        if ($this->dbh) {
            return $this->dbh->query("SET NAMES 'utf8'");
        }
        return null;
    }
}
?>