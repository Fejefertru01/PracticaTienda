<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require "../util/conexion.php" ?>
    <?php require "objetos/productoCesta.php"; ?>
    <link rel="stylesheet" href="styles/style.css">
    <title>Cesta</title>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        // header("Location: iniciar_sesion.php");
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
        $_SESSION["rol"] = "invitado";
        $rol = $_SESSION["rol"];
    }
    if($_SESSION["rol"] == "invitado"){
        ?>
        <div class="container">
            <div class="alert alert-warning mt-3" role="alert">Necesitas una cuenta para poder acceder a tu cesta</div>
            <button type="button" class="btn btn-success"><a class="nav-link active" href="registro.php" tabindex="-1">Creala aquí</a></button>
        </div>
        <?php
            }else{
        ?>
    
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand">Fernando's Corner</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item nav-link active">
                        Bienvenid@ <?php echo $usuario ?>
                    </li>
                    <?php
                    if ($_SESSION["rol"] == "admin") {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="productos.php" tabindex="-1">Insertar Productos</a>
                        </li>
                    <?php
                    }
                    ?>
                    <li class="nav-item"><a class="nav-link active" href="principal.php" tabindex="-1">Pagina Principal</a></li>
                </ul>
                <?php
                if ($_SESSION["usuario"] == "invitado") {
                ?>
                    <button type="button" class="btn btn-success"><a class="nav-link" href="iniciar_sesion.php">Iniciar Sesion</a></button>
                <?php
                } else {
                ?>
                    <button type="button" class="btn btn-success"><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesion</a></button>
                <?php
                }
                ?>
            </div>
        </div>
    </nav>
    
    <img src="../views/images/logo2.PNG" alt="logo" class="logo rounded mx-auto d-block">
    <div class="container">
        <h2></h2>
    </div>
    <div class="container">
        <h1 class="text-center mb-3">Cesta</h1>


        <table class='table table-info table-hover'>
            <thead class='table-dark'>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT idCesta FROM cestas WHERE usuario = '$usuario'";
                $resultado = $conexion->query($sql);
                $fila = $resultado->fetch_assoc();
                $idCesta = $fila['idCesta'];
                $sql = "SELECT * FROM productoscestas WHERE idCesta = '$idCesta' ";
                $resultado = $conexion->query($sql);
                $productosCesta = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $producto_Nuevo = new ProductoCesta(
                        $fila['idProducto'],
                        $fila['idCesta'],
                        $fila['cantidad']
                    );
                    array_push($productosCesta, $producto_Nuevo);
                }
                ?>
                <?php
                foreach ($productosCesta as $productoCesta) {
                    $sql="SELECT nombreProducto FROM productos WHERE idProducto = $productoCesta->idProducto";
                    $resultado = $conexion->query($sql);
                    $fila = $resultado->fetch_assoc();
                    $nombreProducto = $fila['nombreProducto'];
                    $sql="SELECT precio FROM productos WHERE idProducto = $productoCesta->idProducto";
                    $resultado = $conexion->query($sql);
                    $fila = $resultado->fetch_assoc();
                    $precio = $fila['precio'];
                    $sql="SELECT imagen FROM productos WHERE idProducto = $productoCesta->idProducto";
                    $resultado = $conexion->query($sql);
                    $fila = $resultado->fetch_assoc();
                    $imagen = $fila['imagen'];
                    echo "<tr>";
                    echo "<td>$nombreProducto</td>";
                    echo "<td>$precio</td>";
                    echo "<td>$productoCesta->cantidad</td>";
                    echo "<td><img src='$imagen' width='100px' height='100px'></td>";
                    echo "</tr>";
                    $sql="SELECT precioTotal FROM cestas WHERE usuario = '$usuario'";
                    $resultado = $conexion->query($sql);
                    $fila = $resultado->fetch_assoc();
                    $precioTotal = $fila['precioTotal'];
                    $nuevoPrecio = $precioTotal + ($precio*$productoCesta->cantidad);
                    $sql="UPDATE cestas SET precioTotal=$nuevoPrecio WHERE usuario = '$usuario'";
                    $conexion->query($sql);
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>