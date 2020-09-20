<?php

namespace Application\Entity;


class StandardMessages {
    private $translator;

    public function __construct($translator) {
        $this->translator = $translator;
    }

    public function saveSucecssful() {
        return $this->translator->translate("The item was successfully saved.");
    }

    public function saveFailed() {
        return $this->translator->translate("Save failed! Please try again later. If the problem persists, contact your system administrator.");
    }

    public function ladocDocumentationDoesNotExist() {
        return $this->translator->translate("Ladoc Documentation does not exist.");
    }

    public function deleteSuccessful() {
        return $this->translator->translate("Item was deleted successfully.");
    }
}