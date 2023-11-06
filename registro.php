<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require "conexion.php" ?>
    <title>Registrarse</title>
</head>

<body>


    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $temp_usuario = $_POST['usuario'];
        $temp_contrasena = $_POST['contrasena'];
        $temp_fecha = $_POST['fecha'];

        if (strlen($temp_usuario) == 0) {
            $err_usuario = "El nombre es obligatorio";
        } else {

            if (!preg_match("/^[a-zA-Z_]{4,12}$/", $temp_usuario)) {
                $err_usuario = "El nombre solo puede tener letras, y barra baja";
            } else {
                $usuario = $temp_usuario;
            }
        }

        if (strlen($temp_contrasena) == 0) {
            $err_contrasena = "La contraseña es obligatoria";
        } else {
            $patron = "/^[a-zA-Z0-9]{4,255}$/";
            if (!preg_match($patron, $temp_contrasena)) {
                $err_contrasena = "La contraseña debe tener máximo 255 caracteres y contener solamente letras o números";
            } else {
                $contrasena_cifrada = password_hash($temp_contrasena, PASSWORD_DEFAULT);
            }
        }
        if (strlen($temp_fecha) == 0) {
            $err_fecha = "La fecha de nacimiento es obligatoria";
        } else {
            $fecha_actual = date("Y-m-d");
            list($anyo_actual, $mes_actual, $dia_actual) = explode('-', $fecha_actual);
            list($anyo, $mes, $dia) = explode('-', $temp_fecha);
            if (($anyo_actual - $anyo > 12) && ($anyo_actual - $anyo < 120)) {
                $fecha = $temp_fecha;
            } else if (($anyo_actual - $anyo < 12) || ($anyo_actual - $anyo > 120)) {
                $err_fecha = "Debes tener entre 12 y 120 años";
            } else {
                if ($mes_actual - $mes < 0) {
                    $fecha = $temp_fecha;
                } else if ($mes_actual - $mes < 0) {
                    $err_fecha = "Debes tener entre 12 y 120 años";
                } else {
                    if ($dia_actual - $dia >= 0) {
                        $fecha = $temp_fecha;
                    } else {
                        $err_fecha = "Debes tener entre 12 y 120 años";
                    }
                }
            }
        }
    }

    ?>
    <div class="container">
        <h1>Registrarse</h1>
        <form action="" method="post">

            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input class="form-control" type="text" name="usuario">
                <?php if (isset($err_usuario)) echo $err_usuario ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <input class="form-control" type="password" name="contrasena">
                <?php if (isset($err_contrasena)) echo $err_contrasena ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha de nacimiento:</label>
                <input class="form-control" type="date" name="fecha">
                <?php if (isset($err_fecha)) echo $err_fecha ?>
            </div>
            <input class="btn btn-primary" type="submit" value="Registrarse">
        </form>
    </div>
    <?php

    if (isset($usuario) && isset($contrasena_cifrada) && isset($fecha)) {
        $sql = "INSERT INTO usuarios VALUES ('$usuario','$contrasena_cifrada','$fecha')";
        $conexion->query($sql);
        echo "Usuario registrado con éxito";
        session_start();
        $_SESSION["usuario"] = $usuario;
        header("Location: iniciar_sesion.php");
    }
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>