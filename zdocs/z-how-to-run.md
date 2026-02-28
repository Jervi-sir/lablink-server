php artisan serve --host=0.0.0.0 --port=6030
php artisan storage:link
php artisan queue:work

### pm2
sudo npm install -g pm2
pm2 start --name course-api "php artisan serve --host 0.0.0.0 --port 6030"
pm2 start --name course-worker "php artisan queue:work"

### change in php
sudo nano /etc/php/8.4/fpm/php.ini
sudo nano /etc/php/8.4/cli/php.ini

upload_max_filesize = 5G
post_max_size = 5G
max_execution_time = 3600
max_input_time = 3600
memory_limit = 512M

sudo systemctl restart php8.4-fpm
sudo systemctl restart php8.4-cli


### env
sudo apt update && sudo apt install ffmpeg -y

