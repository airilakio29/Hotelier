<?php
// api/login.php
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

$email    = trim($data['email']    ?? '');
$password = trim($data['password'] ?? '');
$role     = trim($data['role']     ?? '');

if (empty($email) || empty($password) || empty($role)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Email, password, and role are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM `User` WHERE username = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $name = 'User';

        // If the user is a guest, fetch their full name from the Guest table
        if ($role === 'guest') {
            $stmtGuest = $pdo->prepare("SELECT fullName FROM `Guest` WHERE email = ?");
            $stmtGuest->execute([$email]);
            $guest = $stmtGuest->fetch();
            if ($guest && !empty($guest['fullName'])) {
                $name = $guest['fullName'];
            }
        } else {
            // For staff/admin, derive display name from email prefix
            $name = ucfirst(explode('@', $email)[0]);
        }

        echo json_encode([
            'success' => true,
            'user'    => [
                'name'  => $name,
                'email' => $email,
                'role'  => $role
            ]
        ]);

    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Invalid email, password, or role.']);
    }

} catch (Exception $e) {
    error_log('login error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Login service temporarily unavailable.']);
}
?>