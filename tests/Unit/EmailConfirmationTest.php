<?php

namespace App\Tests\Unit;



class EmailConfirmationTest extends WebTestCase
{
    use FixtureTrait;

    public function testConfirmation(): void
    {
        $client = static::createClient();
        $user = new User();
        $user->setEmail('test@example.com');
        $this->loadFixture($user);

        $client->request('GET', '/verify/email?id=' . $user->getId());

        $this->assertResponseRedirects('/');
        $this->assertTrue($user->isVerified());
    }
    public function testInvalidConfirmation(): void
    {
        $client = static::createClient();
        $client->request('GET', '/verify/email?id=invalid');

        $this->assertResponseRedirects('/register');
    }
}
?>
