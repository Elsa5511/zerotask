ALTER TABLE equipment_taxonomy ADD template_type INT DEFAULT NULL;
ALTER TABLE ladoc_restraint_certified ADD calculation_information VARCHAR(255) DEFAULT NULL;
ALTER TABLE ladoc_restraint_certified ADD attla VARCHAR(255) DEFAULT NULL, ADD control_list VARCHAR(255) DEFAULT NULL;
ALTER TABLE ladoc_restraint_certified ADD railway_certificate VARCHAR(255) DEFAULT NULL, ADD railway_calculation VARCHAR(255) DEFAULT NULL, ADD railway_tunell_profile VARCHAR(255) DEFAULT NULL;