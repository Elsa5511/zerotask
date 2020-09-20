<?php

namespace Application\Factory\Service;

use Application\Service\LanguageService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LanguageServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $languageService = new LanguageService(array(
            'entity_manager' => $entityManager
        ));

        return $languageService;
    }
}
