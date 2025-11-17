DROP DATABASE IF EXISTS student_passwords;
CREATE DATABASE student_passwords;
USE student_passwords;

/* Create a new user for this database.*/
DROP USER IF EXISTS 'passwords_user'@'localhost';
CREATE USER 'passwords_user'@'localhost' IDENTIFIED BY 'f(D2Whiue9d8yD';
GRANT ALL PRIVILEGES ON student_passwords.*  TO 'passwords_user'@'localhost';

SET block_encryption_mode = 'aes-256-cbc';
SET @key_str = "ThisIsMyKeyStringASHUFIHEWIUFGIUWEFBWIEUFBIWEF";
SET @init_vector = "ThisIsMyInitVector124381y34y2358y9382145y21";

-- users table stores the user information, and uses its id as its primary key
CREATE TABLE IF NOT EXISTS users (
  userId      SMALLINT(5)     NOT NULL AUTO_INCREMENT,
  username    VARCHAR(128)    NOT NULL,
  fname       VARCHAR(128)    NOT NULL,
  lname       VARCHAR(128)    NOT NULL,

  PRIMARY KEY (userId)
);

-- websites table stores website name and URL, using its id as its primary key
CREATE TABLE IF NOT EXISTS websites (
  webId       SMALLINT(5)     NOT NULL AUTO_INCREMENT,
  webName     VARCHAR(128)    NOT NULL,
  webUrl      VARCHAR(255)    UNIQUE NOT NULL, -- URL should be unique to avoid duplicates

  PRIMARY KEY (webId)
);

-- accounts_at table is to have relation between the users and websites, and add the encrypted password and comment
CREATE TABLE IF NOT EXISTS accounts_at (
  userId      SMALLINT(5)     NOT NULL,
  webId       SMALLINT(5)     NOT NULL,
  password    VARBINARY(512)  NOT NULL,
  email       VARCHAR(128)    NOT NULL, -- I moved the email down into the accounts_at table so the users were more specific to the people and users themself, not their accounts

  comment     VARCHAR(512),
  timeStamp DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (userId, webId) -- Same user cant have the same user information for the same website
);

-- Insert initial data that will make up 10 entries
INSERT INTO users (username, fname, lname)
VALUES
  ("userone", "John", "Yousir"),
  ("usertwo", "Michael", "Smith"),
  ("userthree", "Ally", "Jones"),
  ("userfour", "Mary", "Smith"),
  ("userfive", "John", "Michaels"),
  ("usersix", "Natalie", "Night");

-- Websites to use for initial data
INSERT INTO websites (webName, webUrl)
VALUES
  ("Youtube", "https://youtube.com/"),
  ("Club Penguin", "https://clubpenguin.com/"),
  ("Facebook", "https://facebook.com/"),
  ("X", "https://x.com/"),
  ("Reddit", "https://reddit.com/"),
  ("Instagram", "https://instagram.com/"),
  ("LinkedIn", "https://linkedin.com/");

-- The initial 10 entries into the database
INSERT INTO accounts_at (userId, webId, password, email, comment)
VALUES
(1, 1, AES_ENCRYPT("pass1234", @key_str, @init_vector), "johnsir@user.org", "don't forget this password!!"),
(1, 3, AES_ENCRYPT("johnuserspass1234", @key_str, @init_vector), "johnsir@user.org", "My facebook one"),
(2, 2, AES_ENCRYPT("word5678", @key_str, @init_vector), "mike@user.org", NULL),
(2, 6, AES_ENCRYPT("mikeinsta!23", @key_str, @init_vector), "mike@user.org", "I upload pics here!"),
(3, 3, AES_ENCRYPT("RonDog1111", @key_str, @init_vector), "ajones@gmail.com", "Name of my pet!"),
(3, 7, AES_ENCRYPT("allyallyli222", @key_str, @init_vector), "ajones@gmail.com", NULL),
(4, 2, AES_ENCRYPT("maryrocks!", @key_str, @init_vector), "marymary@yahoo.org", "I miss club penguin.."),
(4, 3, AES_ENCRYPT("maryfb!123",  @key_str, @init_vector), "marymary@yahoo.org", NULL),
(5, 4, AES_ENCRYPT("johnybgoode#$%", @key_str, @init_vector), "jman2@gmail.com", NULL),
(6, 5, AES_ENCRYPT("natalie2003", @key_str, @init_vector), "nn123@user.org", ".. Maybe I update this one.");
