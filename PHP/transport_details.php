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
    $id = $_POST['transportId'] ?? null;
    $vehicle_number = $_POST['vehicleNumber'];
    $driver_name = $_POST['driverName'];
    $driver_contact = $_POST['driverContact'];
    $transport_type = $_POST['transportType'];

    if ($id) {
        $stmt = $pdo->prepare("UPDATE transport SET vehicle_number = ?, driver_name = ?, driver_contact = ?, transport_type = ? WHERE id = ?");
        $stmt->execute([$vehicle_number, $driver_name, $driver_contact, $transport_type, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO transport (vehicle_number, driver_name, driver_contact, transport_type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$vehicle_number, $driver_name, $driver_contact, $transport_type]);
    }

    echo json_encode(['success' => true]);
} elseif ($method == 'GET') {
    $id = $_GET['id'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM transport WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $stmt = $pdo->query("SELECT * FROM transport");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
