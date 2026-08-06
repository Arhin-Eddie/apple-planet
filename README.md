# Apple Planet - PHP & MySQL E-Commerce System

Apple Planet is an independent multi-brand electronics retailer web application built as a polished, production-quality PHP/MySQL project.

## Features
- **Customer Frontend**: Responsive product catalog, detail views, shopping cart, and checkout flow.
- **Admin Dashboard**: Secure panel to manage categories, products, stock, and orders.
- **UI/UX**: Premium, minimalist design (Apple-inspired) built mobile-first with Bootstrap 5. Light & Dark mode support via CSS variables.
- **Database**: Full MySQL relational database with seed data for products and categories.
- **No External Dependencies**: Designed to run entirely offline inside XAMPP (placeholder images are loaded locally).

## Installation via XAMPP

1. **Install XAMPP** (if not already installed).
2. **Start Apache and MySQL** from the XAMPP Control Panel.
3. **Copy this project** folder (`apple-planet`) into the `htdocs` directory of your XAMPP installation.
   - Example: `C:\xampp\htdocs\apple-planet`
4. **Setup Database**:
   - Open phpMyAdmin (usually `http://localhost/phpmyadmin`).
   - You do NOT need to manually create the database; the SQL script will do it.
   - Go to the **Import** tab.
   - Select the file `database/apple_planet.sql` from this project.
   - Click **Import**. This will create the `apple_planet` database and seed it with categories, placeholder products, and the default admin account.
5. **Open the Application**:
   - Customer Storefront: `http://localhost/apple-planet`
   - Admin Panel: `http://localhost/apple-planet/admin/login.php`

## Default Admin Credentials

- **Username**: admin
- **Password**: admin123

## Demo Flow
To experience the full functionality:
1. **Customer Side**: Browse products, view details, add multiple items to the cart, adjust quantities, and proceed to checkout to place an order (creates customer and order records).
2. **Admin Side**: Log into the admin panel, view dashboard statistics, see the newly placed order, update its status from "Pending" to "Processing" or "Completed", and manage product inventory.
