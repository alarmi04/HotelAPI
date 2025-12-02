<?php

class Usuario
{
    private $id;
    private $nombre;
    private $correo;
    private $clave;
    private $telefono;

    public function __construct($id, $nombre, $correo, $clave, $telefono)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->telefono = $telefono;
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

    function registrarUsuario($conexion)
    {
        $sql = "INSERT INTO usuario (nombre, correo, clave, telefono) 
                VALUES ('{$this->nombre}', '{$this->correo}', '{$this->clave}', '{$this->telefono}')";
        $conexion->exec($sql);
        $this->id = $conexion->lastInsertId();

        if ($this->id) {
            echo "Usuario registrado correctamente. ID: " . $this->id;
            exit();
        } else {
            echo "Error al registrar el usuario.";
            exit();
        }
    }
}
    
?>