<?php

namespace App\cron;

use App\Entity\Auction;
use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetAuctionWinnerCommand extends Command
{
    private EntityManagerInterface $doctrine;
    public function __construct(EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repoAuction = $this->doctrine->getRepository(Auction::class);
        $repoOffer = $this->doctrine->getRepository(Offer::class);
        $auctions = $repoAuction->findByEndedAuctions();
        foreach ($auctions as $auction) {
            $offer = $repoOffer->findByMaxPrice();
            if($offer != null){
                $auction->setWinner($offer->getBidder());
                $this->doctrine->persist($auction);
                $this->doctrine->flush();
            }
        }
    }
}
