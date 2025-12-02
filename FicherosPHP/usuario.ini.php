<?php

class Usuario
{
    private $id;
    private $nombre;
    private $correo;
    private $clave;

    public function __construct($id, $nombre, $correo, $clave)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($value)
    {
        $this->nombre = $value;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function setCorreo($value)
    {
        $this->correo = $value;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function setClave($value)
    {
        $this->clave = $value;
    }

    function registrarUsuario($conexion, $input)
    {
        // $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO usuario (nombre, correo, clave, telefono) VALUES ('{$input['nombre']}', '{$input['correo']}', '{$input['clave']}', '{$input['telefono']}')";
        echo $sql;
        $conexion->exec($sql);
        $usuId = $conexion->lastInsertId();
        if ($usuId) {
            $input['id'] = $usuId;
            header("HTTP/1.1 200 OK");
            echo json_encode($input);
            exit();
        }
    }
}

?>