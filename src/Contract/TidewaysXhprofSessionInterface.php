<?php

declare(strict_types=1);

namespace Maximaster\TidewaysXhprof\Contract;

interface TidewaysXhprofSessionInterface
{
    public function start(int $flags): void;

    public function stop(): void;
}
