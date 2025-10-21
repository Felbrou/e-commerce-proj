<?php

namespace App\Classes;

class Cart 
{
    /**Initialize the cart session if not exists */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**Add a product to cart
    * @param strig $productId
    * @param int $quantity
    * @param array $attributes Selected attributes like size, color, etc.
    * @return array response with status and message
    */


    public static function addItem(string $productId, int $quantity = 1, array $attributes = []): array
{
    self::init();

    //Create unique cart key combining product ID and attributes
    $cartKey = self::generateCartKey($productId, $attributes);

    //Check if item already exists in cart
    if (isset($_SESSION['cart'][$cartKey])) {
        //Update quantity
        $_SESSION['cart'][$cartKey]['quantity'] += $quantity;

        return [
            'success' => true,
            'message' => 'Product quantity updated',
            'cartCount'=> self::getTotalItems()
        ];
    }

    //Add new item to cart
    $_SESSION['cart'][$cartKey] = [
        'product_id' => $productId,
        'quantity' => $quantity,
        'attributes' => $attributes,
        'added_at' => time()
    ];

    return [
        'success' => true,
        'message' => 'Product added to cart',
        'cartCount'=> self::getTotalItems()
    ];
}

/**Remove item from cart
 * @param string $cartKey
 */

public static function removeItem(string $cartKey): array
{
    self::init();

    if (isset($_SESSION['cart'][$cartKey])) {
        unset($_SESSION['cart'][$cartKey]);

        return [
            'success' => true,
            'message' => 'Product removed from cart',
            'cartCount'=> self::getTotalItems()
        ];
    }

    return [
        'success' => false,
        'message' => 'Product not found in cart'
    ];
}
    /**Update item quantity */

    public static function updateQuantity(string $cartKey, int $quantity): array
    {
        self::init();

        if ($quantity <= 0) {
            return self::removeItem($cartKey);
        }

        if (isset($_SESSION['cart'][$cartKey])) {
            $_SESSION['cart'][$cartKey]['quantity'] = $quantity;

            return [
                'success' => true,
                'message' => 'Product quantity updated',
                'cartCount'=> self::getTotalItems()
            ];
        }

        return [
            'success' => false,
            'message' => 'Product not found in cart'
        ];
}

/**Get all cart items with product details */
    public static function getItems(): array
    {
        self::init();

        $items = [];
        $pdo = getDbConnection();

        foreach ($_SESSION['cart'] as $cartKey => $cartItem) {
            //Fetch product details from database
            $stmt = $pdo->prepare("
                SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_name = c.name
                WHERE p.id = ?
                ");
            $stmt->execute([$cartItem['product_id']]);
            $product = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($product) {
                $items[] = [
                    'cart_key' => $cartKey,
                    'product' => $product,
                    'quantity' => $cartItem['quantity'],
                    'attributes' => $cartItem['attributes'],
                    'subtotal' => $product['price'] * $cartItem['quantity']
                ];
            }
        }
        return $items;
    }

    /**Get total items in cart */

    public static function getTotalItems(): int 
    {
        self::init();

        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }

    /**get cart total price */
    public static function getTotalPrice(): float
    {
        $items = self::getItems();
        $total = 0;

        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        return $total;
    }

    /** Clear entire cart*/
    public static function clear(): array
    {
        self::init();
        $_SESSION['cart'] = [];

        return [
            'success' => true,
            'message' => 'Cart cleared'
        ];
    }


    /**Generate a unique cart key */
    public static function generateCartKey(string $productId, array $attributes): string
    {

        //Sort attributes to ensure consistent key generation
        ksort($attributes);

        //Create a unique indentifier based on Product ID and attributes
        $key = $productId;

        if (!empty($attributes)) {
            $key .= '_' . md5(json_encode($attributes));
        }

        return $key;
    }

    /**Check if product is in stock */
    public static function checkStock(string $productId, int $requestedQuantity = 1): bool
    {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(\PDO::FETCH_ASSOC);

        //For now, just check boolean in_stock
        //Later i can just add actual stock quantity check
        return $product && $product['in_stock'];
    }

}


