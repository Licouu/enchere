<?php
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
class RegistrationControllerTest extends WebTestCase
{
    private $client;
    private $em;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testRegister()
    {
        $crawler = $this->client->request('GET', '/register');
        $form = $crawler->selectButton('Sign up')->form();
        $form['registration_form[firstName]'] = 'John';
        $form['registration_form[lastName]'] = 'Doe';
        $form['registration_form[username]'] = 'johndoe';
        $form['registration_form[email]'] = 'johndoe@example.com';
        $form['registration_form[password]'] = 'password';
        $form['registration_form[agreeTerms]']->tick();

        $this->client->submit($form);
        $this->assertResponseRedirects();

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe@example.com']);
        $this->assertNotNull($user);
        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertEquals('johndoe', $user->getUsername());
    }

   // public function testInvalidForm()

 /* utilisateur est redirigé vers la page d'accueil après s'être enregistré avec des informations valides, l'utilisateur est créé en base de données avec les informations fournies, et que les informations de l'utilisateur sont correctes.
 
 public function testRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[firstName]'] = 'John';
        $form['registration_form[lastName]'] = 'Doe';
        $form['registration_form[username]'] = 'johndoe';
        $form['registration_form[email]'] = 'johndoe@example.com';
        $form['registration_form[agreeTerms]']->tick();
        $form['registration_form[password]'] = 'password';

        $client->submit($form);
        $this->assertResponseRedirects('/');

        $user = self::$container->get(UserRepository::class)->findOneByEmail('johndoe@example.com');
        $this->assertNotNull($user);
        $this->assertSame('johndoe', $user->getUsername());*/
    }

?>
