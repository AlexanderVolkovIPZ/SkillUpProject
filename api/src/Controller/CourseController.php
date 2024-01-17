<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{

    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    #[Route("/api/user-courses", name: "courses_by_user")]
    public function coursesByUserID(): JsonResponse
    {
        $userId = $this->getUser()->getId();

        $courses = $this->courseRepository->findCoursesByUserId($userId);

        return new JsonResponse($courses);
    }

}
