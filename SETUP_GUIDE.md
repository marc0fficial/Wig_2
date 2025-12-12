# Wig Elegance - Quick Setup Guide

## Prerequisites Checklist
- [ ] PHP 7.4+ installed
- [ ] MySQL 5.7+ installed
- [ ] Apache/Nginx web server running
- [ ] Composer installed (for Stripe SDK)

## Step-by-Step Setup

### 1. Database Setup (5 minutes)

**Option A: Using phpMyAdmin**
1. Open phpMyAdmin (usually at http://localhost/phpmyadmin)
2. Click "New" to create a database
3. Name it `wig_shop`
4. Click on the database
5. Go to "Import" tab
6. Choose the `database.sql` file
7. Click "Go"

**Option B: Using Command Line**
```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE wig_shop;
exit;

# Import schema
mysql -u root -p wig_shop < database.sql
```

### 2. Configure Database Connection (2 minutes)

Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');           // Change if different
define('DB_PASS', '');               // Add your MySQL password
define('DB_NAME', 'wig_shop');
```

### 3. Install Stripe PHP SDK (3 minutes)

```bash
cd c:/Users/HP/Desktop/web developper files/Wig
composer require stripe/stripe-php
```

If you don't have Composer:
1. Download from: https://getcomposer.org/download/
2. Install it
3. Run the command above

### 4. Configure Stripe (5 minutes)

1. Create a Stripe account at https://stripe.com
2. Go to https://dashboard.stripe.com/test/apikeys
3. Copy your test keys
4. Edit `config/config.php`:

```php
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_YOUR_KEY_HERE');
define('STRIPE_SECRET_KEY', 'sk_test_YOUR_KEY_HERE');
```

### 5. Set Site URL (1 minute)

Edit `config/config.php`:
```php
define('SITE_URL', 'http://localhost/Wig');
```

Change if your setup is different.

### 6. Create Product Images Directory (1 minute)

```bash
mkdir assets/images/products
chmod 755 assets/images/products
```

Or create the folder manually in Windows Explorer.

### 7. Test the Installation (2 minutes)

1. Open browser and go to: `http://localhost/Wig`
2. You should see the homepage with sample products
3. Try adding a product to cart
4. Go to admin panel: `http://localhost/Wig/admin/login.php`
   - Username: `admin`
   - Password: `admin123`

## Verification Checklist

- [ ] Homepage loads without errors
- [ ] Products are displayed
- [ ] Can add products to cart
- [ ] Cart page works
- [ ] Admin login works
- [ ] Admin dashboard shows statistics
- [ ] Can view products in admin panel
- [ ] Can view orders in admin panel

## Common Issues & Solutions

### Issue: "Database connection failed"
**Solution**: Check database credentials in `config/database.php`

### Issue: "Stripe not found"
**Solution**: Run `composer require stripe/stripe-php`

### Issue: "Cannot modify header information"
**Solution**: Make sure there's no output before PHP tags in config files

### Issue: Images not showing
**Solution**: 
1. Check `assets/images/products/` folder exists
2. Check file permissions (755)
3. Verify image paths in database

### Issue: Email not sending
**Solution**: 
- PHP mail() function may not work on localhost
- For production, configure SMTP in `includes/functions.php`
- For testing, check spam folder

## Testing Payment Methods

### Test Airtel Money
1. Go through checkout
2. Select Airtel Money
3. Use any transaction ID format: `MP240123.1234.A12345`
4. System will accept it (real verification would need Airtel API)

### Test Stripe Card Payment
Use Stripe test cards:
- **Success**: 4242 4242 4242 4242
- **Decline**: 4000 0000 0000 0002
- **Requires Auth**: 4000 0025 0000 3155
- Any future expiry date (e.g., 12/25)
- Any 3-digit CVC (e.g., 123)

## Security Recommendations

### Before Going Live:

1. **Change Admin Password**
   ```sql
   UPDATE admin_users 
   SET password = '$2y$10$YOUR_NEW_HASHED_PASSWORD' 
   WHERE username = 'admin';
   ```
   Use PHP to generate hash:
   ```php
   echo password_hash('your_new_password', PASSWORD_DEFAULT);
   ```

2. **Enable HTTPS**
   - Get SSL certificate
   - Update SITE_URL to use https://

3. **Secure Database**
   - Use strong MySQL password
   - Create dedicated database user (not root)
   - Grant only necessary permissions

4. **Update Stripe Keys**
   - Switch from test keys to live keys
   - Keep keys secure, never commit to git

5. **Configure Email**
   - Set up proper SMTP for production
   - Use real email service (SendGrid, Mailgun, etc.)

6. **File Permissions**
   ```bash
   chmod 644 *.php
   chmod 755 assets/images/products
   ```

## Production Deployment

### Hosting Requirements
- PHP 7.4+
- MySQL 5.7+
- SSL Certificate
- At least 512MB RAM
- 1GB disk space

### Recommended Hosts
- Hostinger
- Bluehost
- SiteGround
- DigitalOcean (for VPS)

### Deployment Steps
1. Upload all files via FTP/SFTP
2. Import database
3. Update config files with production values
4. Install Composer dependencies
5. Set proper file permissions
6. Test all functionality
7. Enable SSL
8. Update Stripe to live mode

## Support

If you encounter issues:
1. Check error logs: `error_log` in root directory
2. Enable PHP error reporting temporarily
3. Contact: marc0urage10@gmail.com

## Next Steps

After setup:
1. Add real product images to `assets/images/products/`
2. Update product information in admin panel
3. Test complete purchase flow
4. Customize colors/branding if needed
5. Add more products
6. Test email notifications
7. Set up backup system

## Backup Recommendations

### Database Backup
```bash
mysqldump -u root -p wig_shop > backup_$(date +%Y%m%d).sql
```

### File Backup
- Backup entire Wig folder regularly
- Use version control (Git) for code
- Keep backups offsite

## Performance Tips

1. Enable PHP OPcache
2. Use CDN for static assets
3. Optimize images before upload
4. Enable gzip compression
5. Use caching for product listings
6. Optimize database queries

---

**Setup Time**: ~20 minutes  
**Difficulty**: Beginner-Intermediate  
**Last Updated**: 2024
