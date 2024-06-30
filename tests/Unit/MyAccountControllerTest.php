<?php

use App\Controller\MyAccountController;
use App\Entity\Auction;
use App\Entity\Offer;
use App\Entity\User;
use App\Repository\AuctionRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use App\Repository\OfferRepository;

class MyAccountControllerTest extends TestCase
{
    public function testIndex()
    {
        // Arrange
        $auction = new Auction();
        $offer = new Offer();
        $offer->setBidder(new User());
        $offer->setAuction($auction);
        $auction->setEndDate(new \DateTimeImmutable());
        $auction->setWinner(new User());
        $auction->setBeatmaker(new User());
        $auctionRepository = $this->createMock(AuctionRepository::class);
        $auctionRepository->expects($this->once())
            ->method('findBy')
            ->willReturn([$auction]);
        $offerRepository = $this->createMock(OfferRepository::class);
        $offerRepository->expects($this->once())
            ->method('findBy')
            ->willReturn([$offer]);
        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine->expects($this->exactly(2))
            ->method('getRepository')
            ->will($this->onConsecutiveCalls($auctionRepository, $offerRepository));
        $controller = new MyAccountController();
        $controller->setContainer($this->createMock(Container::class));

        // Act
        $response = $controller->index($doctrine);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('auction/MyAuctions.html.twig', $response->getName());
        $this->assertEquals([$offer], $response->getData()['currentOffers']);
        $this->assertEquals([$auction], $response->getData()['auctionWin']);
        $this->assertEquals([$auction], $response->getData()['auctionDumpmaker']);
    }
}

?>
