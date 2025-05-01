<?php
require_once("../config/conexion.php");

class Cliente extends Conectar {

    public function get_clientes() {
        $conectar = parent::conexion();
        parent::set_name();
        $sql = "SELECT * FROM clientes";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_cliente($id_cliente) {
        $conectar = parent::conexion();
        parent::set_name();
        $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_cliente);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert_cliente($nit, $razon_social, $direccion, $telefono) {
        $conectar = parent::conexion();
        parent::set_name();
        $sql = "INSERT INTO clientes (nit, razon_social, direccion, telefono) VALUES (?, ?, ?, ?)";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$nit, $razon_social, $direccion, $telefono]);
        return $stmt;
    }

    public function update_cliente($id_cliente, $nit, $razon_social, $direccion, $telefono) {
        $conectar = parent::conexion();
        parent::set_name();
        $sql = "UPDATE clientes SET nit = ?, razon_social = ?, direccion = ?, telefono = ? WHERE id_cliente = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$nit, $razon_social, $direccion, $telefono, $id_cliente]);
        return $stmt;
    }

    public function delete_cliente($id_cliente) {
        $conectar = parent::conexion();
        parent::set_name();
        $sql = "DELETE FROM clientes WHERE id_cliente = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_cliente);
        $stmt->execute();
        return $stmt;
    }
}
?>