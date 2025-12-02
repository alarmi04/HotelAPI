    <?php
    include_once('conexion.ini.php');
    include_once('usuario.ini.php');

    // Indicar que la respuesta será JSON
    header('Content-Type: application/json');

    // Conexión a la base de datos
    $db = json_decode(file_get_contents('credenciales.txt'), true);
    $conectar = new Conexion($db['host'], $db['username'], $db['password'], $db['db']);
    $conexion = $conectar->conectionPDO();

    // Leer el JSON enviado por POST
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar que se recibieron todos los datos necesarios
    if (isset($_POST['nombre'], $_POST['correo'], $_POST['clave'], $_POST['telefono'])) {

        // Leer los datos del formulario
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $clave = $_POST['clave'];
        $telefono = $_POST['telefono'];

        // Convertirlos a JSON dentro de PHP
        $jsonInput = json_encode([
            'nombre' => $nombre,
            'correo' => $correo,
            'clave' => $clave,
            'telefono' => $telefono
        ]);

        // Decodificarlo inmediatamente si quieres usarlo como array
        $input = json_decode($jsonInput, true);

        // Crear usuario con los datos del json
        $usuario = new Usuario(null, $input['nombre'], $input['correo'], $input['clave'], $input['telefono']);
        $usuario->registrarUsuario($conexion);

        echo "<pre>Usuario registrado: $jsonInput</pre>";
    }

    ?>


    <h1>Crea una cuenta</h1>
    <form action="#" method="POST">
        <p>Nombre</p>
        <input type="text" name="nombre">
        <p>Correo electronico</p>
        <input type="email" name="correo">
        <p>Contraseña</p>
        <input type="password" name="clave">
        <p>Telefono</p>
        <input type="text" name="telefono">
        <br>
        <br>
        <input type="submit" value="Registrarse">
    </form>