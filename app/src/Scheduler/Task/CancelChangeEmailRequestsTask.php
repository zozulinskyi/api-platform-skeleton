<?php
declare(strict_types=1);

namespace App\Scheduler\Task;

use App\Repository\Authentication\EmailChangeRequestRepository;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCronTask(expression: '0 #(0-8) * * *')]
final readonly class CancelChangeEmailRequestsTask
{
    public function __construct(
        private EmailChangeRequestRepository $repository,
    )
    {}

    public function __invoke(): void
    {
        $this->repository->cancelOverdueRequests();
    }
}
