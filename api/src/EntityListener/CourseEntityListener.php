<?php

namespace App\EntityListener;

use App\Entity\Course;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CourseEntityListener
{
    /**
     * CourseEntityListener constructor
     */
    public function __construct()
    {
    }

    /**
     * @param Course $course
     * @param LifecycleEventArgs $lifecycleEventArgs
     * @return void
     * @throws \Random\RandomException
     */
    public function prePersist(Course $course, LifecycleEventArgs $lifecycleEventArgs):void
    {
        $course->setCode($this->generateUniqueCode());
    }

    /**
     * @return string
     * @throws \Random\RandomException
     */
    private function generateUniqueCode(): string
    {
        return bin2hex(random_bytes(7));
    }
}