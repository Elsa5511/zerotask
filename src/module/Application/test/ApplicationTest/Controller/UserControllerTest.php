<?php

namespace ApplicationTest\Controller;

use ApplicationTest\BaseSetUp;

class UserControllerTest extends BaseSetUp {
    const PATH_INDEX = '/user/index';
    const PATH_ADD_USER = '/user/add';
    const PATH_EDIT_USER = '/user/edit';
    const PATH_MY_ACCOUNT = '/user/account';
    const HTTP_OK = 200;
    const HTTP_REDIRECTION = 302;
    const HTTP_NOT_FOUND = 404;

    public function testIndexActionCanBeAccessed() {
        $this->serviceManagerMocker->setupMockForName(
                'Application\Service\UserService', $this->getUserServiceMock());

        $this->dispatchWithTestApplication('/user/index');
        $this->assertResponseStatusCode(self::HTTP_OK);
        $this->accessAsserts();
    }

    public function testEditActionCanBeAccessed() {
        $this->serviceManagerMocker->setupMockForName(
                'Application\Service\UserService', $this->getUserServiceMock());
        $userId = 2;
        $this->dispatchWithTestApplication(self::PATH_EDIT_USER . "/id/$userId");
        $this->accessAsserts('/wildcard');
    }

    public function testEditActionPostCanBeAccessed() {
        $this->serviceManagerMocker->setupMockForName(
                'Application\Service\UserService', $this->getUserServiceMock());
        $userFieldset = array(
            'user' => array(
                'username' => 'testuser',
                'email' => 'unit@test.com'
            )
        );
        $userId = 2;
        $this->dispatchWithTestApplication(self::PATH_EDIT_USER . "/id/$userId", 'POST', $userFieldset);
        $this->accessAsserts('/wildcard');
    }

    public function testEditActionWithWrongUserId() {
        $userId = 'xyz';
        $this->dispatchWithTestApplication(self::PATH_EDIT_USER . "/id/$userId");
        $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
        $this->assertRedirectTo($this->constructUrlWithTestApplication(self::PATH_ADD_USER));
        $this->accessAsserts('/wildcard');
    }

    public function testAccountActionCanBeAccessed() {
        $this->serviceManagerMocker->setupMockForName(
                'Application\Service\UserService', $this->getUserServiceMock());
        $this->dispatchWithTestApplication(self::PATH_MY_ACCOUNT);
        $this->accessAsserts();
    }

    public function testAddActionWithValid() {
        $userFieldset = array(
            'user' => array(
                'role-id' => 'admin'
            )
        );

        $serviceMock = $this->getMockBuilder('Application\Service\UserService')
                ->disableOriginalConstructor()
                ->getMock();
        $serviceMock->expects($this->once())
                ->method('persistFormData')
                ->will($this->returnValue(1));

        $this->serviceManagerMocker->setupMockForName(
                'Application\Service\UserService', $serviceMock);
        $this->serviceManagerMocker->setupFormFactoryMock(true);

        $this->dispatchWithTestApplication(self::PATH_ADD_USER, 'POST', $userFieldset);
        $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
        $this->assertRedirectToIndex();
    }

    public function testEditActionWithValid() {
        $userId = 2;
        $expectedUser = new \Application\Entity\User();

        $serviceMock = $this->getUserServiceMock();
        $serviceMock->expects($this->any())
                ->method('getUser')
                ->with($this->equalTo($userId))
                ->will($this->returnValue($expectedUser));
        $serviceMock->expects($this->any())
                ->method('persistFormData')
                ->will($this->returnValue($userId));

        $this->serviceManagerMocker->setupMockForName(
                'Application\Service\UserService', $serviceMock);
        $this->serviceManagerMocker->setupFormFactoryMock(true);

        $this->dispatch(self::PATH_EDIT_USER . "/id/$userId", 'POST', array());
        $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
    }

    public function testAccountActionWithUserRole() {
        $userId = 2;
        $isAccount = true;
        $expectedRole = 'user';

        $entityUserMock = $this->getUserRoleWithMock($expectedRole);

        $serviceMock = $this->getUserServiceMock();
        $serviceMock->expects($this->any())
                ->method('getUser')
                ->with($this->equalTo($userId))
                ->will($this->returnValue($entityUserMock));
        $serviceMock->expects($this->any())
                ->method('persistFormData')
                ->will($this->returnValue($userId));

        $this->serviceManagerMocker->setupMockForName(
                'Application\Service\UserService', $serviceMock);
        $this->serviceManagerMocker->setupFormFactoryMock(true);

        $userFieldset = array(
            'user' => array(
                'role-id' => $expectedRole
            )
        );

        $accountPath = $this->constructUrlWithTestApplication(self::PATH_EDIT_USER . "/id/$userId/isAccount/$isAccount");
        $this->dispatch($accountPath, 'POST', $userFieldset);
        $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
    }

