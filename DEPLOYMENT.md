# AWS Deployment Guide for POS-SYSTEM-G5

## Prerequisites

1. **AWS Account** with EC2, RDS, and S3 services
2. **MobarXterm** installed on your local machine
3. **Key Pair** (.pem file) for EC2 SSH access

---

## Step 1: Set Up MySQL Database on AWS RDS

1. Go to AWS Console → RDS → Create Database
2. Choose **MySQL** as the engine
3. Configure:
   - DB Instance Identifier: `vc1-pos-db`
   - Master Username: `admin` (or your choice)
   - Master Password: `YourSecurePassword123!`
   - DB Instance Class: `db.t3.micro` (Free tier)
   - Storage: 20 GB
4. In **Additional Configuration**:
   - Initial Database Name: `vc1_pos_system`
5. Click **Create Database** (wait 5-10 minutes)
6. Once created, note the **Endpoint** (e.g., `vc1-pos-db.xxxx.us-east-1.rds.amazonaws.com`)

---

## Step 2: Set Up EC2 Instance

1. Go to AWS Console → EC2 → Launch Instance
2. Choose **Ubuntu Server 22.04 LTS** (Free tier eligible)
3. Instance Type: `t2.micro` (Free tier)
4. Configure Security Group:
   - **SSH** (Port 22) - Your IP
   - **HTTP** (Port 80) - Anywhere
   - **HTTPS** (Port 443) - Anywhere
   - **MySQL/Aurora** (Port 3306) - Your IP only
5. Launch and download the `.pem` key pair

---

## Step 3: Connect with MobarXterm

1. Open MobarXterm
2. Click **Session** → **SSH**
3. Configure:
   - Remote host: `YOUR_EC2_PUBLIC_IP`
   - Username: `ubuntu`
   - Specify path to your `.pem` file
4. Click **OK** to connect

---

## Step 4: Install LAMP Stack on EC2

After connecting via MobarXterm, run these commands:

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache, PHP, and MySQL client
sudo apt install -y apache2 php php-mysql php-curl php-gd php-mbstring php-xml php-xmlrpc libapache2-mod-php

# Start Apache
sudo systemctl start apache2
sudo systemctl enable apache2

# Check PHP version
php -v
```

---

## Step 5: Upload Your Application

### Option A: Using MobarXterm SFTP

1. In MobarXterm, click **SFTP** tab
2. Drag and drop your project files to `/var/www/html/`

### Option B: Using Git

```bash
cd /var/www/html
sudo git clone https://github.com/your-repo/POS-SYSTEM-G5.git
sudo mv POS-SYSTEM-G5/* .
sudo mv POS-SYSTEM-G5/.* . 2>/dev/null
sudo rm -rf POS-SYSTEM-G5
```

---

## Step 6: Configure Application

1. Create the `.env` file:

```bash
cd /var/www/html
sudo nano .env
```

Add these contents:

```env
# Database Configuration (Use your RDS endpoint)
DB_HOST=vc1-pos-db.xxxx.us-east-1.rds.amazonaws.com
DB_USER=admin
DB_PASS=YourSecurePassword123!
DB_NAME=vc1_pos_system
DB_PORT=3306

# Application Configuration
APP_ENV=production
APP_DEBUG=false
APP_URL=http://YOUR_EC2_PUBLIC_IP
```

2. Set proper permissions:

```bash
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 775 /var/www/html/uploads
```

---

## Step 7: Import Database

1. Connect to RDS from your local machine or EC2:

```bash
# Install MySQL client on EC2 (if not already)
sudo apt install -y mysql-client

# Connect to RDS
mysql -h vc1-pos-db.xxxx.us-east-1.rds.amazonaws.com -u admin -p vc1_pos_system

# Or import from dump file
mysql -h vc1-pos-db.xxxx.us-east-1.rds.amazonaws.com -u admin -p vc1_pos_system < database_dump.sql
```

---

## Step 8: Configure Apache Virtual Host

```bash
sudo nano /etc/apache2/sites-available/pos.conf
```

Add:

```apache
<VirtualHost *:80>
    ServerName YOUR_EC2_PUBLIC_IP
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/pos-error.log
    CustomLog ${APACHE_LOG_DIR}/pos-access.log combined
</VirtualHost>
```

Enable the site:

```bash
sudo a2ensite pos.conf
sudo a2enmod rewrite
sudo systemctl reload apache2
```

---

## Step 9: Test Your Application

1. Open browser and navigate to: `http://YOUR_EC2_PUBLIC_IP`

---

## Important Notes

### Security
- Always use HTTPS in production (configure SSL with Let's Encrypt)
- Never commit `.env` file to GitHub
- Use IAM roles for AWS access
- Configure proper Security Groups

### File Uploads
- The `uploads/` directory needs write permission
- Consider using AWS S3 for file storage in production

### Troubleshooting
```bash
# Check Apache error logs
sudo tail -f /var/log/apache2/pos-error.log

# Check PHP errors
sudo tail -f /var/log/apache2/error.log

# Restart Apache
sudo systemctl restart apache2

# Check PHP configuration
phpinfo();
```

---

## Database Connection Test

If you get database errors, test the connection:

```bash
# Test MySQL connection from EC2 to RDS
mysql -h vc1-pos-db.xxxx.us-east-1.rds.amazonaws.com -u admin -p -e "SHOW DATABASES;"
```

---

## Quick Commands Reference

| Command | Description |
|---------|-------------|
| `sudo systemctl restart apache2` | Restart Apache |
| `sudo systemctl status apache2` | Check Apache status |
| `php -v` | Check PHP version |
| `mysql -h RDS_ENDPOINT -u USER -p` | Connect to RDS |
