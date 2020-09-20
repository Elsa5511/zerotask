<?php

namespace Equipment\Service;

use Equipment\Entity\EquipmentInstance;
use Equipment\Entity\InstanceExpirationFieldTypes;

class EquipmentInstanceReportService {

    private $translator;

    public function __construct($translator) {
        $this->translator = $translator;
    }

    public function createExpiredReportTable($type, $equipmentInstances) {
        switch($type) {
            case InstanceExpirationFieldTypes::PERIODIC_CONTROL:
                return $this->createPeriodicControlReportTable($equipmentInstances);
            case InstanceExpirationFieldTypes::GUARANTEE:
                return $this->createExpiredGuaranteeReportTable($equipmentInstances);
            case InstanceExpirationFieldTypes::TECHNICAL_LIFETIME:
                return $this->createExpiredLifetimeReportTable($equipmentInstances);
            default:
                return array();
        }
    }

    public function createSearchResultReportTable($equipmentInstances) {
        $headerValues = $this->createSearchResultHeaderValues();
        $dataTable = array();
        foreach ($equipmentInstances as $equipmentInstance) {
            array_push($dataTable, $this->createSearchResultDataRow($equipmentInstance));
        }
        $title = $this->translate("Search results: Instances");
        $reportTable = new \Application\Entity\ReportTable($title, $headerValues, $dataTable);
        return $reportTable;
    }

    public function createControlSearchResultReportTable($controls) {
        $headerValues = $this->createControlSearchResultHeaderValues();
        $dataTable = array();
        foreach ($controls as $control) {
            array_push($dataTable, $this->createControlSearchResultDataRow($control));
        }
        $title = $this->translate("Search results: Controls");
        $reportTable = new \Application\Entity\ReportTable($title, $headerValues, $dataTable);
        return $reportTable;
    }

    /**
     * @param EquipmentInstance $equipmentInstance
     */
    private function createSearchResultDataRow($equipmentInstance) {
        return array(
            $equipmentInstance->getEquipment()->getTitle(),
            $equipmentInstance->getSerialNumber(),
            $equipmentInstance->getRegNumber(),
            $equipmentInstance->getLocation() ? $equipmentInstance->getLocation()->__toString() : "",
            $equipmentInstance->getPrice() !== null ? $equipmentInstance->getPrice() : "",
            $equipmentInstance->getUsageStatus() ? $equipmentInstance->getUsageStatus()->getName() : "",
            $this->prettyPrintControlStatus($equipmentInstance->getControlStatus()),
            $equipmentInstance->isCheckedOut() ? $this->translate("Checked out") : "",
            $equipmentInstance->getMinPeriodicControlDate(),
        );
    }

    private function createControlSearchResultDataRow($control) {
        return array(
            $control->getEquipmentInstance()->getSerialNumber(),
            $control->getRegisteredBy(),
            $control->getControlDate(),
            $control->getNextControlDate(),
            $control->getControlStatus() ? $this->translate($control->getControlStatus()->__toString()) : "",
        );
    }

    private function prettyPrintControlStatus($controlStatus) {
            $controlStatus = trim(strtolower($controlStatus));
            if ($controlStatus === 'expired') {
                return $this->translate("Expired");
            }
            if ($controlStatus === 'not approved') {
                return $this->translate(ucfirst($controlStatus));
            }
            if ($controlStatus === 'temporarily approved') {
                return $this->translate(ucfirst($controlStatus));
            }
            if ($controlStatus === 'approved') {
                return $this->translate(ucfirst($controlStatus));
            }
            return $controlStatus;
    }

    public function createPeriodicControlReportTable($equipmentInstancesWithExpiredControlDate) {
        $headerValues = $this->createPeriodicControlReportHeaderValues();
        $dataTable = array();
        foreach ($equipmentInstancesWithExpiredControlDate as $equipmentInstance) {
            $dataRow = array_merge($this->getSharedReportData($equipmentInstance), array(
                $this->translate($equipmentInstance->getControlStatusOrExpiredStatus()),
                $equipmentInstance->getPeriodicControlDate()
                    )
            );
            array_push($dataTable, $dataRow);
        }
        $title = $this->translate("Instances expired on control date report");
        $reportTable = new \Application\Entity\ReportTable($title, $headerValues, $dataTable);
        return $reportTable;
    }

    public function createExpiredGuaranteeReportTable($equipmentInstancesWithExpiredControlDate) {
        $headerValues = $this->createExpiredGuaranteeReportHeaderValues();
        $dataTable = array();
        foreach ($equipmentInstancesWithExpiredControlDate as $equipmentInstance) {
            $dataRow = array_merge($this->getSharedReportData($equipmentInstance), array(
                $equipmentInstance->getGuaranteeTime(),
                    )
            );
            array_push($dataTable, $dataRow);
        }
        $title = $this->translate("Instances expired on guarantee date report");
        $reportTable = new \Application\Entity\ReportTable($title, $headerValues, $dataTable);
        return $reportTable;
    }

    public function createExpiredLifetimeReportTable($equipmentInstancesWithExpiredControlDate) {
        $headerValues = $this->createExpiredLifetimeReportHeaderValues();
        $dataTable = array();
        foreach ($equipmentInstancesWithExpiredControlDate as $equipmentInstance) {
            $dataRow = array_merge($this->getSharedReportData($equipmentInstance), array(
                $equipmentInstance->getTechnicalLifetime(),
                    )
            );
            array_push($dataTable, $dataRow);
        }
        $title = $this->translate("Instances expired on technical lifetime report");
        $reportTable = new \Application\Entity\ReportTable($title, $headerValues, $dataTable);
        return $reportTable;
    }

    private function createSharedReportHeaderValues() {
        return array(
            $this->translate('Equipment type'),
            (string) $this->translate('Serial #'),
            $this->translate('Reg. number'),
            $this->translate('Location'),
            $this->translate('Usage status')
        );
    }

    private function createPeriodicControlReportHeaderValues() {
        return array_merge($this->createSharedReportHeaderValues(), array(
            $this->translate('Control status'),
            $this->translate('Next control')
        ));
    }

    private function createSearchResultHeaderValues() {
        return array(
            $this->translate('Equipment'),
            $this->translate('Serial #'),
            $this->translate('Reg. number'),
            $this->translate('Location'),
            $this->translate('Price'),
            $this->translate('Usage status'),
            $this->translate('Control status'),
            $this->translate('Check-out status'),
            $this->translate('Next control'),
        );
    }

    private function createControlSearchResultHeaderValues() {
        return array(
            $this->translate('Equipment Instance'),
            $this->translate('Competent person'),
            $this->translate('Control Date'),
            $this->translate('Next Control Date'),
            $this->translate('Control status'),
        );
    }

    private function getSharedReportData($equipmentInstance) {
        return array(
            $equipmentInstance->getEquipment()->getTitle(),
            $equipmentInstance->getSerialNumber(),
            $equipmentInstance->getRegNumber(),
            (string) $equipmentInstance->getLocation(),
            (string) $equipmentInstance->getUsageStatus()
        );
    }

    private function createExpiredLifetimeReportHeaderValues() {
        return array_merge($this->createSharedReportHeaderValues(), array(
            $this->translate('Technical lifetime')
        ));
    }

    private function createExpiredGuaranteeReportHeaderValues() {
        return array_merge($this->createSharedReportHeaderValues(), array(
            $this->translate('Guarantee time')
        ));
    }

    public function translate($text) {
        return $this->translator->translate($text);
    }

}
