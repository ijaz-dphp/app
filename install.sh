#!/bin/bash

# Medical Lab Reports System - Server Installation Script
# This script sets up the complete system on a new server

set -e

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Medical Lab Reports - Server Installation                   ║"
echo "║   https://belldial.softarts.co/                              ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
   echo -e "${RED}Error: This script must be run as root${NC}"
   echo "Try: sudo bash install.sh"
   exit 1
fi

echo -e "${BLUE}Starting installation...${NC}"
echo ""

# System information
OS=$(lsb_release -si 2>/dev/null || echo "Linux")
VERSION=$(lsb_release -sr 2>/dev/null || echo "Unknown")

echo "Detected OS: $OS $VERSION"
echo ""

# Update system
echo -e "${YELLOW}→ Updating system packages...${NC}"
apt-get update
apt-get upgrade -y

# Install PHP
echo -e "${YELLOW}→ Installing PHP 8.1...${NC}"
apt-get install -y php8.1 php8.1-fpm php8.1-mysql php8.1-gd php8.1-curl php8.1-xml php8.1-mbstring

# Install MySQL
echo -e "${YELLOW}→ Installing MySQL Server...${NC}"
apt-get install -y mysql-server

# Install Nginx
echo -e "${YELLOW}→ Installing Nginx...${NC}"
apt-get install -y nginx

# Install Composer
echo -e "${YELLOW}→ Installing Composer...${NC}"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js (optional, for future features)
echo -e "${YELLOW}→ Installing Node.js...${NC}"
apt-get install -y nodejs npm

# Start services
echo -e "${YELLOW}→ Starting services...${NC}"
systemctl start nginx
systemctl start mysql
systemctl start php8.1-fpm
systemctl enable nginx
systemctl enable mysql
systemctl enable php8.1-fpm

# Create database
echo ""
echo -e "${YELLOW}→ Setting up MySQL database...${NC}"
mysql -e "CREATE DATABASE IF NOT EXISTS medical_lab;"
mysql -e "CREATE USER IF NOT EXISTS 'medlab'@'localhost' IDENTIFIED BY 'MedLab@2024';"
mysql -e "GRANT ALL PRIVILEGES ON medical_lab.* TO 'medlab'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

echo -e "${GREEN}✓ Database created${NC}"

# Configure Nginx
echo ""
echo -e "${YELLOW}→ Configuring Nginx...${NC}"

cat > /etc/nginx/sites-available/default << 'EOF'
server {
    listen 80 default_server;
    listen [::]:80 default_server;

    server_name _;

    root /home/nginx/domains/belldial.softarts.co/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Test Nginx configuration
nginx -t

# Reload Nginx
systemctl reload nginx

echo -e "${GREEN}✓ Nginx configured${NC}"

# Install SSL (Let's Encrypt)
echo ""
echo -e "${YELLOW}→ Installing SSL certificate...${NC}"
apt-get install -y certbot python3-certbot-nginx

# Create application directory
echo ""
echo -e "${YELLOW}→ Setting up application directory...${NC}"
mkdir -p /home/nginx/domains/belldial.softarts.co/public
chmod 755 /home/nginx/domains/belldial.softarts.co/public

# Install application files (if not already present)
if [ ! -f "/home/nginx/domains/belldial.softarts.co/public/index.php" ]; then
    echo -e "${YELLOW}→ Copying application files...${NC}"
    # This assumes files are in current directory
    cp -r . /home/nginx/domains/belldial.softarts.co/public/
    chmod -R 755 /home/nginx/domains/belldial.softarts.co/public
    chmod -R 777 /home/nginx/domains/belldial.softarts.co/public/data
fi

# Install Composer dependencies
echo ""
echo -e "${YELLOW}→ Installing Composer dependencies...${NC}"
cd /home/nginx/domains/belldial.softarts.co/public
composer install

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║          ✓ Installation Complete!                             ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════════════╝${NC}"
echo ""

echo "Next steps:"
echo ""
echo "1. Configure your domain (SSL Certificate):"
echo "   ${YELLOW}sudo certbot --nginx -d yourdomain.com${NC}"
echo ""
echo "2. Create database tables:"
echo "   ${YELLOW}mysql -u medlab -p medical_lab < database.sql${NC}"
echo ""
echo "3. Update database configuration:"
echo "   Edit: /home/nginx/domains/belldial.softarts.co/public/config/database.php"
echo "   username: medlab"
echo "   password: MedLab@2024"
echo ""
echo "4. Access the application:"
echo "   ${YELLOW}http://127.0.0.1${NC} (or your domain)"
echo ""
echo "5. Build Android app:"
echo "   ${YELLOW}cd /home/nginx/domains/belldial.softarts.co/public/app${NC}"
echo "   ${YELLOW}bash build.sh${NC}"
echo ""
echo -e "${GREEN}System is ready!${NC}"
echo ""
