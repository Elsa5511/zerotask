<?php

namespace EquipmentTest\Service;

class PeriodicControlReportServiceTest extends \EquipmentTest\BaseSetUp {
    
    public function testExport() {
        $periodicControl = $this->setupPeriodicControlForExport();
        
        $pdfExporterMock = $this->getMockBuilder('Equipment\Service\PeriodicControlPdfExporter')
                ->disableOriginalConstructor()
                ->getmock();
        $pdfExporterMock->expects($this->once())
                ->method('export');

        $serviceManager = $this->getApplicationServiceLocator();
        $reportService = $serviceManager->get('\Equipment\Service\PeriodicControlReportService');
        $reportService->export($periodicControl, $pdfExporterMock);
    }
    
    private function setupPeriodicControlForExport() {
        $periodicControl = new \Equipment\Entity\PeriodicControl();
        $equipmentInstance = new \Equipment\Entity\EquipmentInstance();
        $equipment = new \Equipment\Entity\Equipment();
        $periodicControl->setEquipmentInstance($equipmentInstance);
        $equipmentInstance->setEquipment($equipment);
        $periodicControl->setControlDate(new \DateTime());
        $equipmentInstance->setPeriodicControlDate(new \DateTime());        
        return $periodicControl;
    }
}
