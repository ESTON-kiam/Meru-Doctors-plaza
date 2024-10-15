Create DATABASE meru doctors plaza;
 
 /*appointments*/
CREATE TABLE appointment(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    national_id VARCHAR(50) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    appointment_date DATETIME NOT NULL,
    department VARCHAR(100) NOT NULL,
    doctor VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    comment TEXT,
    is_read TINYINT(1) DEFAULT 0
);

/*business_hours*/
Create table business_hours(
 id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    open_hours VARCHAR(100) NOT NULL
);

/*contact */
CREATE table contact(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_sent DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0
);
/*members*/
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    national_id VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    profile_picture VARCHAR(255)
);
/*Order*/
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    message TEXT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0
);
/*subsciber*/
CREATE TABLE subsciber (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    is_read TINYINT(1) DEFAULT 0
);
