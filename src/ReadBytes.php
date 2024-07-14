<?php

declare(strict_types=1);

namespace Kafkiansky\Binary;

/**
 * @api
 */
interface ReadBytes
{
    /**
     * @psalm-return ($n is positive-int ? non-empty-string : string)
     * @throws BinaryException
     */
    public function read(int $n): string;
}
