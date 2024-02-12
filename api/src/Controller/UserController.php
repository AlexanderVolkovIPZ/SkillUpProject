<?php

namespace App\Controller;

use App\Entity\CourseUser;
use App\Repository\CourseRepository;
use App\Repository\CourseUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route("/api/user-info-by-course/{id}", name: "user_info_by_course", methods: ["GET"])]
    public function userInfoByCourse(string $id): JsonResponse
    {

        $userInfo = $this->userRepository->findUserInfoByCourseId($id);

        return new JsonResponse($userInfo);
    }

}