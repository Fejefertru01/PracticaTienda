<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require "../util/conexion.php" ?>
    <title>Inciar Sesion</title>
</head>

<body>
    <?php
    // session_start();
    // if (isset($_SESSION["usuario"])) {
    //     $usuario = $_SESSION["usuario"];
    // } else {
    //     header("Location: registro.php");

    // }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows == 0) { ?>
            <div class="alert alert-danger" role="alert">
                EL USUARIO NO EXISTE
            </div>
    <?php
        } else {
            while ($fila = $resultado->fetch_assoc()) {
                $contrasena_cifrada = $fila["contrasena"];
                $rol = $fila["rol"];
            }
            $acceso_valido = password_verify($contrasena, $contrasena_cifrada);
            if ($acceso_valido) {
                echo "NOS HEMOS LOGUEADO CON EXITO";
                session_start();
                $_SESSION["usuario"] = $usuario;
                $_SESSION["rol"] = $rol;
                header("Location: principal.php");
            } else {
                echo "LA CONTRASEÑA NO ES CORRECTA";
            }
        }
    }
    ?>
    <div class="container">
        <h1>Iniciar Sesion</h1>
        <form action="" method="post">

            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input class="form-control" type="text" name="usuario">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <input class="form-control" type="password" name="contrasena">
            </div>
            <input class="btn btn-primary" type="submit" value="Registrarse">
        </form>
        <p class="mt-3">¿No tienes cuenta? <a href="registro.php">Registrarse</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>