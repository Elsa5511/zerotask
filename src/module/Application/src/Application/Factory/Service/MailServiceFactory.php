<?php

namespace Application\Factory\Service;

use Application\Service\MailService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\SmtpOptions;

class MailServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $transport = $this->configureMailTransport($config['vidum']['base']['mail']);
        $from = $config['vidum']['base']['mail']['from'];
        $isConsoleRequest = php_sapi_name() === 'cli';

        if (!$isConsoleRequest) {
            $renderer = $serviceLocator->get('ViewRenderer');
        } else {
            $renderer = $this->getConsoleRenderer($serviceLocator);
        }
        $mailService = new MailService($transport, $from, $renderer, $isConsoleRequest);

        return $mailService;
    }

    private function getConsoleRenderer($serviceLocator)
    {
        $renderer = new \Zend\View\Renderer\PhpRenderer();

        $resolver = new \Zend\View\Resolver\AggregateResolver();
        $renderer->setResolver($resolver);

        $renderer->setHelperPluginManager($serviceLocator->get('ViewHelperManager'));
        $rendererArray = array('renderer' => $renderer, 'resolver' => $resolver);
        return $rendererArray;
    }

    private function configureMailTransport($mailConfiguration)
    {

        $transport = new SendmailTransport();
        if ($mailConfiguration['smtp'] === true) {
            $transport = new SmtpTransport();
            $options = new SmtpOptions($mailConfiguration['smtp_options']);
            $transport->setOptions($options);
        } else {
            $transport = new SendmailTransport();
        }
        return $transport;
    }

}

?>
