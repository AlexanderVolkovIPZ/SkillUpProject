<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskUser;
use App\Entity\User;
use App\Repository\TaskUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isEmpty;

class TaskUserController extends AbstractController
{
    private TaskUserRepository $taskUserRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(TaskUserRepository $taskUserRepository, EntityManagerInterface $entityManager)
    {
        $this->taskUserRepository = $taskUserRepository;
        $this->entityManager = $entityManager;
    }

    #[Route("/api/task-user-create", name: "task_user_create", methods: ["POST"])]
    public function taskUserCreate(Request $request){

        $taskId = $request->get("task");
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
        $userId = $request->get("user");
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $link = $request->get("link");
        $uploadedFiles = $request->files->all();

        if(!$task || !$user || (!$link && !$uploadedFiles)){
            throw new InvalidArgumentException('Missing required data. Please provide task, user, and link or file!');
        }

        $taskUser = new TaskUser();
        $taskUser->setUser($user);
        $taskUser->setTask($task);

        if(!empty($link)){
            $taskUser->setLinkSolvedTask($link);
        }

        if ($uploadedFiles) {
            foreach ($uploadedFiles as $uploadedFile) {
                $newFileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
                $taskUser->setSolvedTaskFileName($newFileName);
                $destination = $this->getParameter('upload_directory');
                $uploadedFile->move($destination, $newFileName);
            }
        }

        $this->entityManager->persist($taskUser);
        $this->entityManager->flush();

        return new JsonResponse("TaskUser created successfully", Response::HTTP_OK);
    }

    #[Route("/api/task-user-delete/{id}", name: "task_user_delete", methods: ["DELETE"])]
    public function taskUserDelete(string $id)
    {
        $task = $this->taskUserRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException('TaskUser not found');
        }

        $filePath = $this->getParameter('upload_directory') . '/' . $task->getSolvedTaskFileName();

        if (!file_exists($filePath)) {
            throw new RuntimeException('File not found');
        }

        if (!unlink($filePath)) {
            throw new RuntimeException('Unable to delete file');
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}