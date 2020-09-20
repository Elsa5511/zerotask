<?php

namespace ApplicationTest\Controller;

use ApplicationTest\BaseSetUp;

class LanguageControllerTest extends BaseSetUp {
    const HTTP_OK = 200;
    const HTTP_REDIRECTION = 302;

    public function testIndexActionCanBeAccessed() {
        $this->dispatchWithTestApplication('/language');
        $this->assertResponseStatusCode(self::HTTP_OK);
        $this->accessAsserts();
    }

    public function testAddActionCanBeAccessed() {
        $this->dispatchWithTestApplication('/language/add');
        $this->assertResponseStatusCode(self::HTTP_OK);
        $this->accessAsserts();
    }

    private function accessAsserts($wildcard = '') {
        $this->assertModuleName('Application');
        $this->assertControllerName('Controller\Language');
        $this->assertControllerClass('LanguageController');
        $this->assertMatchedRouteName('base' . $wildcard);
    }

}
