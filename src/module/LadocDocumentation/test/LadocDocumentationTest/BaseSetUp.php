<?php
namespace LadocDocumentationTest;

use LadocDocumentationTest\MockUtil\ServiceManagerMocker;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

abstract class BaseSetUp extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    protected $serviceManagerMocker;

    public function setUp()
    {
        $rootPath = dirname(Bootstrap::findParentPath('module'));
        $this->setApplicationConfig(
                include $rootPath . '/config/application.testconfig.php');
        parent::setUp();
        $_SERVER['SERVER_NAME'] = 'sysco.no';

        $serviceManager = $this->getApplicationServiceLocator();
        $this->serviceManagerMocker = new ServiceManagerMocker($serviceManager);
        $this->serviceManagerMocker->setupZFAuthenticationMock();
        $_SERVER['REQUEST_URI'] = '';
    }

    protected function getServiceManagerMocker()
    {
        return $this->serviceManagerMocker;
    }
}