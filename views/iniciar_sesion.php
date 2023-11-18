<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require "../util/conexion.php" ?>
    <link rel="stylesheet" href="styles/stylesLogin.css">
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
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                El usuario no existe.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
    ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                La contraseña es incorrecta.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php
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
            <h3 class="signin-text mb-3">Iniciar Sesión</h3>
            <form action="" method="post">
                <div class="form-group">
                    <label class="form-label">Usuario:</label>
                    <input class="form-control" type="text" name="usuario">
                </div>
                <div class="form-group">
                <label class="form-label">Contraseña:</label>
                <input class="form-control" type="password" name="contrasena">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" name="checkbox" class="form-check-input" id="checkbox">
                    <label class="form-check-label" for="checkbox">Remember Me</label>
                </div>
                <input class="btn btn-class mt-3" type="submit" value="Iniciar sesion">
                <p class="mt-3">¿No tienes cuenta? <a href="registro.php">Registrarse</a></p>
            </form>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>




<!-- <div class="container">
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
            <input class="btn btn-primary" type="submit" value="Iniciar Sesión">
        </form>
        
    </div> -->