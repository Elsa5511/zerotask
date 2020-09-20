CREATE TABLE feature (id INT AUTO_INCREMENT NOT NULL, `key` VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE equipment_feature_override_to_feature (equipment_id INT NOT NULL, feature_id INT NOT NULL, INDEX IDX_419F8751517FE9FE (equipment_id), INDEX IDX_419F875160E4B879 (feature_id), PRIMARY KEY(equipment_id, feature_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE equipment_feature_override_to_feature ADD CONSTRAINT FK_419F8751517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (equipment_id);
ALTER TABLE equipment_feature_override_to_feature ADD CONSTRAINT FK_419F875160E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id);
ALTER TABLE organization CHANGE address address VARCHAR(255) DEFAULT NULL;
ALTER TABLE equipment_instance ADD min_visual_control_date DATETIME DEFAULT NULL, ADD visual_control_status VARCHAR(64) DEFAULT NULL;
ALTER TABLE ladoc_restraint_certified ADD illustration_image VARCHAR(255) DEFAULT NULL;
INSERT INTO feature (`key`)
VALUES
  ('ladoc-documentation'),
  ('training'),
  ('exercise'),
  ('exam');