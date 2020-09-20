<?php

namespace Equipment\Service;

use Application\Service\AbstractBaseService;
use \DateTime;
use Application\Utility\Json;
use Application\Utility\Image;
use Equipment\Entity\Equipment;
use Application\Service\CannotDeleteException;
use Application\Service\EntityDoesNotExistException;
use Application\Service\ServiceOperationException;
use Application\Utility\ServiceMessage;

class EquipmentService extends AbstractBaseService {

    const ALIAS_KEY_RELATIONSHIPS = 'related';

    /**
     * @return \Equipment\Repository\Equipment
     */
    protected function getEquipmentRepository() {
        return $this->getRepository('Equipment\Entity\Equipment');
    }

    protected function getEntityRepository() {
        return $this->getEquipmentRepository();
    }



    public function listEquipments($criteria = array()) {
        return $this->getEquipmentRepository()->findBy($criteria);
    }

    /**
     * @param int $id
     * @return Equipment
     */
    public function getEquipment($id) {
        return $this->getEquipmentRepository()->find($id);
    }

    public function mergeEntity($entity) {
        return $this->entityManager->merge($entity);
    }

    public function taxonomiesBelongEquipments($equipments) {
        $taxonomies = array();
        foreach ($equipments as $equipment) {
            $tempTaxonomies = array();
            $equipmentTaxonomies = $equipment->getEquipmentTaxonomy();
            foreach ($equipmentTaxonomies as $taxonomy) {
                $tempTaxonomies[] = $taxonomy->getName();
            }
            $taxonomies[$equipment->getEquipmentId()] = $tempTaxonomies;
        }
        return $taxonomies;
    }

    public function getEquipmentJson($currentApplication, $search = "") {

        if (file_exists('data/equipments/equipments.json')) {
            $jsonEquipments = json_decode(file_get_contents('data/equipments/equipments.json'));
        } else {
            $equipments = $this->getEquipmentRepository()->getEquipmentsForEveryApplication();

            $equipmentArray = array();

            foreach ($equipments as $equipment) {
                $equipmentArray[] = array(
                    'equipment_id' => $equipment->getEquipmentId(),
                    'title' => $equipment->getTitle(),
                    'application' => $equipment->getApplication());
            }
            if (!file_exists('data/equipments')) {
                mkdir('data/equipments');
            }
            file_put_contents('data/equipments/equipments.json', json_encode($equipmentArray));
            $jsonEquipments = json_decode(file_get_contents('data/equipments/equipments.json'));
        }

        $jsonEquipmentsForCurrentApplication = array_filter($jsonEquipments, function($equipment) use ($currentApplication, $search) {
            $titleConditional = true;
            if(strlen($search) > 0)
                $titleConditional = strpos(strtolower($equipment->title), strtolower($search)) !== FALSE;
            return $equipment->application === strtolower($currentApplication) && $titleConditional;
        });
        return $jsonEquipmentsForCurrentApplication;
    }

    public function deleteEquipment($equipmentId) {

        $equipment = $this->getEquipmentRepository()->find($equipmentId);

        if ($equipment === null) {
            $message = sprintf($this->translate('Could not find equipment with id %u', $equipmentId));
            throw new EntityDoesNotExistException($message);
        }

        $this->throwExceptionIfEquipmentHasRelations($equipment);

        $this->deleteFromJson($equipmentId);
        $this->deleteImage($equipment->getFeatureImage());
        $this->remove($equipment);
        $message = $equipment->getTitle() . ' ' . $this->translate('has been deleted successfully');
        return new ServiceMessage('success', $message);
    }

