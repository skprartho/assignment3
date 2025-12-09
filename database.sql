CREATE DATABASE IF NOT EXISTS car_workshop;
USE car_workshop;

CREATE TABLE IF NOT EXISTS mechanics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);


INSERT INTO mechanics (name) VALUES 
('Mike Ross'),
('Harvey Specter'),
('Louis Litt'),
('Rachel Zane'),
('Donna Paulsen');

CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_address VARCHAR(255) NOT NULL,
    client_phone VARCHAR(20) NOT NULL,
    car_license VARCHAR(50) NOT NULL,
    car_engine VARCHAR(50) NOT NULL,
    appointment_date DATE NOT NULL,
    mechanic_id INT NOT NULL,
    FOREIGN KEY (mechanic_id) REFERENCES mechanics(id)
);