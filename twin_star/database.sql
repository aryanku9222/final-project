-- Disable foreign key checks (allows dropping tables even with relationships)
SET FOREIGN_KEY_CHECKS = 0;

-- Drop all tables if they exist (order no longer matters)
DROP TABLE IF EXISTS order_details;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS payment;
DROP TABLE IF EXISTS menu;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS admin;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Create customer table
CREATE TABLE customer (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(15) NOT NULL,
    address TEXT,
    password VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create menu table
CREATE TABLE menu (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    food_name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    availability_status ENUM('Available', 'Not Available') DEFAULT 'Available'
);

-- Create orders table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status ENUM('Pending','Preparing','Dispatched','Delivered','Cancelled') DEFAULT 'Pending',
    delivery_status VARCHAR(50) DEFAULT 'Order Placed',
    tracking_id VARCHAR(20) UNIQUE,
    FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
);

-- Create order_details table
CREATE TABLE order_details (
    order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
);

-- Create payment table
CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    method VARCHAR(50) DEFAULT 'Cash on Delivery',
    status VARCHAR(20) DEFAULT 'Pending',
    amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

-- Create admin table
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    password VARCHAR(255) NOT NULL
);

-- Insert admin user
INSERT IGNORE INTO admin (admin_name, email, phone, password) VALUES
('Admin', 'admin@twinstar.com', '9999999999', MD5('admin123'));

-- Insert 30 food items
INSERT IGNORE INTO menu (food_name, category, price, description) VALUES
-- Indian (8 items)
('Butter Chicken', 'Indian', 350, 'Tender chicken in creamy tomato gravy'),
('Chicken Biryani', 'Indian', 320, 'Fragrant basmati rice with spiced chicken'),
('Paneer Tikka', 'Indian', 280, 'Grilled cottage cheese with Indian spices'),
('Dal Makhani', 'Indian', 250, 'Creamy black lentils slow-cooked'),
('Garlic Naan', 'Indian', 50, 'Soft bread with garlic and butter'),
('Rogan Josh', 'Indian', 390, 'Kashmiri lamb curry'),
('Masala Dosa', 'Indian', 120, 'Crispy rice crepe with potato filling'),
('Chole Bhature', 'Indian', 150, 'Spicy chickpeas with fried bread'),

-- Chinese (6 items)
('Kung Pao Chicken', 'Chinese', 320, 'Spicy stir-fry with peanuts'),
('Hakka Noodles', 'Chinese', 220, 'Stir-fried noodles with veggies'),
('Chilli Paneer', 'Chinese', 280, 'Indo-Chinese spicy cottage cheese'),
('Fried Rice', 'Chinese', 180, 'Wok-tossed rice with egg & veggies'),
('Spring Rolls', 'Chinese', 150, 'Crispy vegetable rolls'),
('Manchurian (Gravy)', 'Chinese', 260, 'Vegetable balls in spicy sauce'),

-- Italian (6 items)
('Margherita Pizza', 'Italian', 399, 'Fresh mozzarella, tomato sauce, basil'),
('Pepperoni Pizza', 'Italian', 499, 'Pepperoni, mozzarella, tomato sauce'),
('Spaghetti Carbonara', 'Italian', 350, 'Pasta with eggs, cheese, pancetta'),
('Lasagna', 'Italian', 420, 'Layered pasta with meat sauce and cheese'),
('Risotto', 'Italian', 380, 'Creamy arborio rice with mushrooms'),
('Tiramisu', 'Italian', 200, 'Classic coffee-flavored dessert'),

-- Western (6 items)
('Classic Burger', 'Western', 299, 'Beef patty with lettuce, tomato, onion'),
('Cheeseburger', 'Western', 329, 'Beef patty with cheddar cheese'),
('Grilled Steak', 'Western', 599, 'Juicy beef steak with mashed potatoes'),
('Fish & Chips', 'Western', 399, 'Crispy battered fish with fries'),
('Club Sandwich', 'Western', 249, 'Triple-decker with chicken & bacon'),
('French Fries', 'Western', 120, 'Crispy golden fries with sea salt'),

-- Desserts (4 items)
('Chocolate Lava Cake', 'Dessert', 180, 'Warm chocolate cake with molten center'),
('Cheesecake', 'Dessert', 190, 'New York style with berry sauce'),
('Gulab Jamun', 'Dessert', 100, 'Soft milk dumplings in sugar syrup'),
('Ice Cream Sundae', 'Dessert', 150, 'Vanilla ice cream with chocolate sauce');
INSERT IGNORE INTO menu (food_name, category, price, description) VALUES
('Fish & Chips', 'Western', 399, 'Crispy battered fish with fries'),
('French Fries', 'Western', 120, 'Crispy golden fries with sea salt'),
('Grilled Steak', 'Western', 599, 'Juicy beef steak with mashed potatoes');