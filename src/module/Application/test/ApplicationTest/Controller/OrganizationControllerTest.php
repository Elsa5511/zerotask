<?php
namespace ApplicationTest\Controller;

use ApplicationTest\BaseSetUp;
use Application\Utility\ServiceMessage;

class OrganizationControllerTest extends BaseSetUp
{
   const APPLICATION = '/test-application';
   const PATH_INDEX = '/organization/index';
   const PATH_ADD_ORGANIZATION = '/organization/add';
   const PATH_EDIT_ORGANIZATION = '/organization/edit';
   const PATH_DELETE_ORGANIZATION = '/organization/delete';
   const PATH_MY_ACCOUNT = '/organization/account';
   const PATH_DELETE_MANY_ORGANIZATION = '/organization/delete-many';
   const HTTP_OK = 200;
   const HTTP_REDIRECTION = 302;
   const HTTP_NOT_FOUND = 404;

   public function testIndexActionCanBeAccessed()
   {
       $this->dispatch($this->indexUrl());
       $this->assertResponseStatusCode(self::HTTP_OK);
       $this->accessAsserts();
   }

   public function testIndexActionOrganizationList()
   {
       $expectedOrganizationList = array();
       $serviceOrganizationMock = $this->getServiceOrganizationMock();
       $serviceOrganizationMock->expects($this->once())
               ->method('fetchAll')
               ->will($this->returnValue($expectedOrganizationList));

       $this->getServiceManagerMocker()->setupMockForName(
               'Application\Service\OrganizationService', $serviceOrganizationMock);

       $this->dispatch($this->indexUrl());
   }

   public function testAddActionCanBeAccessed()
   {
       $this->dispatch(self::APPLICATION . self::PATH_ADD_ORGANIZATION);
       $this->assertResponseStatusCode(self::HTTP_OK);
       $this->accessAsserts();
   }

   public function testEditActionValid()
   {
       // input
       $organizationId = 1;
       $expectedOrganization = new \Application\Entity\Organization;
       $expectedOrganization->setName("uniqueName");

       $serviceOrganizationMock = $this->getServiceOrganizationMock();
       $serviceOrganizationMock->expects($this->once())
               ->method('getOrganization')
               ->with($this->equalTo($organizationId))
               ->will($this->returnValue($expectedOrganization));

       $this->getServiceManagerMocker()->setupMockForName(
               'Application\Service\OrganizationService', $serviceOrganizationMock);

       $pathToEdit = self::APPLICATION . self::PATH_EDIT_ORGANIZATION . '/id/' . $organizationId;
       $this->dispatch($pathToEdit);
   }

   public function testEditActionValidPost()
   {
       $organizationId = 1;
       $expectedOrganization = new \Application\Entity\Organization;
       $expectedOrganization->setName("uniqueName");

       $serviceOrganizationMock = $this->getServiceOrganizationMock();
       $serviceOrganizationMock->expects($this->once())
               ->method('getOrganization')
               ->with($this->equalTo($organizationId))
               ->will($this->returnValue($expectedOrganization));

       $serviceOrganizationMock->expects($this->once())
               ->method('persistData')
               ->will($this->returnValue($organizationId));

       $this->getServiceManagerMocker()->setupFormFactoryMock(true);

       $this->getServiceManagerMocker()->setupMockForName(
               'Application\Service\OrganizationService', $serviceOrganizationMock);

       $pathToEdit = self::APPLICATION . self::PATH_EDIT_ORGANIZATION . '/id/' . $organizationId;
       $this->dispatch($pathToEdit, 'POST', array());
       $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
       $this->assertRedirectTo($this->indexUrl());
   }

   public function testEditActionNotValidPost()
   {
       $organizationId = 1;
       $expectedOrganization = new \Application\Entity\Organization;
       $expectedOrganization->setName("uniqueName");

       $serviceOrganizationMock = $this->getServiceOrganizationMock();
       $serviceOrganizationMock->expects($this->once())
               ->method('getOrganization')
               ->with($this->equalTo($organizationId))
               ->will($this->returnValue($expectedOrganization));

       $this->getServiceManagerMocker()->setupMockForName(
               'Application\Service\OrganizationService', $serviceOrganizationMock);

       $pathToEdit = self::APPLICATION . self::PATH_EDIT_ORGANIZATION . '/id/' . $organizationId;
       $this->dispatch($pathToEdit, 'POST', array());
       $this->assertResponseStatusCode(self::HTTP_OK);
   }

   private function getServiceOrganizationMock() {
       return $this->getMockBuilder('Application\Service\OrganizationService')
                       ->disableOriginalConstructor()
                       ->getMock();
   }

   private function accessAsserts($wildcard = '') {
       $this->assertModuleName('Application');
       $this->assertControllerName('Controller\Organization');
       $this->assertControllerClass('OrganizationController');
       $this->assertMatchedRouteName('base' . $wildcard);
   }
   
    private function indexUrl() {
        return self::APPLICATION . self::PATH_INDEX;
    }

}