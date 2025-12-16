<?php
class Reserva
{
    private $id;
    private $fechaEntrada;
    private $fechaSalida;
    private $hora;
    private $adultos;
    private $menores;
    private $habitaciones;
    private $usuario_id;

    public function __construct($id = null, $fechaEntrada = null, $fechaSalida = null, $hora = null, $adultos = null, $menores = null, $habitaciones = null, $usuario_id = null)
    {
        $this->id = $id;
        $this->fechaEntrada = $fechaEntrada;
        $this->fechaSalida = $fechaSalida;
        $this->hora = $hora;
        $this->adultos = $adultos;
        $this->menores = $menores;
        $this->habitaciones = $habitaciones;
        $this->usuario_id = $usuario_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getFechaEntrada()
    {
        return $this->fechaEntrada;
    }

    public function setFechaEntrada($value)
    {
        $this->fechaEntrada = $value;
    }

    public function getFechaSalida()
    {
        return $this->fechaSalida;
    }

    public function setFechaSalida($value)
    {
        $this->fechaSalida = $value;
    }

    public function getHora()
    {
        return $this->hora;
    }

    public function setHora($value)
    {
        $this->hora = $value;
    }

    public function getAdultos()
    {
        return $this->adultos;
    }

    public function setAdultos($value)
    {
        $this->adultos = $value;
    }

    public function getMenores()
    {
        return $this->menores;
    }

    public function setMenores($value)
    {
        $this->menores = $value;
    }

    public function getHabitaciones()
    {
        return $this->habitaciones;
    }

    public function setHabitaciones($value)
    {
        $this->habitaciones = $value;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setUsuarioId($value)
    {
        $this->usuario_id = $value;
    }

    public function crear($conexion)
    {
        $sql = "INSERT INTO reservas (fechaEntrada, fechaSalida, hora, adultos, menores, habitaciones, usuario_id) 
                VALUES ('{$this->fechaEntrada}', '{$this->fechaSalida}', '{$this->hora}', {$this->adultos}, {$this->menores}, {$this->habitaciones}, {$this->usuario_id})";

        $conexion->exec($sql);
        $this->id = $conexion->lastInsertId();

        if ($this->id) {
            return true;
        }
        return false;
    }

    public function leer($conexion)
    {
        $sql = "SELECT r.id, r.fechaEntrada, r.fechaSalida, r.hora, r.adultos, 
                       r.menores, r.habitaciones, r.usuario_id,
                       u.nombre as usuario_nombre, u.correo as usuario_correo
                FROM reservas r
                LEFT JOIN usuario u ON r.usuario_id = u.id
                ORDER BY r.fechaEntrada DESC";

        $resultado = $conexion->query($sql);
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerUno($conexion)
    {
        $sql = "SELECT r.id, r.fechaEntrada, r.fechaSalida, r.hora, r.adultos, 
                       r.menores, r.habitaciones, r.usuario_id,
                       u.nombre as usuario_nombre, u.correo as usuario_correo, u.telefono as usuario_telefono
                FROM reservas r
                LEFT JOIN usuario u ON r.usuario_id = u.id
                WHERE r.id = {$this->id} 
                LIMIT 1";

        $resultado = $conexion->query($sql);
        $row = $resultado->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->fechaEntrada = $row['fechaEntrada'];
            $this->fechaSalida = $row['fechaSalida'];
            $this->hora = $row['hora'];
            $this->adultos = $row['adultos'];
            $this->menores = $row['menores'];
            $this->habitaciones = $row['habitaciones'];
            $this->usuario_id = $row['usuario_id'];
            return $row;
        }
        return false;
    }

    public function leerPorUsuario($conexion)
    {
        $sql = "SELECT r.id, r.fechaEntrada, r.fechaSalida, r.hora, r.adultos, 
                       r.menores, r.habitaciones
                FROM reservas r
                WHERE r.usuario_id = {$this->usuario_id}
                ORDER BY r.fechaEntrada DESC";

        $resultado = $conexion->query($sql);
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar($conexion)
    {
        $sql = "UPDATE reservas 
                SET fechaEntrada = '{$this->fechaEntrada}', 
                    fechaSalida = '{$this->fechaSalida}', 
                    hora = '{$this->hora}', 
                    adultos = {$this->adultos}, 
                    menores = {$this->menores}, 
                    habitaciones = {$this->habitaciones}
                WHERE id = {$this->id}";

        return $conexion->exec($sql);
    }

    public function eliminar($conexion)
    {
        $sql = "DELETE FROM reservas WHERE id = {$this->id}";
        return $conexion->exec($sql);
    }

    public function verificarDisponibilidad($conexion)
    {
        $sql = "SELECT COUNT(*) as total_reservas 
                FROM reservas 
                WHERE (fechaEntrada BETWEEN '{$this->fechaEntrada}' AND '{$this->fechaSalida}')
                   OR (fechaSalida BETWEEN '{$this->fechaEntrada}' AND '{$this->fechaSalida}')
                   OR ('{$this->fechaEntrada}' BETWEEN fechaEntrada AND fechaSalida)";

        $resultado = $conexion->query($sql);
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }
}
?>
