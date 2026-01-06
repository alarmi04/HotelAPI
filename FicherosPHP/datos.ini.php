<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

include_once 'conexion.ini.php';
include_once 'usuario.ini.php';
include_once 'reserva.ini.php';

$metodo = $_SERVER['REQUEST_METHOD'];

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

// Obtener datos JSON para POST/PUT/DELETE
$data = json_decode(file_get_contents("php://input"));

// ============================================
// ENDPOINTS DE USUARIO
// ============================================

// CREAR USUARIO
if($accion == 'usuario_crear' && $metodo == 'POST') {
    if(!empty($data->nombre) && !empty($data->correo) && !empty($data->clave) && !empty($data->telefono)) {
        $usuario = new Usuario(null, $data->nombre, $data->correo, $data->clave, $data->telefono);
        
        $usuario->setCorreo($data->correo);
        if($usuario->existeCorreo($conexion)) {
            http_response_code(400);
            echo json_encode(array("mensaje" => "El correo ya está registrado."));
            exit();
        }

        if($usuario->registrarUsuario($conexion)) {
            http_response_code(201);
            echo json_encode(array("mensaje" => "Usuario creado exitosamente.", "id" => $usuario->getId()));
        } else {
            http_response_code(503);
            echo json_encode(array("mensaje" => "No se pudo crear el usuario."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos."));
    }
    exit();
}

// LOGIN
if($accion == 'usuario_login' && $metodo == 'POST') {
    if(!empty($data->correo) && !empty($data->clave)) {
        $usuario = new Usuario();
        $usuario->setCorreo($data->correo);
        $usuario->setClave($data->clave);

        if($usuario->login($conexion)) {
            http_response_code(200);
            echo json_encode(array(
                "mensaje" => "Login exitoso.",
                "id" => $usuario->getId(),
                "nombre" => $usuario->getNombre(),
                "correo" => $usuario->getCorreo(),
                "telefono" => $usuario->getTelefono()
            ));
        } else {
            http_response_code(401);
            echo json_encode(array("mensaje" => "Credenciales inválidas."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos."));
    }
    exit();
}

// LISTAR USUARIOS
if($accion == 'usuario_leer' && $metodo == 'GET') {
    $usuario = new Usuario();
    $registros = $usuario->leer($conexion);

    if(count($registros) > 0) {
        http_response_code(200);
        echo json_encode(array("registros" => $registros));
    } else {
        http_response_code(404);
        echo json_encode(array("mensaje" => "No se encontraron usuarios."));
    }
    exit();
}

// VER UN USUARIO
if($accion == 'usuario_leer_uno' && $metodo == 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    
    if($id) {
        $usuario = new Usuario();
        $usuario->setId($id);

        $resultado = $usuario->leerUno($conexion);

        if($resultado) {
            http_response_code(200);
            echo json_encode($resultado);
        } else {
            http_response_code(404);
            echo json_encode(array("mensaje" => "Usuario no encontrado."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "ID no proporcionado."));
    }
    exit();
}

// ACTUALIZAR USUARIO
if($accion == 'usuario_actualizar' && ($metodo == 'PUT' || $metodo == 'POST')) {
    if(!empty($data->id) && !empty($data->nombre) && !empty($data->correo) && !empty($data->telefono)) {
        $usuario = new Usuario();
        $usuario->setId($data->id);
        $usuario->setNombre($data->nombre);
        $usuario->setCorreo($data->correo);
        $usuario->setTelefono($data->telefono);

        if($usuario->actualizar($conexion)) {
            http_response_code(200);
            echo json_encode(array("mensaje" => "Usuario actualizado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensaje" => "No se pudo actualizar el usuario."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos."));
    }
    exit();
}

// ELIMINAR USUARIO
if($accion == 'usuario_eliminar' && ($metodo == 'DELETE' || $metodo == 'POST')) {
    if(!empty($data->id)) {
        $usuario = new Usuario();
        $usuario->setId($data->id);

        if($usuario->eliminar($conexion)) {
            http_response_code(200);
            echo json_encode(array("mensaje" => "Usuario eliminado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensaje" => "No se pudo eliminar el usuario."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos."));
    }
    exit();
}

// ============================================
// ENDPOINTS DE RESERVA
// ============================================

// CREAR RESERVA
if($accion == 'reserva_crear' && $metodo == 'POST') {
    if(!empty($data->fechaEntrada) && !empty($data->fechaSalida) && !empty($data->hora) && 
       isset($data->adultos) && isset($data->menores) && isset($data->habitaciones) && !empty($data->usuario_id)) {
        
        $reserva = new Reserva(
            null,
            $data->fechaEntrada,
            $data->fechaSalida,
            $data->hora,
            $data->adultos,
            $data->menores,
            $data->habitaciones,
            $data->usuario_id
        );

        if($reserva->crear($conexion)) {
            http_response_code(201);
            echo json_encode(array("mensaje" => "Reserva creada exitosamente.", "id" => $reserva->getId()));
        } else {
            http_response_code(503);
            echo json_encode(array("mensaje" => "No se pudo crear la reserva."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos."));
    }
    exit();
}

// LISTAR RESERVAS
if($accion == 'reserva_leer' && $metodo == 'GET') {
    $reserva = new Reserva();
    $registros = $reserva->leer($conexion);

    if(count($registros) > 0) {
        http_response_code(200);
        echo json_encode(array("registros" => $registros));
    } else {
        http_response_code(404);
        echo json_encode(array("mensaje" => "No se encontraron reservas."));
    }
    exit();
}

// VER UNA RESERVA
if($accion == 'reserva_leer_uno' && $metodo == 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    
    if($id) {
        $reserva = new Reserva();
        $reserva->setId($id);

        $resultado = $reserva->leerUno($conexion);

        if($resultado) {
            http_response_code(200);
            echo json_encode($resultado);
        } else {
            http_response_code(404);
            echo json_encode(array("mensaje" => "Reserva no encontrada."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "ID no proporcionado."));
    }
    exit();
}

// RESERVAS POR USUARIO
if($accion == 'reserva_leer_por_usuario' && $metodo == 'GET') {
    $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;
    
    if($usuario_id) {
        $reserva = new Reserva();
        $reserva->setUsuarioId($usuario_id);

        $registros = $reserva->leerPorUsuario($conexion);

        if(count($registros) > 0) {
            http_response_code(200);
            echo json_encode(array("registros" => $registros));
        } else {
            http_response_code(404);
            echo json_encode(array("mensaje" => "No se encontraron reservas para este usuario."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "usuario_id no proporcionado."));
    }
    exit();
}

// ACTUALIZAR RESERVA
if($accion == 'reserva_actualizar' && ($metodo == 'PUT' || $metodo == 'POST')) {
    if(!empty($data->id) && !empty($data->fechaEntrada) && !empty($data->fechaSalida) && 
       !empty($data->hora) && isset($data->adultos) && isset($data->menores) && isset($data->habitaciones)) {
        
        $reserva = new Reserva();
        $reserva->setId($data->id);
        $reserva->setFechaEntrada($data->fechaEntrada);
        $reserva->setFechaSalida($data->fechaSalida);
        $reserva->setHora($data->hora);
        $reserva->setAdultos($data->adultos);
        $reserva->setMenores($data->menores);
        $reserva->setHabitaciones($data->habitaciones);

        if($reserva->actualizar($conexion)) {
            http_response_code(200);
            echo json_encode(array("mensaje" => "Reserva actualizada exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensaje" => "No se pudo actualizar la reserva."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos."));
    }
    exit();
}

// ELIMINAR RESERVA
if($accion == 'reserva_eliminar' && ($metodo == 'DELETE' || $metodo == 'POST')) {
    if(!empty($data->id)) {
        $reserva = new Reserva();
        $reserva->setId($data->id);

        if($reserva->eliminar($conexion)) {
            http_response_code(200);
            echo json_encode(array("mensaje" => "Reserva eliminada exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensaje" => "No se pudo eliminar la reserva."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos."));
    }
    exit();
}

// Si no coincide con ninguna acción
http_response_code(400);
echo json_encode(array("mensaje" => "Acción no válida o no especificada."));
?>