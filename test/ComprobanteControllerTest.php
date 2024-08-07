<?php
use model\Comprobante;
require_once('model/Database.php');
require_once('model/Comprobante.php');
require_once('controller/ComprobanteController.php');

use PHPUnit\Framework\TestCase;

class ComprobanteControllerTest extends TestCase {
    public function testFindOne() {
       
        $database = new Database();
        $comprobanteController = new ComprobanteController($database->getConnection());
        
       
        $this->assertInstanceOf(ComprobanteController::class, $comprobanteController);
        
      
        $result = $comprobanteController->findOne(1);

       
        $this->assertNotNull($result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);

       
        $result = $comprobanteController->findOne(1); 
        $this->assertNull($result);
    }

    public function testFindAll() {
        
        $database = new Database();
        $comprobanteController = new ComprobanteController($database->getConnection());

        $this->assertInstanceOf(ComprobanteController::class, $comprobanteController);

        $result = $comprobanteController->findAll(1);

        $this->assertNotNull($result);
        $this->assertIsArray($result);
       
        $result = $comprobanteController->findAll(-1); 
        $this->assertEquals([], $result);
    }

    public function testCreate() {
       
        $database = new Database();
        $comprobanteController = new ComprobanteController($database->getConnection());

        $this->assertInstanceOf(ComprobanteController::class, $comprobanteController);

        $nuevosDatos = [
            'fecha' => '2023-10-20',
            'id_remitente' => 1,
            'id_destinatario' => 2,
            'tipo_cambio' => 1.23,
            'monto_mn' => 100.00,
            'monto_me' => 80.49
        ];

        $result = $comprobanteController->create($nuevosDatos);

        $this->assertTrue($result);

        $invalidData = []; 
        $result = $comprobanteController->create($invalidData);
        $this->assertFalse($result);
    }
}
