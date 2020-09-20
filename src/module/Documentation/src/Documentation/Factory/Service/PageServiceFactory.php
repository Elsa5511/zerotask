<?php

namespace Documentation\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Documentation\Service\PageService;

class PageServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $config = $serviceLocator->get('Vidum\Config');
        $pageService = new PageService(array(
            'entity_manager' => $entityManager,
            'image' => $config['image'],
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator'),
                'equipmentTaxonomyService' => $serviceLocator->get('Equipment\Service\EquipmentTaxonomyService')
            )
        ));

        return $pageService;
    }

}
