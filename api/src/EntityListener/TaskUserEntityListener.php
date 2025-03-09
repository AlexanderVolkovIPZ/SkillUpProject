<?php

namespace App\EntityListener;

use App\Entity\TaskUser;
use DateTime;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TaskUserEntityListener
{
    /**
     * TaskUserEntityListener constructor
     */
    public function __construct()
    {
    }

    /**
     * @param TaskUser $taskUser
     * @param LifecycleEventArgs $lifecycleEventArgs
     * @return void
     */
    public function prePersist(TaskUser $taskUser, LifecycleEventArgs $lifecycleEventArgs):void
    {
        $currentDateTime = new DateTime();
        $taskUser->setDate($currentDateTime);
    }
}