<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param PasswordAuthenticatedUserInterface $user
     * @param string $newHashedPassword
     * @return void
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $courseId
     * @return array
     */
    public function findUserInfoByCourseId(string $courseId): array
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();

        $qb->select('u.firstName', 'u.lastName','u.email', 'tu.id as taskUserId', 'cu.id as courseUserId')
            ->from('App\Entity\User', 'u')
            ->leftJoin('App\Entity\CourseUser', 'cu', 'WITH', 'u.id = cu.user')
            ->leftJoin('App\Entity\TaskUser', 'tu', 'WITH', 'u.id = tu.user')
            ->where('cu.course = :courseId AND cu.isCreator = :isCreator')
            ->setParameters(['courseId' => $courseId, 'isCreator' => 0]);

        return  $qb->getQuery()->getResult();
    }
}
