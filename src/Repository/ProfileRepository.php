<?php

namespace App\Repository;

use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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

    public function findAdvisorMatchsByBoardRequest(int $id)
    {
        $qb = $this->createQueryBuilder('PE')
            ->select('PE.title, UA.id, UA.first_name, UA.last_name, 
        COUNT(DISTINCT PSA.skill_id) as SCORE')
            ->from('profile', 'PE')
            ->join('profile_skill', 'PSE', 'ON', 'PSE.profile_id = PE.id')
            ->join('profile_skill', 'PSA', 'ON', 'PSA.skill_id = PSE.skill_id')
            ->join('profile', 'PA', 'ON', 'PA.id = PSA.profile_id')
            ->join('advisor', 'A', 'ON', 'PA.advisor_id = A.id')
            ->join('user', 'UA', 'ON', 'UA.advisor_id = A.id')
            ->andWhere("PE.id = $id");

        return $qb;
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
