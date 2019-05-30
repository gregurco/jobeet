<?php

namespace App\Tests\Form;

use App\Entity\Affiliate;
use App\Entity\Category;
use App\Form\AffiliateType;

/**
 * Class AffiliateTypeTest
 * @group unit
 */
class AffiliateTypeTest extends AbstractTypeTest
{
    public function testSubmitValidData(): void
    {
        $testAffiliate = new Affiliate();
        $form = $this->factory->create(AffiliateType::class, $testAffiliate);

        $submittedData = [
            'url' => 'https://test.local',
            'email' => 'email@server.local',
            'categories' => [new Category()],
        ];

        $form->submit($submittedData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($submittedData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityClassNames(): array
    {
        return [Category::class];
    }
}
