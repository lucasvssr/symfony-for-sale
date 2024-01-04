<?php

namespace App\Repository;

use App\Entity\Advertisement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Advertisement>
 *
 * @method Advertisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertisement[]    findAll()
 * @method Advertisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertisementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisement::class);
    }

    public function queryAllByDate(): Query
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->addSelect('c')
            ->leftJoin('a.category', 'c')
            ->getQuery()
        ;
    }

    public function findByCategory(int $category): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.category = :category')
            ->setParameter('category', $category)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getAdvertisementWithCategory(int $advertisement): ?Advertisement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :advertisement')
            ->setParameter('advertisement', $advertisement)
            ->addSelect('c')
            ->leftJoin('a.category', 'c')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function search(string $search): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.title LIKE :search or a.description LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAdvertisementByUser(int $user): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.author = :user')
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function queryLikedByUser(int $user): Query
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.advertisementLikes', 'al')
            ->andWhere('al.people = :people')
            ->setParameter('people', $user)
            ->getQuery();
    }
}
