<?php
declare(strict_types=1);

namespace App\Scheduler;

use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule]
final readonly class MainSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private LockFactory $lockFactory,
        private CacheInterface $cacheSchedulerDefault,
    )
    {}

    public function getSchedule(): Schedule
    {
        dd($this->cacheSchedulerDefault);
        return (new Schedule())
            ->lock(lock: $this->lockFactory->createLock(resource: 'schedule-default-lock'))
            ->stateful(state: $this->cache)
            ->processOnlyLastMissedRun(onlyLastMissed: true);
    }
}
