<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'E-commerce Store' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="main-nav">
            <div class="container">
                <a href="/" class="logo">Your Store</a>
                <ul class="nav-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/products">Products</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/cart" class="cart-link">Cart (<span id="cart-count">0</span>)</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main class="site-content">