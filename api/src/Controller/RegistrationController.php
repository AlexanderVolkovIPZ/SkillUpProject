<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public const ROLE_USER    = "ROLE_USER";

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $token
     * @return Response
     */
    #[Route("/api/confirm-registration/{token}", name: "confirm_registration")]
    public function confirmRegistration(string $token): Response
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['registrationToken' => $token]);

        if (!$user) {
            return $this->render('registration/confirmation_failed.html.twig');
        }

        $user->setIsConfirmed(true);
        $user->setRegistrationToken(null);
        $user->setRoles([self::ROLE_USER]);
        $this->entityManager->flush();

        return $this->render('registration/confirmation.html.twig');
    }

}