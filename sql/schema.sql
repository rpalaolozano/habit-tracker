CREATE DATABASE IF NOT EXISTS habit_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE habit_tracker;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE habits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE habit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    habit_id INT NOT NULL,
    date DATE NOT NULL,
    completed BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (habit_id) REFERENCES habits(id) ON DELETE CASCADE,
    UNIQUE KEY unique_log (habit_id, date)
);

CREATE VIEW user_scores AS
SELECT
    u.id AS user_id,
    u.name,
    COUNT(hl.id) * 10 AS total_points
FROM users u
LEFT JOIN habits h ON h.user_id = u.id
LEFT JOIN habit_logs hl ON hl.habit_id = h.id
GROUP BY u.id
ORDER BY total_points DESC;
