<?php

namespace App\Tests\Form\Admin;

use App\Entity\Category;
use App\Form\Admin\CategoryType;
use App\Tests\Form\AbstractTypeTest;

/**
 * Class CategoryTypeTest
 * @group unit
 */
class CategoryTypeTest extends AbstractTypeTest
{
    public function testSubmitValidData(): void
    {
        $testCategory = new Category();
        $form = $this->factory->create(CategoryType::class, $testCategory);

        $submittedData = [
            'name' => '_COMPANY_',
        ];

        $form->submit($submittedData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($submittedData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
