<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 *
 */
class CourseRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    /**
     * @param string $userId
     * @return array
     */
    public function findCoursesByUserId(string $userId): array
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();

        $qb->select('c.id', 'c.name', 'c.title', 'c.description', 'c.code')
            ->from('App\Entity\Course', 'c')
            ->leftJoin('App\Entity\CourseUser', 'cu', 'WITH', 'c.id = cu.course')
            ->where('cu.user = :userId')
            ->setParameter('userId', $userId);

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
