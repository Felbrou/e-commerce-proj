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
        </div>
    </div>
</section>

<?php
require __DIR__ . '/../includes/footer.php';
?>