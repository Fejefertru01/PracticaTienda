<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require "../util/conexion.php" ?>
    <link rel="stylesheet" href="styles/stylesLogin.css">
    <title>Registrarse</title>
</head>

<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $temp_usuario = $_POST['usuario'];
        $temp_contrasena = $_POST['contrasena'];
        $temp_fecha = $_POST['fecha'];

        // Patrón de contraseña
        $patronPassword = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,20}$/";

        // Validación del nombre de usuario
        if (strlen($temp_usuario) == 0) {
            $err_usuario = "El nombre es obligatorio";
        } else {
            if (!preg_match("/^[a-zA-Z_]{4,12}$/", $temp_usuario)) {
                $err_usuario = "El nombre solo puede tener letras y barra baja";
            } else {
                $usuario = $temp_usuario;
            }
        }

        // Validación de la contraseña
        if (strlen($temp_contrasena) <= 0) {
            $err_contrasena = "La contraseña es obligatoria";
        } else {
            if (strlen($temp_contrasena) < 8 || strlen($temp_contrasena) > 20) {
                $err_contrasena = "La contraseña debe tener entre 8 y 20 caracteres";
            } else {
                if (!preg_match($patronPassword, $temp_contrasena)) {
                    $err_contrasena = "La contraseña debe contener al menos una minúscula, una mayúscula, un número y un carácter especial";
                } else {
                    $contrasena_cifrada = password_hash($temp_contrasena, PASSWORD_DEFAULT);
                }
            }
        }

        // Validación de la fecha de nacimiento
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

    <?php
    // Verificar si se han proporcionado los datos necesarios y realiza el registro
    if (isset($usuario) && isset($contrasena_cifrada) && isset($fecha)) {
        $sql = "INSERT INTO usuarios (usuario, contrasena, fechaNacimiento) VALUES ('$usuario', '$contrasena_cifrada','$fecha')";
        $sql2 = "INSERT INTO cestas (usuario, precioTotal) VALUES ('$usuario', 0)";
        $sql3 = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario = '$usuario'");

        // Verificar si el usuario ya existe
        if (mysqli_num_rows($sql3) > 0) {
            $err_usuario = "Este usuario ya existe";
        } else {
            // Insertar datos del nuevo usuario y cesta en la base de datos
            if ($conexion->query($sql) && $conexion->query($sql2)) {
                header('location: iniciar_sesion.php');
            }
        }
    }
    ?>

    <div class="container">
        <div class="row content">
            <div class="col-md-6 mb-3">
                <img src="images/logo2.PNG" alt="logo" height="300px">
            </div>
            <div class="col-md-6">
                <h3 class="signin-text mb-3">Registrarse</h3>
                <form action="" method="post">
                    <!-- Campo de nombre de usuario -->
                    <div class="form-group">
                        <label class="form-label">Usuario:</label>
                        <input class="form-control" type="text" name="usuario">
                        <?php if (isset($err_usuario)) echo $err_usuario ?>
                    </div>
                    <!-- Campo de contraseña -->
                    <div class="form-group mt-3">
                        <label class="form-label">Contraseña:</label>
                        <input class="form-control" type="password" name="contrasena">
                        <?php if (isset($err_contrasena)) echo $err_contrasena ?>
                    </div>
                    <!-- Campo de fecha de nacimiento -->
                    <div class="form-group mt-3">
                        <label class="form-label">Fecha de nacimiento:</label>
                        <input class="form-control" type="date" name="fecha">
                        <?php if (isset($err_fecha)) echo $err_fecha ?>
                    </div>
                    <!-- Botón de registro -->
                    <input class="btn btn-class mt-5" type="submit" value="Registrarse">
                    <!-- Enlace para iniciar sesión si ya se tiene una cuenta -->
                    <p class="mt-3">¿Ya tienes cuenta? <a href="iniciar_sesion.php">Iniciar Sesion</a></p>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>




