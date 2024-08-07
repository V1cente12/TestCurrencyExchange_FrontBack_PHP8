<?php
use model\Comprobante;
require_once('../model/Database.php');
require_once('../model/Comprobante.php');
require_once('../controller/ComprobanteController.php'); 

$db = new Database();
$comprobanteController = new ComprobanteController($db);

$registrosPorPagina = 10;
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

$result = $comprobanteController->findAll($pagina); 
if (!empty($result)) {
    $data = json_decode($result, true); 
} 

$totalRegistros = $comprobanteController->getTotalRegistros();
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

?>

<!DOCTYPE html>
<html>
<head>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
   
<div class="d-flex justify-content-center align-items-center">
    <a class="btn btn-primary" href="crear_comprobante.php">NUEVO</a>
</div>


    <br>
   <div class="container mt-4">
        <h1>Lista de Comprobantes</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Remitente</th>
                    <th>Destinatario</th>
                    <th>Tipo de Cambio</th>
                    <th>Monto (Bs)</th>
                    <th>Monto ($us)</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($data)) {
                foreach ($data as $registro) {
                    echo "<tr>";
                    echo "<td>{$registro['id']}</td>";
                    echo "<td>{$registro['fecha']}</td>";
                    $remitente = $comprobanteController->getClienteNombreApellido($registro['id_remitente']);
                    $remitenteNombreApellido = $remitente['nombre'] . ' ' . $remitente['apellidos'];
                    echo "<td>{$remitenteNombreApellido}</td>";
                    $destinatario = $comprobanteController->getClienteNombreApellido($registro['id_destinatario']);
                    $destinatarioNombreApellido = $destinatario['nombre'] . ' ' . $destinatario['apellidos'];
                    echo "<td>{$destinatarioNombreApellido}</td>";
                    echo "<td>{$registro['tipo_cambio']}</td>";
                    echo "<td>{$registro['monto_mn']}</td>";
                    echo "<td>{$registro['monto_me']}</td>";
                    echo "<td><a href='editar_comprobante.php?id={$registro['id']}'><i class='fas fa-edit'></i></a></td>";
                    echo "<td><a href='borrar_comprobante.php?id={$registro['id']}'><i class='fas fa-trash'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No se encontraron registros en la página $pagina</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <div class="container">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?php if ($i == $pagina) echo 'active'; ?>">
                    <a class="page-link" href="Comprobanteview.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
</body>
</html>
