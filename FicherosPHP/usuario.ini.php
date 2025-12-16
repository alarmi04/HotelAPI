<?php
class Usuario
{
    private $id;
    private $nombre;
    private $correo;
    private $clave;
    private $telefono;

    public function __construct($id = null, $nombre = null, $correo = null, $clave = null, $telefono = null)
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

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($value)
    {
        $this->telefono = $value;
    }

    public function registrarUsuario($conexion)
    {
        $claveHash = password_hash($this->clave, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO usuario (nombre, correo, clave, telefono) 
                VALUES ('{$this->nombre}', '{$this->correo}', '{$claveHash}', '{$this->telefono}')";
        
        $conexion->exec($sql);
        $this->id = $conexion->lastInsertId();

        if ($this->id) {
            return true;
        }
        return false;
    }

    public function leer($conexion)
    {
        $sql = "SELECT id, nombre, correo, telefono FROM usuario ORDER BY id DESC";
        $resultado = $conexion->query($sql);
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerUno($conexion)
    {
        $sql = "SELECT id, nombre, correo, telefono FROM usuario WHERE id = {$this->id} LIMIT 1";
        $resultado = $conexion->query($sql);
        $row = $resultado->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->correo = $row['correo'];
            $this->telefono = $row['telefono'];
            return $row;
        }
        return false;
    }

    public function actualizar($conexion)
    {
        $sql = "UPDATE usuario 
                SET nombre = '{$this->nombre}', correo = '{$this->correo}', telefono = '{$this->telefono}' 
                WHERE id = {$this->id}";
        
        return $conexion->exec($sql);
    }

    public function eliminar($conexion)
    {
        $sql = "DELETE FROM usuario WHERE id = {$this->id}";
        return $conexion->exec($sql);
    }

    public function login($conexion)
    {
        $sql = "SELECT id, nombre, correo, clave, telefono 
                FROM usuario 
                WHERE correo = '{$this->correo}' LIMIT 1";
        
        $resultado = $conexion->query($sql);
        $row = $resultado->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($this->clave, $row['clave'])) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->telefono = $row['telefono'];
            return true;
        }
        return false;
    }

    public function existeCorreo($conexion)
    {
        $sql = "SELECT COUNT(*) as total FROM usuario WHERE correo = '{$this->correo}'";
        $resultado = $conexion->query($sql);
        $row = $resultado->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }
}
?>
