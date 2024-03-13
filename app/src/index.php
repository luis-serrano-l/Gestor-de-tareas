<?php
require_once "./services/conexionDb.php";
require_once "./class/mySqlUsuarioRepository.php";
require_once "./class/usuario.php";
require_once "./class/mySqlTareaRepository.php";
require_once "./class/tarea.php";

$conexionDB = new ConexionDB();
$usuarioRepository = new MySqlUsuarioRepository($conexionDB);
$tareaRepository = new MySqlTareaRepository($conexionDB);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar-usuario'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $usuario = new Usuario($nombre, $email);
        if ($usuarioRepository->agregarUsuario($usuario->getNombre(), $usuario->getEmail())) {
            echo "Usuario agregado correctamente";
        } else {
            echo "Error al agregar usuario";
        }
    }
    if (isset($_POST['agregar-tarea'])) {
        $descripcion = $_POST['descripcion'];
        $usuario_id = $_POST['usuario_id'];
        $tarea = new Tarea($descripcion, $usuario_id);
        if ($tareaRepository->agregarTarea($tarea->getDescripcion(), $usuario_id)) {
            echo "Tarea agregada correctamente";
        } else {
            echo "Error al asignar tarea";
        }
    }
    if (isset($_POST['asignar-tarea'])) {
        $id = $_POST['id'];
        $usuario_id = $_POST['usuario_id'];
        if ($tareaRepository->asignarTarea($id, $usuario_id)) {
            echo "Tarea asignada correctamente";
        } else {
            echo "Error al asignar tarea";
        }
    }
    if (isset($_POST['liberar'])) {
        $id = $_POST['id'];
        if ($tareaRepository->asignarTarea($id, null)) {
            echo "Tarea liberada correctamente";
        } else {
            echo "Error al liberar tarea";
        }
    }
    if (isset($_POST['completada'])) {
        $id = $_POST['id'];
        if ($tareaRepository->completarTarea($id)) {
            echo "Tarea marcada como completada correctamente";
        } else {
            echo "Error al marcar tarea como completada";
        }
    }
}

$usuarios = $usuarioRepository->obtenerUsuarios();
$usuariosDisponibles = $usuarioRepository->obtenerUsuariosDisponibles();
$tareasAsignadas = $tareaRepository->obtenerTareasAsignadas();
$tareasSinAsignar = $tareaRepository->obtenerTareasSinAsignar();
$tareasCompletadas = $tareaRepository->obtenerTareasCompletadas();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de tareas y usuarios</title>
</head>

<body>
    <h1>Gestor de tareas</h1>
    <h2>Agregar tarea</h2>
    <form method="post" action="index.php">
        <label for="descripcion">Tarea:</label>
        <input type="text" name="descripcion" id="descripcion" required><br>
        <label for="usuario_id">Asignar usuario:</label>
        <select name="usuario_id" id="usuario_id">
            <option value="<?= null ?>">
            </option>
            <?php foreach ($usuariosDisponibles as $usuarioDisponible) { ?>
                <option value="<?= $usuarioDisponible['id'] ?>">
                    <?= $usuarioDisponible['nombre'] ?>
                </option>
            <?php } ?>
        </select><br>
        <input type="submit" name="agregar-tarea" value="Agregar tarea">
    </form>

    <div style="display: flex; flex-wrap: wrap;">
        <div style="margin-right: 20px;">
            <h2>Tareas sin Asignar</h2>
            <?php if (!empty($tareasSinAsignar)) { ?>
                <table>
                    <tr>
                        <th>Descripción</th>
                        <th>Usuario</th>
                    </tr>
                    <?php foreach ($tareasSinAsignar as $tareaSinAsignar) { ?>
                        <tr>

                            <form method="post" action="index.php">
                                <input type="hidden" name="id" value="<?= $tareaSinAsignar['id'] ?>">
                                <td>
                                    <?= $tareaSinAsignar['descripcion'] ?>
                                </td>
                                <td>
                                    <label for="usuario_id">Asignar:</label>
                                    <select name="usuario_id" id="usuario_id">
                                        <?php foreach ($usuariosDisponibles as $usuarioDisponible) { ?>
                                            <option value="<?= $usuarioDisponible['id'] ?>">
                                                <?= $usuarioDisponible['nombre'] ?>
                                            </option>
                                        <?php } ?>
                                    </select><br>
                                </td>
                                <td>
                                    <input type="submit" name="asignar-tarea" value="Asignar">
                                </td>

                            </form>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>

        <div style="margin-right: 20px;">
            <h2>Tareas Asignadas</h2>
            <?php if (!empty($tareasAsignadas)) { ?>
                <table>
                    <tr>
                        <th>Descripción</th>
                        <th>Usuario asignado</th>
                        <th></th>
                    </tr>
                    <?php foreach ($tareasAsignadas as $tareaAsignada) { ?>
                        <tr>
                            <td>
                                <?= $tareaAsignada['descripcion'] ?>
                            </td>
                            <td>
                                <?= $usuarioRepository->getNombreUsuario($tareaAsignada['usuario_id'])['nombre'] ?>
                            </td>
                            <td>
                                <form method="post" action="index.php">
                                    <input type="hidden" name="id" value="<?= $tareaAsignada['id'] ?>">
                                    <input type="submit" name="completada" value="Marcar completada">
                                </form>
                            </td>
                            <td>
                                <form method="post" action="index.php">
                                    <input type="hidden" name="id" value="<?= $tareaAsignada['id'] ?>">
                                    <input type="submit" name="liberar" value="Desasignar">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
        <div>
            <h2>Tareas completadas</h2>
            <?php if (!empty($tareasCompletadas)) { ?>
                <table>
                    <tr>
                        <th>Descripcion</th>
                    </tr>
                    <?php foreach ($tareasCompletadas as $tareaCompletada) { ?>
                        <tr>
                            <td>
                                <?= $tareaCompletada['descripcion'] ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
    </div>
    <h1>Registro de usuarios</h1>
    <h2>Agregar usuario</h2>
    <form method="post" action="index.php">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <input type="submit" name="agregar-usuario" value="Agregar usuario">
    </form>
    <h2>Usuarios</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
        </tr>
        <?php foreach ($usuarios as $usuario) { ?>
            <tr>
                <td>
                    <?= $usuario['id'] ?>
                </td>
                <td>
                    <?= $usuario['nombre'] ?>
                </td>
                <td>
                    <?= $usuario['email'] ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>
<?php
$conexionDB->cerrarConexion();
?>