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

    /**
     * @param int|null $id nullable because passed parameter could be null
     *
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findAdvisorMatchsByBoardRequest(?int $id = 0)
    {
        if (!$id) {
            return [];
        }
        $rawSql = "SELECT 
        PE.id as board_request_id,
        PA.id as advisor_id,
        PA.title,
        UA.id,
        UA.first_name,
        UA.last_name,
        E.payment_status AS enterprise_payment_status,
        COUNT(DISTINCT PSA.skill_id) as SCORE
        FROM profile PE
        JOIN profile_skill PSE ON PSE.profile_id = PE.id
        JOIN profile_skill PSA ON PSA.skill_id = PSE.skill_id
        JOIN profile PA ON PA.id = PSA.profile_id
        JOIN advisor A ON PA.advisor_id = A.id
        JOIN user UA ON UA.advisor_id = A.id
        JOIN enterprise E on PE.enterprise_id = E.id
        WHERE PE.id = $id AND PA.archived = 0
        GROUP BY UA.id, PA.id
        ORDER BY SCORE DESC";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    /**
     * @param int $id Advisor id
     *
     * @return mixed[] array of resulgit request
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findEnterpriseMatchsByAdvisor(int $id = 0)
    {
        $rawSql = "SELECT 
        PE.id as board_request_id,
        PA.id as advisor_id,
        PE.title, PE.enterprise_id,
        E.name as enterprise_name, 
        PE.description,
        PE.date_creation,
        COUNT(DISTINCT PSE.skill_id) as SCORE
        FROM profile PA
        JOIN profile_skill PSA ON PSA.profile_id = PA.id # tous les skill de PA.id
        JOIN profile_skill PSE ON PSA.skill_id = PSE.skill_id # tous les skill seulement si  PA les a
        JOIN profile PE ON PE.id = PSE.profile_id # toutes les profils qui contiennent des skills que PA a.
        JOIN enterprise E ON PE.enterprise_id = E.id
        JOIN user UE ON UE.enterprise_id = E.id
        WHERE PA.id = $id AND PE.archived = 0# ID de la Board request
        GROUP BY PE.id
        ORDER BY SCORE DESC";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
        return $stmt->fetchAll();
    }
}
