<?php

namespace App\Repository;

use App\Entity\CourseCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseCategory>
 *
 * @method CourseCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseCategory[]    findAll()
 * @method CourseCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseCategory::class);
    }
}
