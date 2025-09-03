<?php
$pageTitle = 'Welcome to My Website';
require __DIR__ . '/../includes/header.php';
?>

<div class="hero-section">
    <div class="container">
        <h1>Welcome to Our E-commerce Store</h1>
        <p>Discover amazing products at unbeatable prices!</p>
        <a href="/products" class="btn btn-primary">Shop Now</a>
    </div>
</div>

<section class="featured products">
    <div class="container">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <!-- Example product item -->
             <div class="vv-padding vv-center vv-row-padding vv-display-block">
                <div class="item">
                    <img src=<?php __DIR__ . '/../public/css/images/product1.jpg';?> alt="product1" style="width:100%">
                    <h3>
                        Product 1
                    </h3>
                    <p>
                        Description for product 1.
                    </p>
            </div>
                <div class="item">
                    <img src=<?php __DIR__ . '/../public/css/images/product2.jpg';?> alt="product2" style="width:100%">
                    <h3>
                        Product 2
                    </h3>
                    <p>
                        Description for product 2.
                    </p>
                </div>
                <div class="item">
                    <img src=<?php __DIR__ . '/../public/css/images/product3.jpg';?> alt="product3" style="width:100%">
                    <h3>
                        Product 3
                    </h3>
                    <p>
                        Description for product 3.
                    </p>
                </div>
                <div class="item">
                    <img src=<?php __DIR__ . '/../public/css/images/product4.jpg';?> alt="product4" style="width:100%">
                    <h3>
                        Product 4
                    </h3>
                    <p>
                        Description for product 4.
                    </p>
                </div>
                    </h3>
                    <p></p>
                </div>

                <div class="item">
                    <img src=<?php __DIR__ . '/../public/css/images/product5.jpg';?> alt="product5" style="width:100%">
                    <h3>
                        Product 5
                    </h3>
                    <p>
                        Description for product 5.
                    </p>
                </div>

                <div class="item">
                    <img src=<?php __DIR__ . '/../public/css/images/product6.jpg';?> alt="product6" style="width:100%">
                    <h3>
                        Product 6
                    </h3>
                    <p>
                        Description for product 6.
                    </p>
                </div>
             </div>
        </div>
    </div>
</section>

<?php
require __DIR__ . '/../includes/footer.php';
?>