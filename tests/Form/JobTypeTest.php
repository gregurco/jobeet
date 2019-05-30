<?php

namespace App\Tests\Form;

use App\Entity\Category;
use App\Entity\Job;
use App\Form\JobType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class JobTypeTest
 * @group unit
 */
class JobTypeTest extends AbstractTypeTest
{
    public function testSubmitValidData(): void
    {
        $testJob = new Job();
        $form = $this->factory->create(JobType::class, $testJob);

        $submittedData = [
            'type' => 'full-time',
            'company' => '_COMPANY_',
            'logo' => $this->createMock(UploadedFile::class),
            'url' => 'https://test.local',
            'position' => '_POSITION_',
            'location' => '_LOCATION_',
            'description' => '_DESCRIPTION_',
            'howToApply' => '_HOW_TO_APPLY_',
            'public' => true,
            'activated' => true,
            'email' => 'email@server.local',
            'category' => new Category(),
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