    public function testAccountActionWithAdminRole() {
        $userId = 2;
        $isAccount = true;
        $expectedRole = 'admin';

        $entityUserMock = $this->getUserRoleWithMock($expectedRole);

        $serviceMock = $this->getUserServiceMock();
        $serviceMock->expects($this->any())
                ->method('getUser')
                ->with($this->equalTo($userId))
                ->will($this->returnValue($entityUserMock));
        $serviceMock->expects($this->any())
                ->method('persistFormData')
                ->will($this->returnValue($userId));

        $this->serviceManagerMocker->setupMockForName(
                'Application\Service\UserService', $serviceMock);
        $this->serviceManagerMocker->setupFormFactoryMock(true);

        $userFieldset = array(
            'user' => array(
                'role-id' => $expectedRole
            )
        );

        $accountPath = $this->constructUrlWithTestApplication(self::PATH_EDIT_USER . "/id/$userId/isAccount/$isAccount");
        $this->dispatch($accountPath, 'POST', $userFieldset);
        $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
    }

    public function testForgotPasswordActionCanBeAccessed() {
        $this->dispatchWithTestApplication('/user/forgot-password');
        $this->accessAsserts();
        $this->assertResponseStatusCode(self::HTTP_OK);
    }

    public function testForgotPasswordActionWithValidPost() {
        // Mocking the form with valid result
        $this->getServiceManagerMocker()->setupFormFactoryMock(true);

        // Mocking user service
        $userServiceMock = $this->getUserServiceMock();
        $userServiceMock->expects($this->once())
                ->method('sendForgotPasswordEmail')
                ->will($this->returnValue('anything'));
        $this->getServiceManagerMocker()->setupMockForName(
                'Application\Service\UserService', $userServiceMock);

        $forgotPasswordPath = $this->constructUrlWithTestApplication("/user/forgot-password");
        $this->dispatch($forgotPasswordPath, 'POST', array());
        $this->assertResponseStatusCode(self::HTTP_OK);
    }

    public function testResetPasswordActionCanBeAccessed() {
        // Input values
        $securityKey = '231';

        // Mocking user service
        $userServiceMock = $this->getUserServiceMock();
        $userServiceMock->expects($this->once())
                ->method('resetPassword')
                ->with($securityKey)
                ->will($this->returnValue('anything'));
        $this->getServiceManagerMocker()->setupMockForName(
                'Application\Service\UserService', $userServiceMock);

        $this->dispatchWithTestApplication("/user/reset-password/key/$securityKey");
        $this->accessAsserts('/wildcard');
        $this->assertResponseStatusCode(self::HTTP_OK);
    }

    public function testDeleteManyActionCanBeAccessed() {
        // Input
        $list = '1,2,3';
        $userIds = array(
            'delete_list' => $list
        );

        // Mocking user service
        $userServiceMock = $this->getUserServiceMock();
        $userServiceMock->expects($this->once())
                ->method('deleteMany')
                ->with($list)
                ->will($this->returnValue(1));
        $this->serviceManagerMocker->setupMockForName(
                'Application\Service\UserService', $userServiceMock);

        $this->dispatchWithTestApplication("/user/delete-many", 'POST', $userIds);
        $this->accessAsserts();
        $this->assertResponseStatusCode(self::HTTP_REDIRECTION);
        $this->assertRedirectToIndex();
    }

    private function getUserServiceMock() {
        return $this->getMockBuilder('Application\Service\UserService')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getUserRoleWithMock($expectedRole) {
        $entityRoleMock = $this->getRoleEntityMock($expectedRole);

        $entityUserMock = $this->getUserEntityMock();
        $entityUserMock->expects($this->any())
                ->method('getRoles')
                ->will($this->returnValue(array(
                            $entityRoleMock
        )));

        return $entityUserMock;
    }

    private function getUserEntityMock() {
        return $this->getMockBuilder('Application\Entity\User')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getRoleEntityMock($role) {
        $entityRoleMock = $this->getMockBuilder('Application\Entity\Role')
                ->disableOriginalConstructor()
                ->getMock();
        $entityRoleMock->expects($this->any())
                ->method('getRoleId')
                ->will($this->returnValue($role));
        return $entityRoleMock;
    }

    private function getAuthServiceMock() {
        $zfUserAuthServiceMock = $this->getMockBuilder(
                        '\Zend\Authentication\AuthenticationService')
                ->disableOriginalConstructor()
                ->getMock();
        $this->getServiceManagerMocker()->setupMockForName(
                'zfcuser_auth_service', $zfUserAuthServiceMock);
        return $zfUserAuthServiceMock;
    }

    private function accessAsserts($wildcard = '') {
        $this->assertModuleName('Application');
        $this->assertControllerName('Controller\User');
        $this->assertControllerClass('UserController');
        $this->assertMatchedRouteName('base' . $wildcard);
    }

    private function assertRedirectToIndex() {
        $this->assertRedirectTo($this->constructUrlWithTestApplication(self::PATH_INDEX));
    }

}
