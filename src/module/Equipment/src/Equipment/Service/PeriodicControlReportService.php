<?php

namespace Equipment\Service;

use Equipment\Entity\PeriodicControl;

class PeriodicControlReportService {

    private $translator;

    public function __construct($translator) {
        $this->translator = $translator;
    }

    public function export($periodicControl, $pdfExporter) {
        $generalInformationReportTable = $this->createGeneralInformationReportTableFrom($periodicControl);
        $controlPointsReportTable = $this->createControlPointsReportTableFrom($periodicControl);
        $serialNumber = $periodicControl->getEquipmentInstance()->getSerialNumber();
        $title = $this->translate('Periodic control: ') . $serialNumber;
        $pdfExporter->export($title, $generalInformationReportTable, $controlPointsReportTable, $periodicControl);
    }

    private function createGeneralInformationReportTableFrom($periodicControl) {
        return new \Application\Entity\ReportTable(
                $this->translate('General information'), $this->createHeadersForGeneralInformationReport(), $this->createDataArrayForGeneralInformationReport($periodicControl));
    }

    private function createHeadersForGeneralInformationReport() {
        return array(
            $this->translate('Equipment name'),
            $this->translate('Serial #'),
            $this->translate('Registration #'),
            $this->translate('Owner'),
            $this->translate('Location'),
            $this->translate('Control number'),
            $this->translate('Competent person'),
            $this->translate('Control date'),
            $this->translate('Next control date'),
            $this->translate('Expertise organ'),
            $this->translate('Control status')
        );
    }

    /**
     * @param PeriodicControl $periodicControl
     * @return array
     */
    private function createDataArrayForGeneralInformationReport($periodicControl) {
        $equipmentInstance = $periodicControl->getEquipmentInstance();
        $equipment = $equipmentInstance->getEquipment();
        $dateFormatter = \IntlDateFormatter::create(null, \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
        
        return array(
            array(
                $equipment->getTitle(),
                $equipmentInstance->getSerialNumber(),
                $equipmentInstance->getRegNumber(),
                $equipmentInstance->getOwner(),
                $equipmentInstance->getLocation(),
                $periodicControl->getPeriodicControlId(),
                $periodicControl->getRegisteredBy(),
//                $dateFormatter->format($periodicControl->getControlDate()->getTimestamp()),
//                $dateFormatter->format($equipmentInstance->getPeriodicControlDate()->getTimestamp()),
                $periodicControl->getControlDate(),
                $periodicControl->getNextControlDate(),
                $periodicControl->getExpertiseOrgan(),
                $this->translateStatus($periodicControl->getControlStatus())
            )
        );
    }

    private function translateStatus($status) {
        switch ($status) {
            case 'Approved' : return $this->translate('Approved');
            case 'Not approved' : return $this->translate('Not approved');
            case 'Temporarily approved': return $this->translate('Temporarily approved');
            default: return $this->translate($status);
        }
    }

    private function createControlPointsReportTableFrom($periodicControl) {
        $controlPointHeaders = array(
            $this->translate('Control point'),
            $this->translate('Status'),
            $this->translate('Comments')
        );

        $controlPointResults = array();
        foreach ($periodicControl->getControlPointResultCollection() as $i => $controlPoint) {
            $rowNumber = $i + 1;
            $controlPointResult = array(
                $rowNumber . '. ' . $controlPoint->getControlPoint(),
                (string) $controlPoint->getControlPointOption(),
                trim($controlPoint->getRemark())
            );
            array_push($controlPointResults, $controlPointResult);
        }
        return new \Application\Entity\ReportTable($this->translate('Control points'), $controlPointHeaders, $controlPointResults);
    }

    private function translate($text) {
        return $this->translator->translate($text);
    }

}
