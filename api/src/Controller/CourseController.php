<?php

namespace App\Controller;

use App\Entity\CourseUser;
use App\Repository\CourseRepository;
use App\Repository\CourseUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{

    private CourseRepository $courseRepository;
    private CourseUserRepository $courseUserRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CourseRepository $courseRepository, CourseUserRepository $courseUserRepository, EntityManagerInterface $entityManager)
    {
        $this->courseRepository = $courseRepository;
        $this->courseUserRepository = $courseUserRepository;
        $this->entityManager = $entityManager;
    }

    #[Route("/api/user-courses", name: "courses_by_user", methods: ["GET"])]
    public function coursesByUserID(): JsonResponse
    {
        $userId = $this->getUser()->getId();

        $courses = $this->courseRepository->findCoursesByUserId($userId);

        return new JsonResponse($courses);
    }

    #[Route("/api/course-connect/{code}", name: "courses_connect_by_code", methods: ["POST"])]
    public function coursesConnectByCode($code): JsonResponse
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $course = $this->courseRepository->findOneBy([
            'code' => $code
        ]);

        if (!$course) {
            return new JsonResponse(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }

        $courseUser = $this->courseUserRepository->findOneBy([
            "user"   => $userId,
            "course" => $course->getId()
        ]);

        if ($courseUser) {
            return new JsonResponse(['error' => 'You already connected'], Response::HTTP_OK);
        }


        $courseUser = new CourseUser();
        $courseUser->setUser($user);
        $courseUser->setCourse($course);
        $courseUser->setIsCreator(false);
        $this->entityManager->persist($courseUser);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User successfully connected to the course'], Response::HTTP_OK);
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