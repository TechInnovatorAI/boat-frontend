-- Create database for boat website
CREATE DATABASE IF NOT EXISTS boat_website;

-- Create a user for Laravel application
CREATE USER IF NOT EXISTS 'laravel_user'@'localhost' IDENTIFIED BY 'laravel_password';

-- Grant all privileges on the database to the user
GRANT ALL PRIVILEGES ON boat_website.* TO 'laravel_user'@'localhost';

-- Flush privileges to ensure changes take effect
FLUSH PRIVILEGES;

-- Show databases to confirm
SHOW DATABASES;
