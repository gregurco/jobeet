<?php

namespace App\Tests\EventListener;

use App\Entity\Affiliate;
use App\EventListener\AffiliateTokenListener;
use App\Tests\EventListener\Stub\NonEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AffiliateTokenListenerTest extends TestCase
{
    public function testAffiliateTokenIsSet(): void
    {
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->createMock(EntityManagerInterface::class);

        $args = new LifecycleEventArgs(new Affiliate(), $em);
        $listener = new AffiliateTokenListener();
        $listener->prePersist($args);

        $this->assertNotEmpty($args->getObject()->getToken());
    }

    public function testNonAffiliateIgnored(): void
    {
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->createMock(EntityManagerInterface::class);

        $args = new LifecycleEventArgs(new NonEntity(), $em);
        $listener = new AffiliateTokenListener();
        $listener->prePersist($args);

        $this->assertEmpty($args->getObject()->getToken());
    }
}
