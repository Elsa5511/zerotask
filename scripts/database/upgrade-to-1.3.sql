ALTER TABLE equipment_instance ADD vendor_id INT DEFAULT NULL, ADD manufacturer_id INT DEFAULT NULL;
ALTER TABLE equipment_instance ADD CONSTRAINT FK_72F335F8F603EE73 FOREIGN KEY (vendor_id) REFERENCES organization (organization_id);
ALTER TABLE equipment_instance ADD CONSTRAINT FK_72F335F8A23B42D FOREIGN KEY (manufacturer_id) REFERENCES organization (organization_id);
CREATE INDEX IDX_72F335F8F603EE73 ON equipment_instance (vendor_id);
CREATE INDEX IDX_72F335F8A23B42D ON equipment_instance (manufacturer_id);

UPDATE equipment_instance
SET
equipment_instance.vendor_id = (SELECT vendor_id FROM equipment WHERE equipment.equipment_id = equipment_instance.equipment_id),
equipment_instance.manufacturer_id = (SELECT manufacturer_id FROM equipment WHERE equipment.equipment_id = equipment_instance.equipment_id);

ALTER TABLE user ADD organization_restriction_enabled TINYINT(1) NOT NULL;
ALTER TABLE equipment DROP FOREIGN KEY equipment_ibfk_1;
ALTER TABLE equipment DROP FOREIGN KEY equipment_ibfk_3;
DROP INDEX IDX_D338D583F603EE73 ON equipment;
DROP INDEX IDX_D338D583A23B42D ON equipment;

ALTER TABLE equipment DROP manufacturer_id, DROP vendor_id;