<?php
use model\Comprobante;
class ComprobanteController {
    private $comprobante;

    public function __construct($db) {
        $this->comprobante = new Comprobante($db);
    }

    public function findOne($id) {
        $comprobante = $this->comprobante->findOne($id);
        if ($comprobante) {
            return json_encode($comprobante);
        } else {
            echo json_encode(array("message" => "Comprobante no encontrado."));
        }
    }
    public function getTotalRegistros() {
        
        $comprobantes = $this->comprobante->getTotalRegistros();
        if ($comprobantes) {
             return json_encode($comprobantes);
        } else {
            echo json_encode(array("message" => "No se encontraron comprobantes."));
        }
    }
    public function getClients() {
        
        $clients = $this->comprobante->getClients();
        if ($clients) {
             return json_encode($clients);
        } else {
            echo json_encode(array("message" => "No se encontraron comprobantes."));
        }
    }
    public function findAll($pagina) {
        
        $comprobantes = $this->comprobante->findAll($pagina);
        if ($comprobantes) {
             return json_encode($comprobantes);
        } else {
            echo json_encode(array("message" => "No se encontraron comprobantes."));
        }
    }
    public function createComprobante() {
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = array(
                'fecha' => $_POST['fecha'],
                'id_remitente' => $_POST['remitente'],
                'id_destinatario' => $_POST['destinatario'],
                'tipo_cambio' => $_POST['tipo_cambio'],
                'monto_mn' => $_POST['monto_mn'],
                'monto_me' => $_POST['monto_me']
            );
    
            if ($this->comprobante->create($datos)) {
                header("Location: Comprobanteview.php");
                exit();
            } else { 
                echo json_encode(array("message" => "Error al crear el comprobante.")); 
            }
        }
    }

    public function create($datos) {
        if ($this->createComprobante($datos)) {
            echo json_encode(array("message" => "Comprobante creado exitosamente."));
        } else {
            echo json_encode(array("message" => "Error al crear el comprobante."));
        }
    }
    

    public function update($id, $nuevosDatos) {
        if ($this->comprobante->update($id, $nuevosDatos)) {
            header("Location: Comprobanteview.php");
            exit();
        } else {
            echo json_encode(array("message" => "Error al actualizar el comprobante."));
        }
    }

    public function delete($id) {
        if ($this->comprobante->delete($id)) {
            header("Location: Comprobanteview.php");
            exit();
        } else {
            echo json_encode(array("message" => "Error al eliminar el comprobante."));
        }
    }
    public function getClienteNombreApellido($clienteId) {
       
        if (!is_numeric($clienteId) || $clienteId <= 0) {
            echo json_encode(array("message" => "ID de cliente no válido."));
            return;
        }
    
      
        $clienteInfo = $this->comprobante->getClienteNombreApellidoPorId($clienteId);
    
        
        if ($clienteInfo) {
           return $clienteInfo;
        } else {
            echo json_encode(array("message" => "Cliente no encontrado."));
        }
    }
}

?>
