<?php
namespace model;
use \PDO;
class Comprobante {
    
    public $id;
    public $fecha;
    public $id_remitente;
    public $id_destinatario;
    public $tipo_cambio;
    public $monto_mn;
    public $monto_me;
    public $estado;
    public $removido_flag;
    public $fecha_creacion;
    public $fecha_actualizacion;
    private $conn;
    
    
    public function __construct($db) {
       
        $this->conn = $db->getConnection();
    }

   
    public function findOne($id) {
        $query = "SELECT * FROM comprobantes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTotalRegistros() {
        $query = "SELECT COUNT(*) as total FROM comprobantes";
        $stmt = $this->conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    public function getClients() {
        $query = "SELECT id, nombre FROM clientes";
        $stmt = $this->conn->query($query);
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $clientes;
    }
    
    
    public function findAll($pagina) {
        $registros_por_pagina = 10;
        $desplazamiento = ($pagina - 1) * $registros_por_pagina;
        $query = "SELECT * FROM comprobantes WHERE removido_flag IS NULL LIMIT $desplazamiento, $registros_por_pagina";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

   
    public function create($datos) {
        $query = "INSERT INTO comprobantes (fecha, id_remitente, id_destinatario, tipo_cambio, monto_mn, monto_me) VALUES (:fecha, :id_remitente, :id_destinatario, :tipo_cambio, :monto_mn, :monto_me)";
        $stmt = $this->conn->prepare($query);
    
       
        $stmt->bindParam(":fecha", $datos['fecha']);
        $stmt->bindParam(":id_remitente", $datos['id_remitente']);
        $stmt->bindParam(":id_destinatario", $datos['id_destinatario']);
        $stmt->bindParam(":tipo_cambio", $datos['tipo_cambio']);
        $stmt->bindParam(":monto_mn", $datos['monto_mn']);
        $stmt->bindParam(":monto_me", $datos['monto_me']);
    
       
        if ($stmt->execute()) {
            return true; 
        } else {
            return false; 
        }
    }

    
    public function update($id,$nuevosDatos) {
        $query = "UPDATE comprobantes SET fecha = :fecha, id_remitente = :id_remitente, id_destinatario = :id_destinatario, tipo_cambio = :tipo_cambio, monto_mn = :monto_mn, monto_me = :monto_me WHERE id = :id";
        $stmt = $this->conn->prepare($query);

       
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":fecha", $nuevosDatos['fecha']);
        $stmt->bindParam(":id_remitente", $nuevosDatos['id_remitente']);
        $stmt->bindParam(":id_destinatario", $nuevosDatos['id_destinatario']);
        $stmt->bindParam(":tipo_cambio", $nuevosDatos['tipo_cambio']);
        $stmt->bindParam(":monto_mn", $nuevosDatos['monto_mn']);
        $stmt->bindParam(":monto_me", $nuevosDatos['monto_me']);

       
        if ($stmt->execute()) {
            return true; 
        } else {
            return false; 
        }
    }

   
    public function delete($id) {
        $query = "UPDATE comprobantes SET removido_flag = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    public function getClienteNombreApellidoPorId($clienteId) {
        $query = "SELECT nombre, apellidos FROM clientes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $clienteId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>
