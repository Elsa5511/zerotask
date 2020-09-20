CREATE TABLE control_point_to_template (id INT AUTO_INCREMENT NOT NULL, control_point_id INT DEFAULT NULL, control_template_id INT DEFAULT NULL, `order` INT DEFAULT NULL, INDEX IDX_626A98C41FE83EE2 (control_point_id), INDEX IDX_626A98C4FA98A1AA (control_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE ladoc_restraint_certified (id INT AUTO_INCREMENT NOT NULL, load_documentation_id INT DEFAULT NULL, carrier_documentation_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, other_loads VARCHAR(255) DEFAULT NULL, created_by VARCHAR(255) NOT NULL, approved_by VARCHAR(255) NOT NULL, approved_date DATETIME DEFAULT NULL, prerequisites LONGTEXT DEFAULT NULL, INDEX IDX_FBDF742B19D4FA1 (load_documentation_id), INDEX IDX_FBDF7425FD94EA2 (carrier_documentation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE ladoc_restraint_certified_to_form_of_transportation (ladoc_restraint_certified_id INT NOT NULL, form_of_transportation_id INT NOT NULL, INDEX IDX_351E5317FB7224DA (ladoc_restraint_certified_id), INDEX IDX_351E5317E306A8AA (form_of_transportation_id), PRIMARY KEY(ladoc_restraint_certified_id, form_of_transportation_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE ladoc_restraint_certified_attachment (point_attachment_id INT AUTO_INCREMENT NOT NULL, ladoc_restraint_certified_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, file VARCHAR(255) NOT NULL, INDEX IDX_6AB2B6BFB7224DA (ladoc_restraint_certified_id), PRIMARY KEY(point_attachment_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE ladoc_restraint_non_certified (id INT AUTO_INCREMENT NOT NULL, load_documentation_id INT DEFAULT NULL, carrier_documentation_id INT DEFAULT NULL, INDEX IDX_9A30F3BAB19D4FA1 (load_documentation_id), INDEX IDX_9A30F3BA5FD94EA2 (carrier_documentation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE control_point_to_template ADD CONSTRAINT FK_626A98C41FE83EE2 FOREIGN KEY (control_point_id) REFERENCES control_point (control_point_id);
ALTER TABLE control_point_to_template ADD CONSTRAINT FK_626A98C4FA98A1AA FOREIGN KEY (control_template_id) REFERENCES control_template (control_template_id);
ALTER TABLE ladoc_restraint_certified ADD CONSTRAINT FK_FBDF742B19D4FA1 FOREIGN KEY (load_documentation_id) REFERENCES ladoc_documentation (id);
ALTER TABLE ladoc_restraint_certified ADD CONSTRAINT FK_FBDF7425FD94EA2 FOREIGN KEY (carrier_documentation_id) REFERENCES ladoc_documentation (id);
ALTER TABLE ladoc_restraint_certified_to_form_of_transportation ADD CONSTRAINT FK_351E5317FB7224DA FOREIGN KEY (ladoc_restraint_certified_id) REFERENCES ladoc_restraint_certified (id);
ALTER TABLE ladoc_restraint_certified_to_form_of_transportation ADD CONSTRAINT FK_351E5317E306A8AA FOREIGN KEY (form_of_transportation_id) REFERENCES form_of_transportation (id);
ALTER TABLE ladoc_restraint_certified_attachment ADD CONSTRAINT FK_6AB2B6BFB7224DA FOREIGN KEY (ladoc_restraint_certified_id) REFERENCES ladoc_restraint_certified (id);
ALTER TABLE ladoc_restraint_non_certified ADD CONSTRAINT FK_9A30F3BAB19D4FA1 FOREIGN KEY (load_documentation_id) REFERENCES ladoc_documentation (id);
ALTER TABLE ladoc_restraint_non_certified ADD CONSTRAINT FK_9A30F3BA5FD94EA2 FOREIGN KEY (carrier_documentation_id) REFERENCES ladoc_documentation (id);
ALTER TABLE control_point_result ADD control_point_to_template_id INT DEFAULT NULL;
ALTER TABLE control_point_result ADD CONSTRAINT FK_8989058BE418739A FOREIGN KEY (control_point_to_template_id) REFERENCES control_point_to_template (id);
CREATE INDEX IDX_8989058BE418739A ON control_point_result (control_point_to_template_id);
ALTER TABLE equipment ADD control_organ_organization_id INT DEFAULT NULL, ADD instance_type VARCHAR(255) NOT NULL, ADD equipment_type VARCHAR(255) NOT NULL, ADD wll NUMERIC(10, 2) DEFAULT NULL, ADD length NUMERIC(10, 2) DEFAULT NULL, ADD material_quality VARCHAR(255) DEFAULT NULL, ADD standard VARCHAR(255) DEFAULT NULL, ADD type_approval VARCHAR(255) DEFAULT NULL;
ALTER TABLE equipment ADD CONSTRAINT FK_D338D58325C5470A FOREIGN KEY (control_organ_organization_id) REFERENCES organization (organization_id);
CREATE INDEX IDX_D338D58325C5470A ON equipment (control_organ_organization_id);
ALTER TABLE equipment_instance ADD production_date DATETIME DEFAULT NULL, ADD instance_type VARCHAR(255) NOT NULL, ADD is_isolated TINYINT(1) DEFAULT NULL, ADD has_dryair TINYINT(1) DEFAULT NULL, ADD has_volt_220 TINYINT(1) DEFAULT NULL, ADD has_volt_400 TINYINT(1) DEFAULT NULL, ADD has_communication_racks TINYINT(1) DEFAULT NULL, ADD has_other_decor TINYINT(1) DEFAULT NULL;
ALTER TABLE periodic_control ADD createdTime DATETIME NOT NULL;
ALTER TABLE carrier_basic_information ADD responsible_office_id INT DEFAULT NULL;
ALTER TABLE carrier_basic_information ADD CONSTRAINT FK_A965522C31BF8D04 FOREIGN KEY (responsible_office_id) REFERENCES responsible_office (id);
CREATE INDEX IDX_A965522C31BF8D04 ON carrier_basic_information (responsible_office_id);
ALTER TABLE load_basic_information ADD responsible_office_id INT DEFAULT NULL, ADD equivalent_models VARCHAR(255) DEFAULT NULL;
ALTER TABLE load_basic_information ADD CONSTRAINT FK_3082479731BF8D04 FOREIGN KEY (responsible_office_id) REFERENCES responsible_office (id);
CREATE INDEX IDX_3082479731BF8D04 ON load_basic_information (responsible_office_id);
UPDATE equipment_instance
SET instance_type = 'standard';

UPDATE equipment_instance ei, equipment e
SET ei.instance_type = 'container'
WHERE ei.equipment_id = e.equipment_id
AND e.instance_type = 'container';

UPDATE equipment
SET equipment_type = 'standard';

UPDATE equipment
SET equipment_type = 'vedos-mechanical'
WHERE application = 'vedos-mechanical';

drop table if exists load_restraint_certified_to_form_of_transportation;
drop table if exists load_restraint_certified_attachment;
drop table if exists load_restraint_certified;
drop table if exists load_restraint_non_certified;
