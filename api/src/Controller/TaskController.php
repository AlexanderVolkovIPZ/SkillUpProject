<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Task;
use App\Repository\TaskRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{

    private TaskRepository $taskRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    #[Route("/api/tasks-by-course/{id}", name: "tasks_by_course", methods: ["GET"])]
    public function coursesByUserID(string $id): JsonResponse
    {
        $tasks = $this->taskRepository->findBy([
            'course' => $id
        ]);

        return new JsonResponse($tasks);
    }

    #[Route("/api/task-create", name: "task_create", methods: ["POST"])]
    public function taskCreate(Request $request): JsonResponse
    {
        $courseId = $request->get("courseId");
        $course = $this->entityManager->getRepository(Course::class)->find($courseId);
        $name = $request->get("name");
        $description = $request->get("description");
        $mark = $request->get("mark");
        $date = $request->get("date");
        $uploadedFiles = $request->files->all();
        if (!$course || !$name || !($mark == 0 || $mark)) {
            throw new InvalidArgumentException('Missing required data. Please provide course, name, and mark.');
        }

        $task = new Task();
        $task->setCourse($course);
        $task->setName($name);
        $task->setMaxMark($mark);

        if ($description) {
            $task->setDescription($description);
        }

        if ($date) {
            $date = str_replace(' GMT+0200 (за східноєвропейським стандартним часом)', '', $date);
            $dueDate = DateTimeImmutable::createFromFormat('D M d Y H:i:s', $date);
            $currentDate = new DateTimeImmutable();
            $dueDate > $currentDate ? $task->setDueDate($dueDate) : $task->setDueDate(null);
        }

        if ($uploadedFiles) {
            foreach ($uploadedFiles as $uploadedFile) {
                $newFileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
                $task->setFileNameTask($newFileName);
                $destination = $this->getParameter('upload_directory');
                $uploadedFile->move($destination, $newFileName);
            }
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new JsonResponse("Task created successfully", Response::HTTP_OK);
    }

    #[Route("/api/task-file/{name}", name: "task_file_by_name", methods: ["GET"])]
    public function taskFileByName(string $name): BinaryFileResponse
    {
        $filePath = $this->getParameter('upload_directory') . '/' . $name;

        if (!file_exists($filePath)) {
            throw new FileNotFoundException($name);
        }

        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $name);

        return $response;
    }

    #[Route("/api/task-update/{id}", name: "task_update", methods: ["POST"])]
    public function taskUpdate(string $id, Request $request): JsonResponse
    {
        $name = $request->get("name");
        $description = $request->get("description");
        $mark = $request->get("mark");
        $date = $request->get("date");
        $uploadedFiles = $request->files->all();
        if (!$name || !($mark == 0 || $mark)) {
            throw new InvalidArgumentException('Missing required data. Please provide course, name, and mark.');
        }
        $task = $this->taskRepository->find($id);
        $task->setName($name);
        $task->setDescription($description);
        $task->setMaxMark($mark);
        $dueDate = DateTimeImmutable::createFromFormat(DateTimeInterface::ISO8601, $date);
        $dueDate ? $task->setDueDate($dueDate) : $task->setDueDate(null);

        $destination = $this->getParameter('upload_directory');
        $filePath = $destination . '/' . $task->getFileNameTask();

        if ($uploadedFiles) {
            if (file_exists($filePath) && !is_dir($filePath)) {
                unlink($filePath);
            }
            foreach ($uploadedFiles as $uploadedFile) {
                $newFileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
                $task->setFileNameTask($newFileName);
                $uploadedFile->move($destination, $newFileName);
            }
        } else {
            if (file_exists($filePath) && !is_dir($filePath)) {
                unlink($filePath);
                $task->setFileNameTask(null);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse("Task updated successfully", Response::HTTP_OK);
    }

    #[Route("/api/task-delete/{id}", name: "task_delete", methods: ["DELETE"])]
    public function taskDelete(string $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);

        if ($task->getFileNameTask()) {
            $filePath = $this->getParameter('upload_directory') . "/" . $task->getFileNameTask();
            if (file_exists($filePath) && !is_dir($filePath)) {
                unlink($filePath);
            }
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();
        return new JsonResponse("Task delete successfully", Response::HTTP_NO_CONTENT);
    }

}