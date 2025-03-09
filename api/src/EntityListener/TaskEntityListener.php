<?php

namespace App\EntityListener;

use App\Entity\Task;
use DateTime;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TaskEntityListener
{
    /**
     * TaskEntityListener constructor
     */
    public function __construct()
    {
    }

    /**
     * @param Task $task
     * @param LifecycleEventArgs $lifecycleEventArgs
     * @return void
     */
    public function prePersist(Task $task, LifecycleEventArgs $lifecycleEventArgs):void
    {
        $currentDateTime = new DateTime();

        $task->setCreatedAt($currentDateTime);
    }
}