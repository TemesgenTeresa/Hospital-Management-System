<?php
$dsn = 'mysql:host=localhost;dbname=hospital_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    $id = $_POST['patientId'] ?? null;
    $name = $_POST['patientName'];
    $age = $_POST['patientAge'];
    $gender = $_POST['patientGender'];
    $address = $_POST['patientAddress'];
    $phone = $_POST['patientPhone'];
    $email = $_POST['patientEmail'];

    if ($id) {
        $stmt = $pdo->prepare("UPDATE patients SET name = ?, age = ?, gender = ?, address = ?, phone = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $age, $gender, $address, $phone, $email, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO patients (name, age, gender, address, phone, email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $age, $gender, $address, $phone, $email]);
    }

    echo json_encode(['success' => true]);
} elseif ($method == 'GET') {
    $id = $_GET['id'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $stmt = $pdo->query("SELECT * FROM patients");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
