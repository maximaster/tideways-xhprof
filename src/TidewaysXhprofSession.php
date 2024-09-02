<?php

declare(strict_types=1);

namespace Maximaster\TidewaysXhprof;

use Maximaster\TidewaysXhprof\Contract\TidewaysXhprofPersisterInterface;
use Maximaster\TidewaysXhprof\Contract\TidewaysXhprofSessionInterface;
use RuntimeException;

class TidewaysXhprofSession implements TidewaysXhprofSessionInterface
{
    /** @var TidewaysXhprofPersisterInterface */
    private $persister;

    public function __construct(TidewaysXhprofPersisterInterface $persister)
    {
        $this->persister = $persister;
    }

    public function start(int $flags): void
    {
        $this->assertExtension();
        tideways_xhprof_enable($flags);
    }

    public function stop(): void
    {
        $this->assertExtension();
        $this->persister->persist(tideways_xhprof_disable());
    }

    private function assertExtension(): void
    {
        if (extension_loaded('tideways_xhprof') === false) {
            throw new RuntimeException('tideways_xhprof module should be installed');
        }
    }
}
