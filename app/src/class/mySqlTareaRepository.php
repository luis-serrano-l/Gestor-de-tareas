<?php
class MySqlTareaRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function obtenerTareasAsignadas()
    {
        $sql = "SELECT * FROM tareas WHERE usuario_id IS NOT NULL";
        $stmt = $this->db->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerTareasSinAsignar()
    {
        $sql = "SELECT * FROM tareas WHERE usuario_id IS NULL AND completada = 0";
        $stmt = $this->db->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerTareasCompletadas()
    {
        $sql = "SELECT * FROM tareas WHERE completada = 1";
        $stmt = $this->db->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarTarea($descripcion, $usuario_id)
    {
        if ($usuario_id == '') {
            $sql = "INSERT INTO tareas (descripcion) VALUES (:descripcion)";
            $stmt = $this->db->conexion->prepare($sql);
            $stmt->bindParam(':descripcion', $descripcion);
        } else {
            $sql = "INSERT INTO tareas (descripcion, usuario_id) VALUES (:descripcion, :usuario_id)";
            $stmt = $this->db->conexion->prepare($sql);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':usuario_id', $usuario_id);
        }
        return $stmt->execute();
    }

    public function asignarTarea($id, $usuario_id)
    {
        $sql = "UPDATE tareas SET usuario_id = :usuario_id WHERE id = :id";
        $stmt = $this->db->conexion->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function completarTarea($id)
    {
        $sql = "UPDATE tareas SET completada = 1, usuario_id = NULL WHERE id = :id";
        $stmt = $this->db->conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}