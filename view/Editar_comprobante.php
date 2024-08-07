<?php
use model\Comprobante;
require_once('../model/Database.php');
require_once('../model/Comprobante.php');
require_once('../controller/ComprobanteController.php'); 

$db = new Database();
$comprobanteController = new ComprobanteController($db);

$mensaje = '';
$mensajeClase = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $datos = array(
        'id' => $_POST['comprobante_id'], 
        'fecha' => $_POST['fecha'],
        'id_remitente' => $_POST['remitente'],
        'id_destinatario' => $_POST['destinatario'],
        'tipo_cambio' => $_POST['tipo_cambio'],
        'monto_mn' => $_POST['monto_mn'],
        'monto_me' => $_POST['monto_me']
    );
  
   
    $resultado = $comprobanteController->update($_POST['comprobante_id'],$datos);

    if ($resultado) {
        $mensaje = 'Se actualizó el comprobante exitosamente';
        $mensajeClase = 'alert-success';
    } else {
        $mensaje = 'No se pudo actualizar el comprobante';
        $mensajeClase = 'alert-danger';
    }
}
$comprobante_id = $_GET['id'];

$comprobante = $comprobanteController->findOne($comprobante_id); 

if (!empty($comprobante)) {
   $comprobante = json_decode($comprobante, true); 
} 
$fechaFormateada = date("Y-m-d", strtotime($comprobante['fecha']));
$clientes = $comprobanteController->getClients(); 
if (!empty($clientes)) {
    $clientes = json_decode($clientes, true); 
} 
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="nav flex-column">
                    <li class="nav-item active">
                        <a class="nav-link" href="../index.php">INICIO <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Clientesview.php">CLIENTES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Comprobanteview.php">COMPROBANTES</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mt-4">
        <h1>Editar Comprobante</h1>
        <form method="POST">
            
            <input type="hidden" name="comprobante_id" value="<?php echo $comprobante['id']; ?>">
            
         
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo $fechaFormateada; ?>">
            </div>
            <div class="form-group">
                <label for="remitente">Remitente:</label>
                <select id="remitente" name="remitente" class="form-control">
                    <?php
                    foreach ($clientes as $cliente) {
                        $selected = ($cliente['id'] == $comprobante['id_remitente']) ? 'selected' : '';
                        echo "<option value='{$cliente['id']}' {$selected}>{$cliente['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class "form-group">
                <label for="destinatario">Destinatario:</label>
                <select id="destinatario" name="destinatario" class="form-control">
                    <?php
                    foreach ($clientes as $cliente) {
                        $selected = ($cliente['id'] == $comprobante['id_destinatario']) ? 'selected' : '';
                        echo "<option value='{$cliente['id']}' {$selected}>{$cliente['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tipo_cambio">Tipo de Cambio:</label>
                <input type="number" id="tipo_cambio" name="tipo_cambio" class="form-control" step="0.01" value="<?php echo $comprobante['tipo_cambio']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="monto_mn">Monto en MN:</label>
                <input type="number" id="monto_mn" name="monto_mn" class="form-control" step="0.01" value="<?php echo $comprobante['monto_mn']; ?>" onchange="actualizarMontos('mn')">
            </div>
            <div class="form-group">
                <label for="monto_me">Monto en ME:</label>
                <input type="number" id="monto_me" name="monto_me" class="form-control" step="0.01" value="<?php echo $comprobante['monto_me']; ?>" onchange="actualizarMontos('me')">
            </div>
           
            <div class="alert <?php echo $mensajeClase; ?>">
                <?php echo $mensaje; ?>
            </div>
          
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmModal" id="grabarButton">
                Grabar
            </button>
            <a href="Comprobanteview.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea actualizar el comprobante?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function actualizarMontos(campo) {
            const tipoCambio = parseFloat(document.getElementById("tipo_cambio").value);
            const montoMN = parseFloat(document.getElementById("monto_mn").value);
            const montoME = parseFloat(document.getElementById("monto_me").value);

            if (!isNaN(tipoCambio)) {
                if (campo === 'mn') {
                    const montoMEActualizado = (montoMN / tipoCambio).toFixed(2);
                    document.getElementById("monto_me").value = montoMEActualizado;
                } else if (campo === 'me') {
                    const montoMNActualizado = (montoME * tipoCambio).toFixed(2);
                    document.getElementById("monto_mn").value = montoMNActualizado;
                }
            }
        }
    </script>
     <script>    
        document.getElementById("grabarButton").addEventListener("click", function() {
            document.querySelector("form").submit();
        });
    </script>
</body>
</html>







