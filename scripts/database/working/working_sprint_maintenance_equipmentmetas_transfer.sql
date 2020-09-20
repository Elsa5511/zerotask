UPDATE equipment e
SET e.nsn = (SELECT em.value FROM equipmentmeta em WHERE em.key = 'nsn' AND em.equipment_id = e.equipment_id ORDER BY em.equipmentmeta_id DESC LIMIT 1),
e.sap = (SELECT em.value FROM equipmentmeta em WHERE em.key = 'sap' AND em.equipment_id = e.equipment_id ORDER BY em.equipmentmeta_id DESC LIMIT 1),
e.vendor_part = (SELECT em.value FROM equipmentmeta em WHERE em.key = 'vendor_part' AND em.equipment_id = e.equipment_id ORDER BY em.equipmentmeta_id DESC LIMIT 1);

DROP TABLE equipmentmeta;