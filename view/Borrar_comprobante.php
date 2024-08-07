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
   
    $comprobante_id = $_POST['comprobante_id'];
    $resultado = $comprobanteController->delete($comprobante_id);

    if ($resultado) {
        $mensaje = 'Se eliminó el comprobante exitosamente';
        $mensajeClase = 'alert-success';
    } else {
        $mensaje = 'No se pudo eliminar el comprobante';
        $mensajeClase = 'alert-danger';
    }
}

$comprobante_id = $_GET['id'];

$comprobante = $comprobanteController->findOne($comprobante_id);

if (!empty($comprobante)) {
    $comprobante = json_decode($comprobante, true);
}

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
    <h1>Borrar Comprobante</h1>
    <form method="POST">
        <input type="hidden" name="comprobante_id" value="<?php echo $comprobante['id']; ?>">
        <p>¿Está seguro de que desea eliminar el comprobante?</p>
        <div class="alert <?php echo $mensajeClase; ?>">
            <?php echo $mensaje; ?>
        </div>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmModal" id="eliminarButton">
            Eliminar
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
                ¿Está seguro de que desea eliminar el comprobante?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>    
    document.getElementById("eliminarButton").addEventListener("click", function() {
        document.querySelector("form").submit();
    });
</script>
</body>
</html>
