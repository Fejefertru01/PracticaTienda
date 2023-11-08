<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require "conexion.php" ?>
    <?php require "objetos/producto.php"; ?>
    <link rel="stylesheet" href="css/style.css">
    <title>Inciar Sesion</title>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
    } else {
        header("Location: iniciar_sesion.php");
        // $_SESSION["usuario"] = "invitado";
        // $usuario = $_SESSION["usuario"];
    }

    ?>
    <div class="container">
        <h1>Pagina Principal</h1>
        <h2>Bienvenid@ <?php echo $usuario ?></h2>
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