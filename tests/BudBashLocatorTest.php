<?php

use App\DataFixtures\BudBashFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\BudBash;
use App\Entity\BudBashCheckAttendance;
use App\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BudBashLocatorTest extends WebTestCase
{
    protected KernelBrowser $client;
    protected $entityManager;
    protected mixed $databaseTool;

    public function setUp(): void
    {
        // Will boot the kernel and give us a new client for testing
        $this->client = static::createClient();

        $doctrine = static::getContainer()->get('doctrine');
        $this->entityManager = $doctrine->getManager();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function tearDown(): void
    {
        $this->truncateDatabase();
        parent::tearDown();
        unset($this->entityManager);
        restore_error_handler();
    }

    public function testCreateBudBashParty(): void
    {
        $this->loadFixtures([
            UserFixtures::class,
            BudBashFixtures::class,
        ]);

        $this->client->loginUser($this->entityManager->getRepository(User::class)->findOneBy(['email' => 'dev@weedwizard.de']));

        $crawler = $this->client->request('GET', '/budbash-locator');
        $this->assertResponseIsSuccessful();

        // Fill in the form and submit it
        $form = $crawler->selectButton('Party erstellen')->form();
        $form['bud_bash[name]'] = 'Test Party';
        $form['bud_bash[start]'] = '2024-07-01 20:00';
        $form['bud_bash[address]'] = 'Marienplatz 1, 80331 München';
        $form['bud_bash[address_street]'] = 'Marienplatz';
        $form['bud_bash[address_house_number]'] = '1';
        $form['bud_bash[address_postal_code]'] = '80331';
        $form['bud_bash[address_city]'] = 'München';
        $form['bud_bash[entrance_fee]'] = '10';
        $form['bud_bash[extraInfo]'] = 'Test Info';

        // We need to set the mapbox_id manually because we can not use the real mapbox_id in the test
        $form['bud_bash[mapbox_id]'] = 'dXJuOm1ieGFkcjpjOGZkMGVhNi1mZTQ1LTQ3ZGItOTI3MS04NTllYjY1Y2VlZTA';

        $this->client->submit($form);
        $this->assertResponseRedirects();

        // Check if the party was created
        $party = $this->entityManager->getRepository(BudBash::class)->findOneBy(['name' => 'Test Party', 'address' => 'Marienplatz 1, 80331 München']);
        $this->assertNotNull($party);
    }

    public function testCreateBudBashPartyWithCheckAttendance(): void
    {
        $this->loadFixtures([
            UserFixtures::class,
            BudBashFixtures::class,
        ]);

        $this->client->loginUser($this->entityManager->getRepository(User::class)->findOneBy(['email' => 'dev@weedwizard.de']));

        $crawler = $this->client->request('GET', '/budbash-locator');
        $this->assertResponseIsSuccessful();

        // Fill in the form and submit it
        $form = $crawler->selectButton('Party erstellen')->form();
        $form['bud_bash[name]'] = 'Test Party';
        $form['bud_bash[start]'] = '2024-07-01 20:00';
        $form['bud_bash[address]'] = 'Marienplatz 1, 80331 München';
        $form['bud_bash[address_street]'] = 'Marienplatz';
        $form['bud_bash[address_house_number]'] = '1';
        $form['bud_bash[address_postal_code]'] = '80331';
        $form['bud_bash[address_city]'] = 'München';
        $form['bud_bash[entrance_fee]'] = '10';
        $form['bud_bash[CheckAttendances]'] = '1';
        $form['bud_bash[extraInfo]'] = 'Test Info';

        // We need to set the mapbox_id manually because we can not use the real mapbox_id in the test
        $form['bud_bash[mapbox_id]'] = 'dXJuOm1ieGFkcjpjOGZkMGVhNi1mZTQ1LTQ3ZGItOTI3MS04NTllYjY1Y2VlZTA';

        $this->client->submit($form);
        $this->assertResponseRedirects();

        // Check if the party was created
        $party = $this->entityManager->getRepository(BudBash::class)->findOneBy(['name' => 'Test Party', 'address' => 'Marienplatz 1, 80331 München']);
        $this->assertNotNull($party);

        // Check if the check attendance was created
        $checkAttendance = $this->entityManager->getRepository(BudBashCheckAttendance::class)->findOneBy(['BudBashParty' => $party]);
        $this->assertNotNull($checkAttendance);
    }

    public function testCreateInvalidBudBashParty(): void
    {
        $this->loadFixtures([
            UserFixtures::class,
            BudBashFixtures::class,
        ]);

        $this->client->loginUser($this->entityManager->getRepository(User::class)->findOneBy(['email' => 'dev@weedwizard.de']));

        $crawler = $this->client->request('GET', '/budbash-locator');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Party erstellen')->form();
        $this->client->submit($form);

        $responseContent = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('Bitte gib einen Namen ein.', $responseContent);
        $this->assertStringContainsString('Bitte gib eine Adresse ein.', $responseContent);
    }

    private function loadFixtures(array $fixtures = []): void
    {
        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $databaseTool->loadFixtures($fixtures);
    }

    private function truncateDatabase(): void
    {
        $ormPurger = new ORMPurger($this->entityManager);
        $ormPurger->purge();
    }
}
