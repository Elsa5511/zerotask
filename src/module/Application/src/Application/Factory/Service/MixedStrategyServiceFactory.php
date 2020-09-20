<?php

/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Factory\Service;

use Application\View\MixedStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory responsible of instantiating {@see \BjyAuthorize\View\UnauthorizedStrategy}
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class MixedStrategyServiceFactory implements FactoryInterface
{

    /**
     * {@inheritDoc}
     *
     * @return \Application\View\MixedStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $bjyAuthorizeConfig = $serviceLocator->get('BjyAuthorize\Config');
        $config = $serviceLocator->get('Config');

        return new MixedStrategy(array(
            'template' => $bjyAuthorizeConfig['template'],
            'auth' => $serviceLocator->get('zfcuser_auth_service'),
            'after_login_redirect_route' => $config['zfcuser']['login_redirect_route']
        ));
    }

}
