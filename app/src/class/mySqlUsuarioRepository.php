<?php
class MySqlUsuarioRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getNombreUsuario($id)
    {
        $sql = "SELECT nombre FROM usuarios WHERE id = :id";
        $stmt = $this->db->conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarios()
    {
        $sql = "SELECT * FROM usuarios";
        $stmt = $this->db->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuariosDisponibles()
    {
        $sql = "SELECT  usuarios.id, usuarios.nombre, usuarios.email FROM usuarios LEFT JOIN tareas ON usuarios.id = tareas.usuario_id WHERE tareas.usuario_id IS NULL";
        $stmt = $this->db->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarUsuario($nombre, $email)
    {
        $sql = "INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)";
        $stmt = $this->db->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

}
