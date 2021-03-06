<?php

namespace EquipmentTest\MockUtil;

//use Builder\Model\BuilderApplication;
use Application\Model\UserModel;
//use ApplicationTest\StubClass\BuilderApplicationTableStub;

class ServiceManagerMocker extends \PHPUnit_Framework_TestCase {

    protected $serviceManager;
    protected $objectManager;
    
    const LOGGED_USER = 1;
    const LOGGED_USER_DISPLAY_NAME = 'Display name';    

    function __construct($serviceManager) 
    {
        $this->serviceManager = $serviceManager;        
        $this->objectManager = $this->serviceManager->get('Doctrine\ORM\EntityManager');
    }

    /*
    * Form mocks
    */
    public function setupFormFactoryMock($result)
    {
        $factoryMock = $this->getMockBuilder('Application\Form\FormFactory')
            ->disableOriginalConstructor()
            ->getMock();       

        $this->setupMockForName('Application\Form\FormFactory', $factoryMock);
    }    
    
    public function getFormMockWithValidateResult($mockClass, $result)
    {
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

//        $interfaceElement = new \Application\Form\UserAdminFieldset($this->objectManager);
//        $interfaceElement->setName('user');
//        $formMock->expects($this->any())
//            ->method('get')
//            ->will($this->returnValue($interfaceElement));

        return $formMock;
    }
    
    /**
     * ready
     * 
     */
    public function setupZFAuthenticationMock() {
        
//         $mockRoles = $this->getMockBuilder('\ZfcUser\Entity\Role')
//         ->disableOriginalConstructor()
//         ->getMock();
        $mockIdentity = $this->getMockBuilder('\Application\Entity\User')
                ->disableOriginalConstructor()
                ->getMock();
        $mockIdentity->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(self::LOGGED_USER));
        $mockIdentity->expects($this->any())
                ->method('getDisplayName')
                ->will($this->returnValue(self::LOGGED_USER_DISPLAY_NAME));

//         $mockIdentity->expects($this->any())
//         ->method('getDisplayName')
//         ->will($this->returnValue(self::LOGGED_USER_DISPLAY_NAME));
        
        $zfUserAuthServiceMock = $this->getMockBuilder('\Zend\Authentication\AuthenticationService')
                ->disableOriginalConstructor()
                ->getMock();

        $zfUserAuthServiceMock->expects($this->any())
                ->method('getIdentity')
                ->will($this->returnValue($mockIdentity));
        $zfUserAuthServiceMock->expects($this->any())
                ->method('hasIdentity')
                ->will($this->returnValue(true));

        $this->serviceManager->setAllowOverride(true);
        $this->serviceManager->setService('zfcuser_auth_service', $zfUserAuthServiceMock);
    }

    public function setupMockForName($name, $mock) {
        $this->serviceManager->setAllowOverride(true);
        $this->serviceManager->setService($name, $mock);
    }
}
