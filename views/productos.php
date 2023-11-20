<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/stylesLogin.css">
    <?php require '../util/conexion.php' ?>
</head>

<body class="bodyProductos">
    <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    }
    if ($rol != "admin") {
    ?>
        <div class="container">
            <div class="alert alert-warning mt-3" role="alert">No puedes estar aqui</div>
            <button type="button" class="btn btn-success"><a class="nav-link active" href="principal.php" tabindex="-1">Volver a pagina principal</a></button>
        </div>


    <?php

    } else { ?>
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
                    
                        <li class="nav-item">
                            <a class="nav-link active" href="principal.php" tabindex="-1">Volver a Página Principal</a>
                        </li>

                        <li class="nav-item"><a class="nav-link active" href="cesta.php" tabindex="-1">Cesta</a></li>

                </ul>
                    <button type="button" class="btn btn-success"><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesion</a></button>
            </div>
        </div>
    </nav>
        <?php
        function depurar($entrada)
        {
            $salida = htmlspecialchars($entrada);
            $salida = trim($salida);
            return $salida;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $temp_nombreProducto = depurar($_POST["nombreProducto"]);
            $temp_precio = depurar($_POST["precio"]);
            $temp_descripcion = depurar($_POST["descripcion"]);
            $temp_cantidad = depurar($_POST["cantidad"]);
            $nombre_imagen = $_FILES["imagen"]["name"];
            $tipo_imagen = $_FILES["imagen"]["type"];
            $tamano_imagen = $_FILES["imagen"]["size"];
            $ruta_temporal = $_FILES["imagen"]["tmp_name"];

            #VALIDACIONES
            if (strlen($temp_nombreProducto) == 0) {
                $err_nombre = "El nombre del producto no puede estar vacio";
            } else {
                if (!preg_match("/^[a-zA-Z0-9 ]{1,40}$/", $temp_nombreProducto)) {
                    $err_nombre = "El nombre del producto solo puede tener letras, numeros y espacios en blanco";
                } else {
                    $nombreProducto = $temp_nombreProducto;
                }
            }
            if (empty($temp_precio)) {
                $err_precio = "El precio no puede estar vacio";
            } else {
                if ($temp_precio < 0 || $temp_precio > 99999.99) {
                    $err_precio = "El precio debe ser mayor a 0 y menor a 99999,99";
                } else {
                    $precio = $temp_precio;
                }
            }
            if (empty($temp_descripcion)) {
                $err_descripcion = "La descripcion no puede estar vacia";
            } else {
                if (strlen($temp_descripcion) > 255) {
                    $err_descripcion = "La descripcion tiene como maximo 255 caracteres";
                } else {
                    $descripcion = $temp_descripcion;
                }
            }
            if (empty($temp_cantidad)) {
                $err_cantidad = "La cantidad no puede estar vacia";
            } else {
                if ($temp_cantidad < 0 || $temp_cantidad > 99999) {
                    $err_cantidad = "La cantidad debe ser mayor a 0 y menor a 99999";
                } else {
                    $cantidad = $temp_cantidad;
                }
            }
            if (strlen($nombre_imagen) > 1) {
                if ($_FILES["imagen"]["error"] != 0) {
                    $err_imagen = "Error al subir la imagen";
                } else {
                    $permitidos = ["image/jpeg", "image/png", "image/jpg"];
                    if (!in_array($_FILES["imagen"]["type"], $permitidos)) {
                        $err_imagen = "El formato de la imagen no es valido";
                    } else if ($tamano_imagen > 10000000) {
                        $err_imagen = "La imagen no puede pesar mas de 1MB";
                    } else {
                        $ruta_final = "../views/images/" . $nombre_imagen;
                        move_uploaded_file($ruta_temporal, $ruta_final);
                    }
                }
            } else {
                $err_imagen = "La imagen es obligatoria";
            }
        }
    ?>

        <div class="container">
            <div class="row content">
                <div class="col-md-6 mb-3">
                    <img src="images/producto.jpg" alt="logo" height="400px" id="fotoInsertar" style="border-radius: 10%;">
                </div>
                <div class="col-md-6">
                    <h3 class="signin-text mb-3">Insertar Producto</h3>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="form-label">Nombre Producto:</label>
                            <input type="text" name="nombreProducto" class="form-control">
                            <?php if (isset($err_nombre)) echo $err_nombre; ?>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Precio:</label>
                            <input type="number" name="precio" class="form-control" step="0.01">
                            <?php if (isset($err_precio)) echo $err_precio; ?>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripcion:</label>
                            <input type="text" name="descripcion" class="form-control">
                            <?php if (isset($err_descripcion)) echo $err_descripcion; ?>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cantidad:</label>
                            <input type="number" name="cantidad" class="form-control">
                            <?php if (isset($err_cantidad)) echo $err_cantidad; ?>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Imagen:</label>
                            <input type="file" name="imagen" class="form-control">
                            <?php if (isset($err_imagen)) echo $err_imagen; ?>
                        </div>
                        <input type="submit" name="submit" value="Enviar" class="btn btn-primary mt-3">
                        <?php
                        if (isset($nombreProducto) && isset($precio) && isset($descripcion) && isset($cantidad) && isset($ruta_final)) {
                            echo "<div class='container'><h3>Producto insertado correctamente</h3></div>";
                            $sql = "INSERT INTO productos (nombreProducto, precio, descripcion, cantidad, imagen)
                            VALUES (
                                    '$nombreProducto', 
                                    '$precio',
                                    '$descripcion',
                                    '$cantidad',
                                    '$ruta_final')";
                            $conexion->query($sql);
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</body>

</html>






<!-- <div class="form-group container">
            <h1>Insertar Producto</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <label>Nombre Producto: </label>
                <input type="text" name="nombreProducto" class="form-control">
                <?php if (isset($err_nombre)) echo $err_nombre; ?><br><br>
                <label>Precio</label>
                <input type="number" name="precio" class="form-control">
                <?php if (isset($err_precio)) echo $err_precio; ?><br><br>
                <label>Descripcion</label>
                <input type="text" name="descripcion" class="form-control">
                <?php if (isset($err_descripcion)) echo $err_descripcion; ?><br><br>
                <label>Cantidad</label>
                <input type="number" name="cantidad" class="form-control mt-3">
                <?php if (isset($err_cantidad)) echo $err_cantidad; ?><br><br>
                <label class="form-label">Imagen</label>
                <input type="file" name="imagen" class="form-control">
                <?php if (isset($err_imagen)) echo $err_imagen; ?><br><br>
                <input type="submit" name="submit" value="Enviar" class="btn btn-primary mb-3">
            </form> -->