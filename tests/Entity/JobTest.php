<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Job;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{
    public function testGettersSettersAndDefaultValues(): void
    {
        $expires = new \DateTime('2018-01-01');

        $job = (new Job())
            ->setUrl('https://server.local')
            ->setEmail('email@server.local')
            ->setActivated(true)
            ->setPublic(true)
            ->setDescription('_DESCRIPTION_')
            ->setToken('_TOKEN_')
            ->setLogo('_LOGO_')
            ->setLocation('_LOCATION_')
            ->setPosition('_POSITION_')
            ->setHowToApply('_HOW_TO_APPLY_')
            ->setType('_TYPE_')
            ->setExpiresAt($expires);

        $this->assertEquals('https://server.local', $job->getUrl());
        $this->assertEquals('email@server.local', $job->getEmail());
        $this->assertTrue($job->isActivated());
        $this->assertTrue($job->isPublic());
        $this->assertEquals('_DESCRIPTION_', $job->getDescription());
        $this->assertEquals('_TOKEN_', $job->getToken());
        $this->assertEquals('_LOGO_', $job->getLogo());
        $this->assertEquals('_LOCATION_', $job->getLocation());
        $this->assertEquals('_POSITION_', $job->getPosition());
        $this->assertEquals('_HOW_TO_APPLY_', $job->getHowToApply());
        $this->assertEquals('_TYPE_', $job->getType());
        $this->assertEquals($expires, $job->getExpiresAt());

        $this->assertNull($job->getId());
        $this->assertNull($job->getCategory());
        $this->assertNull($job->getCategoryName());

        $category = (new Category())->setName('_CATEGORY_');
        $job->setCategory($category);
        $this->assertEquals('_CATEGORY_', $job->getCategoryName());
        $this->assertSame($category, $job->getCategory());
    }

    /**
     * @throws \Exception
     */
    public function testPrePersistSetsDateTimeFields(): void
    {
        /** @var MockObject|Job $job */
        $job = $this->getMockBuilder(Job::class)
            ->setMethods(['getCurrentDateTime'])
            ->getMock();

        $date = new \DateTime();

        $job
            ->method('getCurrentDateTime')
            ->willReturn($date);

        $this->assertNull($job->getCreatedAt());
        $this->assertNull($job->getUpdatedAt());
        $this->assertNull($job->getExpiresAt());

        $job->prePersist();

        $this->assertSame($date, $job->getCreatedAt());
        $this->assertSame($date, $job->getUpdatedAt());
        $this->assertInstanceOf(\DateTime::class, $job->getExpiresAt());
        $this->assertEquals(
            (clone $date)->modify('+30 days')->getTimestamp(),
            $job->getExpiresAt()->getTimestamp()
        );
    }

    /**
     * @throws \Exception
     */
    public function testPreUpdateUpdatesDateTimeFields(): void
    {
        /** @var MockObject|Job $job */
        $job = $this->getMockBuilder(Job::class)
            ->setMethods(['getCurrentDateTime'])
            ->getMock();

        $date = new \DateTime();

        $job
            ->method('getCurrentDateTime')
            ->willReturn($date);

        $job->preUpdate();

        $this->assertSame($date, $job->getUpdatedAt());
        $this->assertNull($job->getCreatedAt());
        $this->assertNull($job->getExpiresAt());
    }
}
