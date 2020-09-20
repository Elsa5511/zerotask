<?php

namespace BestPractice\Service;

class BestPracticeExporter {

    public function export($bestPractice, $imageUrlArray) {
        $pdf = new \fpdf\FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', 'B', 20);
        $pdf->Cell(180, 10, $this->convertCharacterSet($bestPractice->getTitle()), 0, 1, 'C');
        $pdf->SetFont('Helvetica', '', 16);
        $pdf->Cell(180, 10, $this->convertCharacterSet($bestPractice->getSubtitle()), 0, 0, 'C');
        $offsetX = 15;
        $offsetY = 50;
        $width = 180;

        for ($i = 0; $i < count($imageUrlArray); $i++) {
            if ($i > 0) {
                $pdf->AddPage();
            }
            $pdf->Image($imageUrlArray[$i], $offsetX, $offsetY, $width);
        }
        $pdf->Output();
    }

    private function convertCharacterSet($text) {
        return iconv('UTF-8', 'windows-1252', $text);
    }

}
