CREATE DATABASE Whoops COLLATE "utf8_general_ci";

CREATE TABLE users (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,
    api_token VARCHAR(255),
    image VARCHAR(255),
    created_at DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY(id)
);

CREATE TABLE posts (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    vote INTEGER UNSIGNED DEFAULT 0,
    user_id INTEGER UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE answers (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    body TEXT NOT NULL,
    vote INTEGER UNSIGNED DEFAULT 0,
    user_id INTEGER UNSIGNED NOT NULL,
    post_id INTEGER UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(post_id) REFERENCES posts(id)
);

INSERT INTO users (name, email, password) 
VALUES ('user1', 'test1@user.com', '123'),
('user2', 'test2@user.com', '1234'),
('user3', 'test3@user.com', '12345'),
('user4', 'test4@user.com', '123456');

INSERT INTO posts (title, user_id, body) 
VALUES 
('test post one', 1, 'How are you?'),
('test post two', 1, 'What are you do?'),
('test post three', 2, 'When you will pay?'),
('test post four', 2, 'what are you doing?'),
('test post five', 3, 'how old are you?'),
('test post six', 3, 'How do you do?'),
('test post seven', 4, 'Are you stuped?'),
('test post eight', 4, 'Are you fool?');

INSERT INTO answers (body , user_id, post_id) 
VALUES 
('test answer one',2,1),
('test answer two',4,2);

