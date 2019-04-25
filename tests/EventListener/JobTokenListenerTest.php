<?php

namespace App\EventListener;

use App\Entity\Job;
use App\Tests\EventListener\Stub\NonEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class JobTokenListenerTest extends TestCase
{
    public function testJobTokenIsSet(): void
    {
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->createMock(EntityManagerInterface::class);

        $args = new LifecycleEventArgs(new Job(), $em);
        $listener = new JobTokenListener();
        $listener->prePersist($args);

        $this->assertNotEmpty($args->getObject()->getToken());
    }

    public function testNonJobIgnored(): void
    {
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->createMock(EntityManagerInterface::class);

        $args = new LifecycleEventArgs(new NonEntity(), $em);
        $listener = new JobTokenListener();
        $listener->prePersist($args);

        $this->assertEmpty($args->getObject()->getToken());
    }
}
