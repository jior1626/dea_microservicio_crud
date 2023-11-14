<?php
//api.php
include 'Database.php';
include 'Dea.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

$dea = new Dea($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        $result = $dea->read($id);
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($dea->create($data)) {
            echo json_encode(['message' => 'dea creado']);
        } else {
            echo json_encode(['message' => 'Error al crear dea']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($dea->update($data)) {
            echo json_encode(['message' => 'dea actualizado']);
        } else {
            echo json_encode(['message' => 'Error al actualizar dea']);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'];
        if ($dea->delete($id)) {
            echo json_encode(['message' => 'dea eliminado']);
        } else {
            echo json_encode(['message' => 'Error al eliminar dea']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método no permitido']);
        break;
}
?>
