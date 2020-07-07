<?php

namespace App\Repository;

use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

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

//        $qb = $this->createQueryBuilder('PE')
//            ->select('PE.title, UA.id, UA.first_name, UA.last_name,
//            COUNT(DISTINCT PSA.skill_id) as SCORE')
//            ->join('PE.skills', 'PSE')
//            ->join('profile_skill', 'PSA', 'ON', 'PSA.skill_id = PSE.skill_id')
//            ->join('profile', 'PA', 'ON', 'PA.id = PSA.profile_id')
//            ->join('advisor', 'A', 'ON', 'PA.advisor_id = A.id')
//            ->join('user', 'UA', 'ON', 'UA.advisor_id = A.id')
//            ->andWhere("PE.id = $id")
//            ->groupBy('UA.id')
//            ->orderBy('SCORE DESC');
//        $query = $qb->getQuery();

//        $entityManager = $this->getEntityManager();
//        $rq = "SELECT PE.title, UA.id, UA.first_name, UA.last_name,
//        COUNT(DISTINCT PSA.skill_id) as SCORE
//        FROM profile PE
//        JOIN profile_skill PSE ON PSE.profile_id = PE.id
//        JOIN profile_skill PSA ON PSA.skill_id = PSE.skill_id
//        JOIN profile PA ON PA.id = PSA.profile_id
//        JOIN advisor A ON PA.advisor_id = A.id
//        JOIN user UA ON UA.advisor_id = A.id
//        WHERE
//        PE.id = $id
//        GROUP BY UA.id
//        ORDER BY SCORE DESC";

//        $query = $entityManager->createNativeQuery($rq);


//        return $query->getResult();
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
