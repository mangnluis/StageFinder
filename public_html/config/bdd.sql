CREATE DATABASE IF NOT EXISTS stagefinder DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stagefinder;

CREATE TABLE cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    country_code VARCHAR(2) NOT NULL

);
CREATE TABLE industries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);


-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('admin', 'pilot', 'student') NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city_id INT,
    postal_code VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL,
    UNIQUE KEY unique_email (email)
);

-- Table des étudiants
CREATE TABLE students (
    user_id INT PRIMARY KEY,
    promotion VARCHAR(100),
    cv_filename VARCHAR(255),
    bio TEXT,
    skills TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des entreprises
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    contact_email VARCHAR(100),
    contact_phone VARCHAR(20),
    address TEXT,
    city_id INT,
    industry_id INT,
    website VARCHAR(255),
    logo_filename VARCHAR(255),
    additional_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL,
    FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE SET NULL
);

-- Table des offres de stage
CREATE TABLE internships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    responsibilities TEXT,
    requirements TEXT,
    compensation DECIMAL(10, 2),
    start_date DATE,
    end_date DATE,
    location_id INT,
    remote_option BOOLEAN DEFAULT 0,
    is_published BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (location_id) REFERENCES cities(id) ON DELETE SET NULL

);

-- Table des compétences
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    category VARCHAR(50)
);

-- Table de relation entre offres et compétences
CREATE TABLE internship_skills (
    internship_id INT NOT NULL,
    skill_id INT NOT NULL,
    PRIMARY KEY (internship_id, skill_id),
    FOREIGN KEY (internship_id) REFERENCES internships(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);

-- Table des candidatures
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    internship_id INT NOT NULL,
    motivation_letter TEXT,
    cv_filename VARCHAR(255),
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
    FOREIGN KEY (internship_id) REFERENCES internships(id) ON DELETE CASCADE
);

-- Table des wishlists
CREATE TABLE wishlist (
    student_id INT NOT NULL,
    internship_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id, internship_id),
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
    FOREIGN KEY (internship_id) REFERENCES internships(id) ON DELETE CASCADE
);

-- Table des évaluations d'entreprises
CREATE TABLE company_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    student_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    rated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_rating (company_id, student_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE
);

-- Table des notifications
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255),
    is_read BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table pour les journaux d'activité
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);



CREATE TABLE internship_industries (
    internship_id INT NOT NULL,
    industry_id INT NOT NULL,
    PRIMARY KEY (internship_id, industry_id),
    FOREIGN KEY (internship_id) REFERENCES internships(id) ON DELETE CASCADE,
    FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE CASCADE
);




-- Table des messages privés
CREATE TABLE private_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT 0,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);



-- Ajouter un utilisateur admin par défaut (mot de passe: admin123)
INSERT INTO users (email, password, first_name, last_name, role) 
VALUES ('admin@admin.fr', '$2y$10$BnvS7yCEYPFKgH4tVnM9a.T3LcfHbM9VOBmZJ2yp3ZpZwdxco2KHK', 'Admin', 'System', 'admin');

-- Insérer quelques compétences
INSERT INTO skills (name, category) VALUES 
('PHP', 'Programmation'),
('JavaScript', 'Programmation'),
('HTML/CSS', 'Web'),
('SQL', 'Base de données'),
('Python', 'Programmation'),
('Java', 'Programmation'),
('C#', 'Programmation'),
('C++', 'Programmation'),
('React', 'Framework'),
('Angular', 'Framework'),
('Vue.js', 'Framework'),
('Node.js', 'Programmation'),
('Git', 'DevOps'),
('Docker', 'DevOps'),
('AWS', 'Cloud'),
('UI/UX Design', 'Design'),
('Photoshop', 'Design'),
('SEO', 'Marketing'),
('Gestion de projet', 'Management'),
('Marketing digital', 'Marketing');

-- Ajouter quelques secteurs d'activité
INSERT INTO industries (name) VALUES 
('Informatique & Technologies'),
('Finance & Banque'),
('Santé'),
('Commerce & Distribution'),
('Industrie & Production'),
('Services aux entreprises'),
('Communication & Marketing'),
('Construction & BTP'),
('Transport & Logistique'),
('Autre');


-- Ajouter quelques villes
INSERT INTO cities (name, country_code) VALUES 
('Paris', 'FR'),
('Lyon', 'FR'),
('Marseille', 'FR'),
('Toulouse', 'FR'),
('Nice', 'FR'),
('Bordeaux', 'FR'),
('Lille', 'FR'),
('Strasbourg', 'FR'),
('Nantes', 'FR'),
('Montpellier', 'FR');