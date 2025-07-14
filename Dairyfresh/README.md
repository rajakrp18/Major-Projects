# 🥛 Dairy Fresh – Online Milk & Dairy Product Delivery System

Welcome to **Dairy Fresh**, a simple PHP & MySQL-based web application that allows users to view dairy products, add them to cart, and place orders with a delivery and payment option. It includes user authentication, admin/supplier panels, and order management features.

---

## 🌟 Features

- 🛒 Product Listing with Images, Weights, Prices
- ➕ Add to Cart with Quantity Management
- ✅ Checkout & Payment Form (Card, UPI, COD)
- 👤 Login & Register with Role-Based Access (`admin`, `supplier`, `customer`)
- 🗂️ Admin Panel (User Management)
- 🚚 Supplier Panel (Order Status Management)
- 📩 Contact Form
- 🛠️ Session-Based Cart & Order Persistence

---

## 📂 Project Structure

Dairyfreshup/
├── index.php # Main landing page (products, cart, checkout)
├── script.js # Cart logic, delivery & payment JS
├── styles.css # Styling for all pages
├── login.php # Login page
├── logout.php # Logout script
├── register.php # Registration page
├── checkout.php # Handles order logic and updates DB
├── admin.php # Admin panel (register users)
├── supplier.php # Supplier dashboard for managing orders
├── update_delivery.php # Update delivery status for suppliers
├── contact.php # Contact form backend
├── /images # Folder for all product and banner images
├── /sql # Contains dairy_db.sql to create tables and sample data

yaml
Copy
Edit

---

## 🛠️ Setup Instructions

### ✅ Prerequisites

- PHP >= 7.x
- MySQL Server (via XAMPP, WAMP, or LAMP)
- Git (optional)
- Browser (Chrome/Firefox)

---

### 🚀 Local Installation (XAMPP Example)

1. Clone this repository or download ZIP:
   ```bash
   git clone https://github.com/your-username/dairyfresh.git
Move folder to:

makefile
Copy
Edit
C:\xampp\htdocs\Dairyfreshup
Start XAMPP and enable Apache & MySQL.

Open phpMyAdmin:

Create a new database: dairy_db

Import the provided dairy_db.sql from /sql/

Visit in browser:

bash
Copy
Edit
http://localhost/Dairyfreshup/index.php
🔐 Default Users
Use these sample credentials (or register new ones):

Role	Email	Password
Admin	rajpoddar8907@gmail.com	654321@
Supplier	supplier@dairy.com	123456
Customer	customer@milk.com	123456

📦 Database Tables
users: stores registered user details and role

items: product details (name, price, quantity, image, weight, description)

orders: records each order placed

contacts: feedback submissions

🔧 Notes
Cart data is stored using localStorage for quick frontend interaction

Stock quantity updates after successful checkout

Delivery slot confirmation and payment are simulated via form interactions

All user sessions are tracked using PHP $_SESSION

SQL Injection protections via prepare() used in sensitive queries

🧑‍💻 Contributors
Raj Poddar (MCA Graduate) – Project Lead & Developer

Open for enhancements and suggestions via GitHub Issues