<?php

namespace App\Tests\Controller;

use App\Controller\InstrumentholController;
use App\Entity\Auction;
use App\Service\SearchData;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class InstrumentholControllerTest extends TestCase
{
    /**
     * @var InstrumentholController
     */
    private $controller;

    /**
     * @var EntityManagerInterface|MockObject
     */
    private $entityManager;

    /**
     * @var ObjectRepository|MockObject
     */
    private $repository;

    /**
     * @var UserInterface|MockObject
     */
    private $user;

    public function setUp(): void
    {
        $this->controller = new InstrumentholController();
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(ObjectRepository::class);
        $this->user = $this->createMock(UserInterface::class);
        $this->controller->setManagerRegistry($this->entityManager);
    }

    public function testIndex(): void
    {
        $searchData = new SearchData();
        $searchData->id_user_current = '1234';
        $this->user->expects($this->once())
            ->method('getUserIdentifier')
            ->willReturn($searchData->id_user_current);
        $this->controller->setUser($this->user);

        $auction = new Auction();
        $auctions = [$auction];
        $this->repository->expects($this->once())
            ->method('findBySearch')
            ->with($searchData)
            ->willReturn($auctions);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Auction::class)
            ->willReturn($this->repository);

        $response = $this->controller->index();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('instrumenthol/index.html.twig', $response->getTemplate());
        $this->assertEquals(['auctions' => $auctions], $response->getParameters());
    }
}

