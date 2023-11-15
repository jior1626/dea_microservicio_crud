<?php
//api.php
include 'Database.php';
include 'TipoDeclaracion.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

$dea = new TipoDeclaracion($db);

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $id = $_GET['id'] ?? null;
            $result = $dea->read($id);
            echo json_encode($result);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            // Validación genérica
            if (empty($data)) {
                http_response_code(400); // Respuesta de error Bad Request
                echo json_encode(['message' => 'Los datos de entrada están vacíos o no son válidos.']);
            } else {
                // Los datos son válidos, continúa con el procesamiento.
                if ($dea->create($data)) {
                    echo json_encode(['message' => 'Tipo de declaración creada']);
                } else {
                    throw new Exception('Error al crear tipo de declaración');
                }
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);

            // Validación genérica
            if (empty($data)) {
                http_response_code(400); // Respuesta de error Bad Request
                echo json_encode(['message' => 'Los datos de entrada están vacíos o no son válidos.']);
            } else {
                // Los datos son válidos, continúa con el procesamiento.
                if ($dea->update($data)) {
                    echo json_encode(['message' => 'Tipo de declaración actualizada']);
                } else {
                    throw new Exception('Error al actualizar tipo de declaración');
                }
            }
            break;

        case 'DELETE':
            $id = $_GET['id'];
            if ($dea->delete($id)) {
                echo json_encode(['message' => 'tipo declaración eliminado']);
            } else {
                throw new Exception('Error al eliminar tipo de declaración');
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Método no permitido']);
            break;
    }
} catch (PDOException $e) {
    // Error de base de datos
    error_log('Error de base de datos: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['message' => 'Error interno de la base de datos.']);
} catch (Exception $e) {
    // Otros errores
    error_log('Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['message' => 'Ha ocurrido un error en el servidor.']);
}
?>
