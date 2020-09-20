<?php

namespace ApplicationTest\Controller;

use ApplicationTest\BaseSetUp;
use Application\Utility\ServiceMessage;

class LocationControllerTest extends BaseSetUp {

    const PATH_INDEX = '/location/index';
    const PATH_DELETE_LOCATION = '/location/delete';
    const PATH_DELETE_MANY_LOCATION = '/location/delete-many';
    const HTTP_OK = 200;
    const HTTP_REDIRECTION = 302;
    const HTTP_NOT_FOUND = 404;

    public function testDeleteActionCanBeAccessed() {
        $this->dispatchWithTestApplication(self::PATH_DELETE_LOCATION);
        $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
        $this->assertRedirectTo($this->indexUrl());
        $this->moduleControllerRouteAsserts();
    }

    public function testSuccessfulDeleteAction() {
        // Given
        $locationId = 1;
        $serviceMessageResult = new ServiceMessage('success', 'testDeleteActionSuccess');
        $this->setupLocationServiceMock('deleteById', $locationId, $serviceMessageResult);
        $deleteRoute = $this->constructUrlWithTestApplication(self::PATH_DELETE_LOCATION . '/id/' . $locationId);

        // When
        $this->dispatch($deleteRoute);

        // Then
        $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
        $this->assertRedirectTo($this->indexUrl());
        $this->moduleControllerRouteAsserts('/wildcard');
    }

    public function testDeleteManyActionCanBeAccessed() {
        $this->dispatchWithTestApplication(self::PATH_DELETE_MANY_LOCATION);
        $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
        $this->assertRedirectTo($this->indexUrl());
        $this->moduleControllerRouteAsserts();
    }

    public function testSucessfulDeleteManyAction() {
        // Given
        $inputIds = array('1', '2', '3');
        $serviceMessageResultArray = array(
            new ServiceMessage('', ''),
            new ServiceMessage('', ''),
            new ServiceMessage('', ''),
        );
        $this->setupLocationServiceMock('deleteByIds', $inputIds, $serviceMessageResultArray);
        $deleteRoute = $this->constructUrlWithTestApplication(self::PATH_DELETE_MANY_LOCATION);

        // When
        $this->dispatch($deleteRoute, 'POST', array('delete_list' => '1, 2, 3'));

        // Then
        $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
        $this->assertRedirectTo($this->indexUrl());
    }

    private function setupLocationServiceMock($methodName, $input, $returnValue) {
        $serviceLocationMock = $this->getServiceLocationMock();
        $serviceLocationMock->expects($this->once())
                ->method($methodName)
                ->with($this->equalTo($input))
                ->will($this->returnValue($returnValue));
        $this->getServiceManagerMocker()->setupMockForName(
                'Application\Service\LocationService', $serviceLocationMock);

        return $serviceLocationMock;
    }

    private function getServiceLocationMock() {
        return $this->getMockBuilder('Application\Service\LocationService')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function moduleControllerRouteAsserts($wildcard = '') {
        $this->assertModuleName('Application');
        $this->assertControllerName('Controller\Location');
        $this->assertControllerClass('LocationController');
        $this->assertMatchedRouteName('base' . $wildcard);
    }

    private function indexUrl() {
        return $this->constructUrlWithTestApplication(self::PATH_INDEX);
    }
}
