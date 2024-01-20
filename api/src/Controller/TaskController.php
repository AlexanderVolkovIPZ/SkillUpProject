<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    #[Route("/api/tasks-by-course/{id}", name: "tasks_by_course", methods: ["GET"])]
    public function coursesByUserID(string $id): JsonResponse
    {
        $tasks = $this->taskRepository->findBy([
            'course'=>$id
        ]);

        return new JsonResponse($tasks);
    }
}