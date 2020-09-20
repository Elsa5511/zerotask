<?php

namespace Application\Factory\Service;

use Application\Service\UserService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $userService = new UserService(array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'mail_service' => $serviceLocator->get('Application\Service\MailService'),
                'zfcuser_user_service'=>$serviceLocator->get('zfcuser_user_service'),
                'translator' => $serviceLocator->get('translator')
            ),
            'templateMap' => array(
                'tpl/email/password-about-expire' => __DIR__ . '/../../../../view/tpl/email/password-about-expire.phtml',
                'tpl/email/password-expired' => __DIR__ . '/../../../../view/tpl/email/password-expired.phtml'
            ),
        ));

        return $userService;
    }

}

?>
