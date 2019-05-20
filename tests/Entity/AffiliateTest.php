<?php

namespace App\Tests\Entity;

use App\Entity\Affiliate;
use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class AffiliateTest extends TestCase
{
    public function testGettersSettersAndDefaultValues(): void
    {
        $firstCategory = (new Category())
            ->setName('_FIRST_');
        $secondCategory = (new Category())
            ->setName('_SECOND_');

        $collection = new ArrayCollection([$firstCategory, $secondCategory]);

        $affiliate = (new Affiliate())
            ->setToken('_TOKEN_')
            ->setActive(true)
            ->setEmail('email@server.local')
            ->setUrl('https://server.local')
            ->addCategory($firstCategory)
            ->addCategory($secondCategory);

        $this->assertTrue($affiliate->isActive());
        $this->assertEquals('_TOKEN_', $affiliate->getToken());
        $this->assertEquals('https://server.local', $affiliate->getUrl());
        $this->assertEquals('email@server.local', $affiliate->getEmail());
        $this->assertNull($affiliate->getCreatedAt());
        $this->assertNull($affiliate->getId());

        $this->assertEquals($collection, $affiliate->getCategories());
    }

    public function testRemoveCategories(): void
    {
        $firstCategory = (new Category())
            ->setName('_FIRST_');
        $secondCategory = (new Category())
            ->setName('_SECOND_');

        $affiliate = (new Affiliate())
            ->addCategory($firstCategory)
            ->addCategory($secondCategory);

        $affiliate
            ->removeCategory($firstCategory)
            ->removeCategory($secondCategory);

        $this->assertTrue($affiliate->getCategories()->isEmpty());
    }

    /**
     * @throws \Exception
     */
    public function testPrePersistSetsCreatedAt(): void
    {
        $affiliate = new Affiliate();

        $this->assertNull($affiliate->getCreatedAt());
        $affiliate->prePersist();

        $this->assertInstanceOf(\DateTime::class, $affiliate->getCreatedAt());
    }
}
