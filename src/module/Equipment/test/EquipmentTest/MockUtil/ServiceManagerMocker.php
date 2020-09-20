<?php

namespace EquipmentTest\MockUtil;

class ServiceManagerMocker extends \PHPUnit_Framework_TestCase {

    protected $serviceManager;
    protected $objectManager;

    const LOGGED_USER = 1;
    const LOGGED_USER_DISPLAY_NAME = 'Display name';

    public $testGroupArray = array();

    function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
        $this->objectManager = $this->serviceManager->get('Doctrine\ORM\EntityManager');
    }

    /*
     * Form mocks
     */

    public function setupFormFactoryMock($result) {
        $factoryMock = $this->getMockBuilder('Application\Form\FormFactory')
                ->disableOriginalConstructor()
                ->getMock();

        $factoryMock->expects($this->any())
                ->method('createUserForm')
                ->will($this->returnValue($this->getUserFormMockWithValidateResult('\Sysco\Aurora\Form\Form', $result)));

        $factoryMock->expects($this->any())
                ->method('createOrganizationForm')
                ->will($this->returnValue(
                                $this->getFormMockWithValidateResult('\Sysco\Aurora\Form\Form', $result)));

        $factoryMock->expects($this->any())
                ->method('createForgotPasswordForm')
                ->will($this->returnValue(
                                $this->getFormMockWithValidateResult('\Sysco\Aurora\Form\Form', $result)));

        $this->setupMockForName('Application\Form\FormFactory', $factoryMock);
    }

    public function getUserFormMockWithValidateResult($mockClass, $result) {
        $formMock = $this->getMockBuilder($mockClass)
                ->disableOriginalConstructor()
                ->getMock();

        $formMock->expects($this->any())
                ->method('isValid')
                ->will($this->returnValue($result));

        $formMock->expects($this->any())
                ->method('getAttributes')
                ->will($this->returnValue(array()));

        $interfaceElement = new \Application\Form\UserAdminFieldset($this->objectManager);
        $interfaceElement->setName('user');
        $formMock->expects($this->any())
                ->method('get')
                ->will($this->returnValue($interfaceElement));
        $formMock->add($interfaceElement);
        $formMock->expects($this->any())
                ->method('getIterator')
                ->will($this->returnValue(false));

        return $formMock;
    }

    public function getFormMockWithValidateResult($mockClass, $result) {
        $formMock = $this->getMockBuilder($mockClass)
                ->disableOriginalConstructor()
                ->getMock();

        $formMock->expects($this->any())
                ->method('isValid')
                ->will($this->returnValue($result));

        $formMock->expects($this->any())
                ->method('getAttributes')
                ->will($this->returnValue(array()));

        $interfaceElement = new \Zend\Form\Element();
        $interfaceElement->setName('gg');
        $formMock->expects($this->any())
                ->method('get')
                ->will($this->returnValue($interfaceElement));

        return $formMock;
    }

    public function setupZFAuthenticationMock($testApplicationName) {
        $zfUserAuthServiceMock = $this->getMockBuilder('\Zend\Authentication\AuthenticationService')
                ->disableOriginalConstructor()
                ->getMock();

        $mockIdentity = $this->createMockIdentity($testApplicationName);
        $zfUserAuthServiceMock->expects($this->any())
                ->method('getIdentity')
                ->will($this->returnValue($mockIdentity));
        $zfUserAuthServiceMock->expects($this->any())
                ->method('hasIdentity')
                ->will($this->returnValue(true));

        $this->serviceManager->setAllowOverride(true);
        $this->serviceManager->setService('zfcuser_auth_service', $zfUserAuthServiceMock);
    }

    private function createMockIdentity($testApplicationName) {
        $testApplication = new \Application\Entity\ApplicationDescription();
        $testApplication->setName($testApplicationName);
        $applicationsThatUserCanAccess = new \Doctrine\Common\Collections\ArrayCollection();
        $applicationsThatUserCanAccess->add($testApplication);

        $mockIdentity = $this->getMockBuilder('\Application\Entity\User')
                ->disableOriginalConstructor()
                ->getMock();
        $mockIdentity->expects($this->any())
                ->method('getUserId')
                ->will($this->returnValue(self::LOGGED_USER));
        $mockIdentity->expects($this->any())
                ->method('getDisplayName')
                ->will($this->returnValue(self::LOGGED_USER_DISPLAY_NAME));
        $mockIdentity->expects($this->any())
                ->method('getAccessibleApplications')
                ->will($this->returnValue($applicationsThatUserCanAccess));

        return $mockIdentity;
    }

    public function setupMockForName($name, $mock) {
        $this->serviceManager->setAllowOverride(true);
        $this->serviceManager->setService($name, $mock);
    }

}
