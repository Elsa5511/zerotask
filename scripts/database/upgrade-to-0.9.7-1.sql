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