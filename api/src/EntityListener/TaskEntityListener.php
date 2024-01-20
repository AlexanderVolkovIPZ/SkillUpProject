<?php

namespace App\EntityListener;

use App\Entity\Task;
use DateTime;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TaskEntityListener
{

    public function __construct()
    {
    }

    public function prePersist(Task $task, LifecycleEventArgs $lifecycleEventArgs)
    {
        $currentDateTime = new DateTime();

        $task->setCreatedAt($currentDateTime);
    }

}