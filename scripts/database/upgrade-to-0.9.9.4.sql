ALTER TABLE equipment_attachment ADD link VARCHAR(1024) DEFAULT NULL;
ALTER TABLE equipment_instance_attachment ADD link VARCHAR(1024) DEFAULT NULL;
ALTER TABLE periodic_control_attachment ADD link VARCHAR(1024) DEFAULT NULL;
ALTER TABLE training_section_attachment ADD link VARCHAR(1024) DEFAULT NULL;
ALTER TABLE documentation_section_attachment ADD link VARCHAR(1024) DEFAULT NULL;
ALTER TABLE inline_section_attachment ADD link VARCHAR(1024) DEFAULT NULL;
ALTER TABLE page_inline_section_attachment ADD link VARCHAR(1024) DEFAULT NULL;
ALTER TABLE page_section_attachment ADD link VARCHAR(1024) DEFAULT NULL;