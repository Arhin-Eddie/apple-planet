<?php
// cart-action.php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $product_id = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if ($product_id > 0 && $quantity > 0) {
            // Verify product exists and fetch details
            $stmt = $conn->prepare("SELECT id, product_name, price, image FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                
                // Add to cart
                $found = false;
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['id'] == $product_id) {
                        $item['quantity'] += $quantity;
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    $_SESSION['cart'][] = [
                        'id' => $product['id'],
                        'name' => $product['product_name'],
                        'price' => $product['price'],
                        'image' => $product['image'],
                        'quantity' => $quantity
                    ];
                }
            }
        }
        echo "Success";
        exit;
    }
    
    if ($action === 'remove') {
        $product_id = (int)($_POST['product_id'] ?? 0);
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        // Re-index array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        redirect(BASE_URL . 'cart.php');
    }
    
    if ($action === 'update') {
        $product_id = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if ($quantity > 0) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product_id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
        } else {
            // Remove if quantity is 0
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        redirect(BASE_URL . 'cart.php');
    }
}

// Default redirect if accessed directly
redirect(BASE_URL);
