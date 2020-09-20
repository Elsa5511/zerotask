CREATE TABLE load_weight_and_dimensions_attachment (point_attachment_id INT AUTO_INCREMENT NOT NULL, load_weight_and_dimensions_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, file VARCHAR(255) NOT NULL, INDEX IDX_5D33E51DA9D02E4C (load_weight_and_dimensions_id), PRIMARY KEY(point_attachment_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE load_weight_and_dimensions_attachment ADD CONSTRAINT FK_5D33E51DA9D02E4C FOREIGN KEY (load_weight_and_dimensions_id) REFERENCES load_weight_and_dimensions (id);
ALTER TABLE carrier_lashing_point ADD rupture_strength VARCHAR(255) DEFAULT NULL;
ALTER TABLE load_lashing_point CHANGE rupture_strength rupture_strength VARCHAR(255) DEFAULT NULL;