    private function throwExceptionIfEquipmentHasRelations(Equipment $equipment) {
        $repository = $this->getEquipmentRepository();
        $hasRelations = true;

        $equipmentId = $equipment->getEquipmentId();
        $equipmentTitle = $equipment->getTitle();

        if ($repository->equipmentHasDocumentation($equipmentId)) {
            $message = sprintf($this->translate("Cannot delete %s: It has related documentation that needs to be deleted first."), $equipmentTitle);
        } else if ($repository->equipmentHasAttachments($equipmentId)) {
            $message = sprintf($this->translate("Cannot delete %s: It has related attachments that need to be deleted first."), $equipmentTitle);
        } else if ($repository->equipmentHasInstances($equipmentId)) {
            $message = sprintf($this->translate("Cannot delete %s: It has related instances that need to be deleted first."), $equipmentTitle);
        } else if ($repository->equipmentHasTraining($equipmentId)) {
            $message = sprintf($this->translate("Cannot delete %s: It has related training materials that need to be deleted first."), $equipmentTitle);
        } else if ($repository->equipmentHasCertification($equipmentId)) {
            $message = sprintf($this->translate("Cannot delete %s: It has related certifications that need to be deleted first."), $equipmentTitle);
        } else if ($repository->equipmentHasExercises($equipmentId)) {
            $message = sprintf($this->translate("Cannot delete %s: It has related exercises that need to be deleted first."), $equipmentTitle);
        } else if ($repository->equipmentHasBestPractices($equipmentId)) {
            $message = sprintf($this->translate("Cannot delete %s: It has related best practices that need to be deleted first."), $equipmentTitle);
        } else {
            $hasRelations = false;
        }
        if ($hasRelations) {
            throw new CannotDeleteException($message);
        }
    }

    public function deleteMany($equipmentIds = array()) {
        $serviceMessageArray = array();

        foreach ($equipmentIds as $equipmentId) {
            try {
                array_push($serviceMessageArray, $this->deleteEquipment($equipmentId));
            } catch (ServiceOperationException $exception) {
                $serviceMessage = new ServiceMessage(ServiceMessage::TYPE_ERROR, $exception->getMessage());
                array_push($serviceMessageArray, $serviceMessage);
            }
        }

        return $serviceMessageArray;
    }

    public function getEquipmentSearch($params = array()) {
        return $this->getEquipmentRepository()->getEquipmentSearch($params);
    }

    /**
     * @param Equipment $equipment
     * @param string $action
     */
    public function persistEquipment(Equipment $equipment, $action) {
        if ($action === 'add') {
            $equipment->setDateAdd(new DateTime('NOW'));
        } else {

            $equipment->setDateUpdate(new DateTime('NOW'));
        }

        if($equipment->getWll() === '')
            $equipment->setWll(null);
        if($equipment->getLength() === '')
            $equipment->setLength(null);

        parent::persist($equipment);
    }

    public function deleteImage($imageName) {
        $image = new Image();
        $image->deleteImage('public/content/equipment/' . $imageName);
    }

    public function deleteFromJson($equipmentId) {
        $json = new Json();
        $json->deleteEquipmentJson('data/equipments/equipments.json', $equipmentId);
    }

    public function addEquipmentToJson($equipments = array()) {
        $json = new Json();
        $json->addEquipmentJson('data/equipments/equipments.json', $equipments);
    }

    public function saveEquipmentJson($action, $title, $equipmentId) {
        $equipmentArray = array(
            array(
                'equipment_id' => $equipmentId,
                'title' => trim(strip_tags($title)),
                'application' => $this->application
            )
        );

        if ($action === 'update') {
            $this->deleteFromJson($equipmentId);
        }
        $equipment = $this->getEquipment($equipmentId);
        if ($equipment && $equipment->getStatus() === 'active') {
            $this->addEquipmentToJson($equipmentArray);
        }
    }

    public function resizeImage($post, $limit, $featureImage) {
        if (!empty($post['tmp_name'])) {
            $image = new Image();
            $image->deleteImage('public/content/equipment/' . $featureImage);
            return $image->resizeImage(
                            $post['tmp_name'], $limit, 'public/content/equipment/' .
                            $post['name']);
        }
        return null;
    }

    public function checkEquipmentExists($equipmentId) {
        $equipmentExists = false;
        if ($equipmentId) {
            $equipment = $this->getEquipment($equipmentId);
            if (!empty($equipment)) {
                return $equipment;
            }
        }
        return $equipmentExists;
    }
}
