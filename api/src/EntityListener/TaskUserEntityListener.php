<?php

namespace App\EntityListener;

use App\Entity\TaskUser;
use DateTime;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TaskUserEntityListener
{

    public function __construct()
    {
    }

    public function prePersist(TaskUser $taskUser, LifecycleEventArgs $lifecycleEventArgs)
    {
        $currentDateTime = new DateTime();

        $taskUser->setDate($currentDateTime);
    }

    public function preUpdate(TaskUser $taskUser, LifecycleEventArgs $lifecycleEventArgs)
    {
        $currentDateTime = new DateTime();

        $taskUser->setDate($currentDateTime);
    }

}