<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryServiceTest extends TestCase
{
    public function testCreateCategory(): void
    {
        /** @var EntityManagerInterface|MockObject $em */
        $em = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $em->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($category) {
                /** @var Category $category */
                return $category instanceof Category
                    && $category->getName() === '_NAME_';
            }));

        $em->expects($this->once())
            ->method('flush');

        $service = new CategoryService($em);
        $category = $service->create('_NAME_');

        $this->assertEquals('_NAME_', $category->getName());
    }
}
