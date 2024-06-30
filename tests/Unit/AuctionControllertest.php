<?php

namespace App\Tests\Unit;

use App\Entity\Auction;
use App\Entity\Beat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AuctionControllerTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private static $container;

    public function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$container->get(EntityManagerInterface::class);
    }

    public function testCreateAuction(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/auction');
        $form = $crawler->selectButton('submit')->form();
        $form['auction_form[name]'] = 'test beat';
        $form['auction_form[categorie]'] = 'HIP-HOP';
        $form['auction_form[mood]'] = 'HAPPY';
        $form['auction_form[imageFile]'] = new UploadedFile(
            '/assets/images/ban1.jpg',
            null,
            true
        );
        $form['auction_form[musicFile]'] = new UploadedFile(
            '/assets/images/beat.mp3',
            null,
            true
        );
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertStringContainsString('success', $crawler->filter('div.alert-success')->text());
        $auction = $this->entityManager->getRepository(Auction::class)->findOneBy(['name' => 'test beat']);
        $this->assertNotNull($auction);
        $this->assertNotNull($auction->getAuction());
        $this->assertEquals('test beat', $auction->getAuction()->getName());
        $this->assertEquals('HIP-HOP', $auction->getAuction()->getCategory());
        $this->assertEquals('HAPPY', $auction->getAuction()->getMood());
    }
}

?>
