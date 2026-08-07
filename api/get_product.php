<?php
// api/get_product.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Invalid product ID']);
    exit;
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['error' => 'Product not found']);
    exit;
}

$product = $res->fetch_assoc();

// Format price with global currency
$product['formatted_price'] = format_price($product['price']);
$product['image_url'] = BASE_URL . 'assets/uploads/products/' . ($product['image'] ?: 'placeholder.png'); // Handled by JS if placeholder

echo json_encode($product);
