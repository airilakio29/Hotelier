<?php
// api/register.php
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

$name     = trim($data['name']     ?? '');
$email    = trim($data['email']    ?? '');
$password = trim($data['password'] ?? '');
$phone    = trim($data['phone']    ?? '');
$role     = trim($data['role']     ?? '');

if (empty($name) || empty($email) || empty($password) || empty($role)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'All required fields must be filled.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid email address.']);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Password must be at least 6 characters.']);
    exit;
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT userID FROM `User` WHERE username = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'Email is already registered.']);
        exit;
    }

    $pdo->beginTransaction();

    // 1. Insert into User table
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO `User` (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$email, $hashedPassword, $role]);
    $userId = (int)$pdo->lastInsertId();

    // 2. If guest, also insert into Guest table
    if ($role === 'guest') {
        $phoneVal = !empty($phone) ? $phone : 'N/A';
        $stmtGuest = $pdo->prepare(
            "INSERT INTO `Guest` (guestID, fullName, email, phoneNumber) VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE fullName = VALUES(fullName), phoneNumber = VALUES(phoneNumber)"
        );
        $stmtGuest->execute([$userId, $name, $email, $phoneVal]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'user'    => [
            'name'  => $name,
            'email' => $email,
            'role'  => $role
        ]
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('register error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Registration service temporarily unavailable.']);
}
?>