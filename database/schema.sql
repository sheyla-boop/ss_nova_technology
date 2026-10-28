CREATE DATABASE IF NOT EXISTS ss_nova_technology
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ss_nova_technology;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS order_status_history;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS addresses;
DROP TABLE IF EXISTS inventory_movements;
DROP TABLE IF EXISTS inventory;
DROP TABLE IF EXISTS product_images;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS brands;
DROP TABLE IF EXISTS suppliers;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS user_permissions;
DROP TABLE IF EXISTS role_permissions;
DROP TABLE IF EXISTS permissions;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS payment_methods;
DROP TABLE IF EXISTS order_statuses;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE role_permissions (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_role_permissions_role FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE,
    CONSTRAINT fk_role_permissions_permission FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    first_name VARCHAR(80) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    privacy_policy_version VARCHAR(20) NULL,
    privacy_accepted_at DATETIME NULL,
    phone VARCHAR(30) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles (id),
    INDEX idx_users_role_active (role_id, is_active)
) ENGINE = InnoDB;

CREATE TABLE user_permissions (
    user_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, permission_id),
    CONSTRAINT fk_user_permissions_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_user_permissions_permission FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE brands (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    contact_name VARCHAR(160) NULL,
    email VARCHAR(190) NULL UNIQUE,
    phone VARCHAR(30) NULL,
    address VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_suppliers_active (is_active)
) ENGINE = InnoDB;

CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    brand_id BIGINT UNSIGNED NULL,
    name VARCHAR(180) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    sku VARCHAR(80) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(12, 2) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_products_price CHECK (price >= 0),
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories (id),
    CONSTRAINT fk_products_brand FOREIGN KEY (brand_id) REFERENCES brands (id) ON DELETE SET NULL,
    INDEX idx_products_catalog (is_active, category_id, brand_id),
    INDEX idx_products_name (name)
) ENGINE = InnoDB;

CREATE TABLE product_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(180) NULL,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE,
    INDEX idx_product_images_order (product_id, sort_order)
) ENGINE = InnoDB;

CREATE TABLE inventory (
    product_id BIGINT UNSIGNED PRIMARY KEY,
    quantity_available INT UNSIGNED NOT NULL DEFAULT 0,
    low_stock_threshold INT UNSIGNED NOT NULL DEFAULT 5,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_inventory_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE inventory_movements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    quantity_change INT NOT NULL,
    reason VARCHAR(100) NOT NULL,
    reference_type VARCHAR(50) NULL,
    reference_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_inventory_movements_product FOREIGN KEY (product_id) REFERENCES products (id),
    CONSTRAINT fk_inventory_movements_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL,
    INDEX idx_inventory_movements_product (product_id, created_at)
) ENGINE = InnoDB;

CREATE TABLE addresses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    recipient_name VARCHAR(160) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address_line VARCHAR(255) NOT NULL,
    address_extra VARCHAR(255) NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NULL,
    postal_code VARCHAR(20) NULL,
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_addresses_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    INDEX idx_addresses_user (user_id, is_default)
) ENGINE = InnoDB;

CREATE TABLE carts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_carts_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE cart_items (
    cart_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (cart_id, product_id),
    CONSTRAINT chk_cart_items_quantity CHECK (quantity >= 1),
    CONSTRAINT fk_cart_items_cart FOREIGN KEY (cart_id) REFERENCES carts (id) ON DELETE CASCADE,
    CONSTRAINT fk_cart_items_product FOREIGN KEY (product_id) REFERENCES products (id)
) ENGINE = InnoDB;

CREATE TABLE payment_methods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE = InnoDB;

CREATE TABLE order_statuses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    is_final TINYINT(1) NOT NULL DEFAULT 0
) ENGINE = InnoDB;

CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(30) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NOT NULL,
    status_id BIGINT UNSIGNED NOT NULL,
    payment_method_id BIGINT UNSIGNED NOT NULL,
    payment_status VARCHAR(30) NOT NULL DEFAULT 'pending',
    recipient_name VARCHAR(160) NOT NULL,
    recipient_phone VARCHAR(30) NOT NULL,
    shipping_address VARCHAR(255) NOT NULL,
    shipping_address_extra VARCHAR(255) NULL,
    shipping_city VARCHAR(100) NOT NULL,
    shipping_state VARCHAR(100) NULL,
    shipping_postal_code VARCHAR(20) NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    shipping_cost DECIMAL(12, 2) NOT NULL DEFAULT 0,
    discount_amount DECIMAL(12, 2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(12, 2) NOT NULL,
    customer_notes VARCHAR(500) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_orders_amounts CHECK (subtotal >= 0 AND shipping_cost >= 0 AND discount_amount >= 0 AND total_amount >= 0),
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users (id),
    CONSTRAINT fk_orders_status FOREIGN KEY (status_id) REFERENCES order_statuses (id),
    CONSTRAINT fk_orders_payment_method FOREIGN KEY (payment_method_id) REFERENCES payment_methods (id),
    INDEX idx_orders_user_date (user_id, created_at),
    INDEX idx_orders_status_date (status_id, created_at)
) ENGINE = InnoDB;

CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NULL,
    product_name VARCHAR(180) NOT NULL,
    sku VARCHAR(80) NOT NULL,
    unit_price DECIMAL(12, 2) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    line_total DECIMAL(12, 2) NOT NULL,
    CONSTRAINT chk_order_items_values CHECK (unit_price >= 0 AND quantity >= 1 AND line_total >= 0),
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE SET NULL,
    INDEX idx_order_items_product (product_id)
) ENGINE = InnoDB;

CREATE TABLE order_status_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    status_id BIGINT UNSIGNED NOT NULL,
    changed_by BIGINT UNSIGNED NULL,
    note VARCHAR(500) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_status_history_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
    CONSTRAINT fk_order_status_history_status FOREIGN KEY (status_id) REFERENCES order_statuses (id),
    CONSTRAINT fk_order_status_history_user FOREIGN KEY (changed_by) REFERENCES users (id) ON DELETE SET NULL,
    INDEX idx_order_status_history_order (order_id, created_at)
) ENGINE = InnoDB;

INSERT INTO roles (name, description) VALUES
    ('customer', 'Cliente de la tienda'),
    ('admin', 'Administrador de la plataforma');

INSERT INTO permissions (name, description) VALUES
    ('manage_users', 'Gestionar usuarios y roles'),
    ('manage_catalog', 'Gestionar categorias, marcas y productos'),
    ('manage_inventory', 'Gestionar existencias e inventario'),
    ('manage_orders', 'Consultar y actualizar pedidos'),
    ('view_reports', 'Consultar reportes administrativos');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
CROSS JOIN permissions p
WHERE r.name = 'admin';

INSERT INTO order_statuses (code, name, sort_order, is_final) VALUES
    ('pending', 'Pendiente', 1, 0),
    ('confirmed', 'Confirmado', 2, 0),
    ('preparing', 'En preparacion', 3, 0),
    ('shipped', 'Enviado', 4, 0),
    ('delivered', 'Entregado', 5, 1),
    ('cancelled', 'Cancelado', 6, 1);

INSERT INTO payment_methods (code, name) VALUES
    ('cash_on_delivery', 'Pago contraentrega');

INSERT INTO users (role_id, first_name, last_name, email, password_hash, phone)
SELECT id, 'Administrador', 'Principal', 'admin@ssnovatechnology.local', '$2y$10$21KnrVWsY5Ov0eYhwYs/.OICd0iOeEE1b5GROQ4P52stsLAggUOOu', '0000000000'
FROM roles
WHERE name = 'admin';

