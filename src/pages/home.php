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
             <div class="vv-padding vv-center vv-row-padding-">
                <div class="item1"></div>
                <div class="item2"></div>
                <div class="item3"></div>
                <div class="item4"></div>
                <div class="item5"></div>
                <div class="item6"></div>
             </div>
        </div>
    </div>
</section>

<?php
require __DIR__ . '/../includes/footer.php';
?>