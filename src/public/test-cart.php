<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Classes/Cart.php';


use App\Classes\Cart;

//Initialize session for testing
session_start();

//Handle test actions
$action = $_GET['action'] ?? '';
$result = null;

switch ($action) {
    case 'add':
        $result = Cart::addItem('test-product-1', 2, ['size' => 'M', 'color' => 'blue']);
        break;


    case 'add_another':
        $result = Cart::addItem('test-product-2', 1, ['size' => 'L']);
        break;

    case 'remove':
        $cartKey = $_GET['cartKey'] ?? '';
        if($cartKey){
            $result = Cart::removeItem($cartKey);
        }
        break;

    case 'update':
        $cartKey = $_GET['cartKey'] ?? '';
        $quantity = (int)($_GET['qty'] ?? 1);
        if ($cartKey) {
            $result =Cart::updateQuantity($cartKey, $quantity);
        }
        break;

    case 'clear':
        $result = Cart::clear();
        break;

    case 'destroy_session':
        session_destroy();
        session_start();
        $result = ['success' => true, 'message' => 'Session destroyed and restarted'];
        break;
}


// Get current cart state
$cartItems = Cart::getItems();
$totalItems = Cart::getTotalItems();
$totalPrice = Cart::getTotalPrice();
$rawCart = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Testing Interface</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .warning {
            background: #ff6b6b;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .card h2 {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        .test-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-success {
            background: #48bb78;
            color: white;
        }
        
        .btn-danger {
            background: #f56565;
            color: white;
        }
        
        .btn-warning {
            background: #ed8936;
            color: white;
        }
        
        .btn-info {
            background: #4299e1;
            color: white;
        }
        
        .result-box {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
        }
        
        .result-box.success {
            border-left-color: #48bb78;
            background: #f0fff4;
        }
        
        .result-box.error {
            border-left-color: #f56565;
            background: #fff5f5;
        }
        
        pre {
            background: #2d3748;
            color: #48bb78;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            font-size: 12px;
            line-height: 1.5;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        th {
            background: #f7fafc;
            font-weight: 600;
            color: #2d3748;
        }
        
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status.active {
            background: #c6f6d5;
            color: #22543d;
        }
        
        .status.inactive {
            background: #fed7d7;
            color: #742a2a;
        }
        
        .test-scenarios {
            background: #edf2f7;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }
        
        .test-scenarios h3 {
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .test-scenarios ol {
            margin-left: 20px;
            color: #4a5568;
        }
        
        .test-scenarios li {
            margin-bottom: 8px;
        }
        
        .metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        .metric {
            background: #f7fafc;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        
        .metric-value {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        
        .metric-label {
            color: #718096;
            font-size: 12px;
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
            
            .metrics {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 Cart System Testing Interface</h1>
        
        <div class="warning">
            ⚠️ This is a testing page. Remove from production!
        </div>
        
        <!-- Test Actions -->
        <div class="card">
            <h2>Quick Test Actions</h2>
            <div class="test-buttons">
                <a href="?action=add" class="btn btn-primary">Add Product 1</a>
                <a href="?action=add_another" class="btn btn-success">Add Product 2</a>
                <a href="?action=update&key=test-product-1&qty=5" class="btn btn-info">Update Qty to 5</a>
                <a href="?action=remove&key=test-product-1" class="btn btn-warning">Remove Product 1</a>
                <a href="?action=clear" class="btn btn-danger">Clear Cart</a>
                <a href="?action=destroy_session" class="btn btn-danger">Destroy Session</a>
            </div>
            
            <?php if ($result): ?>
                <div class="result-box <?= $result['success'] ? 'success' : 'error' ?>">
                    <strong>Last Action Result:</strong><br>
                    <?= htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT)) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="grid">
            <!-- Cart Summary -->
            <div class="card">
                <h2>Cart Summary</h2>
                <div class="metrics">
                    <div class="metric">
                        <div class="metric-value"><?= $totalItems ?></div>
                        <div class="metric-label">Total Items</div>
                    </div>
                    <div class="metric">
                        <div class="metric-value">$<?= number_format($totalPrice, 2) ?></div>
                        <div class="metric-label">Total Price</div>
                    </div>
                    <div class="metric">
                        <div class="metric-value"><?= count($rawCart) ?></div>
                        <div class="metric-label">Unique Items</div>
                    </div>
                </div>
                
                <h3 style="margin-top: 20px;">Cart Items</h3>
                <?php if (empty($cartItems)): ?>
                    <p style="color: #718096; margin-top: 10px;">Cart is empty</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <th>Attributes</th>
                                <th>Cart Key</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['product']['id'] ?? 'N/A') ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><?= htmlspecialchars(json_encode($item['attributes'])) ?></td>
                                    <td><code><?= htmlspecialchars($item['cart_key']) ?></code></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
            <!-- Session Info -->
            <div class="card">
                <h2>Session Information</h2>
                <table>
                    <tr>
                        <th>Session ID</th>
                        <td><code><?= session_id() ?></code></td>
                    </tr>
                    <tr>
                        <th>Session Status</th>
                        <td>
                            <span class="status active">
                                <?= session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Session Name</th>
                        <td><?= session_name() ?></td>
                    </tr>
                    <tr>
                        <th>Cookie Params</th>
                        <td><code><?= json_encode(session_get_cookie_params()) ?></code></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Raw Data -->
        <div class="card">
            <h2>Raw Session Data</h2>
            <pre><?= htmlspecialchars(print_r($_SESSION, true)) ?></pre>
        </div>
        
        <!-- Test Scenarios -->
        <div class="card">
            <h2>Test Scenarios to Try</h2>
            <div class="test-scenarios">
                <h3>✅ Basic Functionality</h3>
                <ol>
                    <li>Click "Add Product 1" - Should add item to cart</li>
                    <li>Click "Add Product 1" again - Should increase quantity</li>
                    <li>Click "Add Product 2" - Should add different item</li>
                    <li>Click "Update Qty to 5" - Should change quantity</li>
                    <li>Click "Remove Product 1" - Should remove item</li>
                    <li>Click "Clear Cart" - Should empty entire cart</li>
                </ol>
                
                <h3 style="margin-top: 20px;">🔄 Session Persistence</h3>
                <ol>
                    <li>Add items to cart</li>
                    <li>Refresh the page (F5) - Items should persist</li>
                    <li>Open in new tab - Same cart should appear</li>
                    <li>Click "Destroy Session" - Cart should be empty</li>
                </ol>
                
                <h3 style="margin-top: 20px;">🐛 Edge Cases</h3>
                <ol>
                    <li>Remove non-existent item - Should show error</li>
                    <li>Update quantity to 0 - Should remove item</li>
                    <li>Update quantity to negative - Should handle gracefully</li>
                    <li>Add same product with different attributes - Should create separate entries</li>
                </ol>
            </div>
        </div>
        
        <!-- API Test Form -->
        <div class="card">
            <h2>API Endpoint Tester</h2>
            <div style="margin-top: 15px;">
                <h3>Test via cURL</h3>
                <pre>
# Add item
curl -X POST http://localhost:8080/api/cart.php?action=add \
  -H "Content-Type: application/json" \
  -d '{"product_id":"test-1","quantity":2,"attributes":{"size":"M"}}'

# Get items
curl http://localhost:8080/api/cart.php?action=items

# Remove item
curl -X DELETE http://localhost:8080/api/cart.php?cart_key=test-1

# Update quantity
curl -X POST http://localhost:8080/api/cart.php?action=update \
  -H "Content-Type: application/json" \
  -d '{"cart_key":"test-1","quantity":3}'
                </pre>
            </div>
            
            <div style="margin-top: 15px;">
                <h3>Test via JavaScript Console</h3>
                <pre>
// Add item
fetch('/api/cart.php?action=add', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        product_id: 'test-123',
        quantity: 1,
        attributes: {size: 'L', color: 'red'}
    })
}).then(r => r.json()).then(console.log);

// Get cart count
fetch('/api/cart.php?action=count')
    .then(r => r.json())
    .then(console.log);
                </pre>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-refresh cart count
        setInterval(() => {
            fetch('/api/cart.php?action=count')
                .then(r => r.json())
                .then(data => {
                    document.querySelectorAll('.metric-value')[0].textContent = data.count || 0;
                });
        }, 2000);
    </script>
</body>
</html>