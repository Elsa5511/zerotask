<?php

namespace EquipmentTest;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use EquipmentTest\MockUtil\ServiceManagerMocker;
use EquipmentTest\Bootstrap;

abstract class BaseSetUp extends AbstractHttpControllerTestCase {

    protected $traceError = true;
    protected $serviceManagerMocker;
    protected $testApplication = "test-application";

    public function setUp() {
        $rootPath = dirname(Bootstrap::findParentPath('module'));
        $this->setApplicationConfig(
                include $rootPath . '/config/application.testconfig.php');
        parent::setUp();
        $_SERVER['SERVER_NAME'] = 'sysco.no';

        $serviceManager = $this->getApplicationServiceLocator();
        $this->serviceManagerMocker = new ServiceManagerMocker($serviceManager);
        $this->serviceManagerMocker->setupZFAuthenticationMock($this->testApplication);
        $_SERVER['REQUEST_URI'] = '';
    }

    protected function getServiceManagerMocker() {
        return $this->serviceManagerMocker;
    }

    protected function setServiceManagerMocker($serviceManagerMocker) {
        $this->serviceManagerMocker = $serviceManagerMocker;
    }

    protected function dispatchWithTestApplication($urlFragment, $method = null, $params = array()) {
        $this->dispatch($this->constructUrlWithTestApplication($urlFragment), $method, $params);
    }

    protected function constructUrlWithTestApplication($urlFragment) {
        return '/' . $this->testApplication . $urlFragment;
    }

}
