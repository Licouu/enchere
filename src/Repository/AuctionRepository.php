<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Auction;
use App\Entity\Beat;
use App\Model\SearchData;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Auction>
 *
 * @method Auction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auction[]    findAll()
 * @method Auction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,
        private PaginatorInterface $paginatorInterface
    ){
        parent::__construct($registry, Auction::class);
    }

    public function save(Auction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Auction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByEndedAuctions(): array
    {
        return $this->createQueryBuilder('auction')
            ->where('auction.endDate < :date')
            ->setParameter('date', new \DateTimeImmutable())
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return Auction[] Returns an array of Auction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Auction
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * Get published posts thanks to Search Data value
     *
     * @param SearchData $searchData
     * @return PaginationInterface
     */
    public function findBySearch(SearchData $searchData): PaginationInterface
    {
        //dd($searchData->id_user_current);
        $currentDate = new \DateTime();
        $data = $this
        ->createQueryBuilder('auction')
        ->andWhere('auction.endDate > :currentDate')
        ->andWhere('auction.beatmaker != :me')
        ->setParameter('currentDate', $currentDate)
        ->addOrderBy('auction.endDate', 'ASC')
        ->setParameter('me',$searchData->id_user_current);

        if (!empty($searchData->q)) {
            $data = $data
                //->select('b')
                //->from('App/Entity/Beat', 'b')
                ->innerjoin('App\Entity\Beat', 'beat','WITH','beat.id = auction.Beat')
                ->innerjoin('App\Entity\User','beatmaker','WITH','auction.beatmaker = beatmaker.id')
                ->andWhere('beat.name LIKE :q')
                ->orWhere('beatmaker.username LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }
        //dd($searchData);
        if (!empty($searchData->category)){
            
            $data=$data 
                ->innerjoin('App\Entity\Beat', 'beat2','WITH','beat2.id = auction.Beat')
                ->andWhere('beat2.Category IN (:category)')
                ->setParameter('category', $searchData->category);
        }

        /*A décommenter quand la classe Beat sera mis à jour
        if(!empty($searchData->instrument)){
            $data=$data 
                ->innerjoin('App\Entity\Beat', 'beat3','WITH','beat3.id = auction.Beat')
                ->andWhere('beat3.Instrument IN (:instru)')
                ->setParameter('instru', $searchData->instrument);
        }*/

        if(!empty($searchData->moods)){
            $data=$data 
                ->innerjoin('App\Entity\Beat', 'beat4','WITH','beat4.id = auction.Beat')
                ->andWhere('beat4.mood IN (:moodss)')
                ->setParameter('moodss', $searchData->moods);
        }
    

        $data = $data
            ->getQuery()
            ->getResult();

        $posts = $this->paginatorInterface->paginate($data, $searchData->page, 12);
        
        return $posts;
    }

    public function findMyAuction(string $id_user,int $page){
        $data = $this
        ->createQueryBuilder('auction')
        ->andWhere('auction.endDate > :currentDate')
        ->andWhere('auction.beatmaker = :me')
        ->addOrderBy('auction.endDate', 'ASC')
        ->setParameter('me',$id_user);

        $data = $data
            ->getQuery()
            ->getResult();

        $posts = $this->paginatorInterface->paginate($data, $page, 12);
        
        dd($posts);
        return $posts;
    }

    public function findByUserAndValidBid(User $user): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.offers', 'o')
            ->andwhere('o.bidder = :user')
            ->andWhere('a.endDate > :now')
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getResult();
    }
}
