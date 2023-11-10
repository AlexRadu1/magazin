# Fullstack E-commerce app

This application is intended to help a small e-commerce business with their own stocks managing and commercializing

## Description

THis is an e-commerce platform built using PHP and MySQL, designed for local deployment with XAMPP . This project aims to provide a simple and customizable solution for managing an online store.

## Features

- **Product Management:** Add, edit, and delete products easily through the admin panel.
- **User Authentication:** Secure user authentication system for customer accounts.
- **Shopping Cart:** Users can add products to their cart and proceed to checkout.
- **Order Management:** Admins can view and manage customer orders.
- **Responsive design:** The UI flows to accommodate the user's screen.
- **Database:** Utilizes MySQL for efficient and structured data storage.
- **Local Deployment:** Built to run locally using XAMPP.

## Prerequisites

Before you begin, ensure you have the following installed:

- [XAMPP](https://www.apachefriends.org/index.html) for local development.
- Basic understanding of PHP and MySQL.

## Installation

1. Set up your XAMPP environment.
2. Clone the repository:

   ```bash
   git clone https://github.com/AlexRadu1/magazin.git
   ```

3. Import the provided SQL file into your MySQL database using phpMyAdmin
4. Configure your database connection in includes/connect.php:

   ```php
   define('DB_HOST', 'your_database_host');
   define('DB_USER', 'your_database_user');
   define('DB_PASS', 'your_database_password');
   define('DB_NAME', 'your_database_name');
   ```

## Usage

1. Start your XAMPP server.

2. Access the project through your web browser (e.g., http://localhost/magazin).

3. Log in to the admin panel using the default admin credentials:  
   Username: admin  
   Password: admin1234
4. Customize your store, add products, and manage orders
