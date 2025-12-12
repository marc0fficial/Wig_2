# Wig Elegance - E-Commerce Web Application

A full-featured e-commerce web application for selling wigs, built with PHP, MySQL, JavaScript, and Tailwind CSS.

## Features

### Customer Features
- 🛍️ Browse products with category filtering
- 🔍 Search functionality
- 🛒 Shopping cart management
- 💳 Multiple payment methods (Airtel Money & Visa/Card via Stripe)
- 📧 Email notifications for order confirmations
- 📱 Fully responsive design
- 🎨 Modern UI with purple/white/orange color scheme

### Admin Features
- 📊 Dashboard with statistics
- 📦 Product management (Add, Edit, Delete)
- 📋 Order management
- 🔔 Low stock alerts
- 👤 Secure admin authentication

### Payment Integration
- **Airtel Money**: Manual transaction ID verification
- **Stripe**: Secure card payments (Visa, Mastercard)
- Email notifications sent to owner (marc0urage10@gmail.com) for every payment

## Technology Stack

- **Frontend**: HTML5, Tailwind CSS, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Payment**: Stripe API
- **Icons**: Font Awesome 6.4.0

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for Stripe PHP SDK)

### Step 1: Clone/Download the Project
```bash
cd /path/to/your/webserver/root
# The project is already in: c:/Users/HP/Desktop/web developper files/Wig
```

### Step 2: Database Setup
1. Create a MySQL database:
```sql
CREATE DATABASE wig_shop;
```

2. Import the database schema:
```bash
mysql -u root -p wig_shop < database.sql
```

Or use phpMyAdmin to import `database.sql`

### Step 3: Configure Database Connection
Edit `config/database.php` and update the database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password
define('DB_NAME', 'wig_shop');
```

### Step 4: Install Stripe PHP SDK
```bash
cd /path/to/Wig
composer require stripe/stripe-php
```

### Step 5: Configure Stripe API Keys
Edit `config/config.php` and add your Stripe API keys:
```php
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_your_key_here');
define('STRIPE_SECRET_KEY', 'sk_test_your_key_here');
```

Get your Stripe keys from: https://dashboard.stripe.com/test/apikeys

### Step 6: Set Up File Permissions
```bash
chmod 755 assets/images/products/
```

### Step 7: Configure Site URL
Edit `config/config.php` and update the site URL:
```php
define('SITE_URL', 'http://localhost/Wig');
```

## Default Admin Credentials

- **Username**: admin
- **Password**: admin123
- **Admin Panel**: http://localhost/Wig/admin/login.php

**⚠️ Important**: Change the default password after first login!

## Project Structure

```
Wig/
├── admin/                      # Admin panel
│   ├── includes/              # Admin includes
│   ├── index.php              # Admin dashboard
│   ├── login.php              # Admin login
│   ├── logout.php             # Admin logout
│   ├── products.php           # Product management
│   └── orders.php             # Order management
├── api/                       # API endpoints
│   ├── cart.php               # Cart operations
│   ├── orders.php             # Order creation
│   └── payment.php            # Payment processing
├── assets/                    # Static assets
│   ├── css/
│   │   └── custom.css         # Custom styles
│   ├── js/
│   │   └── main.js            # Main JavaScript
│   └── images/
│       └── products/          # Product images
├── config/                    # Configuration files
│   ├── config.php             # App configuration
│   └── database.php           # Database connection
├── includes/                  # Shared includes
│   ├── header.php             # Site header
│   ├── footer.php             # Site footer
│   ├── navbar.php             # Navigation bar
│   └── functions.php          # Helper functions
├── index.php                  # Homepage
├── products.php               # Product listing
├── product-detail.php         # Product details
├── cart.php                   # Shopping cart
├── checkout.php               # Checkout page
├── payment.php                # Payment page
├── order-confirmation.php     # Order success page
├── database.sql               # Database schema
└── README.md                  # This file
```

## Usage

### For Customers

1. **Browse Products**: Visit the homepage and browse featured products
2. **Search**: Use the search bar to find specific wigs
3. **Add to Cart**: Click "Add to Cart" on any product
4. **Checkout**: Review cart and proceed to checkout
5. **Payment**: Choose payment method (Airtel Money or Card)
6. **Confirmation**: Receive order confirmation via email

### For Admin

1. **Login**: Access admin panel at `/admin/login.php`
2. **Dashboard**: View statistics and recent orders
3. **Manage Products**: Add, edit, or delete products
4. **Manage Orders**: View and update order status
5. **Monitor Stock**: Check low stock alerts

## Payment Methods

### Airtel Money
1. Customer selects Airtel Money at checkout
2. Instructions displayed with merchant number: 0798974781
3. Customer completes payment via USSD (*182*7*1#)
4. Customer enters transaction ID for verification
5. Order confirmed and notifications sent

### Card Payment (Stripe)
1. Customer selects Card payment at checkout
2. Secure Stripe payment form displayed
3. Customer enters card details
4. Payment processed via Stripe API
5. Order confirmed and notifications sent

## Email Notifications

All payment confirmations are automatically sent to:
- **Email**: marc0urage10@gmail.com
- **Phone**: 0798 974 781

Notifications include:
- Order details
- Customer information
- Payment method and status
- Order items and total amount

## Sample Products

The database includes 6 sample products:
1. Brazilian Straight Wig - $150.00
2. Curly Lace Front Wig - $180.00
3. Bob Cut Wig - $120.00
4. Long Wavy Wig - $200.00
5. Pixie Cut Wig - $100.00
6. Afro Kinky Wig - $160.00

## Customization

### Colors
Edit `tailwind.config` in header files to change colors:
```javascript
colors: {
    primary: '#9333ea',    // Purple
    secondary: '#f97316',  // Orange
}
```

### Site Name
Edit `config/config.php`:
```php
define('SITE_NAME', 'Your Site Name');
```

### Contact Information
Edit `config/config.php`:
```php
define('ADMIN_EMAIL', 'your@email.com');
define('ADMIN_PHONE', 'your-phone');
```

## Security Notes

1. **Change default admin password** immediately
2. **Use strong passwords** for database and admin accounts
3. **Enable HTTPS** in production
4. **Keep Stripe keys secure** - never commit to version control
5. **Validate all user inputs** on server-side
6. **Regular backups** of database

## Troubleshooting

### Database Connection Error
- Check database credentials in `config/database.php`
- Ensure MySQL service is running
- Verify database exists

### Stripe Payment Not Working
- Check API keys in `config/config.php`
- Ensure Stripe PHP SDK is installed
- Check error logs for details

### Images Not Displaying
- Check file permissions on `assets/images/products/`
- Verify image paths in database
- Ensure images exist in the directory

### Email Not Sending
- Check PHP mail configuration
- Consider using SMTP for production
- Verify email addresses are correct

## Support

For issues or questions:
- **Email**: marc0urage10@gmail.com
- **Phone**: 0798 974 781

## License

This project is proprietary software. All rights reserved.

## Credits

- **Developer**: BLACKBOXAI
- **Client**: Marc Courage
- **Design**: Tailwind CSS
- **Icons**: Font Awesome
- **Payment**: Stripe

---

**Version**: 1.0.0  
**Last Updated**: 2024
