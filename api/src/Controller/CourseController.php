<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\CourseUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{

    private CourseRepository $courseRepository;
    private CourseUserRepository $courseUserRepository;

    public function __construct(CourseRepository $courseRepository, CourseUserRepository $courseUserRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->courseUserRepository = $courseUserRepository;
    }

    #[Route("/api/user-courses", name: "courses_by_user", methods: ["GET"])]
    public function coursesByUserID(): JsonResponse
    {
        $userId = $this->getUser()->getId();

        $courses = $this->courseRepository->findCoursesByUserId($userId);

        return new JsonResponse($courses);
    }

    #[Route("/api/user-course/{code}", name: "course_by_code", methods: ["GET"])]
    public function coursesByCode(string $code): JsonResponse
    {
        $course = $this->courseRepository->findOneBy([
            'code' => $code
        ]);

        return new JsonResponse($course->jsonSerialize());
    }

    #[Route("/api/user-course-creator/{id}", name: "is_course_creator_by_id_course", methods: ["GET"])]
    public function isUserCourseCreator(string $id): JsonResponse
    {
        $userId = $this->getUser()->getId();

        $courseUser = $this->courseUserRepository->findOneBy([
            "user"   => $userId,
            "course" => $id
        ]);

        if ($courseUser->getIsCreator()) {
            return new JsonResponse(["isUserCourseCreator" => true]);
        }

        return new JsonResponse(["isUserCourseCreator" => false]);
    }

}