<?php
// opentutor/config/database.php
// InfinityFree/MySQL settings
// Use values exactly from your cPanel MySQL database page.

$host = getenv('DB_HOST') ?: 'sql211.infinityfree.com';
$db   = getenv('DB_NAME') ?: 'if0_43117205_ot_1';
$user = getenv('DB_USER') ?: 'if0_43117205';
$pass = getenv('DB_PASS') ?: 'WRWwqdNMfZOG';
$charset = 'utf8mb4';

// If your database name is different in cPanel, replace the value above.
// Example: $db = 'if0_43117205_opentutor';
// Example: $user = 'if0_43117205';
// Example: $pass = 'your_mysql_password';

try {
    // Shared hosting like InfinityFree often does not allow CREATE DATABASE.
    // The database should already exist in cPanel before this app runs.
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        phone VARCHAR(20) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('STUDENT','TUTOR','ADMIN') NOT NULL,
        status ENUM('PENDING','ACTIVE','SUSPENDED') DEFAULT 'ACTIVE',
        profile_image VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS student_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        guardian_name VARCHAR(100) DEFAULT NULL,
        location VARCHAR(255) DEFAULT NULL,
        bio TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS tutor_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        education VARCHAR(255) DEFAULT NULL,
        university VARCHAR(255) DEFAULT NULL,
        department VARCHAR(255) DEFAULT NULL,
        experience VARCHAR(100) DEFAULT NULL,
        bio TEXT,
        expected_salary_min DECIMAL(10,2) DEFAULT NULL,
        expected_salary_max DECIMAL(10,2) DEFAULT NULL,
        location VARCHAR(255) DEFAULT NULL,
        gender ENUM('MALE','FEMALE','OTHER') DEFAULT NULL,
        online_available BOOLEAN DEFAULT FALSE,
        offline_available BOOLEAN DEFAULT FALSE,
        verification_status ENUM('UNVERIFIED','PENDING','VERIFIED','REJECTED') DEFAULT 'UNVERIFIED',
        id_card_copy VARCHAR(255) DEFAULT NULL,
        rating_avg DECIMAL(3,2) DEFAULT 0.00,
        review_count INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS tuition_posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        class_level VARCHAR(100) DEFAULT NULL,
        subjects VARCHAR(255) DEFAULT NULL,
        location VARCHAR(255) DEFAULT NULL,
        salary DECIMAL(10,2) DEFAULT NULL,
        days_per_week INT DEFAULT NULL,
        preferred_time VARCHAR(100) DEFAULT NULL,
        gender_preference ENUM('MALE','FEMALE','ANY') DEFAULT 'ANY',
        learning_mode ENUM('ONLINE','OFFLINE','BOTH') DEFAULT 'OFFLINE',
        description TEXT,
        status ENUM('OPEN','CLOSED','COMPLETED','CANCELLED') DEFAULT 'OPEN',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tuition_id INT NOT NULL,
        tutor_id INT NOT NULL,
        expected_salary DECIMAL(10,2) DEFAULT NULL,
        message TEXT,
        experience_details TEXT,
        status ENUM('PENDING','ACCEPTED','REJECTED','WITHDRAWN','COMPLETED') DEFAULT 'PENDING',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_application (tuition_id, tutor_id),
        FOREIGN KEY (tuition_id) REFERENCES tuition_posts(id) ON DELETE CASCADE,
        FOREIGN KEY (tutor_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS shortlists (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        target_type ENUM('TUTOR','TUITION') NOT NULL,
        target_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tuition_id INT NOT NULL,
        reviewer_id INT NOT NULL,
        reviewed_user_id INT NOT NULL,
        rating TINYINT DEFAULT NULL,
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (tuition_id) REFERENCES tuition_posts(id) ON DELETE CASCADE,
        FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (reviewed_user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        reporter_id INT NOT NULL,
        reported_user_id INT NOT NULL,
        tuition_id INT DEFAULT NULL,
        reason VARCHAR(255) NOT NULL,
        description TEXT,
        status ENUM('PENDING','RESOLVED','DISMISSED') DEFAULT 'PENDING',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (reported_user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        type VARCHAR(50) DEFAULT 'GENERAL',
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;");

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute(['admin@opentutor.com']);
    if (!$stmt->fetch()) {
        $hashed = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->prepare('INSERT INTO users (name, email, phone, password, role, status) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute(['Admin User', 'admin@opentutor.com', '01700000000', $hashed, 'ADMIN', 'ACTIVE']);
    }
} catch (\PDOException $e) {
    die('Database connection failed on InfinityFree. Please update the MySQL credentials in config/database.php with the exact values from cPanel. ' . $e->getMessage());
}
?>
