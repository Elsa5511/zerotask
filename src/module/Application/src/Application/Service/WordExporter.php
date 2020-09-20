<?php

namespace Application\Service;

class WordExporter extends ReportTableExporter {

    private $tableCellSizeInPixels = 2750;

    public function export(\Application\Entity\ReportTable $reportTable) {
        $wordDocument = new \PHPWord();
        $section = $wordDocument->createSection(array('orientation' => 'landscape'));

        $section->addText($this->prepareText($reportTable->getTitle()));

        $table = $section->addTable();

        $this->printHeader($table, $reportTable);
        $this->printData($table, $reportTable);
        $writer = \PHPWord_IOFactory::createWriter($wordDocument, 'Word2007');
        $filename = $this->createFileNameFrom($reportTable->getTitle());
        $this->saveDocument($writer, $filename);
    }

    private function printHeader($table, $reportTable) {
        $table->addRow();

        foreach ($reportTable->getHeaderColumns() as $headerCell) {
            $boldFont = array('bold' => true);
            $table->addCell($this->tableCellSizeInPixels)->addText($this->prepareText($headerCell), $boldFont);
        }
    }

    private function printData($table, \Application\Entity\ReportTable $reportTable) {
        for ($i = 0; $i < $reportTable->getNoOfDataRows(); $i++) {
            $table->addRow();
            $dataTable = $reportTable->getDataTable();
            foreach ($dataTable[$i] as $item) {
                $isOddRow = $i % 2 == 0;
                $cell = $this->addCellTo($table, $isOddRow);
                if (is_string($item)) {
                    $item = $this->prepareText($item);
                }
                $cell->addText($this->format($item));
            }
        }
    }

    private function addCellTo($table, $isOddRow) {
        if ($isOddRow) {
            return $table->addCell($this->tableCellSizeInPixels);
        } else {
            return $table->addCell($this->tableCellSizeInPixels, array('bgColor' => 'EEEEEE'));
        }
    }

    private function saveDocument($writer, $filename) {
        header('Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;Filename="' . $filename . '.docx"');
        $writer->save('php://output');
    }

    // This method is needed beacause PHPWord doesn't handle char encoding right.
    private function prepareText($text) {
        return html_entity_decode(iconv('UTF-8', 'windows-1252', $text));
    }

}
