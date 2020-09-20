<?php

namespace Application\Factory\Service;

class ReportExporterFactory {
    
    public function createReportExporter($type) {
        if ($type === 'excel') {         
            return new \Application\Service\ExcelExporter();
        }
        else if ($type === 'word') {
            return new \Application\Service\WordExporter();
        }
        else if ($type === 'pdf') {
            return new \Application\Service\ExcelExporter(true);
        }
        throw new \InvalidArgumentException("No exporter for type: " . $type);
    }
    
}
