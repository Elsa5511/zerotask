<?php

namespace Application\Form\User;

class FieldDisplayOptions {

    private $showNewUserFields;
    private $showAdminFields;
    private $showModifyOwnAccountFields;
    private $showAdminFieldsForVedos;

    public function __construct($showNewUserFields, $showAdminFields, $showModifyOwnAccountFields, $showAdminFieldsForVedos) {
        $this->showNewUserFields = $showNewUserFields;
        $this->showAdminFields = $showAdminFields;
        $this->showModifyOwnAccountFields = $showModifyOwnAccountFields;
        $this->showAdminFieldsForVedos = $showAdminFieldsForVedos;
    }

    public function getShowNewUserFields() {
        return $this->showNewUserFields;
    }

    public function getShowAdminFields() {
        return $this->showAdminFields;
    }

    public function getShowModifyOwnAccountFields() {
        return $this->showModifyOwnAccountFields;
    }

    public function getShowAdminFieldsForVedos() {
        return $this->showAdminFieldsForVedos;
    }

    public function setShowNewUserFields($showNewUserFields) {
        $this->showNewUserFields = $showNewUserFields;
    }

    public function setShowAdminFields($showAdminFields) {
        $this->showAdminFields = $showAdminFields;
    }

    public function setShowModifyOwnAccountFields($showModifyOwnAccountFields) {
        $this->showModifyOwnAccountFields = $showModifyOwnAccountFields;
    }

    public function setShowAdminFieldsForVedos($showAdminFieldsForVedos) {
        $this->showAdminFieldsForVedos = $showAdminFieldsForVedos;
    }

}
