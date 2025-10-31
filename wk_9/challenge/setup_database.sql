
CREATE TABLE IF NOT EXISTS users (
    user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    registration_date DATETIME NOT NULL,
    last_login DATETIME,
    PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS user_sessions (
    session_id VARCHAR(255) NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    token VARCHAR(255) NOT NULL,
    expiry DATETIME NOT NULL,
    PRIMARY KEY (session_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);