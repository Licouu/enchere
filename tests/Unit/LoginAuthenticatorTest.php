<?php

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class LoginAuthenticatorTest extends WebTestCase
{
    public function testAuthenticate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Sign in')->form();
        $form['username'] = 'test_user';
        $form['password'] = 'test_password';

        $client->submit($form);

        $this->assertResponseRedirects('/');
    }
}


?>
