<?php

namespace App\EventListener;

use App\Entity\Job;
use App\Service\FileUploader;
use App\Tests\EventListener\Stub\NonEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class JobUploadListenerTest extends TestCase
{
    public function testPrePersistIgnoresNonJobs(): void
    {
        /** @var FileUploader|MockObject $uploader */
        $uploader = $this->createMock(FileUploader::class);
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->createMock(EntityManagerInterface::class);

        $entity = $this->getMockBuilder(NonEntity::class)
            ->getMock();
        $entity->expects($this->never())
            ->method('getLogo');

        $args = new LifecycleEventArgs($entity, $em);
        $listener = new JobUploadListener($uploader);
        $listener->prePersist($args);
    }

    public function testPreUpdateIgnoresNonJobs(): void
    {
        /** @var FileUploader|MockObject $uploader */
        $uploader = $this->createMock(FileUploader::class);
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->createMock(EntityManagerInterface::class);

        $entity = $this->getMockBuilder(NonEntity::class)
            ->getMock();
        $entity->expects($this->never())
            ->method('getLogo');

        $changeSet = [];
        $args = new PreUpdateEventArgs($entity, $em, $changeSet);
        $listener = new JobUploadListener($uploader);
        $listener->preUpdate($args);
    }

    public function testPostLoadIgnoresNonJobs(): void
    {
        /** @var FileUploader|MockObject $uploader */
        $uploader = $this->createMock(FileUploader::class);
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->createMock(EntityManagerInterface::class);

        $entity = $this->getMockBuilder(NonEntity::class)
            ->getMock();
        $entity->expects($this->never())
            ->method('getLogo');

        $changeSet = [];
        $args = new PreUpdateEventArgs($entity, $em, $changeSet);
        $listener = new JobUploadListener($uploader);
        $listener->postLoad($args);
    }
}
