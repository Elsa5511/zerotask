<?php

namespace Equipment\Service;

use Application\Entity\ReportTable;

class PeriodicControlPdfExporter
{
    /**
     * @var \Equipment\Entity\ControlTemplate
     */
    private $controlTemplate;
    private $defaultCellWidth = 50;
    private $defaultCellHeight = 10;
    private $translator;

    public function __construct($translator, $controlTemplate = null) {
        $this->translator = $translator;
        $this->controlTemplate = $controlTemplate;
    }

    public function export($title,
                           ReportTable $generalInformationReportTable,
                           ReportTable $controlPointsReportTable, $periodicControl) {
        $document = new Helper\HtmlToPdfConverter();
        $document->SetMargins(20, 20, 20);

        $document->AddPage();
        $this->printTitle($document, $title);
        $this->printHeader($document, $generalInformationReportTable->getTitle());
        $this->printGeneralInformationReport($document, $generalInformationReportTable);

        if ($this->controlTemplate) {
            $document->Ln(5);
            $this->printCertificationSection($document, $periodicControl->getRegisteredBy());
        }

        $document->AddPage();
        $this->printHeader($document, $controlPointsReportTable->getTitle());
        $this->printControlPointTable($document, $controlPointsReportTable);

        $this->sendToBrowser($document);
    }

    private function sendToBrowser($document) {
        ob_end_clean();
        $document->Output();
    }
    
    private function printTitle($document, $title) {
        $this->setHeader1Font($document);
        $document->Cell($this->defaultCellWidth, $this->defaultCellHeight, $title);
        $document->Ln(20);
    }

    private function printHeader($document, $text) {
        $this->setHeader2Font($document);
        $document->Cell($this->defaultCellWidth, $this->defaultCellHeight, $text);
        $document->Ln(15);
    }

    private function printGeneralInformationReport($document, $generalInformationReportTable) {
        $headerColumns = $generalInformationReportTable->getHeaderColumns();
        $data = $generalInformationReportTable->getDataTable();
        for ($i = 0; $i < $generalInformationReportTable->getNoOfColumns(); $i++) {
            $this->setStrongFont($document);
            $document->Cell($this->defaultCellWidth, $this->defaultCellHeight, $this->convertCharacterSet($headerColumns[$i]));
            $this->setDefaultFont($document);
            $document->Cell($this->defaultCellWidth, $this->defaultCellHeight, $this->convertCharacterSet($data[0][$i]));
            $document->Ln();
        }
    }

    /**
     * @param Helper\HtmlToPdfConverter $document
     */
    private function printCertificationSection($document, $competentPerson = null) {
        $c1 = $this->translator->translate('Customer signature Date');
        $c2 = $this->translator->translate('Verifier signature Date');

        $fullPageWidth = 170;

        $document->WriteHTML($this->convertCharacterSet($this->controlTemplate->getStandardText()));
        $document->Ln(10);
        $document->Cell($fullPageWidth, $this->defaultCellHeight, $this->convertCharacterSet($c1));
        $document->Ln(12);
        $document->Cell($fullPageWidth, $this->defaultCellHeight, $this->convertCharacterSet($c2));

        if($competentPerson) {
            $document->Ln(10);
            $document->Cell($fullPageWidth, $this->defaultCellHeight, $this->convertCharacterSet($competentPerson));
        }
    }

    private function printControlPointTable($document, $controlPointsReportTable) {
        $controlPointHeaders = $controlPointsReportTable->getHeaderColumns();               
        $controlPointData = $controlPointsReportTable->getDataTable();

        $this->printControlPointTableHeaders($document, $controlPointHeaders);
        
        for ($i = 0; $i < $controlPointsReportTable->getNoOfDataRows(); $i++) {
            $this->setStrongFont($document);
            $document->Cell($this->defaultCellWidth, $this->defaultCellHeight, $this->convertCharacterSet($controlPointData[$i][0]), 'T');
            $this->setDefaultFont($document);
            $document->Cell($this->defaultCellWidth, $this->defaultCellHeight, $this->convertCharacterSet($controlPointData[$i][1]), 'T');
            $document->MultiCell($this->defaultCellWidth + 10, $this->defaultCellHeight, $this->convertCharacterSet($controlPointData[$i][2]), 'T');
            $document->Ln(0);
        }
    }
    
    private function convertCharacterSet($data) {
        if (is_a($data, '\DateTime')) {
            $dateFormatter = \IntlDateFormatter::create('nb-NO', \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
            return $dateFormatter->format($data->getTimestamp());
        }
        return iconv('UTF-8', 'windows-1252', $data);
    }
    
    private function printControlPointTableHeaders($document, $controlPointHeaders) {
        $this->setStrongFont($document);

        foreach ($controlPointHeaders as $header) {
            $document->Cell($this->defaultCellWidth, $this->defaultCellHeight, $header);
        }
        $document->Ln();        
    }
    
    private function setHeader2Font($document) {
        $document->setFont('Arial', 'B', 16);
    }

    private function setHeader1Font($document) {
        $document->setFont('Arial', 'B', 18);
    }

    private function setDefaultFont($document) {
        $document->setFont('Arial', '', 11);
    }

    private function setStrongFont($document) {
        $document->setFont('Arial', 'B', 11);
    }

}
