-- First using the right database
USE ecommerce;

-- 1. Categories table
-- This is our simplest table - just a list of department names.
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Products table
-- This is our main table - like the master inventory list.
CREATE TABLE IF NOT EXISTS products (
    id VARCHAR(100) PRIMARY KEY,  -- Using VARCHAR because your IDs are strings like "ps-5"
    name VARCHAR(255) NOT NULL,
    description TEXT,  -- TEXT allows for long HTML descriptions
    brand VARCHAR(100),
    price DECIMAL(10, 2) NOT NULL,  -- Fixed typo: DECIMAL not DECIMA
    currency_symbol VARCHAR(10) DEFAULT '$',
    currency_label VARCHAR(10) DEFAULT 'USD',
    in_stock BOOLEAN DEFAULT TRUE,
    category_name VARCHAR(50),  -- Links to categories.name
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Index for faster lookups
    INDEX idx_category (category_name),
    INDEX idx_in_stock (in_stock),
    
    -- Foreign key ensures category exists
    FOREIGN KEY (category_name) REFERENCES categories(name) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Product Gallery table
-- each product can have multoiple images - like a photo album.
CREATE TABLE IF NOT EXISTS product_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(100) NOT NULL,  -- Links to products.id
    image_url TEXT NOT NULL,  -- URL of the product image
    position INT DEFAULT 0,  -- Position in the gallery

    INDEX idx_produxt_position (product_id, position),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Attributes Table
-- This defines what kind of variations exist (Size, Color, etc.)
CREATE TABLE IF NOT EXISTS attributes (
    id VARCHAR(100) PRIMARY KEY,  -- Your JSON uses string IDs like "Size"
    name VARCHAR(100) NOT NULL,
    type ENUM('text', 'swatch') NOT NULL DEFAULT 'text'  -- Fixed typo: proper ENUM syntax
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Attribute Items Table
-- The actual options for each attribute (Small, Medium, Large, etc.)
CREATE TABLE IF NOT EXISTS attribute_items (
    id VARCHAR(100) PRIMARY KEY,  -- IDs like "Small", "Green"
    attribute_id VARCHAR(100) NOT NULL,
    display_value VARCHAR(100) NOT NULL,  -- What users see: "Small"
    value VARCHAR(100) NOT NULL,  -- Actual value: "S" or "#44FF03"
    
    INDEX idx_attribute (attribute_id),
    FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Product Attributes Junction Table
-- Links products to their available attributes
CREATE TABLE IF NOT EXISTS product_attributes (
    product_id VARCHAR(100) NOT NULL,
    attribute_id VARCHAR(100) NOT NULL,
    
    PRIMARY KEY (product_id, attribute_id),  -- Composite primary key
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE  -- Fixed typo: FOREIGN not FOREING
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Orders Table (for future use)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_amount DECIMAL(10, 2) NOT NULL,
    currency_symbol VARCHAR(10) DEFAULT '$',
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    selected_attributes JSON,  -- Stores selected options as JSON
    
    INDEX idx_order (order_id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;