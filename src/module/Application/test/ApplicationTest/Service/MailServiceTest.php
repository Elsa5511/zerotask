<?php

namespace ApplicationTest\Service;

use ApplicationTest\BaseSetUp;

class MailServiceTest extends BaseSetUp
{

    public function testSendEmailOk()
    {
        $from = 'webmaster@test.com';
        $name = 'webmaster';
        $to = 'benito@benito.com';
        $subject = 'test mail';
        $template = null;
        $htmlContent = 'hello world';

        $message = new \Zend\Mail\Message;
        $message->addFrom($from, $name)
                ->addTo($to)
                ->setSubject($subject);

        $message->setBody($htmlContent);
        $message->setEncoding("UTF-8");

        $serviceMailMock = $this->getServiceMailTransportMock();
        $serviceMailMock->expects($this->exactly(1))
                ->method('send')
                ->will($this->returnCallback(function($actualMessage) {
                                    $from = 'webmaster@test.com';
                                    $name = 'webmaster';
                                    $to = 'benito@benito.com';
                                    $subject = 'test mail';
                                    $htmlContent = 'hello world';

                                    $message = new \Zend\Mail\Message;
                                    $message->addFrom($from, $name)
                                    ->addTo($to)
                                    ->setSubject($subject);

                                    $message->setBody($htmlContent);
                                    $message->setEncoding("UTF-8");
                                    \PHPUnit_Framework_Assert::assertEquals($actualMessage->getTo(), $message->getTo());
                                    \PHPUnit_Framework_Assert::assertEquals($actualMessage->getFrom(), $message->getFrom());
                                    \PHPUnit_Framework_Assert::assertEquals($actualMessage->getSubject(), $message->getSubject());
                                    $messageParts = $actualMessage->getBody()->getParts();
                                    $messagePart = $messageParts[0];
                                    $actualMessageContent = $messagePart->getContent();

                                    \PHPUnit_Framework_Assert::assertEquals($actualMessageContent, $htmlContent);
                                }));
        $this->getMailService($serviceMailMock)->sendMessage($to, $subject, $template, $htmlContent);
    }

    private function getServiceMailTransportMock()
    {

        return $this->getMockBuilder('Zend\Mail\Transport\Sendmail')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getViewRenderMock()
    {

        return $this->getMockBuilder('Zend\View\Renderer\PhpRenderer')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getViewHelperManagerMock(){
        return $this->getMockBuilder('viewHelperManager')
                        ->disableOriginalConstructor()
                        ->getMock();

        
    }
    public function getMailService($mockTransport)
    {

        $viewRenderMock = $this->getViewHelperManagerMock();
        $from = array('email' => 'webmaster@test.com', 'name' => 'webmaster');
        return new \Application\Service\MailService($mockTransport, $from,$viewRenderMock,false );
    }

}