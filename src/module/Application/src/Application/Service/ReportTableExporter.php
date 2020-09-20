<?php

namespace Application\Service;

abstract class ReportTableExporter {
    
    /**
     * Exports the data in the reoprt table to a file and sends the
     * file to the browser.
     * 
     * @param \Application\Entity\ReportTable $reportTable The table containing the headers and the report data.
     */
    public abstract function export(\Application\Entity\ReportTable $reportTable);
    
    public function format($data) {
        if (is_a($data, '\DateTime')) {
            $dateFormatter = \IntlDateFormatter::create('nb-NO', \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
            return $dateFormatter->format($data->getTimestamp());
        }
        return $data;
    }
    
    public function createFileNameFrom($title) {

        return str_replace(' ', '-', str_replace(': ', '-',strtolower(trim($title))));
    }
        
}
