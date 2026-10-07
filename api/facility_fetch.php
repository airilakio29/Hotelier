<?php
// api/facility_fetch.php
// Returns all facilities from the Facilities table as JSON.

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
        "SELECT facilID, type, location, priceRate
         FROM `Facilities`
         ORDER BY facilID ASC"
    );
    $facilities = $stmt->fetchAll();

    echo json_encode(['success' => true, 'facilities' => $facilities]);

} catch (Exception $e) {
    error_log('facility_fetch error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to fetch facilities.']);
}
?>
