<?php

namespace App\Tests\Entity;

use App\Entity\Affiliate;
use App\Entity\Category;
use App\Entity\Job;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testGettersSettersAndDefaultValues(): void
    {
        $category = (new Category())
            ->setName('_NAME_')
            ->setSlug('_SLUG_');

        $this->assertEquals('_NAME_', $category->getName());
        $this->assertEquals('_SLUG_', $category->getSlug());
        $this->assertNull($category->getId());
    }

    public function testAffiliateCollectionManipulations(): void
    {
        $category = new Category();

        $emptyCollection = new ArrayCollection();
        $this->assertEquals($emptyCollection, $category->getAffiliates());

        $first = (new Affiliate())
            ->setEmail('first@server.local');
        $second = (new Affiliate())
            ->setEmail('second@server.local');
        $category
            ->addAffiliate($first)
            ->addAffiliate($second);

        $this->assertEquals(new ArrayCollection([$first, $second]), $category->getAffiliates());

        $category
            ->removeAffiliate($first)
            ->removeAffiliate($second);

        $this->assertEquals($emptyCollection, $category->getAffiliates());

    }
    public function testJobCollectionManipulations(): void
    {
        $category = new Category();

        $emptyCollection = new ArrayCollection();
        $this->assertEquals($emptyCollection, $category->getActiveJobs());
        $this->assertEquals($emptyCollection, $category->getJobs());

        $first = (new Job())
            ->setDescription('_FIRST_')
            ->setActivated(false)
            ->setExpiresAt(new \DateTime('+3 days'));
        $second = (new Job())
            ->setDescription('_SECOND_')
            ->setActivated(true)
            ->setExpiresAt(new \DateTime('+3 days'));
        $third = (new Job())
            ->setDescription('_THIRD_')
            ->setActivated(true)
            ->setExpiresAt(new \DateTime('-3 days'));
        $category
            ->addJob($first)
            ->addJob($second)
            ->addJob($third);

        $this->assertEquals(new ArrayCollection([$first, $second, $third]), $category->getJobs());
        $this->assertEquals($second, $category->getActiveJobs()->first());
        $this->assertEquals(1, $category->getActiveJobs()->count());

        $category
            ->removeJob($first)
            ->removeJob($second)
            ->removeJob($third);

        $this->assertEquals(new ArrayCollection(), $category->getAffiliates());
    }
}
