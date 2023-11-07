<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
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
        $nombre_imagen

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
    }
    ?>
        <div class="form-group container">
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
            <input type="file" name="imagen" class="form-control"><br><br>
            <input type="submit" name="submit" value="Enviar" class="btn btn-primary mb-3">
            <?php 
                    if (isset($nombreProducto) && isset($precio) && isset($descripcion) && isset($cantidad)) {
                        echo "<div class='container'><h3>Producto insertado correctamente</h3></div>";
                    }
            ?>
        </form>
    </div>
</body>

</html>