<?php
$dsn = 'mysql:host=localhost;dbname=hospital_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    $id = $_POST['employeeId'] ?? null;
    $name = $_POST['employeeName'];
    $position = $_POST['employeePosition'];
    $contact = $_POST['employeeContact'];

    if ($id) {
        $stmt = $pdo->prepare("UPDATE employee SET name = ?, position = ?, contact = ? WHERE id = ?");
        $stmt->execute([$name, $position, $contact, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO employee (name, position, contact) VALUES (?, ?, ?)");
        $stmt->execute([$name, $position, $contact]);
    }

    echo json_encode(['success' => true]);
} elseif ($method == 'GET') {
    $id = $_GET['id'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM employee WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $stmt = $pdo->query("SELECT * FROM employee");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
