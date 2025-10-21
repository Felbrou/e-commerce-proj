<?php
// src/public/api/cart.php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/database.php';

use App\Classes\Cart;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Set JSON header
header('Content-Type: application/json');

// Handle preflight requests for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

// Initialize response
$response = [
    'success' => false,
    'message' => 'Invalid request'
];

try {
    // Get request method and action
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? '';
    
    switch ($method) {
        case 'POST':
            // Get JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            
            switch ($action) {
                case 'add':
                    // Validate input
                    if (empty($input['product_id'])) {
                        throw new Exception('Product ID is required');
                    }
                    
                    $productId = $input['product_id'];
                    $quantity = $input['quantity'] ?? 1;
                    $attributes = $input['attributes'] ?? [];
                    
                    // Validate quantity
                    if ($quantity < 1) {
                        throw new Exception('Invalid quantity');
                    }
                    
                    // Check stock
                    if (!Cart::checkStock($productId, $quantity)) {
                        throw new Exception('Product is out of stock');
                    }
                    
                    // Add to cart
                    $response = Cart::addItem($productId, $quantity, $attributes);
                    break;
                    
                case 'update':
                    if (empty($input['cart_key']) || !isset($input['quantity'])) {
                        throw new Exception('Cart key and quantity are required');
                    }
                    
                    $response = Cart::updateQuantity($input['cart_key'], $input['quantity']);
                    break;
                    
                case 'clear':
                    $response = Cart::clear();
                    break;
                    
                default:
                    throw new Exception('Invalid action');
            }
            break;
            
        case 'DELETE':
            // Remove item from cart
            $cartKey = $_GET['cart_key'] ?? '';
            
            if (empty($cartKey)) {
                throw new Exception('Cart key is required');
            }
            
            $response = Cart::removeItem($cartKey);
            break;
            
        case 'GET':
            switch ($action) {
                case 'items':
                    // Get all cart items
                    $items = Cart::getItems();
                    $response = [
                        'success' => true,
                        'items' => $items,
                        'totalItems' => Cart::getTotalItems(),
                        'totalPrice' => Cart::getTotalPrice()
                    ];
                    break;
                    
                case 'count':
                    // Get cart count only
                    $response = [
                        'success' => true,
                        'count' => Cart::getTotalItems()
                    ];
                    break;
                    
                default:
                    throw new Exception('Invalid action');
            }
            break;
            
        default:
            throw new Exception('Method not allowed');
    }
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
    
    // Set appropriate HTTP status code
    http_response_code(400);
}

// Output JSON response
echo json_encode($response);