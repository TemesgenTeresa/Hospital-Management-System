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
    $id = $_POST['labId'] ?? null;
    $lab_test_name = $_POST['labTestName'];
    $date = $_POST['date'];
    $duration = $_POST['duration'];
    $cost = $_POST['cost'];
    $type = $_POST['type'];
    $result = $_POST['result'];

    if ($id) {
        $stmt = $pdo->prepare("UPDATE lab SET lab_test_name = ?, date = ?, duration = ?, cost = ?, type = ?, result = ? WHERE id = ?");
        $stmt->execute([$lab_test_name, $date, $duration, $cost, $type, $result, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO lab (lab_test_name, date, duration, cost, type, result) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$lab_test_name, $date, $duration, $cost, $type, $result]);
    }

    echo json_encode(['success' => true]);
} elseif ($method == 'GET') {
    $id = $_GET['id'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM lab WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $stmt = $pdo->query("SELECT * FROM lab");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
