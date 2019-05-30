<?php

namespace App\Tests\Service;

use App\Entity\Affiliate;
use App\Service\MailerService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class MailerServiceTest
 * @group unit
 */
class MailerServiceTest extends TestCase
{
    public function testCreateCategory(): void
    {
        /** @var Swift_Mailer|MockObject $mailer */
        $mailer = $this->getMockBuilder(Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function ($message) {
                return $message instanceof Swift_Message
                    && $message->getBody() === '_TEMPLATE_';
            }));

        /** @var EngineInterface|MockObject $templating */
        $templating = $this->getMockBuilder(EngineInterface::class)
            ->getMock();
        $templating->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('emails/affiliate_activation.html.twig'),
                $this->callback(function ($parameters) {
                    return count($parameters) === 1
                        && $parameters['token'] ?? '' === '_TOKEN_';
                })
            )
            ->willReturn('_TEMPLATE_');

        $affiliate = (new Affiliate())
            ->setToken('_TOKEN_');

        $mailer = new MailerService($mailer, $templating);
        $mailer->sendActivationEmail($affiliate);
    }
}
