<?php

declare(strict_types=1);

namespace Maximaster\TidewaysXhprof;

use InvalidArgumentException;
use Maximaster\TidewaysXhprof\Contract\TidewaysXhprofPersisterInterface;
use Maximaster\TidewaysXhprof\Exception\TidewaysXhprofPersistException;

class TidewaysXhprofPersister implements TidewaysXhprofPersisterInterface
{
    private string $directory;
    private string $filenameFormat;
    private string $datetimeFormat;

    public function __construct(string $directory, string $filenameFormat, string $datetimeFormat)
    {
        assert(
            is_dir($directory),
            new InvalidArgumentException(sprintf('directory should point to a directory, %s given', $directory))
        );
        assert(
            mb_strlen($filenameFormat) > 0,
            new InvalidArgumentException('filenameFormat should contain a non-empty string')
        );

        $this->directory = $directory;
        $this->filenameFormat = $filenameFormat;
        $this->datetimeFormat = $datetimeFormat;
    }

    /**
     * {@inheritDoc}
     *
     * @throws TidewaysXhprofPersistException
     */
    public function persist(array $data): void
    {
        $targetFile = $this->directory . DIRECTORY_SEPARATOR . str_replace(
            ['#date#',                      '#uniqid#'],
            [date($this->datetimeFormat),   uniqid()],
            $this->filenameFormat
        );

        $persistedBytes = file_put_contents($targetFile, serialize($data));
        if ($persistedBytes === false) {
            throw new TidewaysXhprofPersistException(sprintf("Unable to save session data to '%s'", $targetFile));
        }
    }
}
