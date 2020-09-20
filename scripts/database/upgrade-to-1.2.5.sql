CREATE TABLE calculator_info (calculator_info_id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, link VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, application VARCHAR(255) NOT NULL, PRIMARY KEY(calculator_info_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE page ADD search_enabled TINYINT(1) NOT NULL;
