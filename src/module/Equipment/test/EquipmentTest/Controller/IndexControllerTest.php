<?php

namespace EquipmentTest\Controller;

class IndexControllerTest { // extends \EquipmentTest\BaseSetUp {

// TODO: These tests are all breaking. Fix or remove.
//    public function testIndexActionCanBeAccessed() {
//        $this->dispatchWithTestApplication('/equipment/index');
//        $this->assertResponseStatusCode(200);
//        $this->accessAsserts();
//    }
//
//    private function accessAsserts($wildcard = '') {
//        $this->assertModuleName('Equipment');
//        $this->assertControllerName('Controller\Equipment');
//        $this->assertControllerClass('EquipmentController');
//        $this->assertMatchedRouteName('base' . $wildcard);
//    }

//    public function testIndexActionSearchAdvanced() {
//        $expectedTaxonomyList = array();
//        $serviceTaxonomyMock = $this->getServiceEquipmentTaxonomyMock();
//        $serviceTaxonomyMock->expects($this->once())
//                ->method('fetchEquipmentTaxonomy')
//                ->will($this->returnValue($expectedTaxonomyList));
//
//        $this->getServiceManagerMocker()->setupMockForName(
//                'Equipment\Service\EquipmentTaxonomyService', $serviceTaxonomyMock);
//
//        $this->dispatchWithTestApplication('/equipment/index');
//    }

//    public function testIndexActionTaxonomyList() {
//        $expectedTaxonomyList = array();
//
//        $serviceTaxonomyMock = $this->getServiceEquipmentTaxonomyMock();
//        $serviceTaxonomyMock->expects($this->once())
//                ->method('fetchEquipmentTaxonomy')
//                ->will($this->returnValue($expectedTaxonomyList));
//
//        $this->getServiceManagerMocker()->setupMockForName(
//                'Equipment\Service\EquipmentTaxonomyService', $serviceTaxonomyMock);
//        $this->dispatchWithTestApplication('/equipment/index/category/0');
//    }

    private function getServiceEquipmentTaxonomyMock() {
        return $this->getMockBuilder('Equipment\Service\EquipmentTaxonomyService')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

}
