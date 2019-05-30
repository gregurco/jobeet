<?php

namespace App\Tests\Service;

use App\Entity\Job;
use App\Repository\JobRepository;
use App\Service\JobHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class JobHistoryServiceTest
 * @group unit
 */
class JobHistoryServiceTest extends TestCase
{
    public function testAddJob(): void
    {
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        /** @var MockObject|SessionInterface $session */
        $session = $this->getMockBuilder(SessionInterface::class)
            ->getMock();
        $session->expects($this->once())
            ->method('get')
            ->with($this->equalTo('job_history'))
            ->willReturn([1, 2, 3]);
        $session->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo('job_history'),
                $this->callback(function ($jobs) {
                    return is_array($jobs)
                        && count($jobs) === 3
                        && array_shift($jobs) === 4;
                })
            );

        /** @var Job|MockObject $job */
        $job = $this->getMockBuilder(Job::class)
            ->getMock();
        $job->expects($this->once())
            ->method('getId')
            ->willReturn(4);

        $history = new JobHistoryService($session, $em);

        $history->addJob($job);
    }

    public function testGetJobs(): void
    {
        $repository = $this->getMockBuilder(JobRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repository->expects($this->exactly(3))
            ->method('findActiveJob')
            ->withConsecutive(
                [$this->equalTo(1)],
                [$this->equalTo(2)],
                [$this->equalTo(3)]
            )
            ->will(
                $this->onConsecutiveCalls(
                    (new Job())->setDescription('_1_'),
                    (new Job())->setDescription('_2_'),
                    (new Job())->setDescription('_3_')
                )
            );

        /** @var MockObject|SessionInterface $session */
        $session = $this->getMockBuilder(SessionInterface::class)
            ->getMock();
        $session->expects($this->once())
            ->method('get')
            ->with($this->equalTo('job_history'))
            ->willReturn([1, 2, 3]);

        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();
        $em->expects($this->once())
            ->method('getRepository')
            ->willReturn($repository);

        $history = new JobHistoryService($session, $em);
        $jobs = $history->getJobs();

        $this->assertCount(3, $jobs);
        $this->assertEquals('_1_', $jobs[0]->getDescription());
        $this->assertEquals('_2_', $jobs[1]->getDescription());
        $this->assertEquals('_3_', $jobs[2]->getDescription());
    }
}
