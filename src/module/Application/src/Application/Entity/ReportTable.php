<?php

namespace Application\Entity;

class ReportTable {

    private $headerColumns;
    private $dataTable = array();
    private $title;

    public function __construct($title, $headerColumns, $dataTable) {
        $this->headerColumns = $headerColumns;
        $this->dataTable = $dataTable;
        $this->title = $title;
    }

    public function getHeaderColumns() {
        return $this->headerColumns;
    }

    public function getDataTable() {
        return $this->dataTable;
    }

    public function getNoOfColumns() {
        return count($this->headerColumns);
    }

    public function getNoOfDataRows() {
        return count($this->dataTable);
    }

    public function getTitle() {
        return $this->title;
    }

}
