<?php

namespace Application\Service;

use Application\Service\AbstractBaseService;
use Application\Repository\ImportingRepository;
use Documentation\Entity\Page;
use Documentation\Entity\PageSection;
use Equipment\Entity\EquipmentTaxonomy;
use Documentation\Entity\HtmlContentPageSection;
use Documentation\Entity\HtmlContentPageInlineSection;
use Documentation\Entity\PageInlineSection;
use Documentation\Entity\DocumentationSection;
use Documentation\Entity\HtmlContentDocumentationSection;
use Equipment\Entity\Equipment;
use Doctrine\Common\Collections\ArrayCollection;
use Sysco\Aurora\Stdlib\DateTime;
use Documentation\Entity\DocumentationSectionAttachment;
use Application\Entity\Organization;
use Quiz\Entity\Exercise;
use Quiz\Entity\Question;
use Quiz\Entity\Option;

class ImportingService extends AbstractBaseService {
    private $baseUrl = ''; //vidum/src/public for system running in localhost.
    private $serverName = ''; //application domain
    
    /**
     * Values to
     */
    private $databaseConfig = null;
    private $importingRepository = null;
    
    /*
     * Valid values
     */
    private $validImageExtensions = array('png', 'jpg', 'jpeg', 'gif');
    private $validMimeTypes = array(
        'image/jpg',
        'image/gif',
        'image/png',
        'image/jpeg',
        'video/mp4', //mp4
        'video/quicktime', //mov
        'video/x-msvideo', //avi
        'video/x-ms-wmv', //wmv
        'video/x-ms-asf ', //wmv
        'application/msword',
        'application/application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.ms-office', // Fileinfo returns this for ppt files
        'application/ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/pdf',
        'application/zip', 'application/octet-stream');

    /**
     * External urls
     */
    const LADOC_DOMAIN = 'http://flo-ladok.vidum.no/';
    const MEDOC_DOMAIN = 'http://flo-medoc.vidum.no/';
    const PATH_CATEGORY_IMAGES_LADOC = 'http://flo-ladok.vidum.no/images/stories/category/%s';
    const PATH_CATEGORY_IMAGES_MEDOC = 'http://flo-medoc.vidum.no/administrator/components/com_storage/category-medoc/%s/%s';
    const PATH_EQUIPMENT_IMAGES_MEDOC = 'http://flo-medoc.vidum.no/administrator/components/com_storage/equipment-medoc/%s/%s';
    const PATH_ATTACHMENT_DOCSECTION_MEDIA_LADOC = 'http://flo-ladok.vidum.no/administrator/components/com_storage/files-ladok/%s/%s';
    const PATH_ATTACHMENT_DOCSECTION_PDF_LADOC = 'http://flo-ladok.vidum.no/index.php?option=com_storage&controller=storage&task=downloadPDF&pdf=%s&big=1&id=%s&lang=nb';
    const PATH_ATTACHMENT_DOCSECTION_MEDIA_MEDOC = 'http://flo-medoc.vidum.no/administrator/components/com_storage/files-medoc/%s/%s';
    const PATH_ATTACHMENT_DOCSECTION_PDF_MEDOC = 'http://flo-medoc.vidum.no/index.php?option=com_storage&controller=storage&task=downloadPDF&pdf=%s&big=1&id=%s&lang=nb';
    const PATH_QUESTION_IMAGES_MEDOC = 'http://flo-medoc.vidum.no/administrator/components/com_training/files/question/%s/%s';
    
    /**
     * Internal paths
     */
    const PATH_EQUIPMENT_TAXONOMY_IMAGE_FOLDER = "./public/content/equipment_taxonomy/";
    const PATH_EQUIPMENT_IMAGE_FOLDER = "./public/content/equipment/";
    const PATH_ATTACHMENTS_FOLDER = "./public/attachment/";
    const PATH_QUESTION_FOLDER = "./public/content/question/";
    const PATH_CONTENT_FOLDER = './public/attachment/moxiemanager/';
    const PATH_FILE_BACKUP_IMPORTING = './data/backup_before_importing.sql';
    const PATH_FILE_EQUIPMENT_QUICKSEARCH = './data/equipments/equipments.json';
    
    /**
     * Internal urls
     */
    const MOXIE_MANAGER_PATH = '/attachment/moxiemanager/';
    
    /**
     * constants for importing task
     */
    const FILE_TRANSFER_ACTIVE = true;
    const MAX_EXECUTION_TIME_TO_GET_RESOURCE = 43200; //seconds
    const PHP_MEMORY_LIMIT = '1024M';
    const REQUIRED_FOR_PASS_EXERCISE = 85;
    const TYPE_PAGE = 0;
    const TYPE_PAGESECTION = 1;
    const TYPE_PAGESUBSECTION = 2;
    const TYPE_PAGEINLINESECTION = 3;
    