INSERT INTO categories (name, slug, description) VALUES
    ('Celulares', 'celulares', 'Dispositivos moviles y telefonos inteligentes'),
    ('Computadores', 'computadores', 'Computadores portatiles y de escritorio'),
    ('Accesorios', 'accesorios', 'Accesorios y complementos tecnologicos'),
    ('Tecnologia para el hogar', 'tecnologia-para-el-hogar', 'Dispositivos inteligentes para el hogar'),
    ('Control e Infraestructura', 'control-infraestructura', 'Hubs, pantallas centrales, enchufes e interruptores inteligentes'),
    ('Climatización y Calidad del Aire', 'climatizacion-calidad-aire', 'Termostatos, purificadores, humidificadores y deshumidificadores conectados'),
    ('Limpieza y Mantenimiento', 'limpieza-mantenimiento', 'Robots aspiradores, mopas automáticas y basureros con sensor'),
    ('Seguridad y Accesos', 'seguridad-accesos', 'Cámaras IP, timbres con video, cerraduras y sensores'),
    ('Iluminación Inteligente', 'iluminacion-inteligente', 'Bombillas RGB, tiras LED, paneles y lámparas inteligentes'),
    ('Cocina y Electrodomésticos', 'cocina-electrodomesticos', 'Freidoras, cafeteras, ollas y electrodomésticos conectados'),
    ('Audio y Entretenimiento', 'audio-entretenimiento', 'Altavoces, barras de sonido, streaming y proyectores conectados');

INSERT INTO brands (name, slug) VALUES
    ('Generica', 'generica');

