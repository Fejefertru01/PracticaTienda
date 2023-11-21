<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require "../util/conexion.php" ?>
    <?php require "objetos/producto.php"; ?>
    <link rel="stylesheet" href="styles/style.css">
    <title>Pagina principal</title>
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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["idProducto"], $_POST["cantidad"])) {
            $id_producto = $_POST["idProducto"];
            $cantidad_seleccionada = $_POST["cantidad"];

            // Obtener la cantidad disponible en la tabla productos
            $sqlCantidadProducto = "SELECT cantidad FROM productos WHERE idProducto = '$id_producto'";
            $resultadoCantidadProducto = $conexion->query($sqlCantidadProducto);

            if ($resultadoCantidadProducto && $filaCantidadProducto = $resultadoCantidadProducto->fetch_assoc()) {
                $cantidad_disponible = $filaCantidadProducto['cantidad'];

                // Verifica si la cantidad seleccionada es menor o igual a la cantidad disponible
                if ($cantidad_seleccionada <= $cantidad_disponible) {
                    // Obtener el idCesta correspondiente al usuario actual
                    $sqlCesta = "SELECT idCesta FROM cestas WHERE usuario = '$usuario'";
                    $resultadoCesta = $conexion->query($sqlCesta);

                    if ($resultadoCesta && $filaCesta = $resultadoCesta->fetch_assoc()) {
                        $idCesta = $filaCesta['idCesta'];

                        // Actualizar la cantidad en la tabla de productos
                        $nueva_cantidad = $cantidad_disponible - $cantidad_seleccionada;
                        $sqlActualizarCantidad = "UPDATE productos SET cantidad = $nueva_cantidad WHERE idProducto = '$id_producto'";
                        $conexion->query($sqlActualizarCantidad);

                        // Insertar o actualizar la tabla productoscestas
                        $sql3 = "INSERT INTO productoscestas (idProducto, idCesta, cantidad) 
                                                 VALUES ('$id_producto', '$idCesta', $cantidad_seleccionada)
                                                 ON DUPLICATE KEY UPDATE cantidad = cantidad + $cantidad_seleccionada";

                        $conexion->query($sql3);

                        $mensajeCesta = "<div class='container'><h3>Producto añadido correctamente</h3></div>";

                        // Actualizar el precio total en la tabla cestas
                        $sql = "SELECT precioTotal FROM cestas WHERE usuario = '$usuario'";
                        $resultado = $conexion->query($sql);
                        $fila = $resultado->fetch_assoc();
                        $precioTotal = $fila['precioTotal'];
                        $sql = "SELECT precio FROM productos WHERE idProducto = '$id_producto'";
                        $resultado = $conexion->query($sql);
                        $fila = $resultado->fetch_assoc();
                        $precioProducto = $fila['precio'];
                        $nuevoPrecio = $precioTotal + ($precioProducto * $cantidad_seleccionada);
                        $sql = "UPDATE cestas SET precioTotal=$nuevoPrecio WHERE usuario = '$usuario'";
                        $conexion->query($sql);
                    }
                } else {
                    // Mensaje de error si la cantidad seleccionada es mayor a la disponible
                    $mensajeCesta = "<div class='container'><h3>Error</h3></div>";
                }
            }
        }
    }
    ?>
    <!-- NavBar-->
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
                    <!-- Enlace para registrarse si el usuario es invitado -->
                    <?php
                    if ($_SESSION["usuario"] == "invitado") {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="registro.php">¿No tienes cuenta? Registrarse</a>
                        </li>
                    <?php
                    }
                    ?>
                    <!-- Enlace para insertar productos si el usuario es administrador -->
                    <?php
                    if ($_SESSION["rol"] == "admin") {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="productos.php" tabindex="-1">Insertar Productos</a>
                        </li>
                    <?php
                    }
                    ?>
                    <!-- Enlace a la cesta si el usuario es cliente o administrador -->
                    <?php
                    if (($_SESSION["rol"] == "cliente") || ($_SESSION["rol"] == "admin")) {
                    ?>
                        <li class="nav-item"><a class="nav-link active" href="cesta.php" tabindex="-1">Cesta</a></li>
                    <?php
                    }
                    ?>
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
    <!-- Logo -->
    <img src="../views/images/logo2.PNG" alt="logo" class="logo rounded mx-auto d-block">
    <div class="container">
        <h2></h2>
    </div>
    <div class="container">
        <h1 class="text-center mb-3">Listado de productos</h1>

        <?php
        $sql = "SELECT * FROM productos";
        $resultado = $conexion->query($sql);
        ?>
        <?php
        // Mensaje de éxito o error para agregar productos a la cesta
        if (isset($mensajeCesta)) { ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo $mensajeCesta; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        ?>

        <!-- Tabla que muestra los productos disponibles -->
        <table class='table table-info table-hover'>
            <thead class='table-dark'>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                    <th>Añadir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM productos";
                $resultado = $conexion->query($sql);
                $productos = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $producto_Nuevo = new Producto(
                        $fila['idProducto'],
                        $fila['nombreProducto'],
                        $fila['precio'],
                        $fila['descripcion'],
                        $fila['cantidad'],
                        $fila['imagen']
                    );
                    array_push($productos, $producto_Nuevo);
                }
                ?>
                <?php
                foreach ($productos as $producto) {
                    echo "<tr>";
                    echo "<td>" . $producto->idProducto . "</td>";
                    echo "<td>" . $producto->nombreProducto . "</td>";
                    echo "<td>" . $producto->precio . " €</td>";
                    echo "<td>" . $producto->descripcion . "</td>";
                    echo "<td>" . $producto->cantidad . "</td>";
                ?>
                    <td><img height="120px" width="150px" src="<?php echo $producto->imagen ?>" alt=""></td>
                    <td>
                        <!-- Formulario para agregar productos a la cesta -->
                        <form action="" method="post">
                            <?php if (($_SESSION["rol"] == "cliente") || ($_SESSION["rol"] == "admin")) { ?>
                                <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                                <label for="cantidad">Cantidad:</label>
                                <select name="cantidad" id="cantidad">
                                    <?php
                                    // Obtener la cantidad disponible del producto
                                    $sql = "SELECT cantidad FROM productos where idProducto = '$producto->idProducto'";
                                    $resultado = $conexion->query($sql);
                                    while ($fila = $resultado->fetch_assoc()) {
                                        $cantidad = $fila['cantidad'];
                                    }
                                    if ($cantidad == 0) { ?>
                                        <option value="">-</option>

                                        <?php } elseif ($cantidad > 5) {
                                        for ($i = 1; $i <= 5; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php
                                        }
                                    } else {
                                        for ($i = 1; $i <= $cantidad; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php
                                        }
                                    }

                                    ?>
                                </select>
                                <?php
                                // Habilitar o deshabilitar el botón de añadir según la disponibilidad de la cantidad
                                if ($cantidad > 0) {
                                ?>
                                    <input class="btn btn-warning" type="submit" value="Añadir">
                                <?php
                                } else {
                                ?>
                                    <input class="btn btn-warning" type="submit" value="Añadir" disabled>
                                <?php
                                }
                            } else { ?>
                                <!-- Deshabilitar el botón de añadir si el usuario no es cliente o admin -->
                                <input class="btn btn-warning" type="submit" value="Añadir" disabled>
                            <?php } ?>
                        </form>
                    </td>
                <?php
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>