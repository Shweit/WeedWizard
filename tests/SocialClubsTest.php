<?php

use App\DataFixtures\ClubFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\CannabisVerein;
use App\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SocialClubsTest extends WebTestCase
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
        $users = $this->entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $user->setJoinedClub(null);
            $user->setCreatedClub(null);
        }
        $this->entityManager->flush();
        $this->truncateDatabase();
        parent::tearDown();
        unset($this->entityManager);
        restore_exception_handler();
    }

    public function testCreateSocialClub(): void
    {
        $this->loadFixtures([
            UserFixtures::class,
            ClubFixtures::class,
        ]);

        $this->client->loginUser($this->entityManager->getRepository(User::class)->findOneBy(['email' => 'dev@weedwizard.de']));

        $crawler = $this->client->request('GET', '/social-club');

        $this->assertResponseIsSuccessful();

        // Fill in the form and submit it
        $form = $crawler->selectButton('Verein erstellen')->form();
        $form['cannabis_verein[name]'] = 'Test Social Club';
        $form['cannabis_verein[beschreibung]'] = 'Dies ist eine test Beschreibung für den Test Social Club';
        $form['cannabis_verein[adresse]'] = 'Josef-Wolter-Weg 2, 41569 Rommerskirchen';
        $form['cannabis_verein[strasse]'] = 'Josef-Wolter-Weg';
        $form['cannabis_verein[hausnummer]'] = '2';
        $form['cannabis_verein[plz]'] = '41569';
        $form['cannabis_verein[ort]'] = 'Rommerskirchen';
        $form['cannabis_verein[mitgliedsbeitrag]'] = '50';
        $form['cannabis_verein[website]'] = 'https://www.example.com';
        $form['cannabis_verein[sonstiges]'] = 'Dies ist ein Test';

        // We need to set the mapbox_id manually because we can not use the real mapbox_id in the test
        $form['cannabis_verein[mapbox_id]'] = 'dXJuOm1ieGFkcjpjOGZkMGVhNi1mZTQ1LTQ3ZGItOTI3MS04NTllYjY1Y2VlZTA';

        $this->client->submit($form);

        $this->assertResponseRedirects();

        // Check if the social club was created
        $club = $this->entityManager->getRepository(CannabisVerein::class)->findOneBy(['name' => 'Test Social Club', 'adresse' => 'Josef-Wolter-Weg 2, 41569 Rommerskirchen']);
        $this->assertNotNull($club, 'The created Social Club was not found in the database.');
    }

    public function testCreateInvalidSocialClub(): void
    {
        $this->loadFixtures([
            UserFixtures::class,
            ClubFixtures::class,
        ]);

        $this->client->loginUser($this->entityManager->getRepository(User::class)->findOneBy(['email' => 'dev@weedwizard.de']));

        $crawler = $this->client->request('GET', '/social-club');

        $this->assertResponseIsSuccessful();

        // Fill in the form and submit it
        $form = $crawler->selectButton('Verein erstellen')->form();

        $this->client->submit($form);

        $responseContent = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('Bitte gib einen gültigen Namen ein.', $responseContent, 'The name field was not validated correctly.');
        $this->assertStringContainsString('Bitte gib eine Adresse ein.', $responseContent, 'The address field was not validated correctly.');
        $this->assertStringContainsString('Bitte gib einen gültigen Mitgliedsbeitrag ein.', $responseContent, 'The membership fee field was not validated correctly.');
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
