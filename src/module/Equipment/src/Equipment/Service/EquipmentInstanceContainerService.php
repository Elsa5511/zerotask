<?php

namespace Equipment\Service;


class EquipmentInstanceContainerService extends EquipmentInstanceService {
    protected function getEquipmentInstanceRepository() {
        return $this->getRepository('Equipment\Entity\EquipmentInstanceContainer');
    }
}