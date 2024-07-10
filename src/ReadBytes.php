<?php

declare(strict_types=1);

namespace Kafkiansky\Binary;

/**
 * @api
 */
interface ReadBytes
{
    /**
     * @throws BinaryException
     *
     * @psalm-return ($n is positive-int ? non-empty-string : string)
     */
    public function read(int $n): string;
}
