# LabLink Server Deployment Guide

This guide provides instructions for setting up and running the LabLink server using Laravel, Nginx (as a reverse proxy), and Supervisor for process management.

## 1. Prerequisites

- PHP 8.4+
- Composer
- Node.js & NPM
- Nginx
- Supervisor

## 2. Application Setup

Clone the repository and follow these steps:

```bash
# Install PHP dependencies
composer install

# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Install and build frontend assets
npm install
npm run build
```

## 3. Supervisor Configuration

Supervisor is used to keep the Laravel server running in the background.

Create a configuration file at `/etc/supervisor/conf.d/lablink.conf`:

```ini
[program:lablink-server]
process_name=%(program_name)s_%(process_num)02d
command=php /home/jervi/projects/lablink-server/artisan serve --host=0.0.0.0 --port=6035
autostart=true
autorestart=true
user=jervi
numprocs=1
redirect_stderr=true
stdout_logfile=/home/jervi/projects/lablink-server/storage/logs/supervisor-app.log
stopwaitsecs=3600
```

Apply the changes:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start lablink-server:*
```

## 4. Nginx Configuration

Nginx acts as a reverse proxy, forwarding traffic from `lablink.jervi.dev` to the Laravel server running on port `6035`.

Create/edit the configuration at `/etc/nginx/sites-available/lablink.jervi.dev`:

```nginx
server {
    server_name lablink.jervi.dev;

    location / {
        proxy_pass http://localhost:6035;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        
        proxy_read_timeout 300s;
        proxy_connect_timeout 300s;
        proxy_send_timeout 300s;

        proxy_request_buffering off;
        proxy_buffering off;

        proxy_buffer_size 128k;
        proxy_buffers 4 256k;
        proxy_busy_buffers_size 256k;
    }

    listen 443 ssl; # managed by Certbot
    ssl_certificate /etc/letsencrypt/live/lablink.jervi.dev/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/lablink.jervi.dev/privkey.pem; # managed by Certbot
    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot
}

server {
    if ($host = lablink.jervi.dev) {
        return 301 https://$host$request_uri;
    } # managed by Certbot

    listen 80;
    server_name lablink.jervi.dev;
    return 404; # managed by Certbot
}
```

Enable the site (if not already enabled) and restart Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/lablink.jervi.dev /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

## 5. Summary

- **Domain:** [https://lablink.jervi.dev](https://lablink.jervi.dev)
- **Local Port:** 6035
- **Process Manager:** Supervisor (`lablink-server`)
- **Web Server:** Nginx (Reverse Proxy)
