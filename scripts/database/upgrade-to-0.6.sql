CREATE TABLE equipment_exercise (quiz_id INT AUTO_INCREMENT NOT NULL, equipment_id INT NOT NULL, name VARCHAR(255) NOT NULL, required_for_pass DOUBLE PRECISION NOT NULL, introduction_text LONGTEXT DEFAULT NULL, INDEX IDX_9E19513A517FE9FE (equipment_id), PRIMARY KEY(quiz_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE equipment_exercise ADD CONSTRAINT FK_9E19513A517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (equipment_id);
ALTER TABLE user ADD CONSTRAINT FK_8D93D64963D7ADF1 FOREIGN KEY (superior_id) REFERENCES user (user_id);
CREATE INDEX IDX_8D93D64963D7ADF1 ON user (superior_id);
ALTER TABLE checkin_equipment_instance DROP FOREIGN KEY FK_5575C94E5B13863A;
ALTER TABLE checkin_equipment_instance ADD CONSTRAINT FK_5575C94E5B13863A FOREIGN KEY (equipment_instance_id) REFERENCES equipment_instance (equipment_instance_id) ON DELETE CASCADE;
ALTER TABLE checkout_equipment_instance DROP FOREIGN KEY FK_BF67EAF95B13863A;
ALTER TABLE checkout_equipment_instance ADD CONSTRAINT FK_BF67EAF95B13863A FOREIGN KEY (equipment_instance_id) REFERENCES equipment_instance (equipment_instance_id) ON DELETE CASCADE;
ALTER TABLE periodic_control CHANGE competent_person competent_person INT DEFAULT NULL, CHANGE equipment_instance_id equipment_instance_id INT DEFAULT NULL, CHANGE control_status control_status INT DEFAULT NULL, CHANGE organ_id organ_id INT DEFAULT NULL, CHANGE control_date control_date DATETIME DEFAULT NULL, CHANGE next_control_date next_control_date DATETIME DEFAULT NULL;
