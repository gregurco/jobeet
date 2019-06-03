<?php

namespace App\Tests\Controller;

use App\Entity\Job;

/**
 * Class JobControllerTest
 * @group functional
 */
class JobControllerTest extends BaseControllerTest
{
    public function testHomePageDisplaysListOfJobs(): void
    {
        $client = $this->getClient();

        $client->request('GET', '/');

        // page loaded
        $this->assertHttpStatusCode(200);


        // 10 programming jobs
        $this->assertSelectorContainsText('.container h4:first-child', 'Programming');
        $this->assertCount(10, $this->firstElement('.container table')->filter('tbody tr'));

        // 1 design job
        $this->assertContains('Design', $this->getCrawler()->filter('.container h4')->eq(1)->text());
        $this->assertCount(1, $this->getCrawler()->filter('.container table')->eq(1)->filter('tbody tr'));
    }

    public function testShowJobDisplaysActiveJob(): void
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var Job $job */
        $job = $em->getRepository(Job::class)->findOneBy([
            'type' => 'full-time',
            'company' => 'Sensio Labs',
            'position' => 'Web Developer',
            'location' => 'Paris, France',
        ]);

        $client = $this->getClient();
        $client->request('GET', '/job/' . $job->getId());
        $this->assertHttpStatusCode(200);

        $this->assertSelectorContainsText('.media h3.media-heading strong', 'Sensio Labs');
        $this->assertSelectorContainsText('.media h3.media-heading i', 'Paris, France');
        $this->assertSelectorContainsText('.media div.media-body p strong', 'Developer');
        $this->assertSelectorContainsText('.media div.media-body p small i', 'full-time');
    }

    public function testCreateJobPageDisplaysForm(): void
    {
        $this->getClient()->request('GET', '/job/create');
        $this->assertHttpStatusCode(200);

        $this->assertSelectorContainsText('h1', 'Job creation');

        $form = $this->firstElement('form[name="job"]');
        $this->assertFormHasInputWithName('job[url]', $form);
        $this->assertFormHasInputWithName('job[type]', $form);
        $this->assertFormHasInputWithName('job[company]', $form);
        $this->assertFormHasInputWithName('job[logo]', $form);
        $this->assertFormHasInputWithName('job[position]', $form);
        $this->assertFormHasInputWithName('job[location]', $form);
        $this->assertFormHasInputWithName('job[description]', $form);
        $this->assertFormHasInputWithName('job[howToApply]', $form);
        $this->assertFormHasInputWithName('job[public]', $form);
        $this->assertFormHasInputWithName('job[activated]', $form);
        $this->assertFormHasInputWithName('job[email]', $form);
        $this->assertFormHasInputWithName('job[category]', $form);
    }
}