INSERT INTO products (category_id, name, slug, sku, description, price, is_active)
SELECT c.id, seed.name, seed.slug, seed.sku, seed.description, seed.price, 1
FROM categories c
INNER JOIN (
    SELECT 'Control e Infraestructura' AS category_name, 'Pantalla Inteligente con Asistente de Voz (Hub Central)' AS name, 'pantalla-inteligente-hub-central-asistente-voz' AS slug, 'SMH-CTRL-001' AS sku, 'Hub central para controlar dispositivos inteligentes del hogar mediante voz.' AS description, 399900.00 AS price
    UNION ALL SELECT 'Control e Infraestructura', 'Enchufe Inteligente Wi-Fi con Medidor de Consumo', 'enchufe-inteligente-wifi-medidor-consumo', 'SMH-CTRL-002', 'Enchufe conectado con medicion del consumo electrico.', 59900.00
    UNION ALL SELECT 'Control e Infraestructura', 'Interruptor Táctil Inteligente de Pared (3 Canales)', 'interruptor-tactil-inteligente-pared-3-canales', 'SMH-CTRL-003', 'Interruptor inteligente de pared con control independiente de tres canales.', 99900.00
    UNION ALL SELECT 'Control e Infraestructura', 'Control Remoto Infrarrojo Universal Wi-Fi', 'control-remoto-infrarrojo-universal-wifi', 'SMH-CTRL-004', 'Control universal Wi-Fi para equipos con mando infrarrojo.', 79900.00
    UNION ALL SELECT 'Climatización y Calidad del Aire', 'Termostato Inteligente Programable Wi-Fi', 'termostato-inteligente-programable-wifi', 'SMH-CLIMA-001', 'Termostato programable conectado para controlar la climatizacion.', 519900.00
    UNION ALL SELECT 'Climatización y Calidad del Aire', 'Purificador de Aire Inteligente con Control de App', 'purificador-aire-inteligente-control-app', 'SMH-CLIMA-002', 'Purificador de aire con control desde aplicacion movil.', 599900.00
    UNION ALL SELECT 'Climatización y Calidad del Aire', 'Humidificador Ultrasónico Wi-Fi compatible con Alexa', 'humidificador-ultrasonico-wifi-alexa', 'SMH-CLIMA-003', 'Humidificador ultrasonico conectado y compatible con asistentes de voz.', 183900.00
    UNION ALL SELECT 'Limpieza y Mantenimiento', 'Robot Aspirador y Trapeador con Mapeo Láser LiDAR', 'robot-aspirador-trapeador-mapeo-lidar', 'SMH-LIMP-001', 'Robot de limpieza con aspirado, trapeado y navegacion LiDAR.', 1199900.00
    UNION ALL SELECT 'Limpieza y Mantenimiento', 'Tacho de Basura Automático con Sensor de Movimiento', 'tacho-basura-automatico-sensor-movimiento', 'SMH-LIMP-002', 'Basurero con apertura automatica mediante sensor de movimiento.', 199900.00
    UNION ALL SELECT 'Seguridad y Accesos', 'Cámara de Seguridad IP 360° para Interior con IA', 'camara-seguridad-ip-360-interior-ia', 'SMH-SEG-001', 'Camara IP interior con vision panoramica y funciones de inteligencia artificial.', 159900.00
    UNION ALL SELECT 'Seguridad y Accesos', 'Timbre Inteligente con Videocámara HD y Visión Nocturna', 'timbre-inteligente-videocamara-hd-vision-nocturna', 'SMH-SEG-002', 'Timbre conectado con video HD y vision nocturna.', 319900.00
    UNION ALL SELECT 'Seguridad y Accesos', 'Cerradura Digital Biométrica con Huella y Teclado', 'cerradura-digital-biometrica-huella-teclado', 'SMH-SEG-003', 'Cerradura digital con apertura por huella y teclado.', 479900.00
    UNION ALL SELECT 'Seguridad y Accesos', 'Sensor de Movimiento y Apertura de Puertas Wi-Fi', 'sensor-movimiento-apertura-puertas-wifi', 'SMH-SEG-004', 'Sensor conectado para detectar movimiento y apertura de puertas.', 75900.00
    UNION ALL SELECT 'Iluminación Inteligente', 'Bombilla LED RGBW Inteligente Wi-Fi', 'bombilla-led-rgbw-inteligente-wifi', 'SMH-LUZ-001', 'Bombilla LED conectada con iluminacion RGBW regulable.', 51900.00
    UNION ALL SELECT 'Iluminación Inteligente', 'Tira LED Inteligente Neón con Sincronización de Música', 'tira-led-neon-sincronizacion-musica', 'SMH-LUZ-002', 'Tira LED inteligente con efectos sincronizados con musica.', 139900.00
    UNION ALL SELECT 'Iluminación Inteligente', 'Lámpara de Noche Inteligente con Carga Inalámbrica', 'lampara-noche-inteligente-carga-inalambrica', 'SMH-LUZ-003', 'Lampara de noche conectada con base de carga inalambrica.', 159900.00
    UNION ALL SELECT 'Cocina y Electrodomésticos', 'Freidora de Aire Inteligente con Pantalla Táctil', 'freidora-aire-inteligente-pantalla-tactil', 'SMH-COC-001', 'Freidora de aire con pantalla tactil y controles inteligentes.', 359900.00
    UNION ALL SELECT 'Cocina y Electrodomésticos', 'Cafetera Inteligente Programable vía Smartphone', 'cafetera-inteligente-programable-smartphone', 'SMH-COC-002', 'Cafetera programable y controlable desde un smartphone.', 279900.00
    UNION ALL SELECT 'Audio y Entretenimiento', 'Barra de Sonido Inteligente con Dolby Atmos', 'barra-sonido-inteligente-dolby-atmos', 'SMH-AUDIO-001', 'Barra de sonido conectada compatible con Dolby Atmos.', 719900.00
    UNION ALL SELECT 'Audio y Entretenimiento', 'Altavoz Inteligente Portátil con Bluetooth y Wi-Fi', 'altavoz-inteligente-portatil-bluetooth-wifi', 'SMH-AUDIO-002', 'Altavoz portatil inteligente con conectividad Bluetooth y Wi-Fi.', 239900.00
) seed ON seed.category_name = c.name;

