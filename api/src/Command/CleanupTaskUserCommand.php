<?php

namespace App\Command;

use App\Repository\TaskUserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:task-user:cleanup',
    description: 'Deletes old task_user records with mark = 0 older than 2 months.'
)]
class CleanupTaskUserCommand extends Command
{
    /**
     * @param TaskUserRepository $taskUserRepository
     */
    public function __construct(
        private TaskUserRepository $taskUserRepository
    ) {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $timeAgo = (new \DateTime())->modify('-2 months');

        $affectedRows = $this->taskUserRepository->deleteOldUnmarkedTasks($timeAgo);

        $output->writeln("Deleted $affectedRows task_user records.");

        return Command::SUCCESS;
    }
}