<?php

namespace App\Repository;

use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Profile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profile[]    findAll()
 * @method Profile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }

    /**
     *
     * @return array
     */
    public function countBoardRequest()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(u)')
            ->from($this->_entityName, 'u')
            ->where('u.isRequest = 1');

        return $qb->getQuery()->getResult();
    }

    /**
     *
     * @return array
     */
    public function countBoardRequestArchived()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(u)')
            ->from($this->_entityName, 'u')
            ->where('u.isRequest = 1')
            ->andWhere('u.archived = 1');

        return $qb->getQuery()->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Profile
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
