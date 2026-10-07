<?php
// api/room_update.php
// Updates status of a room identified by its roomNumber (e.g. 301, 502, 701).

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/db.php';

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    exit;
}

$roomNumber = isset($data['roomNumber']) ? (int)$data['roomNumber'] : 0;
$status     = trim($data['status'] ?? '');

$allowedStatuses = ['available', 'occupied', 'maintenance'];
if ($roomNumber < 301 || $roomNumber > 720 || !in_array($status, $allowedStatuses, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid room number or status value.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE `Room` SET status = ? WHERE roomNumber = ?");
    $stmt->execute([$status, $roomNumber]);

    if ($stmt->rowCount() === 0) {
        // Could be that status was already the same, or room was not found
        // Check if room exists
        $checkStmt = $pdo->prepare("SELECT roomID FROM `Room` WHERE roomNumber = ?");
        $checkStmt->execute([$roomNumber]);
        if (!$checkStmt->fetch()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => "Room {$roomNumber} not found."]);
            exit;
        }
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    error_log('room_update error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to update room.']);
}
?>