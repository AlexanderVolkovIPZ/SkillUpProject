<?php

namespace App\EntityListener;

use App\Entity\Course;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CourseEntityListener
{

    public function __construct()
    {
    }

    public function prePersist(Course $course, LifecycleEventArgs $lifecycleEventArgs)
    {
        $course->setCode($this->generateUniqueCode());
    }

    private function generateUniqueCode(): string
    {
        return bin2hex(random_bytes(7));
    }

}