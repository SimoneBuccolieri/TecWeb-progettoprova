-- Importare nel database dedicato assegnato, senza modificare altri database.
CREATE TABLE IF NOT EXISTS users (
 id INTEGER PRIMARY KEY AUTO_INCREMENT,
 username VARCHAR(30) NOT NULL UNIQUE,
 email VARCHAR(254) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role VARCHAR(10) NOT NULL DEFAULT 'user' CHECK(role IN ('user','admin'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS hikes (
 id INTEGER PRIMARY KEY AUTO_INCREMENT,
 title VARCHAR(100) NOT NULL,
 location VARCHAR(100) NOT NULL,
 difficulty VARCHAR(10) NOT NULL CHECK(difficulty IN ('facile','media','difficile')),
 distance DECIMAL(5,1) NOT NULL CHECK(distance > 0),
 duration INTEGER NOT NULL CHECK(duration > 0),
 elevation INTEGER NOT NULL CHECK(elevation >= 0),
 description TEXT NOT NULL,
 image VARCHAR(30) NOT NULL,
 updated_at VARCHAR(19) NOT NULL,
 INDEX hikes_difficulty (difficulty)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS favorites (
 user_id INTEGER NOT NULL,
 hike_id INTEGER NOT NULL,
 PRIMARY KEY(user_id,hike_id),
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY(hike_id) REFERENCES hikes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS reviews (
 id INTEGER PRIMARY KEY AUTO_INCREMENT,
 user_id INTEGER NOT NULL,
 hike_id INTEGER NOT NULL,
 rating INTEGER NOT NULL CHECK(rating BETWEEN 1 AND 5),
 body TEXT NOT NULL,
 updated_at VARCHAR(19) NOT NULL,
 UNIQUE(user_id,hike_id),
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY(hike_id) REFERENCES hikes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS login_attempts (
 attempt_key VARCHAR(64) PRIMARY KEY,
 attempts INTEGER NOT NULL,
 last_attempt INTEGER NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
