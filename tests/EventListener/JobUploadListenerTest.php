<?php

namespace App\EventListener;

use App\Entity\Job;
use App\Service\FileUploader;
use App\Tests\EventListener\Stub\NonEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function testPrePersistUploadsNewJobFiles(): void
    {
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->createMock(EntityManagerInterface::class);
        /** @var UploadedFile|MockObject $file */
        $file = $this->createMock(UploadedFile::class);
        $job = (new Job())->setLogo($file);

        /** @var FileUploader|MockObject $uploader */
        $uploader = $this->getMockBuilder(FileUploader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $uploader->expects($this->once())
            ->method('upload')
            ->with($this->equalTo($file))
            ->willReturn('_PATH_');

        $args = new LifecycleEventArgs($job, $em);
        $listener = new JobUploadListener($uploader);
        $listener->prePersist($args);

        $this->assertEquals('_PATH_', $job->getLogo());
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

    public function testPreUpdateUploadsNewJobFiles(): void
    {
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->createMock(EntityManagerInterface::class);
        /** @var UploadedFile|MockObject $file */
        $file = $this->createMock(UploadedFile::class);
        $job = (new Job())->setLogo($file);

        /** @var FileUploader|MockObject $uploader */
        $uploader = $this->getMockBuilder(FileUploader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $uploader->expects($this->once())
            ->method('upload')
            ->with($this->equalTo($file))
            ->willReturn('_PATH_');

        $changeSet = [];
        $args = new PreUpdateEventArgs($job, $em, $changeSet);
        $listener = new JobUploadListener($uploader);
        $listener->preUpdate($args);

        $this->assertEquals('_PATH_', $job->getLogo());
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

    public function testPostLoadUploadsNewJobFiles(): void
    {
        $root = vfsStream::setup();
        $root->addChild(vfsStream::newFile('file.txt'));

        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->createMock(EntityManagerInterface::class);
        $job = (new Job())->setLogo('file.txt');
        /** @var FileUploader|MockObject $uploader */
        $uploader = $this->getMockBuilder(FileUploader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $uploader->expects($this->once())
            ->method('getTargetDirectory')
            ->willReturn('vfs://root');

        $args = new LifecycleEventArgs($job, $em);
        $listener = new JobUploadListener($uploader);
        $listener->postLoad($args);

        $this->assertInstanceOf(File::class, $job->getLogo());
        $this->assertEquals('vfs://root', $job->getLogo()->getPath());
        $this->assertEquals('file.txt', $job->getLogo()->getFilename());
    }
}
