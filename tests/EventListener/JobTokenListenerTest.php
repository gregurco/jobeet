<?php

namespace App\EventListener;

use App\Entity\Affiliate;
use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class JobTokenListenerTest extends TestCase
{
    public function testJobTokenIsSet(): void
    {
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $args = new LifecycleEventArgs(new Job(), $em);
        $listener = new JobTokenListener();
        $listener->prePersist($args);

        $this->assertNotEmpty($args->getObject()->getToken());
    }

    public function testNonJobIgnored(): void
    {
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $mock = $this->getMockBuilder(Affiliate::class)
            ->getMock();
        $mock->expects($this->never())
            ->method('setToken');

        $args = new LifecycleEventArgs($mock, $em);
        $listener = new JobTokenListener();
        $listener->prePersist($args);

        $this->assertEmpty($args->getObject()->getToken());
    }
}
