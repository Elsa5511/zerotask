<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\View\ViewEvent;
use Zend\View\Renderer\PhpRenderer;
use Zend\Mvc\ModuleRouteListener;
use Doctrine\Common\Collections\ArrayCollection;

class Module {

    public function onBootstrap(MvcEvent $mvcEvent) {
        $eventManager = $mvcEvent->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $serviceManager = $mvcEvent->getApplication()->getServiceManager();

        $translator = $serviceManager->get('translator');
        $config = $serviceManager->get('Config');

        $authorizationServic = $serviceManager->get('zfcuser_auth_service');
        $this->handleAuthenticationAndAuthorization($authorizationServic, $config, $mvcEvent, $serviceManager, $translator);


        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'validateThatUserCanAccessApplication'), 0);

        /* Globally setting dateformat to nb_NO @TODO: Adapt to user's language */
        $sharedEvents = $eventManager->getSharedManager();
        $sharedEvents->attach('Zend\View\View', ViewEvent::EVENT_RENDERER_POST, function($event) {
            $renderer = $event->getRenderer();
            if ($renderer instanceof PhpRenderer) {
                $renderer->plugin("dateFormat")->setTimezone("Europe/Oslo")->setLocale("nb_NO");
            }
        });
    }

    public function validateThatUserCanAccessApplication(MvcEvent $mvcEvent) {
        $request = $mvcEvent->getApplication()->getRequest();
        if ($request->isXmlHttpRequest()) {
            return;
        }
        $applicationThatUserCanAccessArray = $this->getApplicationThatUserCanAccessArray($mvcEvent);
        $currentApplication = $this->getCurrentApplication($mvcEvent);
        $userCanAccessApplication = false;
        foreach ($applicationThatUserCanAccessArray as $applicationThatUserCanAccess) {
            if ($currentApplication === strtolower($applicationThatUserCanAccess->getName())) {
                $userCanAccessApplication = true;
            }
        }

        if ($currentApplication !== null && !$userCanAccessApplication) {
            $controller = $mvcEvent->getTarget();
            $this->displayAccessViolationErrorMessage($mvcEvent, $currentApplication);
            return $controller->redirect()->toRoute('home');
        }
    }

    private function getApplicationThatUserCanAccessArray($mvcEvent) {
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();
        $controller = $mvcEvent->getTarget();
        $currentUser = $controller->zfcUserAuthentication()->getIdentity();
        if ($currentUser === null) {
            /* For guest access - se acl.local.php.dist */
            $guestArray = new ArrayCollection();
            $config = $serviceManager->get('Config');
            if(isset($config['guest_access_applications'])) {
                $applicationService = $serviceManager->get('Application\Service\ApplicationService');
                foreach($config['guest_access_applications'] as $application) {
                    $guestArray->add($applicationService->getApplication($application));
                }
            }
            return $guestArray;
        }                    
        else if ($currentUser->getAccessibleApplications() !== null && $currentUser->getAccessibleApplications()->count() > 0) {
            return $currentUser->getAccessibleApplications();
        } else {
            $serviceManager = $mvcEvent->getApplication()->getServiceManager();
            $userService = $serviceManager->get('Application\Service\UserService');
            $user = $userService->getUser($currentUser->getUserId());
            return $user->getAccessibleApplications();
        }
    }

    private function getCurrentApplication($mvcEvent) {
        $routeMatch = $mvcEvent->getRouteMatch();
        return $routeMatch->getParam('application');
    }

    private function displayAccessViolationErrorMessage($mvcEvent, $currentApplication) {
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();
        $translator = $serviceManager->get('translator');
        $controller = $mvcEvent->getTarget();
        $errorMessage = sprintf($translator->translate('Invalid application: %s'), strtoupper($currentApplication));
        $controller->flashMessenger()
                ->setNamespace('error')
                ->addMessage($errorMessage);
    }

    protected function handleAuthenticationAndAuthorization($auth, $config, $e, $sm, $translator) {
        if ($auth->hasIdentity()) {
            $vidumConfig = $config['vidum'];

            if ($auth->getIdentity()->getLastActivity()) {
                $lastActivityDiffNOW = $auth->getIdentity()->getLastActivity()->diff()->format('%i') + $vidumConfig['base']['inactivity_timeout'];

                if ($lastActivityDiffNOW < 0) {
                    $auth->clearIdentity();
                    $response = $e->getResponse();
                    $response
                            ->setHeaders($response->getHeaders()->addHeaderLine('Location', $_SERVER['REQUEST_URI']))
                            ->setStatusCode(302)
                            ->sendHeaders();
                    exit();
                }
            } else {
                $auth->getIdentity()->setLastActivity('NOW');
            }

            $language = $auth->getIdentity()->getLanguage();

            if (!is_object($language)) {
                $language = $sm
                                ->get('Doctrine\ORM\EntityManager')->find('Application\Entity\Language', $language);
                $identity = $auth->getIdentity();
                $identity->setLanguage($language);

                $auth->getStorage()->write($identity);
            }

            if (count($auth->getIdentity()->getRoles()) == 0) {
                $roles = $sm
                                ->get('Doctrine\ORM\EntityManager')->find('Application\Entity\User', $auth->getIdentity()->getUserId())->getRoles();

                foreach ($roles as $role) {
                    $auth->getIdentity()->addRole($role);
                }
            }

            $translator->setLocale($language->getIsocode());

            $e->getViewModel()->setVariables(
                    array('language' => $language->getIsocode())
            );
        } elseif(isset($config['guest_locale'])) {
            /* For guest access - se acl.local.php.dist */
            $translator->setLocale($config['guest_locale']);
            $e->getViewModel()->setVariables(
                array('language' => $config['guest_locale'])
            );
        } else {
            $e->getViewModel()->setVariables(
                array('language' => substr($translator->getLocale(), 0, 2))
            );
        }
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getViewHelperConfig() {
        return include __DIR__ . '/config/view.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'invokables' => array(
                'Application\View\RedirectionStrategy' => 'Application\View\RedirectionStrategy'
            ),
            'factories' => array(
                'doctrine.entitymanager.orm_default' => new \Acl\Factory\AclEntityManagerFactory('orm_default'),
                'Application\Form\FormFactory' => function ($sm) {
                    $formFactory = new \Application\Form\FormFactory();
                    $formFactory->setTranslator($sm->get('translator'));
                    $formFactory->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'Vidum\Config' => 'Application\Factory\Service\ConfigServiceFactory',
                'Application\Service\ApplicationService' => 'Application\Factory\Service\ApplicationServiceFactory',
                'Application\Service\ApplicationFeatureService' => 'Application\Factory\Service\ApplicationFeatureServiceFactory',
                'Application\Service\UserService' => 'Application\Factory\Service\UserServiceFactory',
                'Application\Service\LanguageService' => 'Application\Factory\Service\LanguageServiceFactory',
                'Application\Service\OrganizationService' => 'Application\Factory\Service\OrganizationServiceFactory',
                'Application\Service\LocationService' => 'Application\Factory\Service\LocationServiceFactory',
                'Application\Service\RoleService' => 'Application\Factory\Service\RoleServiceFactory',
                'Application\Service\MailService' => 'Application\Factory\Service\MailServiceFactory',
                'Application\Service\ImportingService' => 'Application\Factory\Service\ImportingServiceFactory',
                'Application\Service\LadocImportingService' => 'Application\Factory\Service\LadocImportingServiceFactory',
                'Application\View\MixedStrategy' => 'Application\Factory\Service\MixedStrategyServiceFactory',
                'Application\Service\Cache\LocationCacheService' => 'Application\Factory\Service\LocationCacheServiceFactory',
                'ZendCacheStorageFactory' => function() {
                    return \Zend\Cache\StorageFactory::factory(
                        array(
                            'adapter' => array(
                                'name' => 'filesystem',
                                'options' => array(
                                    'dirLevel' => 2,
                                    'cacheDir' => './data/cache',
                                    'dirPermission' => 0755,
                                    'filePermission' => 0666,
                                    'namespaceSeparator' => '-db-',
                                    'ttl' => 86400 //24 hours
                                ),
                            ),
                            'plugins' => array('serializer'),
                        )
                    );
                },
                'Utility\Image' => function() {
                    return new \Application\Utility\Image();
                },
                'PhpExcel' => function() {
                    return new \PHPExcel();
                },
                'PhpWord' => function() {
                    return new \PHPWord();
                },
            ),
        );
    }

}
