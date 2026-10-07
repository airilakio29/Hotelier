<?php
// api/room_fetch.php
// Returns all rooms with their display roomNumber (e.g. 301, 302 ... 720)

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/db.php';

try {
    $stmt = $pdo->query(
        "SELECT roomNumber as number, roomType, priceRate, status
         FROM `Room`
         ORDER BY roomNumber ASC"
    );
    $rooms = $stmt->fetchAll();

    echo json_encode(['success' => true, 'rooms' => $rooms]);

} catch (Exception $e) {
    error_log('room_fetch error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to fetch rooms.']);
}
?>