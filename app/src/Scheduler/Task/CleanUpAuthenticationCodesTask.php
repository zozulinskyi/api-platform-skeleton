<?php
declare(strict_types=1);

namespace App\Scheduler\Task;

use App\Repository\Authentication\CodeRepository;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCronTask(expression: '0 #(0-8) * * *')]
final readonly class CleanUpAuthenticationCodesTask
{
    public function __construct(
        private CodeRepository $repository,
    )
    {}

    public function __invoke(): void
    {
        $this->repository->deleteOverdueCodes();
    }
}
