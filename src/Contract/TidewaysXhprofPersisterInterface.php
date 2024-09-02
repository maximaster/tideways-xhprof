<?php

declare(strict_types=1);

namespace Maximaster\TidewaysXhprof\Contract;

interface TidewaysXhprofPersisterInterface
{
    /**
     * @psalm-param array<mixed> $data
     */
    public function persist(array $data): void;
}
