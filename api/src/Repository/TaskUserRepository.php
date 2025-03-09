<?php

namespace App\Repository;

use App\Entity\TaskUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskUserRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskUser::class);
    }

    /**
     * @param $courseId
     * @return array
     */
    public function getTaskUsersByCourseId($courseId): array
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();

        $qb->select('u.firstName', 'u.lastName', 'u.email', 't.name', 'tu.date', 't.dueDate', 'tu.solvedTaskFileName', 'tu.linkSolvedTask', 'tu.mark', 'tu.id')
            ->from('App\Entity\TaskUser', 'tu')
            ->leftJoin('App\Entity\Task', 't', 'WITH', 't.id = tu.task')
            ->leftJoin('App\Entity\User', 'u', 'WITH', 'u.id = tu.user')
            ->where('t.course = :courseId')
            ->setParameter('courseId', $courseId);

        return $qb->getQuery()->getResult();
    }
}
