<?php
require_once __DIR__ . '/config/database.php';

$sql = "ALTER TABLE products ADD COLUMN hover_image VARCHAR(255) DEFAULT NULL AFTER image";
if ($conn->query($sql) === TRUE) {
    echo "Column hover_image added successfully.";
} else {
    echo "Error adding column: " . $conn->error;
}
$conn->close();
?>
