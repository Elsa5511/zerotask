<?php


namespace Equipment\Controller;

use Application\Controller\AbstractBaseController;
use Equipment\Entity\InstanceExpirationFieldTypes;
use Equipment\Service\EquipmentInstanceService;
use Equipment\Service\EquipmentService;
use Equipment\Service\EquipmentTaxonomyService;
use Zend\View\Model\ViewModel;

class EquipmentInstanceReportController extends AbstractBaseController {

    public function getTemplate($expirationField) {
        switch ($expirationField) {
            case InstanceExpirationFieldTypes::PERIODIC_CONTROL:
                return 'equipment/equipment-instance-report/expired-periodic-control.phtml';
            case InstanceExpirationFieldTypes::TECHNICAL_LIFETIME:
                return 'equipment/equipment-instance-report/expired-lifetime.phtml';
            case InstanceExpirationFieldTypes::GUARANTEE:
                return 'equipment/equipment-instance-report/expired-guarantee.phtml';
        }
    }

    public function expiredPeriodicControlForEquipmentAction() {
        $viewModel = $this->reportExpired(InstanceExpirationFieldTypes::PERIODIC_CONTROL, 'equipment');
        $viewModel->setTemplate($this->getTemplate(InstanceExpirationFieldTypes::PERIODIC_CONTROL));
        return $viewModel;
    }

    public function expiredPeriodicControlForCategoryAction() {
        $viewModel = $this->reportExpired(InstanceExpirationFieldTypes::PERIODIC_CONTROL, 'category');
        $viewModel->setTemplate($this->getTemplate(InstanceExpirationFieldTypes::PERIODIC_CONTROL));
        return $viewModel;
    }

    public function expiredPeriodicControlAction() {
        $viewModel = $this->reportExpired(InstanceExpirationFieldTypes::PERIODIC_CONTROL);
        return $viewModel;
    }

    public function expiredGuaranteeForEquipmentAction() {
        $viewModel = $this->reportExpired(InstanceExpirationFieldTypes::GUARANTEE, 'equipment');
        $viewModel->setTemplate($this->getTemplate(InstanceExpirationFieldTypes::GUARANTEE));
        return $viewModel;
    }

    public function expiredGuaranteeForCategoryAction() {
        $viewModel = $this->reportExpired(InstanceExpirationFieldTypes::GUARANTEE, 'category');
        $viewModel->setTemplate($this->getTemplate(InstanceExpirationFieldTypes::GUARANTEE));
        return $viewModel;
    }

    public function expiredGuaranteeAction() {
        $viewModel = $this->reportExpired(InstanceExpirationFieldTypes::GUARANTEE);
        return $viewModel;
    }

    public function expiredLifetimeForEquipmentAction() {
        $viewModel = $this->reportExpired(InstanceExpirationFieldTypes::TECHNICAL_LIFETIME, 'equipment');
        $viewModel->setTemplate($this->getTemplate(InstanceExpirationFieldTypes::TECHNICAL_LIFETIME));
        return $viewModel;
    }

    public function expiredLifetimeForCategoryAction() {
        $viewModel = $this->reportExpired(InstanceExpirationFieldTypes::TECHNICAL_LIFETIME, 'category');
        $viewModel->setTemplate($this->getTemplate(InstanceExpirationFieldTypes::TECHNICAL_LIFETIME));
        return $viewModel;
    }

    public function expiredLifetimeAction() {
        $viewModel = $this->reportExpired(InstanceExpirationFieldTypes::TECHNICAL_LIFETIME);
        return $viewModel;
    }

    private function reportExpired($expirationField, $idType = null) {
        $titlePrefix = "";

        $id = $this->params()->fromRoute('id', 0);

        if ($idType === 'category') {
            $category = $this->getEquipmentTaxonomyService()->findById($id);
            $this->setBreadcrumbForApplication();
            $this->setBreadcrumbForTaxonomy($category);
            $titlePrefix = $category->getName() . ' | ';
        }
        else if ($idType === 'equipment') {
            $equipment = $this->getEquipmentService()->findById($id);
            $this->setBreadcrumbForEquipmentFeature($equipment);
            $titlePrefix = $equipment->getTitle() . ' | ';
        }
        $equipmentInstances = $this->getEquipmentInstanceService()
            ->getAllExpired($expirationField, $idType, $id);

        return new ViewModel(array(
            'equipmentInstances' => $equipmentInstances,
            'titlePrefix' => $titlePrefix,
            'reportLevel' => $idType,
            'id' => $id
        ));
    }

    /**
     * @return EquipmentService
     */
    private function getEquipmentService() {
        return $this->getService('Equipment\Service\EquipmentService');
    }

    /**
     * @return EquipmentInstanceService
     */
    protected function getEquipmentInstanceService() {
        return $this->getService('Equipment\Service\EquipmentInstanceService');
    }

    /**
     * @return EquipmentTaxonomyService
     */
    private function getEquipmentTaxonomyService() {
        return $this->getService('Equipment\Service\EquipmentTaxonomyService');
    }
}