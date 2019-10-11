<?php

namespace App\Tests\Controller;

use App\DataFixtures\AffiliateFixtures;
use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\JobFixtures;
use App\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Client;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        $this->loadFixtures([
            AffiliateFixtures::class,
            CategoryFixtures::class,
            JobFixtures::class,
            UserFixtures::class,
        ]);

        $this->client = self::createClient();
    }

    protected function getClient(): Client
    {
        if ($this->client === null) {
            throw new \LogicException('Client not configured!');
        }

        return $this->client;
    }

    protected function getCrawler(): Crawler
    {
        return $this->getClient()->getCrawler();
    }

    protected function assertSelectorContainsText(string $selector, string $text): void
    {
        $this->assertContains(
            $text,
            $this->firstElement($selector)->text()
        );
    }

    protected function firstElement(string $selector, Crawler $crawler = null): Crawler
    {
        if ($crawler === null) {
            $crawler = $this->getCrawler();
        }

        return $crawler->filter($selector)->first();
    }

    protected function assertHttpStatusCode(int $code): void
    {
        $this->assertStatusCode($code, $this->getClient());
    }

    protected function assertFormHasInputWithName(string $name, Crawler $form = null): void
    {
        if ($form === null) {
            $form = $this->getCrawler();
        }

        $this->assertGreaterThan(
            0,
            $this->firstElement($this->composeNamedSelectors(['input', 'textarea', 'select'], $name), $form)->count()
        );
    }

    private function composeNamedSelectors(array $elements, string $name): string
    {
        $names = array_reduce($elements, function ($carry, $element) use ($name) {
            $carry[] = sprintf('%s[name="%s"]', $element, $name);

            return $carry;
        }, []);

        return implode(',', $names);
    }
}
