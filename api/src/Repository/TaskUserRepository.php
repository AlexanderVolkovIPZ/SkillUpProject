<?php

namespace App\Repository;

use App\Entity\TaskUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskUser>
 *
 * @method TaskUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskUser[]    findAll()
 * @method TaskUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskUserRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskUser::class);
    }

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

        $query = $qb->getQuery();

        return $query->getResult();
    }




//    /**
//     * @return TaskUser[] Returns an array of TaskUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TaskUser
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
