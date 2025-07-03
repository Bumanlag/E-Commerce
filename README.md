OCEANIC SHOPPING WEBSITE - FILE OVERVIEW
========================================

This is a database-driven eCommerce website for selling computer peripherals.
It includes user login, cart management, product browsing, checkout, and order receipt.

---------------------------------------------------
PROJECT STRUCTURE AND FILE DESCRIPTIONS
---------------------------------------------------

Main Pages:
-----------
index.php              - Homepage; shows welcome section, featured products, and links.
shop.php               - Displays all available products; supports categories and featured items.
product.php            - Dynamically shows a product’s full details using its ID.
cart.php               - Displays the user's cart using database-stored cart items.
checkout.php           - Lets the user review and confirm their order.
thankyou.php           - Displays a confirmation page and online receipt after purchase.

Authentication & Users:
------------------------
login.php              - User login form and authentication logic.
registration.php       - User signup form with data validation.
welcome.php            - Optional welcome screen after successful login.

Cart & Order Logic:
-------------------
add_to_cart.php        - Adds a product to the logged-in user's cart.
update_cart.php        - Updates quantities of items in the cart.
remove_from_cart.php   - Removes a specific item from the cart.
checkout_process.php   - Saves the confirmed order to the database.

Informational:
--------------
aboutus.php            - Describes the company’s background, mission, and team.

Database Connection:
--------------------
database.php           - PDO-based connection to the MySQL database.

Static & Media:
---------------
images/                - Folder for product images and team profile photos.

User Data (Text-Based):
-----------------------
users.txt              - Stores registered user information (only if using text-file fallback).

SQL Setup:
----------
database.sql           - SQL dump file. Contains all necessary tables:
                         * users
                         * products
                         * cart
                         * orders
                         * order_items

---------------------------------------------------
NOTES
---------------------------------------------------

- All pages requiring user sessions use session_start().
- Cart and orders are tied to the user’s ID stored in session.
- Product images must match their filenames inside /images/.
- Make sure to import `database.sql` into phpMyAdmin or your MySQL server.
- Update database.php with your own DB host, name, username, and password.

This project is best hosted using Apache + PHP + MySQL (e.g., XAMPP or LAMP stack).
