
<?php
/* api.php - P10 - Eventos en formato JSON */

session_start();

// Indicar que la respuesta será JSON
header('Content-Type: application/json; charset=utf-8');

// Funcion para responder con JSON
function responder(int $codigo, array $datos): void
{
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

// Aceptar solamente GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    responder(405, [
        'error' => 'Método no permitido. Usa GET.'
    ]);
}

// Verificar que exista una sesion
if (!isset($_SESSION['uid'])) {
    responder(401, [
        'error' => 'Inicia sesión para consultar tus eventos.'
    ]);
}

$usuarioId = (int) $_SESSION['uid'];

// Conectar a la base de datos
require_once __DIR__ . '/conexion.php';

// Obtener los filtros
$q = substr(trim($_GET['q'] ?? ''), 0, 60);
$categoriaId = (int) ($_GET['categoria'] ?? 0);

// Condiciones de la consulta
$condiciones = ['e.usuario_id = :usuario_id'];

$valores = [
    ':usuario_id' => $usuarioId
];

// Filtro de busqueda
if ($q !== '') {
    $patron = '%' . addcslashes($q, '%_\\') . '%';

    $condiciones[] = '(e.titulo LIKE :titulo OR e.descripcion LIKE :descripcion)';

    $valores[':titulo'] = $patron;
    $valores[':descripcion'] = $patron;
}

// Filtro de categoria
if ($categoriaId > 0) {
    $condiciones[] = 'e.categoria_id = :categoria';

    $valores[':categoria'] = $categoriaId;
}

// Consulta de eventos
$sql = "SELECT e.id, e.titulo, e.fecha,
               TIME_FORMAT(e.hora, '%H:%i') AS hora,
               c.nombre AS categoria, e.descripcion
        FROM eventos e
        JOIN categorias c ON c.id = e.categoria_id
        WHERE " . implode(' AND ', $condiciones) . "
        ORDER BY e.fecha, e.hora";

// Ejecutar consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($valores);

$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Responder con los eventos
responder(200, [
    'usuario' => $_SESSION['nombre'] ?? '',
    'total' => count($eventos),
    'filtros' => [
        'q' => $q,
        'categoria' => $categoriaId
    ],
    'eventos' => $eventos
]);
