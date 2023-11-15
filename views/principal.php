<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require "../util/conexion.php" ?>
    <?php require "objetos/producto.php"; ?>
    <link rel="stylesheet" href="styles/style.css">
    <title>Inciar Sesion</title>
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_producto = $_POST["idProducto"];
        echo $id_producto;
    }
    ?>
    <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        // header("Location: iniciar_sesion.php");
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
        $_SESSION["rol"] = "cliente";
        $rol = $_SESSION["rol"];
    }
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
                    if ($_SESSION["usuario"] == "invitado") {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="registro.php">¿No tienes cuenta? Registrarse</a>
                        </li>
                    <?php
                    }
                    ?>

                    <?php
                    if ($_SESSION["rol"] == "admin") {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="productos.php" tabindex="-1">Insertar Productos</a>
                        </li>
                    <?php
                    }
                    ?>
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
    <img src="../views/images/logo.PNG" alt="logo" class="logo">
    <div class="container">
        <h2></h2>
    </div>
    <div class="container">
        <h1 class="text-center mb-3">Listado de productos</h1>

        <?php
        $sql = "SELECT * FROM productos";
        $resultado = $conexion->query($sql);
        ?>

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
                    echo "<td>" . $producto->precio . "</td>";
                    echo "<td>" . $producto->descripcion . "</td>";
                    echo "<td>" . $producto->cantidad . "</td>";
                ?>
                    <td><img height="100px" width="150px" src="<?php echo $producto->imagen ?>" alt=""></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                            <input class="btn btn-warning" type="submit" value="Añadir">
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