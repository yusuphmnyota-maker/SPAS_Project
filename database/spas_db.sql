CREATE DATABASE spas_db;

USE spas_db;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100),
    username VARCHAR(50),
    password VARCHAR(255)
);

INSERT INTO users(fullname, username, password)
VALUES('Administrator', 'admin', 'admin123');

CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    gender VARCHAR(10),
    class VARCHAR(20)
);

CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100)
);

CREATE TABLE marks (
    mark_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    marks INT,

    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
);

CREATE TABLE reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    average_score DECIMAL(5,2),
    grade VARCHAR(5),
    position INT,

    FOREIGN KEY (student_id) REFERENCES students(student_id)
);
