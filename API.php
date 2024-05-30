<?php
header("Content-Type: application/json");

$host = 'localhost';
$db = 'dunkin_donut';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO($dsn, $user, $pass, $options);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT o.orderID, o.productname, o.quantity, o.totalprice, c.firstname, c.lastname, o.orderDate
                        FROM orders o
                        INNER JOIN customers c ON o.customerID = c.customerID");
    $orders = $stmt->fetchAll();
    echo json_encode($orders);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $sql = "INSERT INTO orders (productname, quantity, totalprice, customerID, orderdate) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$input['productname'], $input['quantity'], $input['totalprice'], $input['customerID'], $input['orderdate']]);
    echo json_encode(['message' => 'Order Created successfully']);
}
?>