    public function getBaseUrl() {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    public function getDatabaseConfig() {
        return $this->databaseConfig;
    }

    public function setDatabaseConfig($databaseConfig) {
        $this->databaseConfig = $databaseConfig;
    }
    
    public function updateServerName(){
        $databaseConfig = $this->getDatabaseConfig();
        if(array_key_exists('server_name', $databaseConfig) && !empty($databaseConfig['server_name'])){
            $this->serverName = $databaseConfig['server_name'];
            return;
        }
        if (isset($_SERVER['SERVER_NAME']) && empty($this->serverName)) {
            $this->serverName = $_SERVER['SERVER_NAME'];
        }
    }

    public function importData() {
        $this->updateServerName();
        
        set_time_limit(self::MAX_EXECUTION_TIME_TO_GET_RESOURCE);
        ini_set('memory_limit', self::PHP_MEMORY_LIMIT);
        
        $insertedOrganizations = $this->importOrganizations(); //medoc

        $insertedEquipmentTaxonomies = $this->importEquipmentTaxonomies(); //ladoc, medoc
        $insertedEquipments = $this->importEquipments($insertedEquipmentTaxonomies, $insertedOrganizations); //ladoc, medoc
        $insertedContentsAndDocumentations = $this->importContentAndDocumentations($insertedEquipments); //ladoc, medoc
        $insertedAttachmentsForDocSections = $this->importAttachmentsForDocSections($insertedContentsAndDocumentations); //ladoc, medoc
        $insertedExercisesQuestionsOptions = $this->importExercises($insertedEquipments, $insertedContentsAndDocumentations); //medoc
        
        $insertedPages = $this->importPages(); //ladoc
        $insertedPageSections = $this->importPageSections($insertedPages); //ladoc
        $insertedPageSubsections = $this->importPageSubSections($insertedPageSections); //ladoc
        $insertedPageInlineSections = $this->importPageInlineSections($insertedPageSubsections); //ladoc
        $insertedPageContents = $this->importPageContents($insertedPages, $insertedPageSections, $insertedPageSubsections, $insertedPageInlineSections); //ladoc
        
        $this->manageHtmlContents($insertedContentsAndDocumentations, $insertedAttachmentsForDocSections, $insertedPages, array_merge($insertedPageSections, $insertedPageSubsections), $insertedEquipmentTaxonomies, $insertedPageContents);
        $this->getEntityManager()->clear();
        
        if(file_exists(self::PATH_FILE_EQUIPMENT_QUICKSEARCH))
            unlink(self::PATH_FILE_EQUIPMENT_QUICKSEARCH);
        
        echo $this->translate("importing task finished");
    }
    
    public function backupData(){
        return $this->makeDatabaseBackup($this->getDatabaseConfig());
    }

    public function restoreData($hours) {
        $this->deleteImportedFilesByLastHours($hours);
        return $this->restoreDatabase($this->getDatabaseConfig());
    }

    private function importOrganizations() {
        $medocAdapter = $this->getMedocAdapter();

        $insertedOrganizations = array();

        $suppliersFromMedoc = $this->getImportingRepository()->getSuppliersFromMedoc($medocAdapter);
        if ($suppliersFromMedoc) {
            foreach ($suppliersFromMedoc as $externalSupplier) {
                $organization = new Organization();
                $organization->setStatus('active');

                if (!empty($externalSupplier['chr_name_supplier']))
                    $organization->setName($externalSupplier['chr_name_supplier']);

                if ($this->isFormattedDate($externalSupplier['date_update']))
                    $organization->setDateUpdate(new DateTime($externalSupplier['date_update']));

                if ($this->isFormattedDate($externalSupplier['date_register']))
                    $organization->setDateAdd(new DateTime($externalSupplier['date_register']));

                if (!empty($externalSupplier['int_phone']))
                    $organization->setPhone($externalSupplier['int_phone']);

                if (!empty($externalSupplier['chr_email']))
                    $organization->setEmail($externalSupplier['chr_email']);

                if (!empty($externalSupplier['url_website']))
                    $organization->setUrl($externalSupplier['url_website']);

                if (!empty($externalSupplier['contact_name']))
                    $organization->setContactPerson($externalSupplier['contact_name']);
                
                $organization->temporalId = $externalSupplier['id_supplier'];

                $this->getEntityManager()->persist($organization);
                array_push($insertedOrganizations, $organization);
            }

            $this->getEntityManager()->flush();
        }

        return $insertedOrganizations;
    }

    protected function importPages() {
        $ladocAdapter = $this->getLadocAdapter();
        $ladocPages = $this->getImportingRepository()->getPagesFromLadoc($ladocAdapter);

        if ($ladocPages) {
            $insertedPages = array();
            foreach ($ladocPages as $ladocPage) {
                $page = new Page();
                $page->setName($ladocPage['name']);

                $page->temporalId = $ladocPage['id'];
                $page->temporalLink = $this->getArticleIdFromLinkMenu($ladocPage['link']);
                if (empty($page->temporalLink)) {
                    $infoLink = $this->getCategoryOrSectionIdFromLinkMenu($ladocPage['link']);
                    if ($infoLink) {
                        $page->temporalView = $infoLink['view'];
                        $page->temporalViewId = $infoLink['id'];
                    }
                }
                $page->temporalType = self::TYPE_PAGE;
                $page->setApplication($ladocPage['application']);
                $page->temporalContent = 'introtext';
                
                $this->getEntityManager()->persist($page);

                if ($page->temporalLink > 0) {
                    $newSection = new PageSection();
                    $newSection->setLabel("Hovedside");
                    $newSection->setPage($page);
                    $newSection->setApplication($page->getApplication());
                    $newSection->temporalContent = 'introtext';

                    $this->getEntityManager()->persist($newSection);
                    $page->temporalSection = $newSection;
                }

                array_push($insertedPages, $page);
            }

            $this->getEntityManager()->flush();
            
            return $insertedPages;
        }

        return null;
    }

    protected function importPageSections($insertedPages) {
        $ladocAdapter = $this->getLadocAdapter();
        $insertedSections = array();
        $ladocSections = $this->getImportingRepository()->getSectionsFromLadoc($ladocAdapter);
        $executeFlush = false;
        
        if ($ladocSections) {
            foreach ($ladocSections as $ladocSection) {
                if ($ladocSection['parent'] > 0) {
                    $parentPage = $this->findObjectFromArray($insertedPages, 'temporalId', $ladocSection['parent']);
                    if ($parentPage) {
                        $pageSection = new PageSection();
                        $pageSection->setLabel($ladocSection['name']);
                        $pageSection->setApplication($ladocSection['application']);
                        if(!empty($ladocSection['ordering']))
                            $pageSection->setSectionOrder($ladocSection['ordering']);

                        $pageSection->temporalId = $ladocSection['id'];
                        $pageSection->temporalLink = $this->getArticleIdFromLinkMenu($ladocSection['link']);
                        if (empty($pageSection->temporalLink)) {
                            $infoLink = $this->getCategoryOrSectionIdFromLinkMenu($ladocSection['link']);
                            if ($infoLink) {
                                $pageSection->temporalView = $infoLink['view'];
                                $pageSection->temporalViewId = $infoLink['id'];
                            }
                        }
                        $pageSection->temporalParent = $ladocSection['parent'];
                        $pageSection->temporalType = self::TYPE_PAGESECTION;
                        $pageSection->temporalContent = 'introtext';

                        $pageSection->setPage($parentPage);
                        $executeFlush = true;

                        $this->getEntityManager()->persist($pageSection);
                        array_push($insertedSections, $pageSection);
                    }
                }
            }
            
        }
        
        $sectionids = $this->getArrayOfSpecificField($insertedPages, 'temporalViewId', array('key' => 'temporalView', 'value' => 'section'));
        if(count($sectionids) > 0){
            $ladocSections2 = $this->getImportingRepository()->getSections2FromLadoc($ladocAdapter, $sectionids);
            if($ladocSections2){
                foreach ($ladocSections2 as $ladocSection){
                    $parentPage = $this->findObjectFromArray($insertedPages, 
                            array('temporalView', 'temporalViewId'), array('section', $ladocSection['section']));
                    if($parentPage){
                        $pageSection = new PageSection();
                        $pageSection->setLabel($ladocSection['title']);
                        if(!empty($ladocSection['ordering']))
                            $pageSection->setSectionOrder($ladocSection['ordering']);
                        $pageSection->setPage($parentPage);
                        $pageSection->setApplication($ladocSection['application']);
                        
                        $pageSection->temporalId = 0;
                        $pageSection->temporalLink = null;
                        $pageSection->temporalView = 'category';
                        $pageSection->temporalViewId = $ladocSection['id'];
                        $pageSection->temporalType = self::TYPE_PAGESECTION;
                        $pageSection->temporalContent = 'fulltext';
                        
                        $executeFlush = true;
                        $this->getEntityManager()->persist($pageSection);
                        array_push($insertedSections, $pageSection);
                    }
                }
            }
            
            $ladocSections3 = $this->getImportingRepository()->getSections3FromLadoc($ladocAdapter, $sectionids);
            if($ladocSections3){
                foreach($ladocSections3 as $ladocSection){
                    $parentPage = $this->findObjectFromArray($insertedPages, 
                            array('temporalView', 'temporalViewId'), array('section', $ladocSection['id']));
                    if($parentPage){
                        $articleIds = $this->getArticleIdsFromContent($ladocSection['description']);
                        if(count($articleIds) > 0){
                            $contents = $this->getImportingRepository()->getContentsFromLadoc($ladocAdapter, $articleIds);
                            foreach($contents as $content){
                                $pageSection = new PageSection();
                                $pageSection->setLabel($content['title']);
                                $pageSection->setApplication($ladocSection['application']);

                                $pageSection->temporalId = 0;
                                $pageSection->temporalLink = $content['id'];
                                $pageSection->temporalParent = null;
                                $pageSection->temporalType = self::TYPE_PAGESECTION;
                                $pageSection->temporalContent = 'fulltext';

                                $pageSection->setPage($parentPage);
                                $executeFlush = true;

                                $this->getEntityManager()->persist($pageSection);
                                array_push($insertedSections, $pageSection);
                            }
                        }
                    }
                }
            }
        }
        
        if ($executeFlush)
            $this->getEntityManager()->flush();

        return count($insertedSections) > 0 ? $insertedSections : null;
    }

    protected function importPageSubSections($insertedSections) {
        $ladocAdapter = $this->getLadocAdapter();
        $ladocSubSections = $this->getImportingRepository()->getSubSectionsFromLadoc($ladocAdapter);
        if ($ladocSubSections) {
            $insertedSubSections = array();
            $executeFlush = false;
            foreach ($ladocSubSections as $ladocSubSection) {
                if ($ladocSubSection['parent'] > 0) {
                    $parentSection = $this->findObjectFromArray($insertedSections, 'temporalId', $ladocSubSection['parent']);
                    if ($parentSection) {
                        $pageSection = new PageSection();
                        $pageSection->setLabel($ladocSubSection['name']);
                        $pageSection->setApplication($ladocSubSection['application']);
                        if(!empty($ladocSubSection['ordering']))
                            $pageSection->setSectionOrder($ladocSubSection['ordering']);

                        $pageSection->temporalId = $ladocSubSection['id'];
                        $pageSection->temporalLink = $this->getArticleIdFromLinkMenu($ladocSubSection['link']);
                        if (empty($pageSection->temporalLink)) {
                            $infoLink = $this->getCategoryOrSectionIdFromLinkMenu($ladocSubSection['link']);
                            if ($infoLink) {
                                $pageSection->temporalView = $infoLink['view'];
                                $pageSection->temporalViewId = $infoLink['id'];
                            }
                        }
                        $pageSection->temporalParent = $ladocSubSection['parent'];
                        $pageSection->temporalType = self::TYPE_PAGESUBSECTION;
                        $pageSection->temporalContent = 'introtext';

                        $pageSection->setParent($parentSection);
                        $pageSection->setPage($parentSection->getPage());
                        $executeFlush = true;

                        $this->getEntityManager()->persist($pageSection);
                        array_push($insertedSubSections, $pageSection);
                    }
                }
            }

            if ($executeFlush)
                $this->getEntityManager()->flush();

            return $insertedSubSections;
        }

        return null;
    }

    protected function importPageInlineSections($insertedSubsections) {
        $ladocAdapter = $this->getLadocAdapter();
        $ladocInlineSections = $this->getImportingRepository()->getInlineSectionsFromLadoc($ladocAdapter);
        if ($ladocInlineSections) {
            $insertedInlineSections = array();
            $executeFlush = false;
            foreach ($ladocInlineSections as $ladocInlineSection) {
                if ($ladocInlineSection['parent'] > 0) {
                    $parentSubSection = $this->findObjectFromArray($insertedSubsections, 'temporalId', $ladocInlineSection['parent']);
                    if ($parentSubSection) {
                        $pageInlineSection = new PageInlineSection();
                        $pageInlineSection->setLabel($ladocInlineSection['name']);
                        $pageInlineSection->setApplication($ladocInlineSection['application']);
                        if(!empty($ladocInlineSection['ordering']))
                            $pageInlineSection->setSectionOrder($ladocInlineSection['ordering']);

                        $pageInlineSection->temporalId = $ladocInlineSection['id'];
                        $pageInlineSection->temporalLink = $this->getArticleIdFromLinkMenu($ladocInlineSection['link']);
                        $pageInlineSection->temporalParent = $ladocInlineSection['parent'];
                        $pageInlineSection->temporalType = self::TYPE_PAGEINLINESECTION;
                        $pageInlineSection->temporalContent = 'introtext';

                        $pageInlineSection->setPageSection($parentSubSection);
                        $executeFlush = true;

                        $this->getEntityManager()->persist($pageInlineSection);
                        array_push($insertedInlineSections, $pageInlineSection);
                    }
                }
            }

            if ($executeFlush)
                $this->getEntityManager()->flush();

            return $insertedInlineSections;
        }
        return null;
    }

    protected function importPageContents($insertedPages, &$insertedSections, $insertedSubSections, $insertedInlineSections) {
        $ladocAdapter = $this->getLadocAdapter();

        $items = array();
        if (is_array($insertedPages) && count($insertedPages) > 0)
            $items = array_merge($items, $insertedPages);
        if (is_array($insertedSections) && count($insertedSections) > 0)
            $items = array_merge($items, $insertedSections);
        if (is_array($insertedSubSections) && count($insertedSubSections) > 0)
            $items = array_merge($items, $insertedSubSections);
        if (is_array($insertedInlineSections) && count($insertedInlineSections) > 0)
            $items = array_merge($items, $insertedInlineSections);

        $ids = $this->getArrayOfSpecificField($items, "temporalLink");

        $catids = $this->getArrayOfSpecificField($items, "temporalViewId", array('key' => 'temporalView', 'value' => 'category'));
        $catids = count($catids) > 0 ? $catids : null;

        $contentsPage = $this->getImportingRepository()->getContentsFromLadoc($ladocAdapter, $ids, $catids);
        if ($contentsPage) {
            $insertedContentsPage = array();
            $executeFlush = false;
            foreach ($contentsPage as $content) {
                $related = $this->findObjectFromArray($items, "temporalLink", $content['id']);
                if (!$related) {
                    $related = $this->findObjectFromArray($items, array("temporalView", "temporalViewId"), array("category", $content['catid']));
                    if ($related && $related->temporalType == self::TYPE_PAGESECTION) {
                        $pageSection = new PageSection();
                        $pageSection->setParent($related);
                        $pageSection->setApplication($related->getApplication());
                        if (!empty($content['title']))
                            $pageSection->setLabel($content['title']);
                        $pageSection->setPage($related->getPage());
                        $pageSection->temporalType = self::TYPE_PAGESUBSECTION;
                        $pageSection->temporalContent = 'fulltext';

                        $this->getEntityManager()->persist($pageSection);

                        array_push($insertedSections, $pageSection);

                        $related = $pageSection;
                    }else {
                        $related = null;
                    }
                }

                if ($related) {
                    $executeFlush = true;
                    switch ($related->temporalType) {
                        case self::TYPE_PAGE:
                            $htmlContent = new HtmlContentPageSection();
                            $htmlContent->setPageSection($related->temporalSection);
                            break;
                        case self::TYPE_PAGESECTION:
                        case self::TYPE_PAGESUBSECTION:
                            $htmlContent = new HtmlContentPageSection();
                            $htmlContent->setPageSection($related);
                            break;
                        case self::TYPE_PAGEINLINESECTION:
                            $htmlContent = new HtmlContentPageInlineSection();
                            $htmlContent->setPageInlineSection($related);
                            break;
                    }

                    $titleContent = $content['title'] ? sprintf('<h2>%s</h2>', $content['title']) : '';

                    $htmlContent->setHtmlContent($titleContent . $content[$related->temporalContent]);

                    if ($this->isFormattedDate($content['created']))
                        $htmlContent->setDateAdd($content['created']);

                    if ($this->isFormattedDate($content['modified']))
                        $htmlContent->setDateUpdate($content['modified']);

                    $htmlContent->temporalId = $content['id'];

                    $this->getEntityManager()->persist($htmlContent);
                    array_push($insertedContentsPage, $htmlContent);
                }
            }

            if ($executeFlush)
                $this->getEntityManager()->flush();

            return $insertedContentsPage;
        }

        return null;
    }

    protected function importEquipmentTaxonomies() {
        $ladocAdapter = $this->getLadocAdapter();
        $medocAdapter = $this->getMedocAdapter();

        $insertedEquipmentTaxonomiesFromLadoc = array();
        $insertedEquipmentTaxonomiesFromMedoc = array();

        $ladocCategories = $this->getImportingRepository()->getCategoriesFromLadoc($ladocAdapter);
        if ($ladocCategories) {
            $columnsSprintfLadoc = array('chr_description');
            $insertedEquipmentTaxonomiesFromLadoc =
                    $this->manageImportEquipmentTaxonomies($ladocCategories, self::PATH_CATEGORY_IMAGES_LADOC, $columnsSprintfLadoc);
        }

        $medocCategories = $this->getImportingRepository()->getCategoriesFromMedoc($medocAdapter);
        if ($medocCategories) {
            $columnsSprintfMedoc = array('id_category', 'chr_description');
            $insertedEquipmentTaxonomiesFromMedoc =
                    $this->manageImportEquipmentTaxonomies($medocCategories, self::PATH_CATEGORY_IMAGES_MEDOC, $columnsSprintfMedoc);
        }

        return array('ladoc' => $insertedEquipmentTaxonomiesFromLadoc,
            'medoc' => $insertedEquipmentTaxonomiesFromMedoc);
    }

    protected function importEquipments($insertedEquipmentTaxonomies, $insertedOrganizations) {
        $ladocAdapter = $this->getLadocAdapter();
        $medocAdapter = $this->getMedocAdapter();

        $insertedTaxonomiesFromLadoc = $insertedEquipmentTaxonomies['ladoc'];
        $insertedTaxonomiesFromMedoc = $insertedEquipmentTaxonomies['medoc'];

        $insertedEquipmentsFromLadoc = array();
        $insertedEquipmentsFromMedoc = array();

        $ladocEquipments = $this->getImportingRepository()->getEquipmentsFromLadoc($ladocAdapter);
        if ($ladocEquipments) {
            $insertedEquipmentsFromLadoc = $this->manageImportEquipments($insertedTaxonomiesFromLadoc, $ladocEquipments);
        }

        $medocEquipments = $this->getImportingRepository()->getEquipmentsFromMedoc($medocAdapter);
        if ($medocEquipments) {
            $insertedEquipmentsFromMedoc = $this->manageImportEquipments($insertedTaxonomiesFromMedoc, $medocEquipments, self::PATH_EQUIPMENT_IMAGES_MEDOC,
                   $insertedOrganizations);
        }

        return array("ladoc" => $insertedEquipmentsFromLadoc,
            "medoc" => $insertedEquipmentsFromMedoc);
    }

    private function importContentAndDocumentations($insertedEquipments) {
        $ladocAdapter = $this->getLadocAdapter();
        $medocAdapter = $this->getMedocAdapter();

        $insertedEquipmentsFromLadoc = $insertedEquipments['ladoc'];
        $insertedEquipmentsFromMedoc = $insertedEquipments['medoc'];

        $insertedDataFromLadoc = array();
        $insertedDataFromMedoc = array();

        $ladocEquipmentsDetails = $this->getImportingRepository()->getEquipmentsDetailsFromLadoc($ladocAdapter);
        if ($ladocEquipmentsDetails) {
            $insertedDataFromLadoc = $this->manageImportHtmlDocSections($insertedEquipmentsFromLadoc, $ladocEquipmentsDetails);
        }

        $medocEquipmentsDetails = $this->getImportingRepository()->getEquipmentsDetailsFromMedoc($medocAdapter);
        if ($medocEquipmentsDetails) {
            $insertedDataFromMedoc = $this->manageImportHtmlDocSections($insertedEquipmentsFromMedoc, $medocEquipmentsDetails);
        }

        return array('ladoc' => $insertedDataFromLadoc, 'medoc' => $insertedDataFromMedoc);
    }

    private function importAttachmentsForDocSections($insertedDocSections) {
        $ladocAdapter = $this->getLadocAdapter();
        $medocAdapter = $this->getMedocAdapter();

        $insertedDocSectionsFromLadoc = $insertedDocSections['ladoc']['documentations'];
        $insertedDocSectionsFromMedoc = $insertedDocSections['medoc']['documentations'];

        $insertedAttachmentsFromLadoc = array();
        $insertedAttachmentsFromMedoc = array();

        $attachmentsFromLadoc = $this->getImportingRepository()->getEquipmentsFilesFromLadoc($ladocAdapter);
        if ($attachmentsFromLadoc) {
            $insertedAttachmentsFromLadoc = $this->manageImportAttachmentsForDocSections($insertedDocSectionsFromLadoc, $attachmentsFromLadoc);
        }

        $attachmentsFromMedoc = $this->getImportingRepository()->getEquipmentsFilesFromMedoc($medocAdapter);
        if ($attachmentsFromMedoc) {
            $insertedAttachmentsFromMedoc = $this->manageImportAttachmentsForDocSections($insertedDocSectionsFromMedoc, $attachmentsFromMedoc, false);
        }

        return array('ladoc' => $insertedAttachmentsFromLadoc, 'medoc' => $insertedAttachmentsFromMedoc);
    }

    private function importExercises($insertedEquipments, $insertedContentsAndDocumentations) {
        $medocAdapter = $this->getMedocAdapter();

        $insertedExercises = array();
        $insertedQuestions = array();
        $insertedOptions = array();

        $insertedEquipmentsFromMedoc = $insertedEquipments['medoc'];
        $insertedDocumentationSections = $insertedContentsAndDocumentations['medoc']['documentations'];

        $executeFlush = false;
        $exercisesFromMedoc = $this->getImportingRepository()->getExamnsFromMedoc($medocAdapter);
        if ($exercisesFromMedoc) {
            foreach ($exercisesFromMedoc as $externalExercise) {
                $exercise = new Exercise();

                $equipmentFound = $this->findObjectFromArray($insertedEquipmentsFromMedoc, 'temporalEquipmentId', $externalExercise['id_equipment']);
                if ($equipmentFound) {
                    $exercise->setEquipment($equipmentFound);
                    $exercise->setApplication($externalExercise['application']);

                    if (!empty($externalExercise['chr_title']))
                        $exercise->setName($externalExercise['chr_title']);

                    $exercise->setRequiredForPass(self::REQUIRED_FOR_PASS_EXERCISE);

                    $exercise->temporalId = $externalExercise['id_exam'];

                    $this->getEntityManager()->persist($exercise);
                    array_push($insertedExercises, $exercise);

                    $executeFlush = true;
                }
            }

            if ($executeFlush) {
                $this->getEntityManager()->flush();
                $executeFlush = false;
            }
        }

        if (count($insertedExercises) > 0) {
            $questionsFromMedoc = $this->getImportingRepository()->getQuestionsFromMedoc($medocAdapter);
            if ($questionsFromMedoc) {
                foreach ($questionsFromMedoc as $externalQuestion) {
                    $exerciseFound = $this->findObjectFromArray($insertedExercises, 'temporalId', $externalQuestion['id_exam']);
                    if ($exerciseFound) {
                        $question = new Question();
                        $question->setExercise($exerciseFound);
                        $question->setApplication($externalQuestion['application']);

                        if (!empty($externalQuestion['id_question_type']))
                            $question->setType($externalQuestion['id_question_type'] == '1' ? 'one' : 'many');
                        else
                            $question->setType('one');

                        $questionInfo = $this->getQuestionInfo($externalQuestion['chr_question']);
                        $question->setSubject($questionInfo['subject']);
                        $question->setExplanatoryText($questionInfo['explanatoryText']);
                        $question->setQuestionText($questionInfo['questionText']);

                        if (!empty($externalQuestion['int_weight']))
                            $question->setWeight($externalQuestion['int_weight']);

                        if (!empty($externalQuestion['int_indexorder']))
                            $question->setOrderNumber($externalQuestion['int_indexorder']);

                        $equipmentDetailId = $this->getDocSectionIdFromResourceLink($externalQuestion['url_resource_link']);
                        if ($equipmentDetailId) {
                            $docSectionFound = $this->findObjectFromArray($insertedDocumentationSections, 'temporalId', $equipmentDetailId);
                            if ($docSectionFound) {
                                $equipmentId = $docSectionFound->getEquipment()->getEquipmentId();
                                $docSectionId = $docSectionFound->getSectionId();
                                $question->setResourceLink($this->serverName . $this->getBaseUrl() .
                                        sprintf('/medoc/documentation/index/id/%s/sectionId/%s', $equipmentId, $docSectionId));
                            }
                        }

                        if (!empty($externalQuestion['chr_url_image']) && self::FILE_TRANSFER_ACTIVE) {
                            $externalImage = $externalQuestion['chr_url_image'];
                            if ($this->isImageName($externalImage)) {
                                $urlResourceImage = $this->buildExternalResourceUrl(self::PATH_QUESTION_IMAGES_MEDOC, $externalQuestion, array('id_question', 'chr_url_image'));
                                $image = $this->manageResourceFile(self::PATH_QUESTION_FOLDER, $urlResourceImage, $this->image['width']);
                                if ($image) {
                                    $question->setImage($image);
                                }
                            }
                        }

                        $question->temporalId = $externalQuestion['id_question'];

                        $this->getEntityManager()->persist($question);
                        array_push($insertedQuestions, $question);
                        $executeFlush = true;
                    }
                }

                if ($executeFlush) {
                    $this->getEntityManager()->flush();
                    $executeFlush = false;
                }
            }
        }

        if (count($insertedQuestions) > 0) {
            $optionsFromMedoc = $this->getImportingRepository()->getOptionsFromMedoc($medocAdapter);
            foreach ($optionsFromMedoc as $externalOption) {
                $questionFound = $this->findObjectFromArray($insertedQuestions, 'temporalId', $externalOption['id_question']);
                if ($questionFound) {
                    $option = new Option();
                    $option->setQuestion($questionFound);

                    if (!empty($externalOption['chr_option']))
                        $option->setOptionText($externalOption['chr_option']);

                    if (!empty($externalOption['bool_is_correct']))
                        $option->setIsCorrect($externalOption['bool_is_correct'] == '1' ? true : false);

                    $option->temporalId = $externalOption['id_option'];

                    $this->getEntityManager()->persist($option);
                    array_push($insertedOptions, $option);
                    $executeFlush = true;
                }
            }

            if ($executeFlush) {
                $this->getEntityManager()->flush();
            }
        }

        return array(
            'exercises' => $insertedExercises,
            'questions' => $insertedQuestions,
            'options' => $insertedOptions
        );
    }

    private function manageImportAttachmentsForDocSections($insertedDocSections, $externalAttachments, $isLadoc = true) {
        $insertedAttachments = array();
        $executeFlush = false;
        foreach ($externalAttachments as $externalAttachment) {
            if (!empty($externalAttachment['chr_title']) && !empty($externalAttachment['id_file_type']) && self::FILE_TRANSFER_ACTIVE) {
                $columnsSprintf = array();
                $basePathExternalResources = $this->getResourceUrlFormatAttachmentsForDocSections($externalAttachment['id_file_type'], $columnsSprintf, $isLadoc);
                $urlExternalResource = $this->buildExternalResourceUrl($basePathExternalResources, $externalAttachment, $columnsSprintf);
                $filename = $this->manageResourceFile(self::PATH_ATTACHMENTS_FOLDER, $urlExternalResource, null, false);
                if ($filename) {
                    $attachment = new DocumentationSectionAttachment();

                    $attachment->setFile($filename);
                    $mimeType = mime_content_type(self::PATH_ATTACHMENTS_FOLDER . $filename);
                    $attachment->setMimeType($mimeType);

                    $docSection = $this->findObjectFromArray($insertedDocSections, 'temporalId', $externalAttachment['id_equipment_detail']);
                    if ($docSection) {
                        $attachment->setDocumentationSection($docSection);
                        $attachment->setApplication($externalAttachment['application']);

                        if (!empty($externalAttachment['chr_title_publish']))
                            $attachment->setTitle($externalAttachment['chr_title_publish']);

                        if (!empty($externalAttachment['txt_description']))
                            $attachment->setDescription($externalAttachment['txt_description']);

                        if ($this->isFormattedDate($externalAttachment['date_registered']))
                            $attachment->setDateAdd(new DateTime($externalAttachment['date_registered']));

                        $attachment->temporalId = $externalAttachment['id_equipment_file'];

                        $this->getEntityManager()->persist($attachment);
                        array_push($insertedAttachments, $attachment);

                        $executeFlush = true;
                    }
                }
            }
        }

        if ($executeFlush)
            $this->getEntityManager()->flush();

        return $insertedAttachments;
    }

    protected function manageImportEquipmentTaxonomies($externalCategories, $basePathExternalImages, $columnsSprintf) {
        $insertedEquipmentTaxonomies = array();
        foreach ($externalCategories as $externalCategory) {
            $equipmentTaxonomy = new EquipmentTaxonomy();
            $equipmentTaxonomy->setType('category');
            $equipmentTaxonomy->setStatus('active');

            if (!empty($externalCategory['chr_name']))
                $equipmentTaxonomy->setName($externalCategory['chr_name']);

            if (!empty($externalCategory['chr_description']) && self::FILE_TRANSFER_ACTIVE) {
                $externalImage = $externalCategory['chr_description'];
                if ($this->isImageName($externalImage)) {
                    $urlResourceImage = $this->buildExternalResourceUrl($basePathExternalImages, $externalCategory, $columnsSprintf);
                    $featuredImage = $this->manageResourceFile(self::PATH_EQUIPMENT_TAXONOMY_IMAGE_FOLDER, $urlResourceImage, $this->image['width']);
                    if ($featuredImage) {
                        $equipmentTaxonomy->setFeaturedImage($featuredImage);
                    }
                }
            }

            if (!empty($externalCategory['content']))
                $equipmentTaxonomy->setDescription($externalCategory['content']);

            if (!empty($externalCategory['int_indexorder']))
                $equipmentTaxonomy->setOrder($externalCategory['int_indexorder']);

            if (!empty($externalCategory['int_indexlevel']))
                $equipmentTaxonomy->setLevel($externalCategory['int_indexlevel']);
            
            $equipmentTaxonomy->setApplication($externalCategory['application']);

            $equipmentTaxonomy->tempIdCategory = $externalCategory['id_category'];
            $equipmentTaxonomy->tempParentCategory = $externalCategory['parent_category'];
            $equipmentTaxonomy->tempToUpdateParent = $externalCategory['parent_category'] > 0;

            $this->getEntityManager()->persist($equipmentTaxonomy);
            array_push($insertedEquipmentTaxonomies, $equipmentTaxonomy);
        }

        $this->getEntityManager()->flush();

        $executeFlush = false;
        foreach ($insertedEquipmentTaxonomies as $equipmentTaxonomy) {
            if ($equipmentTaxonomy->tempToUpdateParent) {
                $parentEquipmentTaxonomy = $this->findObjectFromArray($insertedEquipmentTaxonomies, 'tempIdCategory', $equipmentTaxonomy->tempParentCategory);
                if ($parentEquipmentTaxonomy) {
                    $executeFlush = true;
                    $equipmentTaxonomy->setParent($parentEquipmentTaxonomy);
                    $this->getEntityManager()->persist($equipmentTaxonomy);
                }
            }
        }

        if ($executeFlush)
            $this->getEntityManager()->flush();

        return $insertedEquipmentTaxonomies;
    }

    private function manageImportHtmlDocSections($insertedEquipments, $externalEquipmentsDetails) {
        $insertedDocSections = array();
        $insertedHtmlDocSections = array();
        $executeFlush = false;
        foreach ($externalEquipmentsDetails as $externalEquipmentDetail) {
            $documentation = new DocumentationSection();

            $equipmentFound = $this->findObjectFromArray($insertedEquipments, 'temporalEquipmentId', $externalEquipmentDetail['id_equipment']);
            if ($equipmentFound) {
                $documentation->setEquipment($equipmentFound);
                $documentation->setApplication($externalEquipmentDetail['application']);

                if (!empty($externalEquipmentDetail['chr_title'])) {
                    $documentation->setLabel($externalEquipmentDetail['chr_title']);
                }
                
                if(!empty($externalEquipmentDetail['int_indexorder'])){
                    $documentation->setSectionOrder($externalEquipmentDetail['int_indexorder']);
                }

                $documentation->temporalId = $externalEquipmentDetail['id_equipment_detail'];
                $documentation->temporalParentId = $externalEquipmentDetail['parent_equipment_detail'];
                $documentation->temporalUpdateParent = $externalEquipmentDetail['parent_equipment_detail'] > 0;
                $documentation->temporalEquipmentId = $externalEquipmentDetail['id_equipment'];

                $this->getEntityManager()->persist($documentation);
                array_push($insertedDocSections, $documentation);

                $htmlDocumentationSection = new HtmlContentDocumentationSection();
                $htmlDocumentationSection->setDocumentationSection($documentation);

                if ($this->isFormattedDate($externalEquipmentDetail['date_register']))
                    $htmlDocumentationSection->setDateAdd($externalEquipmentDetail['date_register']);

                if ($this->isFormattedDate($externalEquipmentDetail['date_update']))
                    $htmlDocumentationSection->setDateUpdate($externalEquipmentDetail['date_update']);

                if (!empty($externalEquipmentDetail['txt_description'])) {
                    $htmlDocumentationSection->setHtmlContent($externalEquipmentDetail['txt_description']);
                }

                $this->getEntityManager()->persist($htmlDocumentationSection);
                array_push($insertedHtmlDocSections, $htmlDocumentationSection);

                $executeFlush = true;
            }
        }

        if ($executeFlush) {
            $this->getEntityManager()->flush();
            $executeFlush = false;
        }

        foreach ($insertedDocSections as $documentation) {
            if ($documentation->temporalUpdateParent) {
                $parentFound = $this->findObjectFromArray($insertedDocSections, 'temporalId', $documentation->temporalParentId);
                if ($parentFound) {
                    $executeFlush = true;
                    $documentation->setParent($parentFound);

                    $this->getEntityManager()->persist($documentation);
                }
            }
        }

        if ($executeFlush)
            $this->getEntityManager()->flush();

        return array('documentations' => $insertedDocSections, 'contents' => $insertedHtmlDocSections);
    }

    protected function manageImportEquipments($insertedTaxonomies, $externalEquipments, $basePathExternalImages = null, $insertedOrganizations = null) {
        $insertedEquipments = array();
        foreach ($externalEquipments as $externalEquipment) {
            $equipment = new Equipment();
            $equipment->setStatus('active');

            if (!empty($externalEquipment['chr_name']))
                $equipment->setTitle($externalEquipment['chr_name']);

            if (!empty($externalEquipment['txt_description']))
                $equipment->setDescription($externalEquipment['txt_description']);

            if (!empty($externalEquipment['chr_code']))
                $equipment->setCode($externalEquipment['chr_code']);

            if ($this->isFormattedDate($externalEquipment['date_register']))
                $equipment->setDateAdd(new DateTime($externalEquipment['date_register']));

            if ($this->isFormattedDate($externalEquipment['date_update']))
                $equipment->setDateUpdate(new DateTime($externalEquipment['date_update']));
            
            if($insertedOrganizations && !empty($externalEquipment['id_supplier'])){
                $organizationFound = $this->findObjectFromArray($insertedOrganizations, 'temporalId', $externalEquipment['id_supplier']);
                if($organizationFound)
                    $equipment->setVendor ($organizationFound);
            }

            if (array_key_exists('img_equipment', $externalEquipment) && $basePathExternalImages && self::FILE_TRANSFER_ACTIVE) {
                $externalImage = $externalEquipment['img_equipment'];
                if ($this->isImageName($externalImage)) {
                    $urlResourceImage = $this->buildExternalResourceUrl($basePathExternalImages, $externalEquipment, array('id_equipment', 'img_equipment'));
                    $featureImage = $this->manageResourceFile(self::PATH_EQUIPMENT_IMAGE_FOLDER, $urlResourceImage, $this->image['width']);
                    if ($featureImage) {
                        $equipment->setFeatureImage($featureImage);
                    }
                }
            }

            $equipment->temporalEquipmentId = $externalEquipment['id_equipment'];

            $equipmentTaxonomy = $this->findObjectFromArray($insertedTaxonomies, 'tempIdCategory', $externalEquipment['id_category']);
            if ($equipmentTaxonomy) {
                $collection = new ArrayCollection(array($equipmentTaxonomy));
                $equipment->setEquipmentTaxonomy($collection);
            }

            $equipment->setInstanceType(Equipment::INSTANCE_TYPE_STANDARD);
            $equipment->setApplication($externalEquipment['application']);

            $this->getEntityManager()->persist($equipment);
            array_push($insertedEquipments, $equipment);
        }

        $this->getEntityManager()->flush();

        return $insertedEquipments;
    }

    protected function manageHtmlContents($insertedContentsAndDocumentations, $insertedAttachmentsForDocSections, $insertedPages, $insertedPageSections, $insertedEquipmentTaxonomies, $insertedPageContents) {
        $insertedDocSectionsFromLadoc = $insertedContentsAndDocumentations['ladoc']['documentations'];
        $insertedDocSectionsFromMedoc = $insertedContentsAndDocumentations['medoc']['documentations'];
        $insertedContentsForDocSectionsFromLadoc = $insertedContentsAndDocumentations['ladoc']['contents'];
        $insertedContentsForDocSectionsFromMedoc = $insertedContentsAndDocumentations['medoc']['contents'];
        $insertedAttachmentsForDocSectionsFromLadoc = $insertedAttachmentsForDocSections['ladoc'];
        $insertedAttachmentsForDocSectionsFromMedoc = $insertedAttachmentsForDocSections['medoc'];
        $insertedEquipmentTaxonomiesFromLadoc = $insertedEquipmentTaxonomies['ladoc'];
        
        $this->manageContentForDocSections($insertedContentsForDocSectionsFromLadoc, $insertedAttachmentsForDocSectionsFromLadoc, $insertedDocSectionsFromLadoc, self::LADOC_DOMAIN, 'ladoc');
        $this->manageContentForDocSections($insertedContentsForDocSectionsFromMedoc, $insertedAttachmentsForDocSectionsFromMedoc, $insertedDocSectionsFromMedoc, self::MEDOC_DOMAIN, 'medoc');
        
        foreach ($insertedPageContents as $contentForPageSections) {
            $modifiedContent = $this->updateHtmlContentLinksForPageSections($contentForPageSections->getHtmlContent(), $insertedPages, $insertedPageSections, $insertedEquipmentTaxonomiesFromLadoc, $insertedPageContents, 'ladoc');
            if (self::FILE_TRANSFER_ACTIVE) {
                $modifiedContent = $this->updateAttachmentsFromContent($modifiedContent, $insertedAttachmentsForDocSectionsFromLadoc, 'page-section-attachment', self::LADOC_DOMAIN, 'ladoc');
                $modifiedContent = $this->updateImagesFromContent($modifiedContent, self::LADOC_DOMAIN, self::PATH_CONTENT_FOLDER, $this->getBaseUrl() . self::MOXIE_MANAGER_PATH);
            }
            $modifiedContent = $this->clearHtmlContents($modifiedContent);

            $contentForPageSections->setHtmlContent($modifiedContent);

            $this->getEntityManager()->persist($contentForPageSections);
        }
        $this->getEntityManager()->flush();
    }

    private function manageContentForDocSections($insertedContentsForDocSections, $insertedAttachmentsForDocSections, $insertedDocSections, $externalDomain, $detinationApp) {
        foreach ($insertedContentsForDocSections as $contentForDocSection) {
            $modifiedContent = $this->updateHtmlContentLinksForDocSections($contentForDocSection->getHtmlContent(), $insertedDocSections, $detinationApp);
            if (self::FILE_TRANSFER_ACTIVE) {
                $modifiedContent = $this->updateAttachmentsFromContent($modifiedContent, $insertedAttachmentsForDocSections, 'documentation-section-attachment', $externalDomain, $detinationApp);
                $modifiedContent = $this->updateImagesFromContent($modifiedContent, $externalDomain, self::PATH_CONTENT_FOLDER, $this->getBaseUrl() . self::MOXIE_MANAGER_PATH);
            }
            $modifiedContent = $this->clearHtmlContents($modifiedContent);

            $contentForDocSection->setHtmlContent($modifiedContent);

            $this->getEntityManager()->persist($contentForDocSection);
        }
    }

    /**
     * Get an array of values about specific field of an array of objects
     * @param array $rows
     * @param string $field
     * @param array|null $criteria format: array('key' => 'propertyA', 'value' => 'somevalue')
     * @return array
     */
    private function getArrayOfSpecificField($rows, $field, $criteria = null) {
        $data = array();
        foreach ($rows as $row) {
            if (property_exists($row, $field) && !empty($row->$field)) {
                if($criteria){
                    if(property_exists($row, $criteria['key']) && $row->$criteria['key'] == $criteria['value']){
                        array_push($data, $row->$field);
                    }
                }else{
                    array_push($data, $row->$field);
                }
            }
        }
        return $data;
    }

    /**
     * get the article id from a specific format text (column 'link' from ldk_menu)
     * @param string $link
     * @return integer|null
     */
    private function getArticleIdFromLinkMenu($link) {
        if (strpos($link, "view=article")) {
            $matches = array();
            if (preg_match('/\Wid=(\d+)/', $link, $matches) && count($matches) > 1) {
                return $matches[1];
            }
        }
        return null;
    }

    private function getCategoryOrSectionIdFromLinkMenu($link) {
        if (strpos($link, "view=section")) {
            $matches = array();
            if (preg_match('/\Wid=(\d+)/', $link, $matches) && count($matches) > 1) {
                return array('view' => 'section', 'id' => $matches[1]);
            }
        }
        if (strpos($link, "view=category")) {
            $matches = array();
            if (preg_match('/\Wid=(\d+)/', $link, $matches) && count($matches) > 1) {
                return array('view' => 'category', 'id' => $matches[1]);
            }
        }
        return null;
    }
    
    private function getArticleIdsFromContent($content){
        $articleIds = array();
        
        //<div class="wrapperCustomArticleInt" onclick="window.location='/index.php?option=com_content&amp;view=article&amp;id=86%3Alastsikringsutstyr-generelle-krav&amp;catid=43%3Alastsikringsutstyr&amp;Itemid=106&amp;lang=nb'">
        $regexForLinksJs = '/<[^\>]+ onclick="window.location=\'[^\>]*view=article[^\>]*\Wid=(\d+)[^\>]*\'"/';
        $matchedLinksJs = array();
        if(preg_match_all($regexForLinksJs, $content, $matchedLinksJs)){
            if(count($matchedLinksJs) >= 2){
                $articleIds = array_merge($articleIds, $matchedLinksJs[1]);
            }
        }
        
        $regexForLinksHref = '/<a [^\>]*href="[^\>]*view=article[^\>]*\Wid=(\d+)[^\>]*"/';
        $matchedLinksHref = array();
        if(preg_match_all($regexForLinksHref, $content, $matchedLinksHref)){
            if(count($matchedLinksHref) >= 2){
                $articleIds = array_merge($articleIds, $matchedLinksHref[1]);
            }
        }
        
        return $articleIds;
    }

    /**
     * Get the equipment detail id from resource link text
     * @param string $resourceLink
     * @return integer|null
     */
    private function getDocSectionIdFromResourceLink($resourceLink) {
        if ($resourceLink) {
            $matches = array();
            if (preg_match('/id_equipment_detail=(\d+)/', $resourceLink, $matches) && count($matches) > 1) {
                    return $matches[1];
            }
        }
        return null;
    }

    /**
     * 
     * @param string $baseResourcePath base path folder for the image
     * @param string $resourceUrl http url for the resource image
     * @param int $imageWidth new width for the image
     * @return string the new name of the resized image
     */
    private function manageResourceFile($baseResourcePath, $resourceUrl, $imageWidth, $isImage = true, $ignoreResize = false) {
        $errorLevel = error_reporting();
        error_reporting(E_ALL ^ E_WARNING);
        $resourceContent = file_get_contents($this->replaceWhiteSpacesFromUrl($resourceUrl)); //this write a E_WARNING when file_get_contents return 404 not found resource
        error_reporting($errorLevel);
        if ($resourceContent) {
            $realFilename = $resourceUrl;
            $headers = get_headers($resourceUrl, 1);
            if ($headers) {
                $headersLower = array_change_key_case($headers, CASE_LOWER);
                if (array_key_exists('content-disposition', $headersLower) && $headersLower['content-disposition']) {
                    $tmpName = explode('=', $headersLower['content-disposition']);
                    if ($tmpName[1])
                        $realFilename = trim($tmpName[1], '";\'');
                }
            }
            $fileExtension = $this->getFileExtension($realFilename);
            $fileName = $this->getRandomString() . '.' . $fileExtension;
            $pathNewImageName = $baseResourcePath . $fileName;
            if (file_put_contents($pathNewImageName, $resourceContent)) {
                if ($isImage) {
                    $imageInfo = getimagesize($pathNewImageName);
                    if ($imageInfo){
                        if($ignoreResize)   return $fileName;
                        else    return $this->resizeImage($pathNewImageName, $imageWidth, $pathNewImageName);
                    }
                } else {
                    $mimeType = mime_content_type($pathNewImageName);
                    if ($this->isValidMimeType($mimeType))
                        return $fileName;
                }
                unlink($pathNewImageName);
            }
        }
        return null;
    }

    /**
     * 
     * @param string $mimeType
     * @return boolean
     */
    private function isValidMimeType($mimeType) {
        return in_array($mimeType, $this->validMimeTypes);
    }

    /**
     * 
     * @param string $urlToFormat url with parameters %s to replace
     * @param array $row array with de data
     * @param string $columnsToExtract key columns to extract from $row. Have to be the same number of %s in $urlToFormat
     * @return string builded url for a resource
     */
    private function buildExternalResourceUrl($urlToFormat, $row, $columnsToExtract) {
        $vsprintfArgs = array();
        foreach ($columnsToExtract as $column) {
            array_push($vsprintfArgs, $row[$column]);
        }
        return vsprintf($urlToFormat, $vsprintfArgs);
    }

    /**
     * 
     * @param string $imageName
     * @return boolean
     */
    private function isImageName($imageName) {
        if (!empty($imageName)) {
            $extension = $this->getFileExtension($imageName);
            if ($extension)
                return in_array($extension, $this->validImageExtensions);
        }
        return false;
    }

    /**
     * get an md5 of actual time(in seconds)
     * @return string
     */
    private function getRandomString() {
        return md5(microtime());
    }

    /**
     * 
     * @param string $question
     */
    private function getTextFromHtml($htmlText) {
        if ($htmlText) {
            $textReplaced = preg_replace('/(<br\s*\/?>|\.\s*)+/', '. ', $htmlText);
            $textStripped = strip_tags($textReplaced);
            return $textStripped;
        }
        return "";
    }

    /**
     * Gets the question information that is in the follow format: <p><strong>{subject}</strong>{explanatory text}</p><h4>{question}</h4>
     * @param string $questionHtmlText
     * @return array
     */
    private function getQuestionInfo($questionHtmlText) {
        $subject = '';
        $explanatoryText = '';
        $questionText = '';

        if ($questionHtmlText) {
            $matchedSubject = array();
            if (preg_match('/<strong>[^<]+<\/strong>/', $questionHtmlText, $matchedSubject)) {
                $questionHtmlText = str_replace($matchedSubject[0], '', $questionHtmlText);
                $subject = $this->getTextFromHtml($matchedSubject[0]);
            }
            $matchedQuestionText = array();
            if (preg_match('/<h4>.+<\/h4>/', $questionHtmlText, $matchedQuestionText)) {
                $questionHtmlText = str_replace($matchedQuestionText[0], '', $questionHtmlText);
                $questionText = $this->getTextFromHtml($matchedQuestionText[0]);
            }
            $matchedExplanatoryText = array();
            if (preg_match('/<p>.+<\/p>/', $questionHtmlText, $matchedExplanatoryText)) {
                $questionHtmlText = str_replace($matchedExplanatoryText[0], '', $questionHtmlText);
                $explanatoryText = $this->getTextFromHtml($matchedExplanatoryText[0]);
            }
        }

        return array(
            'subject' => $subject,
            'explanatoryText' => $explanatoryText,
            'questionText' => $questionText
        );
    }

    /**
     * 
     * @param string $imageName
     * @return string
     */
    private function getFileExtension($imageName) {
        return substr(strrchr(strtolower(trim($imageName)), '.'), 1);
    }

    /**
     * replace all white spaces to %20 in an url
     * @param string $url
     * @return type
     */
    private function replaceWhiteSpacesFromUrl($url) {
        return str_replace(" ", "%20", trim($url));
    }

    /**
     * 
     * @param integer $idType
     * @param array $columnsSprintf
     * @param boolean $isLadoc
     * @return string|null
     */
    private function getResourceUrlFormatAttachmentsForDocSections($idType, &$columnsSprintf = array(), $isLadoc = true) {
        if (in_array($idType, array(2, 3, 11, 12, 17))) {
            $columnsSprintf = array('id_equipment_detail', 'chr_title');
            return $isLadoc ? self::PATH_ATTACHMENT_DOCSECTION_MEDIA_LADOC : self::PATH_ATTACHMENT_DOCSECTION_MEDIA_MEDOC;
        } else if (in_array($idType, array(1))) {
            $columnsSprintf = array('chr_title', 'id_equipment_detail');
            return $isLadoc ? self::PATH_ATTACHMENT_DOCSECTION_PDF_LADOC : self::PATH_ATTACHMENT_DOCSECTION_PDF_MEDOC;
        } else {
            return null;
        }
    }

    /**
     * 
     * @param string $pathImage
     * @param int $imageWidth
     * @param string $pathNewImageName
     * @return string
     */
    private function resizeImage($pathImage, $imageWidth, $pathNewImageName) {
        $imageUtil = $this->getImageUtility();
        $newImage = $imageUtil->resizeImage(
                $pathImage, $imageWidth, $pathNewImageName/*, false*/);
        return $newImage;
    }

    private function mergeMatchedAttachments(&$matchedAttachments, $regex, $content) {
        $matches = array();
        if (preg_match_all($regex, $content, $matches)) {
            if (count($matches) >= 3) {
                if (count($matchedAttachments) == 0)
                    $matchedAttachments = $matches;
                else {
                    $matchedAttachments[1] = array_merge($matchedAttachments[1], $matches[1]);
                    $matchedAttachments[2] = array_merge($matchedAttachments[2], $matches[2]);
                }
            }
        }
    }

    protected function updateImagesFromContent($htmlContent, $domainExternalPath, $folderPathDestination, $pathUrlImages) {
        $htmlContentModified = $htmlContent;

        $regexForImages = '/<img[^>]+src=[\"|\']([^\"\']+)[\"|\'][^>]+>/i';
        $matchedImages = array();
        if (preg_match_all($regexForImages, $htmlContent, $matchedImages)) {
            if (count($matchedImages) >= 2) {
                foreach ($matchedImages[1] as $match) {
                    $urlResource = $domainExternalPath . $match;
                    $imageName = $this->manageResourceFile($folderPathDestination, $urlResource, $this->image['width'], true, true);
                    if ($imageName)
                        $htmlContentModified = str_replace($match, $pathUrlImages . $imageName, $htmlContentModified);
                    else
                        $htmlContentModified = str_replace($match, '', $htmlContentModified);
                }
            }
        }

        return $htmlContentModified;
    }

    protected function updateAttachmentsFromContent($htmlContent, $insertedDocSectionAttachments, $attachmentController, $externalDomain, $destinationApp) {
        $htmlContentModified = $htmlContent;
        $matchedAttachments = array();

        $regexForLinksToPdfAttachments = '/<a[^>]*href="([^\"]*task=downloadPDF[^\"]*\Wid=(\d+)[^\"]*)"/i';
        $this->mergeMatchedAttachments($matchedAttachments, $regexForLinksToPdfAttachments, $htmlContent);

        $regexForLinksToMediaAttachments = '/<a[^>]*href="([^\"]*administrator\/components\/com_storage\/files[^\/]*\/(\d+)\/[^\"]+)"/i';
        $this->mergeMatchedAttachments($matchedAttachments, $regexForLinksToMediaAttachments, $htmlContent);

        //images/stories/generell_info/dispensasjoner/Dispensasjon-Tidsbegrenset-2.doc
        $regexForLinksToOtherAttachments = '/<a[^>]*href="([^\"]*images\/stories\/generell_info[^\"]*\/([^\"\/]+))"/i';
        $this->mergeMatchedAttachments($matchedAttachments, $regexForLinksToOtherAttachments, $htmlContent);
        
        ///images/stories/ladok/pdf/Lastsikringsutstyr/T-00-HOVEDOVERSIKT%20LASTSURRING%20-%20TS%203990-1485%20R.pdf
        $regexForLinksToOtherAttachments = '/<a[^>]*href="([^\"]*images\/stories\/ladok\/pdf\/[^\"]*\/([^\"\/]+))"/i';
        $this->mergeMatchedAttachments($matchedAttachments, $regexForLinksToOtherAttachments, $htmlContent);
        
        if (count($matchedAttachments) >= 3) {
            $hrefsToReplace = array();
            foreach ($matchedAttachments[1] as $i => $match) {
                $newHrefLink = '#';
                if (is_numeric($matchedAttachments[2][$i]))
                    $attachmentFound = $this->findObjectFromArray($insertedDocSectionAttachments, 'temporalId', $matchedAttachments[2][$i]);
                else
                    $attachmentFound = null;
                if ($attachmentFound) {
                    $newHrefLink = sprintf('%s/%s/%s/handle/id/%s', $this->getBaseUrl(), $destinationApp, $attachmentController, $attachmentFound->getAttachmentId());
                } else {
                    //Some urls contains '/files/' that don't work, and work with '/files-ladok/' or '/files-medoc/'
                    $matchToDownload = str_replace('/files/', $externalDomain == self::LADOC_DOMAIN ? '/files-ladok/' : '/files-medoc/', $match);
                    $newFileName = $this->manageResourceFile(self::PATH_CONTENT_FOLDER, $externalDomain . $matchToDownload, null, false);
                    if ($newFileName)
                        $newHrefLink = $this->getBaseUrl() . self::MOXIE_MANAGER_PATH . $newFileName;
                }
                array_push($hrefsToReplace, $newHrefLink);
            }
            $htmlContentModified = str_replace($matchedAttachments[1], $hrefsToReplace, $htmlContentModified);
        }

        return $htmlContentModified;
    }

    private function updateHtmlContentLinksForDocSections($htmlContent, $insertedDocSections, $destinationApp) {
        $htmlContentModified = $htmlContent;

        //http://flo-ladok.vidum.no/index.php?option=com_storage&id_section=1&id_equipment=15&Itemid=74&id_equipment_detail=80&detail=1&id_category=18&flag=1&lang=nb
        $regexForLinksToDocSections = '/<a[^>]*href="([^\"]*equipment_detail=(\d+)[^\"]*)"/i';
        $matchedLinksToDocSections = array();
        if (preg_match_all($regexForLinksToDocSections, $htmlContentModified, $matchedLinksToDocSections)) {
            if (count($matchedLinksToDocSections) >= 3) {
                $hrefsToReplace = array();
                foreach ($matchedLinksToDocSections[2] as $idMatched) {
                    $newHrefLink = '#';
                    $docSectionFound = $this->findObjectFromArray($insertedDocSections, 'temporalId', $idMatched);
                    if ($docSectionFound) {
                        $equipmentId = $docSectionFound->getEquipment()->getEquipmentId();
                        $docSectionId = $docSectionFound->getSectionId();
                        $newHrefLink = sprintf('%s/%s/documentation/index/id/%s/sectionId/%s', $this->getBaseUrl(), $destinationApp, $equipmentId, $docSectionId);
                    }
                    array_push($hrefsToReplace, $newHrefLink);
                }
                $htmlContentModified = str_replace($matchedLinksToDocSections[1], $hrefsToReplace, $htmlContentModified);
            }
        }
        //index.php?option=com_storage&amp;id_section=1&amp;id_equipment=90&amp;Itemid=73
        //index.php?option=com_storage&amp;id_equipment=89&amp;id_section=1&amp;id_category=22&amp;Itemid=73&amp;detail=1&amp;lang=en
        //index.php?option=com_storage&id_equipment=88&id_section=1&id_category=12&Itemid=72&detail=1
        //index.php?option=com_storage&id_equipment=93&id_section=1&id_category=20&Itemid=73&detail=1
        //index.php?option=com_storage&amp;id_section=1&amp;id_category=42&amp;id_equipment=108&amp;Itemid=74&amp;detail=1
        //Run this regex after of applying the $regexForLinksToDocSections, because first we need have updated all of the links that had "equipment_detail=" inside
        $regexForLinksToDocSections2 = '/<a[^>]*href="([^\"]*id_equipment=(\d+)[^\"]*ItemId=\d+[^\"]*)"/i';
        $matchedLinksToDocSections2 = array();
        if (preg_match_all($regexForLinksToDocSections2, $htmlContentModified, $matchedLinksToDocSections2)) {
            if (count($matchedLinksToDocSections2) >= 3) {
                $hrefsToReplace = array();
                foreach ($matchedLinksToDocSections2[2] as $idMatched) {
                    $newHrefLink = '#';
                    $docSectionsFound = $this->findObjectsFromArray($insertedDocSections, 'temporalEquipmentId', $idMatched, 'getSectionOrder');
                    if (is_array($docSectionsFound) && count($docSectionsFound) > 0) {
                        $docSectionFound = $docSectionsFound[0];
                        $equipmentId = $docSectionFound->getEquipment()->getEquipmentId();
                        $docSectionId = $docSectionFound->getSectionId();
                        $newHrefLink = sprintf('%s/%s/documentation/index/id/%s/sectionId/%s', $this->getBaseUrl(), $destinationApp, $equipmentId, $docSectionId);
                    }
                    array_push($hrefsToReplace, $newHrefLink);
                }
                $htmlContentModified = str_replace($matchedLinksToDocSections2[1], $hrefsToReplace, $htmlContentModified);
            }
        }

        return $htmlContentModified;
    }

    protected function clearHtmlContents($htmlContent) {
        $htmlContentModified = $htmlContent;

        //strip "style" attribute from html tags, except from <img../> tags
        $regexForStripStylesExceptImages = '/(<(?!img)[^>]+) style=".*?"/i';
        $htmlContentModified = preg_replace($regexForStripStylesExceptImages, '$1', $htmlContentModified);
        
        $regexForStripStylesExceptImages = '/(<[^>]+) class=".*?"/i';
        $htmlContentModified = preg_replace($regexForStripStylesExceptImages, '$1', $htmlContentModified);

        //strip "style" attribute from html tags, except from <img../> tags
        $regexForOtherLinks = '/(<a[^>]*href=\")\/?index.php\?option=[^\"]+/i';
        $htmlContentModified = preg_replace($regexForOtherLinks, '${1}#', $htmlContentModified);
        
        $htmlContentModified = str_replace('<table', '<table class="table" ', $htmlContentModified);

        return $htmlContentModified;
    }

    protected function updateHtmlContentLinksForPageSections($htmlContent, $insertedPages, $insertedPageSections, $insertedTaxonomies, $insertedHtmlContents, $destinationApp) {
        $htmlContentModified = $htmlContent;

        //index.php?option=com_content&amp;view=article&amp;id=77&amp;Itemid=84
        //index.php?option=com_content&amp;view=category&amp;id=42&amp;Itemid=65
        //index.php?option=com_content&view=section&id=9&Itemid=69
        $regexForLinksToPagesAndSections = '/<a[^>]*href="([^\"]*view=(article|category|section)[^\"]*\Wid=(\d+)[^\"]*)"/i';
        $matchedLinksToPagesAndSections = array();
        if (preg_match_all($regexForLinksToPagesAndSections, $htmlContentModified, $matchedLinksToPagesAndSections)) {
            if (count($matchedLinksToPagesAndSections) >= 4) {
                $hrefsToReplace = array();
                foreach ($matchedLinksToPagesAndSections[3] as $i => $idMatched) {
                    $newHrefLink = '#';
                    switch ($matchedLinksToPagesAndSections[2][$i]) {
                        case 'article':
                            $htmlContentFound = $this->findObjectFromArray($insertedHtmlContents, 'temporalId', $idMatched);
                            if ($htmlContentFound) {
                                if (strpos(get_class($htmlContentFound), "HtmlContentPageSection")) {
                                    $pageSection = $htmlContentFound->getPageSection();
                                } else if (strpos(get_class($htmlContentFound), "HtmlContentPageInlineSection")) {
                                    $pageInlineSection = $htmlContentFound->getPageInlineSection();
                                    $pageSection = $pageInlineSection->getPageSection();
                                } else {
                                    break;
                                }
                                $newHrefLink = sprintf('%s/%s/page/index/id/%s/sectionId/%s', $this->getBaseUrl(), $destinationApp, $pageSection->getPage()->getPageId(), $pageSection->getSectionId());
                            }
                            break;
                        case 'category':
                            $pageSectionFound = $this->findObjectFromArray($insertedPageSections, array('temporalView', 'temporalViewId'), array('category', $idMatched));
                            if ($pageSectionFound) {
                                $newHrefLink = sprintf('%s/%s/page/index/id/%s/sectionId/%s', $this->getBaseUrl(), $destinationApp, $pageSectionFound->getPage()->getPageId(), $pageSectionFound->getSectionId());
                            }
                            break;
                        case 'section':
                            $pageFound = $this->findObjectFromArray($insertedPages, array('temporalView', 'temporalViewId'), array('section', $idMatched));
                            if ($pageFound) {
                                $newHrefLink = sprintf('%s/%s/page/index/id/%s', $this->getBaseUrl(), $destinationApp, $pageFound->getPageId());
                            }
                            break;
                    }
                    array_push($hrefsToReplace, $newHrefLink);
                }
                $htmlContentModified = str_replace($matchedLinksToPagesAndSections[1], $hrefsToReplace, $htmlContentModified);
            }
        }

        //index.php?option=com_lastbaerere&id_category=18&id_section=1&Itemid=74
        $regexForTaxonomies = '/<a[^>]*href="([^\"]*id_category=(\d+)[^\"]*)"/i';
        $matchedLinksForTaxonomies = array();
        if (preg_match_all($regexForTaxonomies, $htmlContentModified, $matchedLinksForTaxonomies)) {
            if (count($matchedLinksForTaxonomies) >= 3) {
                $hrefsToReplace = array();
                foreach ($matchedLinksForTaxonomies[2] as $i => $idMatched) {
                    $newHrefLink = '#';
                    $taxonomyFound = $this->findObjectFromArray($insertedTaxonomies, 'tempIdCategory', $idMatched);
                    if ($taxonomyFound) {
                        $newHrefLink = sprintf('%s/%s/equipment/index/category/%s', $this->getBaseUrl(), $destinationApp, $taxonomyFound->getEquipmentTaxonomyId());
                    }
                    array_push($hrefsToReplace, $newHrefLink);
                }
                $htmlContentModified = str_replace($matchedLinksForTaxonomies[1], $hrefsToReplace, $htmlContentModified);
            }
        }

        return $htmlContentModified;
    }

    private function makeDatabaseBackup($databaseConfig) {
        $backupFile = self::PATH_FILE_BACKUP_IMPORTING;
        $returnedValue = null;
        $output = "";
        $message = null;
        $matches = array();
        if (is_array($databaseConfig) && preg_match('/dbname=([^\;\s]+)/i', $databaseConfig['dsn'], $matches)) {
            $database = $matches[1];
            $user = $databaseConfig['user'];
            $password = $databaseConfig['password'];
            $mysqlbinDir = array_key_exists('mysqlbin', $databaseConfig) ? $databaseConfig['mysqlbin'] : "";
            $commandPassword = !empty($password) ? "-p$password" : "";

            $command = $mysqlbinDir . "mysqldump --opt -u $user $commandPassword $database > $backupFile";

            exec($command, $output, $returnedValue);
            $message = $returnedValue != 0 ? $this->translate('ERROR') : $this->translate('CORRECT');
        }
        return array(
            'output' => $output,
            'status' => $returnedValue,
            'message' => $message
        );
    }

    private function restoreDatabase($databaseConfig) {
        $backupFile = self::PATH_FILE_BACKUP_IMPORTING;
        $returnedValue = null;
        $output = "";
        $message = null;
        $matches = array();
        if (is_array($databaseConfig) && preg_match('/dbname=([^\;\s]+)/i', $databaseConfig['dsn'], $matches)) {
            $database = $matches[1];
            $user = $databaseConfig['user'];
            $password = $databaseConfig['password'];
            $mysqlbinDir = array_key_exists('mysqlbin', $databaseConfig) ? $databaseConfig['mysqlbin'] : "";
            $commandPassword = !empty($password) ? "-p$password" : "";

            $command = $mysqlbinDir . "mysql -u $user $commandPassword $database < $backupFile";

            exec($command, $output, $returnedValue);
            $message = $returnedValue != 0 ? $this->translate('ERROR') : $this->translate('CORRECT');
        }
        return array(
            'output' => $output,
            'status' => $returnedValue,
            'message' => $message
        );
    }

    private function deleteImportedFilesByLastHours($hours) {
        $directoriesToAnalize = array(
            self::PATH_EQUIPMENT_TAXONOMY_IMAGE_FOLDER,
            self::PATH_EQUIPMENT_IMAGE_FOLDER,
            self::PATH_ATTACHMENTS_FOLDER,
            self::PATH_QUESTION_FOLDER,
            self::PATH_CONTENT_FOLDER
        );
        foreach ($directoriesToAnalize as $directory) {
            $handle = opendir($directory);
            if ($handle) {
                while (false !== ($file = readdir($handle))) {
                    $pathFile = $directory . $file;
                    $fileLastModified = filemtime($pathFile);
                    if ((time() - $fileLastModified) <= $hours * 3600 && is_file($pathFile) && !is_dir($pathFile)) {
                        unlink($pathFile);
                    }
                }
                closedir($handle);
            }
        }
    }

    /**
     * check if a date in string is valid or not
     * @param string $stringDate
     * @return boolean
     */
    private function isFormattedDate($stringDate) {
        $stamp = strtotime($stringDate);
        if (!is_numeric($stamp))
            return FALSE;
        $month = date('m', $stamp);
        $day = date('d', $stamp);
        $year = date('Y', $stamp);
        if (checkdate($month, $day, $year))
            return TRUE;
        return FALSE;
    }

    /**
     * Search an object in array of objects, by key and value, or by a group of keys and values
     * @param array $dataArray
     * @param string|array $key
     * @param string|numeric|array $value
     * @return object|null
     */
    private function findObjectFromArray($dataArray, $key, $value) {
        foreach ($dataArray as $row) {
            if (is_array($key) && is_array($value) && count($key) == count($value)) {
                $counter = 0;
                foreach ($key as $i => $k) {
                    if (property_exists($row, $k) && $row->$k == $value[$i]){
                        $counter++;
                    }
                }
                if ($counter == count($value)){
                    return $row;
                }
            }else if (is_string($key) && (is_string($value) || is_numeric($value))) {
                if (property_exists($row, $key) && $row->$key == $value){
                    return $row;
                }
            }
            else{
                break;
            }
        }
        return null;
    }

    /**
     * Custom function to sort an specific array of objects
     * @param string $getter
     * @return function
     */
    private function cmpByGetter($getter) {
        return function ($a, $b) use ($getter) {
                    if ($a->$getter() == $b->$getter()) {
                        return 0;
                    }
                    return ($a->$getter() < $b->$getter()) ? -1 : 1;
                };
    }

    /**
     * Find objects that match a specific value in array of objects. The result array can be sorted
     * @param array $dataArray
     * @param string $key
     * @param string $value
     * @param string $getterToSortBy
     * @return array
     */
    private function findObjectsFromArray($dataArray, $key, $value, $getterToSortBy = null) {
        $objects = array();
        foreach ($dataArray as $row) {
            if ($row->$key == $value){
                array_push($objects, $row);
            }
        }
        if ($getterToSortBy){
            usort($objects, $this->cmpByGetter($getterToSortBy));
        }
        return $objects;
    }

    protected function getImportingRepository() {
        if ($this->importingRepository == null){
            $this->importingRepository = new ImportingRepository();
        }
        return $this->importingRepository;
    }

    protected function getLadocAdapter() {
        return $this->getDependency('db_ladoc');
    }

    protected function getMedocAdapter() {
        return $this->getDependency('db_medoc');
    }

}