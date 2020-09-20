<?php

namespace Application\Service;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\ViewModel;

class MailService
{

    private $from;
    private $transport;
    private $viewRender;
    private $isConsoleRequest;

    public function __construct($transport, $from, $viewRender, $isConsoleRequest)
    {
        $this->transport = $transport;
        $this->viewRender = $viewRender;
        $this->from = $from;
        $this->isConsoleRequest = $isConsoleRequest;
    }

    private function getRenderedTemplate($template)
    {

        if ($this->isConsoleRequest) {
            return $this->getConsoleRenderedTemplate($template);
        }

        foreach ($template['params'] as $key => $param) {
            $this->viewRender->{$key} = $param;
        }
        $htmlContent = $this->viewRender->render($template['source'], null);
        return $htmlContent;
    }

    private function getConsoleRenderedTemplate($template)
    {

        $viewModel = new ViewModel($template['params']);
        $viewModel->setTemplate($template['source']);

        $renderer = $this->viewRender['renderer'];
        $map = new \Zend\View\Resolver\TemplateMapResolver($template['map']);

        $this->viewRender['resolver']->attach($map);
        $htmlContent = $renderer->render($viewModel);
        return $htmlContent;
    }

    /**
     * 
     * @param string|array $to 
     * if array $to = array('to'=>'mainemail@mail.com','copy'=>array('email1@mail.com','email2@mail.com'))
     * 
     * @param string $subject
     * @param html $template
     * @param html $htmlContent
     */
    public function sendMessage($to, $subject, $template = null, $htmlContent = '')
    {
        if ($template) {
            $htmlContent = $this->getRenderedTemplate($template);
        }
        $html = new MimePart($htmlContent);
        $html->type = "text/html";
        $body = new MimeMessage();
        $body->setParts(array($html));
        $message = new Message();
        $message->addFrom($this->from['email'], $this->from['name'])
                ->setSubject($subject);
        if (is_array($to)) {

            $message->addTo($to['to']);
            foreach ($to['copy'] as $toCc) {
                $message->addTo($toCc);
            }
        } else {
            $message
                    ->addTo($to);
        }



        $message->setBody($body);
        $message->setEncoding("UTF-8");
        $this->transport->send($message);
    }

}