<?php

namespace Application\Service;

use PHPExcel;

class ExcelExporter extends ReportTableExporter {

    private $shouldConvertToPdf;

    public function __construct($shouldConvertToPdf = false) {
        $this->shouldConvertToPdf = $shouldConvertToPdf;
    }

    public function export(\Application\Entity\ReportTable $reportTable) {
        $phpExcel = new PHPExcel();
        $phpExcel->setActiveSheetIndex(0);
        $activeSheet = $phpExcel->getActiveSheet();

        $activeSheet->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->getStyle('A1')->getFont()->setSize(16);
        $activeSheet->setCellValue('A1', $reportTable->getTitle());
        $lastColumn = 'A'; // + $reportTable->getNoOfColumns();
        for ($i = 1; $i < $reportTable->getNoOfColumns(); $i++) {
            $lastColumn++;
        }
        $activeSheet->mergeCells('A1:' . $lastColumn . '1');

        $this->printHeader($reportTable, $activeSheet);
        $this->printData($reportTable, $activeSheet);

        if ($this->shouldConvertToPdf) {
            $rendererName = \PHPExcel_Settings::PDF_RENDERER_MPDF; // PDF_RENDERER_TCPDF;
            $rendererLibraryPath = './vendor/mpdf60/';
            \PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
            $writer = \PHPExcel_IOFactory::createWriter($phpExcel, 'PDF');
        }
        else {
            $writer = \PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
        }

        $filename = $this->createFileNameFrom($reportTable->getTitle());
        $this->sendDocumentToBrowser($writer, $activeSheet, $filename);
    }

    private function printHeader($reportTable, $activeSheet) {
        $currentColumn = 'A';
        $columnWidth = 140 / $reportTable->getNoOfColumns();
        foreach ($reportTable->getHeaderColumns() as $headerCell) {
            $cellPosition = $currentColumn . '3';
            $activeSheet->getStyle($cellPosition)->getFont()->setBold(true);
            $activeSheet->getColumnDimension($currentColumn)->setWidth($columnWidth);
            $activeSheet->setCellValue($cellPosition, $headerCell);
            $currentColumn++;
        }
    }

    private function printData($reportTable, $activeSheet) {
        $currentRow = 4;

        foreach ($reportTable->getDataTable() as $dataRow) {
            $currentColumn = 'A';
            foreach ($dataRow as $dataCell) {
                $currentCell = $currentColumn . $currentRow;
                $activeSheet->setCellValue($currentCell, (string)$this->format($dataCell));
                $this->addColorToEveryOddRow($currentRow, $currentCell, $activeSheet);
                $currentColumn++;
            }
            $currentRow++;
        }
    }

    private function addColorToEveryOddRow($currentRow, $currentCell, $activeSheet) {
        if ($currentRow % 2 == 1) {
            $activeSheet->getStyle($currentCell)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'EEEEEE')
                    )));
        }
    }

    private function sendDocumentToBrowser($writer, $activeSheet, $filename) {
        if ($this->shouldConvertToPdf) {
            $activeSheet->setShowGridLines(false);
            $activeSheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');
        }
        else {
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        }

        $writer->save('php://output');
    }

}
