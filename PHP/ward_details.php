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
    $id = $_POST['wardId'] ?? null;
    $ward_name = $_POST['wardName'];
    $ward_type = $_POST['wardType'];
    $capacity = $_POST['capacity'];

    if ($id) {
        $stmt = $pdo->prepare("UPDATE ward SET ward_name = ?, ward_type = ?, capacity = ? WHERE id = ?");
        $stmt->execute([$ward_name, $ward_type, $capacity, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO ward (ward_name, ward_type, capacity) VALUES (?, ?, ?)");
        $stmt->execute([$ward_name, $ward_type, $capacity]);
    }

    echo json_encode(['success' => true]);
} elseif ($method == 'GET') {
    $id = $_GET['id'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM ward WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $stmt = $pdo->query("SELECT * FROM ward");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
