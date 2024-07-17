<?php

use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
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
        restore_exception_handler();
    }

    public function testRegisterNewUser(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $this->assertResponseIsSuccessful();

        // Fill in the form and submit it
        $form = $crawler->selectButton('Registrieren')->form();
        $form['registration_form[firstname]'] = 'Test';
        $form['registration_form[lastname]'] = 'User';
        $form['registration_form[birthdate]'] = '1990-01-01';
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[password][first]'] = 'password';
        $form['registration_form[password][second]'] = 'password';
        $form['registration_form[agreeTerms]'] = '1';

        $this->client->submit($form);

        $this->assertResponseRedirects(message: 'Fehler beim Registrieren des Benutzers.');
    }

    public function testRegisterExistingUser(): void
    {
        $this->loadFixtures([
            UserFixtures::class,
        ]);

        $crawler = $this->client->request('GET', '/register');

        $this->assertResponseIsSuccessful();

        // Fill in the form and submit it
        $form = $crawler->selectButton('Registrieren')->form();
        $form['registration_form[firstname]'] = 'Test';
        $form['registration_form[lastname]'] = 'User';
        $form['registration_form[birthdate]'] = '1990-01-01';
        $form['registration_form[email]'] = 'dev@weedwizard.de';
        $form['registration_form[password][first]'] = 'password';
        $form['registration_form[password][second]'] = 'password';
        $form['registration_form[agreeTerms]'] = '1';

        $this->client->submit($form);

        $responseContent = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('Es gibt schon ein Account mit dieser E-Mail Adresse.', $responseContent, 'The user was still created, even though the email address already exists.');
    }

    public function testRegisterYoungerThan18(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $this->assertResponseIsSuccessful();

        // Fill in the form and submit it
        $form = $crawler->selectButton('Registrieren')->form();
        $form['registration_form[firstname]'] = 'Test';
        $form['registration_form[lastname]'] = 'User';
        $form['registration_form[birthdate]'] = '2010-01-01';
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[password][first]'] = 'password';
        $form['registration_form[password][second]'] = 'password';
        $form['registration_form[agreeTerms]'] = '1';

        $this->client->submit($form);

        $responseContent = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('Du musst mindestens 18 Jahre alt sein.', $responseContent, 'The user was still created, even though the user is younger than 18.');
    }

    private function loadFixtures(array $fixtures): void
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
