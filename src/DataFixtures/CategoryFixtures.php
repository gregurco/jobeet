<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager) : void
    {
        $designCategory = new Category();
        $designCategory->setName('Design');

        $programmingCategory = new Category();
        $programmingCategory->setName('Programming');

        $managerCategory = new Category();
        $managerCategory->setName('Manager');

        $administratorCategory = new Category();
        $administratorCategory->setName('Administrator');

        $manager->persist($designCategory);
        $manager->persist($programmingCategory);
        $manager->persist($managerCategory);
        $manager->persist($administratorCategory);

        $manager->flush();

        $this->addReference('category-design', $designCategory);
        $this->addReference('category-programming', $programmingCategory);
        $this->addReference('category-manager', $managerCategory);
        $this->addReference('category-administrator', $administratorCategory);
    }

    /**
     * @return int
     */
    public function getOrder() : int
    {
        return 1;
    }
}