INSERT INTO inventory (product_id, quantity_available)
SELECT p.id, seed.quantity_available
FROM products p
INNER JOIN (
    SELECT 'SMH-CTRL-001' AS sku, 95 AS quantity_available
    UNION ALL SELECT 'SMH-CTRL-002', 280
    UNION ALL SELECT 'SMH-CTRL-003', 140
    UNION ALL SELECT 'SMH-CTRL-004', 110
    UNION ALL SELECT 'SMH-CLIMA-001', 65
    UNION ALL SELECT 'SMH-CLIMA-002', 50
    UNION ALL SELECT 'SMH-CLIMA-003', 85
    UNION ALL SELECT 'SMH-LIMP-001', 40
    UNION ALL SELECT 'SMH-LIMP-002', 75
    UNION ALL SELECT 'SMH-SEG-001', 160
    UNION ALL SELECT 'SMH-SEG-002', 90
    UNION ALL SELECT 'SMH-SEG-003', 45
    UNION ALL SELECT 'SMH-SEG-004', 210
    UNION ALL SELECT 'SMH-LUZ-001', 350
    UNION ALL SELECT 'SMH-LUZ-002', 130
    UNION ALL SELECT 'SMH-LUZ-003', 80
    UNION ALL SELECT 'SMH-COC-001', 60
    UNION ALL SELECT 'SMH-COC-002', 35
    UNION ALL SELECT 'SMH-AUDIO-001', 55
    UNION ALL SELECT 'SMH-AUDIO-002', 120
) seed ON seed.sku = p.sku;

INSERT INTO product_images (product_id, file_path, alt_text, is_primary)
SELECT p.id, seed.file_path, p.name, 1
FROM products p
INNER JOIN (
    SELECT 'SMH-CTRL-001' AS sku, 'assets/images/products/smart-hub.jpg' AS file_path
    UNION ALL SELECT 'SMH-CTRL-002', 'assets/images/products/smart-plug.jpg'
    UNION ALL SELECT 'SMH-CTRL-003', 'assets/images/products/smart-plug.jpg'
    UNION ALL SELECT 'SMH-CTRL-004', 'assets/images/products/ir-remote.jpg'
    UNION ALL SELECT 'SMH-CLIMA-001', 'assets/images/products/smart-thermostat.jpg'
    UNION ALL SELECT 'SMH-CLIMA-002', 'assets/images/products/air-purifier.jpg'
    UNION ALL SELECT 'SMH-CLIMA-003', 'assets/images/products/smart-humidifier.jpg'
    UNION ALL SELECT 'SMH-LIMP-001', 'assets/images/products/robot-vacuum.jpg'
    UNION ALL SELECT 'SMH-LIMP-002', 'assets/images/products/auto-trash-bin.jpg'
    UNION ALL SELECT 'SMH-SEG-001', 'assets/images/products/ip-camera.jpg'
    UNION ALL SELECT 'SMH-SEG-002', 'assets/images/products/smart-plug.jpg'
    UNION ALL SELECT 'SMH-SEG-003', 'assets/images/products/smart-plug.jpg'
    UNION ALL SELECT 'SMH-SEG-004', 'assets/images/products/ip-camera.jpg'
    UNION ALL SELECT 'SMH-LUZ-001', 'assets/images/products/rgbw-bulb.jpg'
    UNION ALL SELECT 'SMH-LUZ-002', 'assets/images/products/neon-led-strip.jpg'
    UNION ALL SELECT 'SMH-LUZ-003', 'assets/images/products/charging-night-lamp.jpg'
    UNION ALL SELECT 'SMH-COC-001', 'assets/images/products/smart-air-fryer.jpg'
    UNION ALL SELECT 'SMH-COC-002', 'assets/images/products/smart-coffee-maker.jpg'
    UNION ALL SELECT 'SMH-AUDIO-001', 'assets/images/products/smart-soundbar.jpg'
    UNION ALL SELECT 'SMH-AUDIO-002', 'assets/images/products/smart-hub.jpg'
) seed ON seed.sku = p.sku;
