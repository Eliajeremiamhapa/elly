CREATE DATABASE IF NOT EXISTS must_voting_final_v2;
USE must_voting_final_v2;

CREATE TABLE students (
  reg_number VARCHAR(20) PRIMARY KEY,
  password VARCHAR(50) NOT NULL
);

INSERT INTO students (reg_number, password) VALUES
('2310053335001', '123'),
('2310053335002', '123'),
('2310053335003', '123');

CREATE TABLE leaders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  description TEXT,
  image_url TEXT
);

CREATE TABLE votes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reg_number VARCHAR(20),
  leader_id INT,
  UNIQUE(reg_number, leader_id),
  FOREIGN KEY (leader_id) REFERENCES leaders(id)
);
