<?php

namespace Application\Controller;

use Application\Controller\AbstractBaseController;

class ImportingController extends AbstractBaseController
{
    public function indexAction() {
        $importingService = $this->getImportingService();
        //$importingService->setBaseUrl($this->getRequest()->getBasePath());
        $importingService->setDatabaseConfig($this->getDatabaseConfig());
        $importingService->importData();
        return $this->response;
    }

    public function ladocImportAction()
    {
        $importingService = $this->getLadocImportingService();
        //$importingService->setBaseUrl($this->getRequest()->getBasePath());
        $importingService->setDatabaseConfig($this->getDatabaseConfig());
        $importingService->importData();
        return $this->response;
    }
    
    public function backupAction(){
        $importingService = $this->getImportingService();
        $importingService->setDatabaseConfig($this->getDatabaseConfig());
        $res = $importingService->backupData();
        echo $res['message'];
        return $this->response;
    }
    
    public function restoreAction(){
        $hours = (float) $this->params()->fromRoute('hours', 2);
        $importingService = $this->getImportingService();
        $importingService->setDatabaseConfig($this->getDatabaseConfig());
        $res = $importingService->restoreData($hours);
        echo $res['message'];
        return $this->response;
    }
    
    private function getDatabaseConfig(){
        $config = $this->getServiceLocator()->get('\Config');
        return $config['db'];
    }
    
    /**
     * 
     * @return \Application\Service\ImportingService
     */
    private function getImportingService(){
        return $this->getService('Application\Service\ImportingService');
    }

    /**
     *
     * @return \Application\Service\LadocImportingService
     */
    private function getLadocImportingService(){
        return $this->getService('Application\Service\LadocImportingService');
    }

}