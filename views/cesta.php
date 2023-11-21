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
    // Inicia la sesión y verifica si existe un usuario en la sesión
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        // Si no hay usuario en la sesión, se establece como invitado
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
        $_SESSION["rol"] = "invitado";
        $rol = $_SESSION["rol"];
    }
    ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener el ID de la cesta del usuario
        $sqlCesta = "SELECT idCesta FROM cestas WHERE usuario = '$usuario'";
        $resultadoCesta = $conexion->query($sqlCesta);
        $filaCesta = $resultadoCesta->fetch_assoc();
        $idCesta = $filaCesta['idCesta'];

        // Obtener el precio total de la cesta
        $sql = "SELECT precioTotal from cestas WHERE idCesta = '$idCesta'";
        $resultado = $conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        $precioTotal = $fila['precioTotal'];

        // Insertar un nuevo pedido
        $sql = "INSERT INTO pedidos (usuario, precioTotal) VALUES ('$usuario', '$precioTotal')";
        $conexion->query($sql);

        // Obtener el ID del pedido recién insertado
        $sql = "SELECT idPedido FROM pedidos WHERE usuario = '$usuario' ORDER BY idPedido DESC LIMIT 1";
        $resultado = $conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        $idPedido = $fila['idPedido'];

        // Insertar productos de la cesta en la tabla LineasPedidos
        $sql = "SELECT * FROM productoscestas WHERE idCesta = '$idCesta'";
        $resultado = $conexion->query($sql);
        $lineaPedido = 1;
        while ($fila = $resultado->fetch_assoc()) {
            $idProducto = $fila['idProducto'];
            $sql = "SELECT precio FROM productos WHERE idProducto = '$idProducto'";
            $resultado2 = $conexion->query($sql);
            $fila = $resultado2->fetch_assoc();
            $precioUnitario = $fila['precio'];
            $sql = "SELECT cantidad FROM productoscestas WHERE idProducto = '$idProducto' AND idCesta = '$idCesta'";
            $resultado3 = $conexion->query($sql);
            $fila = $resultado3->fetch_assoc();
            $cantidad = $fila['cantidad'];

            // Insertar detalles en la tabla LineasPedidos
            $sql = "INSERT INTO lineaspedidos (lineaPedido,idProducto, idPedido, precioUnitario,cantidad) VALUES ('$lineaPedido','$idProducto', '$idPedido', '$precioUnitario', '$cantidad')";
            $conexion->query($sql);
            $lineaPedido++;
        }

        // Borrar los productos de la cesta
        $sql = "DELETE FROM productoscestas WHERE idCesta = '$idCesta'";
        $conexion->query($sql);

        // Actualizar el precio total de la cesta a 0
        $sql = "UPDATE cestas SET precioTotal = 0 WHERE usuario = '$usuario'";
        $conexion->query($sql);

        // Mensaje de éxito para mostrar al usuario
        $mensaje_pedido = "<div class='alert alert-success mt-3' role='alert'>Pedido realizado con éxito</div>";
    }

    // Verificar si el usuario es un invitado
    if ($_SESSION["rol"] == "invitado") {
    ?>
        <div class="container">
            <div class="alert alert-warning mt-3" role="alert">Necesitas una cuenta para poder acceder a tu cesta</div>
            <button type="button" class="btn btn-success"><a class="nav-link active" href="registro.php" tabindex="-1">Creala aquí</a></button>
        </div>
    <?php
    } else {
    ?>
        <!-- NavBar -->
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
                    <!-- Botón de inicio de sesión o cierre de sesión dependiendo del estado del usuario -->
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

            <!-- Tabla que muestra los productos en la cesta -->
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
                    // Obtener los productos de la cesta del usuario
                    $sql = "SELECT idCesta FROM cestas WHERE usuario = '$usuario'";
                    $resultado = $conexion->query($sql);
                    $fila = $resultado->fetch_assoc();
                    $idCesta = $fila['idCesta'];
                    $sql = "SELECT * FROM productoscestas WHERE idCesta = '$idCesta' ";
                    $resultado = $conexion->query($sql);
                    $productosCesta = [];
                    while ($fila = $resultado->fetch_assoc()) {
                        // Crear objetos ProductoCesta para cada producto en la cesta
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
                        $sql = "SELECT nombreProducto FROM productos WHERE idProducto = $productoCesta->idProducto";
                        $resultado = $conexion->query($sql);
                        $fila = $resultado->fetch_assoc();
                        $nombreProducto = $fila['nombreProducto'];
                        $sql = "SELECT precio FROM productos WHERE idProducto = $productoCesta->idProducto";
                        $resultado = $conexion->query($sql);
                        $fila = $resultado->fetch_assoc();
                        $precio = $fila['precio'];
                        $sql = "SELECT imagen FROM productos WHERE idProducto = $productoCesta->idProducto";
                        $resultado = $conexion->query($sql);
                        $fila = $resultado->fetch_assoc();
                        $imagen = $fila['imagen'];

                        // Mostrar los detalles del producto en la tabla
                        echo "<tr>";
                        echo "<td>$nombreProducto</td>";
                        echo "<td>" . $precio . " €</td>";
                        echo "<td>$productoCesta->cantidad</td>";
                        echo "<td><img src='$imagen' width='150px' height='100px'></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <?php
                    // Mostrar el precio total de la cesta en el pie de la tabla
                    $sql = "SELECT precioTotal FROM cestas WHERE usuario = '$usuario'";
                    $resultado = $conexion->query($sql);
                    $fila = $resultado->fetch_assoc();
                    $precioTotal = $fila['precioTotal'];
                    echo "<tr>";
                    echo "<th></th>";
                    echo "<th></th>";
                    echo "<th></th>";
                    echo "<th> Precio Total: " . $precioTotal . " €</th>";
                    echo "</tr>";
                    ?>
                </tfoot>
            </table>

            <?php
            // Mostrar el mensaje de éxito después de realizar un pedido
            if (isset($mensaje_pedido)) {
                echo $mensaje_pedido;
            }
            ?>
            <?php
            // Verificar si hay productos en la cesta antes de mostrar el formulario para realizar el pedido
            $sql = "SELECT * FROM productoscestas WHERE idCesta = '$idCesta' ";
            $resultado = $conexion->query($sql);
            if ($resultado->num_rows > 0) { ?>
                <!-- Formulario para realizar el pedido -->
                <form action="" method="post">
                    <input class="btn btn-primary mt-3" type="submit" value="Realizar Pedido">
                </form>
            <?php
            }
            ?>
        </div>
    <?php
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
