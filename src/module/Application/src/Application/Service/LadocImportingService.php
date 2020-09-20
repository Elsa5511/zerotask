<?php

namespace Application\Service;


class LadocImportingService extends ImportingService {
    public function importData()
    {
        $this->updateServerName();

        set_time_limit(self::MAX_EXECUTION_TIME_TO_GET_RESOURCE);
        ini_set('memory_limit', self::PHP_MEMORY_LIMIT);

        $insertedEquipmentTaxonomies = $this->importEquipmentTaxonomies(); //ladoc
        $this->importEquipments($insertedEquipmentTaxonomies); //ladoc

        $insertedPages = $this->importPages(); //ladoc
        $insertedPageSections = $this->importPageSections($insertedPages); //ladoc
        $insertedPageSubsections = $this->importPageSubSections($insertedPageSections); //ladoc
        $insertedPageInlineSections = $this->importPageInlineSections($insertedPageSubsections); //ladoc
        $insertedPageContents = $this->importPageContents($insertedPages, $insertedPageSections, $insertedPageSubsections, $insertedPageInlineSections); //ladoc

        $this->manageHtmlContents($insertedPages, array_merge($insertedPageSections, $insertedPageSubsections), $insertedEquipmentTaxonomies, $insertedPageContents);
        $this->getEntityManager()->clear();

        if(file_exists(self::PATH_FILE_EQUIPMENT_QUICKSEARCH))
            unlink(self::PATH_FILE_EQUIPMENT_QUICKSEARCH);

        echo $this->translate("importing task finished");
    }

    protected function importEquipmentTaxonomies() {
        $ladocAdapter = $this->getLadocAdapter();

        $insertedEquipmentTaxonomiesFromLadoc = array();

        $ladocCategories = $this->getImportingRepository()->getCategoriesFromLadoc($ladocAdapter);
        if ($ladocCategories) {
            $columnsSprintfLadoc = array('chr_description');
            $insertedEquipmentTaxonomiesFromLadoc =
                $this->manageImportEquipmentTaxonomies($ladocCategories, self::PATH_CATEGORY_IMAGES_LADOC, $columnsSprintfLadoc);
        }

        return $insertedEquipmentTaxonomiesFromLadoc;
    }

    protected function importEquipments($insertedEquipmentTaxonomies) {
        $ladocAdapter = $this->getLadocAdapter();

        $insertedEquipmentsFromLadoc = array();

        $ladocEquipments = $this->getImportingRepository()->getEquipmentsFromLadoc($ladocAdapter);
        if ($ladocEquipments) {
            $insertedEquipmentsFromLadoc = $this->manageImportEquipments($insertedEquipmentTaxonomies, $ladocEquipments);
        }

        return $insertedEquipmentsFromLadoc;
    }

    protected function manageHtmlContents($insertedPages, $insertedPageSections, $insertedEquipmentTaxonomies, $insertedPageContents) {
        $insertedEquipmentTaxonomiesFromLadoc = $insertedEquipmentTaxonomies;

        foreach ($insertedPageContents as $contentForPageSections) {
            $modifiedContent = $this->updateHtmlContentLinksForPageSections($contentForPageSections->getHtmlContent(), $insertedPages, $insertedPageSections, $insertedEquipmentTaxonomiesFromLadoc, $insertedPageContents, 'ladoc');
            if (self::FILE_TRANSFER_ACTIVE) {
                $modifiedContent = $this->updateAttachmentsFromContent($modifiedContent, array(), 'page-section-attachment', self::LADOC_DOMAIN, 'ladoc');
                $modifiedContent = $this->updateImagesFromContent($modifiedContent, self::LADOC_DOMAIN, self::PATH_CONTENT_FOLDER, $this->getBaseUrl() . self::MOXIE_MANAGER_PATH);
            }
            $modifiedContent = $this->clearHtmlContents($modifiedContent);

            $contentForPageSections->setHtmlContent($modifiedContent);

            $this->getEntityManager()->persist($contentForPageSections);
        }
        $this->getEntityManager()->flush();
    }
}